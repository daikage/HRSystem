<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOnboardingSubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $candidateName, public string $candidateEmail)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New onboarding request')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('A new onboarding request has been submitted by **'.$this->candidateName.'** ('.$this->candidateEmail.').')
            ->action('Review onboarding requests', url('/admin/onboarding'));
    }
}
