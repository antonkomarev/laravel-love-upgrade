<?php

use Faker\Generator as Faker;

$factory->define(\Wink\WinkAuthor::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->name,
        'slug' => $faker->slug,
        'email' => $faker->email,
        'bio' => $faker->paragraph,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(40),
    ];
});
