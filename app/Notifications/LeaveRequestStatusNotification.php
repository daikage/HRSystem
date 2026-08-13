<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $status, public string $type, public string $dateRange)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Leave request '.$this->status)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your '.$this->type.' leave request ('.$this->dateRange.') has been **'.$this->status.'**.');

        if ($this->status === 'approved') {
            $message->line('Enjoy your time off!');
        } elseif ($this->status === 'rejected') {
            $message->line('Please contact HR if you have any questions.');
        }

        return $message->action('View your leave requests', url('/leave-requests'));
    }
}
