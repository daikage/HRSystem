<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLeaveRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $employeeName,
        public string $type,
        public string $dateRange,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New leave request from '.$this->employeeName)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('**'.$this->employeeName.'** has submitted a **'.$this->type.'** leave request ('.$this->dateRange.').')
            ->action('Review leave requests', url('/leave-requests'));
    }
}