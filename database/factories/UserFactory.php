<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {

    $date_time = $faker->date. '' .$faker->time;
    //让所有用户密码设置为相同
    //密码设置为静态共享变量，下方生成一次后存入变量，再次设置时三元运算，获取共享变量
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $password ? : $password=bcrypt('123123'), // secret
        'remember_token' => str_random(10),
        'is_admin' => false,
        //注册时间和更新时间默认为同一个
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});
