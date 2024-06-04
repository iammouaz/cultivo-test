<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Sentry\Laravel\Integration;

class SendCustomEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $mailObj;
    // protected $shortcodes;
    // protected $isClient;
    // protected $objtype;

    public function __construct($email,$mailObj)
    {
        $this->email = $email;
        $this->mailObj = $mailObj;
        // $this->shortcodes = $shortcodes;
        // $this->isClient=$isClient;
        // $this->objtype=$objtype;
    }


    public function handle()
    {
        try {
            // if($this->isClient){
            //     if($this->objtype=='offer'){
            //         sendEmail_v2($this->email,'Offer_Order_Confirmation',$this->shortcodes);
            //     }
            //     if($this->objtype=='auction'){
            //         sendEmail_v2($this->email,'Auction_Order_Confirmation',$this->shortcodes);
            //     }
            // }else{

                Mail::to($this->email)->send($this->mailObj);
            // }
         
        }
        catch (\Exception $e) {
            Log::error("error sending email of subject". $this->mailObj->subject . " to " . $this->email . " :". $e->getMessage());
            Integration::captureUnhandledException($e);
        }

    }
}
