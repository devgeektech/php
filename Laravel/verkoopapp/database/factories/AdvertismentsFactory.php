<?php

use Faker\Generator as Faker;

$factory->define(App\Advertisments::class, function (Faker $faker) {
    
    return [
        'name' => $faker->name,
        'image' => 'public/images/advertisments'.$faker->image('public/images/advertisments',150,100, null, false),
        'category_id' => 1 
    ];
});
