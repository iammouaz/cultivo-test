<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Sentry\Laravel\Integration;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $event;
    protected $userName;

    public function __construct($email, $event, $userName)
    {
        $this->email = $email;
        $this->event = $event;
        $this->userName = $userName;
    }


    public function handle()
    {
        try {
            sendEmail_v2($this->email,'Request_Access_Notification',[
                'user_name'=>$this->userName,
                'event_name'=>$this->event->name
            ]);
        }
        catch (\Exception $e) {
            Log::error("the error send email :". $e->getMessage());
            Integration::captureUnhandledException($e);
        }

    }
}
