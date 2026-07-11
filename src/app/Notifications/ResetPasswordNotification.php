<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expire = (int) config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);

        return (new MailMessage)
            ->subject('Reset Password — VoltFix')
            ->greeting('Halo, ' . ($notifiable->name ?? 'Pelanggan') . '!')
            ->line('Kami menerima permintaan untuk mengatur ulang password akun VoltFix Anda.')
            ->line('Klik tombol di bawah untuk membuka halaman buat password baru:')
            ->action('Buat Password Baru', $url)
            ->line('Link ini berlaku selama ' . $expire . ' menit.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini — akun Anda tetap aman.')
            ->salutation('Salam, Tim VoltFix');
    }
}
