<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Model\User::class, function (Faker\Generator $faker) {
    return [
        'email'   => $faker->email,
        'token'   => $faker->regexify('/[A-Za-z0-9]{20}/'),
        'name'    => $faker->name,
        'profile' => ['USER', 'AGENT'][rand(0,1)],
    ];
});

$factory->define(App\Model\Request::class, function (Faker\Generator $faker) {
    return [
        'value' => $faker->word,
        'from'    => 'ENG',
        'to'      => 'TR',
        'situation' => 0,
    ];
});