<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OnboardingStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  string  $recipientName  Candidate display name
     * @param  string  $status  approved | rejected | info_requested
     */
    public function __construct(
        public string $recipientName,
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
            ->subject('Your onboarding '.$this->status)
            ->greeting('Hello '.$this->recipientName.',')
            ->line('Your onboarding request has been **'.$this->status.'**.');

        if ($this->feedback) {
            $mail->line('Message from HR: "'.$this->feedback.'"');
        }

        if ($this->status === 'info_requested') {
            $mail->line('Please reply to this email or contact HR with the requested information.');
        }

        return $mail;
    }
}
