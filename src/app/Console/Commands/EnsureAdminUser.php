<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class EnsureAdminUser extends Command
{
    protected $signature = 'voltfix:ensure-admin
                            {--email=admin@servis.com : Email admin}
                            {--password=password : Password admin}
                            {--name=Admin Servis : Nama admin}';

    protected $description = 'Buat atau reset akun admin agar bisa login ke panel /admin';

    public function handle(): int
    {
        $email = (string) $this->option('email');
        $password = (string) $this->option('password');
        $name = (string) $this->option('name');

        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name'              => $name,
                'email'             => $email,
                'phone'             => '6280000000000',
                'password'          => $password,
                'role'              => 'ADMIN',
                'email_verified_at' => now(),
            ]);
            $this->info("Admin baru dibuat: {$email}");
        } else {
            $user->forceFill([
                'name'              => $name,
                'password'          => $password,
                'role'              => 'ADMIN',
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
            $this->info("Admin sudah ada — password & role di-reset: {$email}");
        }

        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }

        $this->line('Login di: ' . url('/admin/login'));
        $this->line("Email   : {$email}");
        $this->line("Password: {$password}");

        return self::SUCCESS;
    }
}
