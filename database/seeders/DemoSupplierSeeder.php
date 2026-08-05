<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DemoSupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [

            [
                'company' => 'Emzor Pharmaceutical Industries Ltd',
                'name' => 'John Adeyemi',
                'phone_number' => '08031234567',
                'email' => 'sales@emzor.com',
                'address' => 'Lagos, Nigeria',
                'status' => 'active',
                'notes' => 'Major supplier of OTC medicines.',
            ],

            [
                'company' => 'Fidson Healthcare Plc',
                'name' => 'Mary Okafor',
                'phone_number' => '08039876543',
                'email' => 'orders@fidson.com',
                'address' => 'Lagos, Nigeria',
                'status' => 'active',
                'notes' => 'Prescription medicines.',
            ],

            [
                'company' => 'Swiss Pharma Nigeria',
                'name' => 'Chinedu Obi',
                'phone_number' => '08035551234',
                'email' => 'sales@swipha.com',
                'address' => 'Ikeja, Lagos',
                'status' => 'active',
                'notes' => 'Hospital supplies.',
            ],

            [
                'company' => 'May & Baker Nigeria Plc',
                'name' => 'Grace Musa',
                'phone_number' => '08036667890',
                'email' => 'info@maybaker.com',
                'address' => 'Lagos, Nigeria',
                'status' => 'active',
                'notes' => 'General pharmaceuticals.',
            ],

            [
                'company' => 'Drugfield Pharmaceuticals',
                'name' => 'Samuel Bello',
                'phone_number' => '08034445566',
                'email' => 'orders@drugfield.com',
                'address' => 'Lagos, Nigeria',
                'status' => 'active',
                'notes' => 'Injectables and tablets.',
            ],

        ];

        foreach ($suppliers as $supplier) {

            Supplier::updateOrCreate(
                ['company' => $supplier['company']],
                $supplier
            );

        }
    }
}