<?php

use Faker\Generator as Faker;

$factory->define(App\Prescription::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['Amoxicillin', 'Cetirizine', 'Clindamycin',
            'Clopidogrel', 'Diclofenac', 'Domperidone', 'Dulcolax', 'Fluoxetine',
            'Gabapentin', 'Lisinopril', 'Losartan', 'Metronidazole', 'Naproxen',
            'Omeprazole', 'Pantoprazole', 'Simvastatin', 'Tramadol', 'Vimovo',
            'Zapain', 'Zinnat']),
        'isRenewable' => $faker->boolean
    ];
});
