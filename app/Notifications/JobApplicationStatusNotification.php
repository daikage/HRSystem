<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplicationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $jobTitle,
        public string $status,
        public ?string $feedback = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Update on your application: '.$this->jobTitle)
            ->greeting('Hello!')
            ->line('The status of your application for the '.$this->jobTitle.' position has been updated to: '.ucfirst($this->status).'.');

        if ($this->feedback) {
            $mail->line('Feedback: '.$this->feedback);
        }

        if ($this->status === 'approved') {
            $mail->line('Congratulations! We would like to move forward with your application.');
        }

        return $mail->action('View Job', url('/jobs'));
    }
}