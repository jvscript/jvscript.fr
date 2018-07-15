<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class notifyStatus extends Notification {

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
                ->subject('Notification de jvscript.fr');
        if ($script->status == 1) {
            $mail->line("Le $item que vous avez ajouté sur jvscript.fr a été validé. ")
                    ->action('Suivez ce lien pour le voir', route($item . '.show', $script->slug));
        } elseif ($script->status == 2) {
            $mail->greeting('Bonjour,')
                    ->line("Le $item que vous avez ajouté sur jvscript.fr a été refusé. ")
                    ->action("Contactez-nous pour plus d'info", route('contact.form'));
        }

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
            'notification_type' => 'script_status',
            'status' => $this->script->status,
        ];
    }

}
