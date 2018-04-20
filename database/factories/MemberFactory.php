<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Member::class, function (Faker $faker) {
    return [
        \App\Models\Member::EMAIL => $faker->unique()->safeEmail
    ];
});
