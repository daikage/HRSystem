<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayrollPaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $payPeriod,
        public string $netPay,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your payslip for '.$this->payPeriod.' is available')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your payroll for the period **'.$this->payPeriod.'** has been processed.')
            ->line('Net pay: **$'.$this->netPay.'**')
            ->action('View your payslip', url('/payroll'));
    }
}
