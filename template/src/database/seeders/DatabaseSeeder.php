<?php

namespace Database\Seeders;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
            'name'     => 'Admin Servis',
            'email'    => 'admin@servis.com',
            'phone'    => '6281234567890',
            'password' => Hash::make('password'),
            'role'     => 'ADMIN',
        ]);
        $admin->assignRole($superAdmin);

        // 2. Manager
        $manager = User::create([
            'name'     => 'Manager Servis',
            'email'    => 'manager@servis.com',
            'phone'    => '6284444444444',
            'password' => Hash::make('password'),
            'role'     => 'MANAGER',
        ]);
        $manager->assignRole($superAdmin);

        // 3. Teknisi (KULKAS, TV, MESIN_CUCI)
        $teknisiData = [
            ['name' => 'Agus Kulkas', 'email' => 'agus@servis.com',  'phone' => '6281111111111', 'skill' => 'KULKAS',     'experience' => '5 tahun'],
            ['name' => 'Budi TV',     'email' => 'budi@servis.com',  'phone' => '6282222222222', 'skill' => 'TV',         'experience' => '7 tahun'],
            ['name' => 'Citra Cuci',  'email' => 'citra@servis.com', 'phone' => '6283333333333', 'skill' => 'MESIN_CUCI', 'experience' => '4 tahun'],
        ];

        foreach ($teknisiData as $data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'],
                'password' => Hash::make('password'),
                'role'     => 'TECHNICIAN',
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
            'name'     => 'Pelanggan Test',
            'email'    => 'customer@test.com',
            'phone'    => '6285555555555',
            'password' => Hash::make('password'),
            'role'     => 'CUSTOMER',
        ]);
    }
}
