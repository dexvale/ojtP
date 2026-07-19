<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeCoordinatorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $plainPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('OJT Portal Access Granted — Coordinator Credentials')
            ->greeting('Hello!')
            ->line('A department coordinator account has been provisioned for you on the BISU Balilihan OJT Management System.')
            ->line('You have been assigned to coordinate OJT students.')
            ->line('**Login Email:** ' . $notifiable->email)
            ->line('**Temporary Password:** ' . $this->plainPassword)
            ->action('Login to Portal', url('/login'))
            ->line('For data security, please change your temporary password immediately upon logging in for the first time.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
