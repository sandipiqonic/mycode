<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Subscriptions\Models\Subscription;
use App\Mail\ExpiringSubscriptionEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;

class SendSubscriptionNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:notify';

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
        $expiry_days = intVal((setting('expiry_plan'))) ?? 7;
        $expiryThreshold = Carbon::now()->addDays($expiry_days);
        $userIds = Subscription::where('status', 'active')
            ->where('end_date', '<=', $expiryThreshold)
            ->pluck('user_id')
            ->toArray();
        // Get users with the retrieved user IDs
        $users = User::with('subscriptionPackage')->whereIn('id', $userIds)->get();
        foreach ($users as $user) {
            // Customize email send
            Mail::to($user->email)->send(new ExpiringSubscriptionEmail($user));

            $notification_data = [
                'id' => optional($user->subscriptionPackage)->id ?? null,
                'user_id' => $user->id,
                'name' => optional($user->subscriptionPackage)->name ?? null,
                'type' => optional($user->subscriptionPackage)->type ?? null,
                'amount' => optional($user->subscriptionPackage)->amount ?? null,
                'end_date' => optional($user->subscriptionPackage)->end_date ?? null,
            ];
            $this->SendPushNotification($notification_data);
        }

        $this->info('Subscription notifications sent successfully.');
    }
    protected function SendPushNotification($data){
        $description = 'Your plan will expire in 7 days.';
        $heading = array(
            "en" => $data['name']
        );
        
        $content = array(
            "en" => $description
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
