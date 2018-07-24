<?php

$factory->define(App\Employee::class, function (Faker\Generator $faker) {
    static $password;
    $faker = \Faker\Factory::create('ru_RU');
    return [
        // 'director_id' => App\Employee::count() != 0 ? $faker->optional(0.99)->numberBetween(App\Employee::min('id'), App\Employee::max('id')) : null,
        'fio' => $faker->name,
        'position' => $faker->jobTitle,
        'employment_at' => $faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
        'wages' => $faker->numberBetween(1000, 9000),
    ];
});

$factory->state(App\Employee::class, 'directors', function ($faker) {
    return [
        'director_id' => null,
    ];
});

$factory->state(App\Employee::class, 'employees', function ($faker) {
    return [
        'director_id' => $faker->numberBetween(App\Employee::min('id'), App\Employee::max('id')),
    ];
});