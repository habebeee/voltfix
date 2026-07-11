<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        if (! filled(config('services.resend.key')) && config('mail.default') === 'resend') {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Konfigurasi email belum siap. Isi RESEND_KEY di file .env, lalu jalankan: php artisan config:clear',
                ]);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with(
                'status',
                'Jika email terdaftar dan terverifikasi di VoltFix, link reset password sudah dikirim. Cek inbox dan folder spam Anda.'
            );
        }

        if ($user->email_verified_at === null) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email ini belum terverifikasi. Hubungi admin VoltFix untuk verifikasi akun terlebih dahulu.',
                ]);
        }

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (Throwable $e) {
            Log::error('Gagal kirim email reset password', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Gagal mengirim email. Pastikan RESEND_KEY valid dan domain pengirim sudah diverifikasi di Resend.',
                ]);
        }

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(
                'status',
                'Link reset password sudah dikirim ke ' . $request->email . '. Cek inbox dan folder spam, lalu klik link untuk membuat password baru.'
            );
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Tunggu sekitar 1 menit sebelum meminta link reset lagi.',
                ]);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
