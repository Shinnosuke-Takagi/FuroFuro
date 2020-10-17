<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangePasswordNotification extends Notification
{
    use Queueable;
    protected $user_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_name)
    {
        $this->user_name = $user_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
        ->subject(__('パスワードが変更されました'))
        ->greeting('こんにちは！')
        ->line('こちらはパスワードが変更された方に送らせて頂いております。')
        ->action(__('ユーザーページはこちら'), url('users/'.$this->user_name))
        ->line('パスワードを変更した覚えがない方は上記のボタンからアカウントを確認してください。')
        ->line('こちらのメールに身に覚えがない場合はなりすましなどの危険があるので破棄してください。');

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
