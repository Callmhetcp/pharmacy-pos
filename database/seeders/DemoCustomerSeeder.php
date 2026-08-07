<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class DemoCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [

            [
                'name' => 'Walk-in Customer',
                'phone_number' => '00000000000',
                'address' => 'Walk-in',
                'status' => 'Active',
            ],

            [
                'name' => 'Chinedu Okafor',
                'phone_number' => '08031234567',
                'address' => 'Lagos',
                'status' => 'Active',
            ],

            [
                'name' => 'Amina Bello',
                'phone_number' => '08042345678',
                'address' => 'Abuja',
                'status' => 'Active',
            ],

            [
                'name' => 'Emeka Obi',
                'phone_number' => '08053456789',
                'address' => 'Enugu',
                'status' => 'Active',
            ],

            [
                'name' => 'Fatima Musa',
                'phone_number' => '08064567890',
                'address' => 'Kano',
                'status' => 'Inactive',
            ],

            [
                'name' => 'Grace Johnson',
                'phone_number' => '08075678901',
                'address' => 'Port Harcourt',
                'status' => 'Active',
            ],

            [
                'name' => 'David Eze',
                'phone_number' => '08086789012',
                'address' => 'Owerri',
                'status' => 'Active',
            ],

            [
                'name' => 'Ngozi Umeh',
                'phone_number' => '08097890123',
                'address' => 'Onitsha',
                'status' => 'Active',
            ],

            [
                'name' => 'Ibrahim Yusuf',
                'phone_number' => '08108901234',
                'address' => 'Kaduna',
                'status' => 'Active',
            ],

            [
                'name' => 'Blessing Peter',
                'phone_number' => '08119012345',
                'address' => 'Uyo',
                'status' => 'Active',
            ],

        ];

        foreach ($customers as $customer) {

            Customer::updateOrCreate(

                ['phone_number' => $customer['phone_number']],

                $customer

            );
        }
    }
}