<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        // $this->token is available from the parent class
        // $this->createUrl($notifiable) generates the link, but parent uses a callback static::$createUrlCallback
        // We can just use standard route generation to be safe or rely on parent logic if we don't change the link structure.
        // Default Laravel sends to route('password.reset', ...)

        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Permintaan Atur Ulang Kata Sandi - RSUP Prof. I.G.N.G. Ngoerah')
            ->view('emails.reset-password', ['url' => $url]);
    }
}
