<?php

namespace App\Models;

use Cog\Contracts\Love\Likeable\Models\Likeable as LikeableContract;
use Cog\Laravel\Love\Likeable\Models\Traits\Likeable;
use Wink\WinkPost;

class Post extends WinkPost implements LikeableContract
{
    use Likeable;
}
