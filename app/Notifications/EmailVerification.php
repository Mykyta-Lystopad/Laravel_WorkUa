<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmailVerification extends Notification
{
    use Queueable;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
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
     * @param $user
     * @param null $password
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($user)
    {
        if ($user->verify_code == 'done'){
            return (new MailMessage)
                ->line('The introduction to the notification.')
                ->line('It is your new password:')
                ->line([ 'verify_code'=>$user->verify_code])
                ->line('You could change it in your options')
                ->line('Thank you for using our application!');
        }
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->line('Enter this code, please:')
                    ->line(['verify_code'=>$user->verify_code])
//                    ->action('Notification Action', url('/', ['id'=>$user->id,'verify_code'=>$user->verify_code]))
                    ->line('Thank you for using our application!');
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
