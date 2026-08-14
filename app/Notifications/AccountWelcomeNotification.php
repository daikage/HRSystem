<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountWelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $recipientName, public string $temporaryPassword)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your HR System account')
            ->greeting('Welcome, '.$this->recipientName.'!')
            ->line('An account has been created for you on the HR System.')
            ->line('Your temporary password is: '.$this->temporaryPassword)
            ->action('Sign in and change your password', url('/login'))
            ->line('For security, you will be required to set a new password on your first sign in.');
    }
}
