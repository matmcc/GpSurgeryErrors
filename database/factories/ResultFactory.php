<?php

use Faker\Generator as Faker;

$factory->define(App\Result::class, function (Faker $faker) {
    return [
        'name'=> 'Result:'.$faker->realText(20),
        'body' => $faker->paragraph(5),
        'dateTime' => $faker->dateTimeThisYear('now')
    ];
});
