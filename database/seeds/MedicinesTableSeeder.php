<?php

use Illuminate\Database\Seeder;
use Faker\Generator;

class MedicinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();
        $faker->addProvider(new Faker\Provider\Miscellaneous($faker));
        $drugs = ['Amoxicillin', 'Cetirizine', 'Clindamycin',
            'Clopidogrel', 'Diclofenac', 'Domperidone', 'Dulcolax', 'Fluoxetine',
            'Gabapentin', 'Lisinopril', 'Losartan', 'Metronidazole', 'Naproxen',
            'Omeprazole', 'Pantoprazole', 'Simvastatin', 'Tramadol', 'Vimovo',
            'Zapain', 'Zinnat'];

        foreach ($drugs as $drug) {
            $med = new \App\Medicine([
                'name' => $drug,
                'isRenewable' => $faker->boolean(80)
            ]);
            $med->save();
            }

    }
}
