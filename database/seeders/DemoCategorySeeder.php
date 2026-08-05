<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DemoCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Antibiotics',
                'description' => 'Medicines used to treat bacterial infections.',
            ],

            [
                'name' => 'Analgesics',
                'description' => 'Pain relief medications.',
            ],

            [
                'name' => 'Antimalarials',
                'description' => 'Medicines for malaria prevention and treatment.',
            ],

            [
                'name' => 'Antihypertensives',
                'description' => 'Blood pressure medications.',
            ],

            [
                'name' => 'Antidiabetics',
                'description' => 'Medicines used to manage diabetes.',
            ],

            [
                'name' => 'Vitamins & Supplements',
                'description' => 'Nutritional supplements and vitamins.',
            ],

            [
                'name' => 'Respiratory',
                'description' => 'Medicines for cough, asthma and breathing conditions.',
            ],

            [
                'name' => 'Gastrointestinal',
                'description' => 'Medicines for stomach and digestive conditions.',
            ],

            [
                'name' => 'Dermatological',
                'description' => 'Skin care and topical medications.',
            ],

            [
                'name' => 'Medical Supplies',
                'description' => 'Syringes, gloves, bandages and other medical consumables.',
            ],

            [
                'name' => 'Eye & Ear Care',
                'description' => 'Eye drops, ear drops and related products.',
            ],

            [
                'name' => 'Family Planning',
                'description' => 'Contraceptives and reproductive health products.',
            ],

        ];

        foreach ($categories as $category) {

            Category::updateOrCreate(

                ['name' => $category['name']],

                [
                    'description' => $category['description'],
                ]

            );

        }
    }
}