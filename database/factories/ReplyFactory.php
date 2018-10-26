<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {

    // 随机取一个月的一天
    $time = $faker->dateTimeThisMonth();

    return [
        'content' => $faker->sentence,
        'created_at' => $time,
        'updated_at' => $time
    ];
});
