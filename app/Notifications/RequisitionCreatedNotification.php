<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionCreatedNotification extends Notification implements ShouldQueue
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
        $this->reqData = $reqData;
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
            // ->action('Notification Action', url('/'))
            ->line($this->reqData['thanks']);
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
            // 'requisition_number' => $this->reqData['requisition_number']
        ];
    }
    public function toDatabase($notifiable)
    {
        return [
            'requisition_number' => $this->reqData['requisition_number'],
            'status' => 'Submitted',
            'approval_level'=>0
        ];
    }
}
