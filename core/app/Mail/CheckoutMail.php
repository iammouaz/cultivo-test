<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutMail extends Mailable
{
    use Queueable, SerializesModels;


    // public $is_newOrder;
    public $order;
    public $isClient;


    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($order,$isClient)
    {
        $this->order=$order;
        $this->isClient=$isClient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order=$this->order;
        $isClient=$this->isClient;
        if ($isClient)
        {
            $subject='Thank you for sending your order';
        }
        else
        {
            $subject="You have a new fixed price order! Order #{$order->id}";
        }
        return $this->subject($subject)->view('emails.checkout',compact('order','isClient'));
    }
}
