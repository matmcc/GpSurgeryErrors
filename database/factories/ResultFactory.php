<?php

use Faker\Generator as Faker;

$factory->define(App\Result::class, function (Faker $faker) {
    return [
        'name'=> 'Result: '.$faker->sentence(3),
        'body' => $faker->paragraph(8),
        'date' => $faker->dateTimeThisYear('now')
    ];
});
