<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notify extends Mailable
{
    use Queueable,
        SerializesModels;

    public $script;

    /**
     * Create a new message_body instance.
     *
     * @return void
     */
    public function __construct($script)
    {
        $this->script = $script;
    }

    /**
     * Build the message_body.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.notify')->subject("Notification de jvscript.fr");
    }
}
