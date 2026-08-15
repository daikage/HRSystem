<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $applicantName,
        public string $jobTitle,
        public string $email,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New job application received')
            ->greeting('Hello!')
            ->line($this->applicantName.' has just applied for the '.$this->jobTitle.' position ('.$this->email.').')
            ->action('Review Applications', url('/admin/job-applications'))
            ->line('Log in to the HR System to review and respond to this application.');
    }
}