<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $reqData;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($reqData)
    {
        $this->reqData=$reqData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->greeting($this->reqData['greeting'])
        ->line($this->reqData['body'])
        ->action('Click here to act on the request number '.$this->reqData['requisition_number'], route('requisitions'))
        ->line($this->reqData['thanks']);
    }

    public function toDatabase($notifiable)
    {
        return [
            'requisition_number' => $this->reqData['requisition_number'],
            'status' => 'Approval'
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
