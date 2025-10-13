<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'المدير العام',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'super-admin'
        ]);

        User::create([
            'name' => 'أحمد محمد',
            'username' => 'ahmed',
            'email' => 'ahmed@example.com',
            'password' => Hash::make('password'),
            'role' => 'saler'
        ]);

        User::create([
            'name' => 'فاطمة علي',
            'username' => 'fatima',
            'email' => 'fatima@example.com',
            'password' => Hash::make('password'),
            'role' => 'saler'
        ]);

        User::create([
            'name' => 'محمد حسن',
            'username' => 'mohammed',
            'email' => 'mohammed@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        $this->command->info('تم إنشاء 4 مستخدمين للتجربة');
    }
}