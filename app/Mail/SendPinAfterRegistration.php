<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPinAfterRegistration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    
    protected $pinnumber;

    public function __construct($pinnumber)
    {
        
        $this->pinnumber = $pinnumber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@gmail.com')->markdown('emails.users.sendpinaferregister',[
            
            'pinnumber' => $this->pinnumber,

        ]);
    }
}
