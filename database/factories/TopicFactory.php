<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {

    $sentence = $faker->sentence(); // 随机生成一小段文本

    $updated_at = $faker->dateTimeThisMonth();

    // 传参时间不得超过修改时间
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'title' => $sentence,
        'body' => $faker->text,
        'created_at' => $created_at,
        'updated_at' => $updated_at
    ];
});
