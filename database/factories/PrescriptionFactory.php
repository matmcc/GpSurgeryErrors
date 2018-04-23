<?php

use App\Medicine;
use Faker\Generator as Faker;

$factory->define(App\Prescription::class, function (Faker $faker) {
    $medicine = Medicine::find($faker->numberBetween(1, 20));
    return [
        'name' => $medicine->name,
        'isRenewable' => $medicine->isRenewable,
        'medicine_id' => $medicine->id
    ];
});
