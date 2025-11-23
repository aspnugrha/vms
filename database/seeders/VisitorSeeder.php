<?php

namespace Database\Seeders;

use App\Helpers\CodeHelper;
use App\Helpers\IdGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visitor = [
            [
                'name' => 'visitor1',
                'email' => 'visitor1@gmail.com',
                'phone_number' => '6285712341234',
            ],
            [
                'name' => 'visitor2',
                'email' => 'visitor2@gmail.com',
                'phone_number' => '6285712351235',
            ],
        ];

        foreach($visitor as $item){
            DB::table('visitors')->insert([
                'id' => IdGenerator::generate('VSTR', 'visitors'),
                'code' => 'VST-'.time().'-'.CodeHelper::generateRandomCodeCapital(6),
                'name' => $item['name'],
                'email' => $item['email'],
                'phone_number' => $item['phone_number'],
                'email_verified_at' => date('Y-m-d H:i:s'),
                'active' => 1,
            ]);
        }
    }
}
