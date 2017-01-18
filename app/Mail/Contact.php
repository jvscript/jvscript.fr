<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Contact extends Mailable {

    use Queueable,
        SerializesModels;

    public $email;
    public $message_body;

    /**
     * Create a new message_body instance.
     *
     * @return void
     */
    public function __construct($email, $message_body) {
        $this->email = $email;
        $this->message_body = $message_body;
    }

    /**
     * Build the message_body.
     *
     * @return $this
     */
    public function build() {
        return $this->view('mails.contact');
    }

}
