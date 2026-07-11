<?php

namespace Database\Seeders;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SiteSettingSeeder::class);

        // Ensure super_admin role exists (shield:setup should have run before seeder)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // 1. Admin
        $admin = User::create([
            'name'              => 'Admin Servis',
            'email'             => 'admin@servis.com',
            'phone'             => '6281234567890',
            'password'          => 'password',
            'role'              => 'ADMIN',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($superAdmin);

        // 2. Manager
        $manager = User::create([
            'name'              => 'Manager Servis',
            'email'             => 'manager@servis.com',
            'phone'             => '6284444444444',
            'password'          => 'password',
            'role'              => 'MANAGER',
            'email_verified_at' => now(),
        ]);
        $manager->assignRole($superAdmin);

        // 3. Teknisi (TV, HP, LAPTOP)
        $teknisiData = [
            ['name' => 'Rizki Pratama', 'email' => 'rizki@servis.com', 'phone' => '6281111111111', 'skill' => 'LAPTOP', 'experience' => '6 tahun — board level & ganti LCD'],
            ['name' => 'Budi Santoso',  'email' => 'budi@servis.com',  'phone' => '6282222222222', 'skill' => 'TV',     'experience' => '8 tahun — LED, OLED, Smart TV'],
            ['name' => 'Dedi Wijaya',   'email' => 'dedi@servis.com',  'phone' => '6283333333333', 'skill' => 'HP',     'experience' => '5 tahun — iPhone, Samsung, Xiaomi'],
        ];

        foreach ($teknisiData as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'phone'             => $data['phone'],
                'password'          => 'password',
                'role'              => 'TECHNICIAN',
                'email_verified_at' => now(),
            ]);

            Technician::create([
                'user_id'        => $user->id,
                'skill_category' => $data['skill'],
                'experience'     => $data['experience'],
                'average_rating' => 4.5,
                'is_available'   => true,
            ]);
        }

        // 4. Customer sample
        User::create([
            'name'              => 'Pelanggan Test',
            'email'             => 'customer@test.com',
            'phone'             => '6285555555555',
            'password'          => 'password',
            'role'              => 'CUSTOMER',
            'email_verified_at' => now(),
        ]);
    }
}
