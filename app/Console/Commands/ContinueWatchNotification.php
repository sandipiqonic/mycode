<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Entertainment\Models\ContinueWatch;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContinueWatchEmail;
use App\Models\User;
use Carbon\Carbon;

class ContinueWatchNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'continuewatch:notify';

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
        $days = intVal((setting('continue_watch'))) ?? 2;
        $expiryThreshold = Carbon::now()->subDays($days);
        $continuewatch_data = ContinueWatch::with('entertainment','episode','video')->where('updated_at', '<=', $expiryThreshold)->get();

        foreach ($continuewatch_data as $continuewatch) {
            $user = User::where('id', $continuewatch->user_id)->first();

            $entertainment = null;
            if($continuewatch->entertainment_type == 'movie'){
                $entertainment = $continuewatch->entertainment;
            }
            else if($continuewatch->entertainment_type == 'episode'){
                $entertainment = $continuewatch->episode;
            }
            else if($continuewatch->entertainment_type == 'video'){
                $entertainment = $continuewatch->video;
            }
            
            Mail::to($user->email)->send(new ContinueWatchEmail($user));

            $notification_data = [
                'id' => $entertainment->id ?? null,
                'user_id' => $continuewatch->user_id,
                'name' => $entertainment->name ?? null,
                'posterimage' => $entertainment->poster_url ?? null,
                'type' => $entertainment->type ?? null,
                'release_date' => $entertainment->release_date ?? null,
                'description' => $entertainment->description ?? null,
            ];
            $this->SendPushNotification($notification_data);
        }

        $this->info('Continue Watch notifications sent successfully.');
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
