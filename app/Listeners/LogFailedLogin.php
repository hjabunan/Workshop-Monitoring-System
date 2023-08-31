<?php

namespace App\Listeners;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;

class LogFailedLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Failed $event){
        
        $failedLogin = Session::get('failed_login');

        
        if ($failedLogin) {
            $reason = __('auth.failed');

            LoginLog::create([
                'login_username' => $failedLogin,
                'login_time' => now(),
                'login_ipaddress' => request()->ip(),
                'login_eventtype' => 'login',
                'login_status' => 'failed',
                'failure_reason' => $reason,
            ]);
        }

        Session::forget('failed_login');
    }
}
