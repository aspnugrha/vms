<?php

namespace Database\Seeders;

use App\Helpers\IdGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = [
            [
                'id' => 'USR2000000000001',
                'name' => 'superadmin',
                'email' => 'superadmin@admin.com',
                'phone_number' => '6280000000000',
            ],
            [
                'id' => 'USR2000010100001',
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'phone_number' => '6280000000001',
            ],
        ];

        foreach($admin as $item){
            DB::table('users')->insert([
                'id' => $item['id'],
                'name' => $item['name'],
                'email' => $item['email'],
                'phone_number' => $item['phone_number'],
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'email_verified_at' => date('Y-m-d H:i:s'),
                'active' => 1,
            ]);
        }
    }
}
