<?php

use Faker\Generator as Faker;

$factory->define(\Wink\WinkPost::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'author_id' => factory(\Wink\WinkAuthor::class),
        'slug' => $faker->slug,
        'title' => implode(' ', $faker->words),
        'excerpt' => implode(' ', $faker->words),
        'body' => $faker->paragraph,
        'published' => $faker->boolean,
        'featured_image_caption' => '',
    ];
});
