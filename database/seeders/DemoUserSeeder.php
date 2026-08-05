<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [

            [
                'name' => 'Demo Administrator',
                'email' => 'demo@gopharmacy.com',
                'role' => 'admin',
            ],

            [
                'name' => 'Demo Cashier',
                'email' => 'cashier@gopharmacy.com',
                'role' => 'cashier',
            ],

            [
                'name' => 'Demo Pharmacist',
                'email' => 'pharmacist@gopharmacy.com',
                'role' => 'pharmacist',
            ],

            [
                'name' => 'Demo Storekeeper',
                'email' => 'storekeeper@gopharmacy.com',
                'role' => 'storekeeper',
            ],

        ];

        foreach ($users as $user) {

            User::updateOrCreate(

                ['email' => $user['email']],

                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'status' => 'active',
                    'avatar' => null,
                    'password' => Hash::make('Demo1234'),
                ]

            );

        }
    }

    
}