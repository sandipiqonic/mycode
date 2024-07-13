<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Entertainment\Models\UserReminder;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderEmail;
use App\Models\User;
use Carbon\Carbon;

class ReminderNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = intVal((setting('upcoming'))) ?? 1;

        $thresholdDate = Carbon::now()->addDays($days)->startOfDay();
        $reminder_data = UserReminder::with('entertainment')->whereDate('release_date', '=', $thresholdDate)->get();

        foreach ($reminder_data as $reminder) {
            $user = User::where('id', $reminder->user_id)->first();
            
            $entertainment = $reminder->entertainment;

            Mail::to($user->email)->send(new ReminderEmail($user));

            $notification_data = [
                'id' => $entertainment->id ?? null,
                'user_id' => $reminder->user_id,
                'name' => $entertainment->name ?? null,
                'posterimage' => $entertainment->poster_url ?? null,
                'type' => $entertainment->type ?? null,
                'release_date' => $entertainment->release_date ?? null,
                'description' => $entertainment->description ?? null,
            ];
            $this->SendPushNotification($notification_data);
        }

        $this->info('Reminder notifications sent successfully.');
    }
    protected function SendPushNotification($data){
        $heading = array(
            "en" => $data['name']
        );
        
        $content = array(
            "en" => $data['description']
        );

        $user_id = $data['user_id'];
        return fcm([
    
            'to'=>'/topics/user_'.$user_id,
            'collapse_key' => 'type_a',
            'notification' => [
                'body' =>  $content,
                'title' => $heading ,
            ],
            
        ]);
    }
}
