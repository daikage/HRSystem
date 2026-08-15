<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentReviewedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $documentTitle,
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
            ->subject('Document verification '.$this->status)
            ->greeting('Hello!')
            ->line('Your document "'.$this->documentTitle.'" has been reviewed and is now: '.ucfirst($this->status).'.');

        if ($this->feedback) {
            $mail->line('Feedback: '.$this->feedback);
        }

        return $mail->action('View My Documents', url('/documents'));
    }
}