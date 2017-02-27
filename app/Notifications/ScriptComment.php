<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ScriptComment extends Notification {

    use Queueable;

    public $script;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($script) {
        //
        $this->script = $script;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        $script = $this->script;
        if (isset($script->js_url)) {
            $item = "script";
        } elseif (isset($script->skin_url)) {
            $item = "skin";
        }
        $mail = (new MailMessage)
                ->greeting('Bonjour,')
                ->subject("Nouveau commentaire sur votre $item : $script->name");
        $mail->line("Vous avez recu un nouveau commentaire sur votre $item : $script->name")
                ->action('Voir les commentaires', route($item . '.show', $script->slug) . '#comments');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
            'item_id' => $this->script->id,
            'item_type' => $this->script->js_url ? 'script' : 'skin',
            'notification_type' => 'script_comment',
        ];
    }

}
