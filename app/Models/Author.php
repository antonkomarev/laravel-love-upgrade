<?php

namespace App\Models;

use Cog\Contracts\Love\Liker\Models\Liker as LikerContract;
use Cog\Laravel\Love\Liker\Models\Traits\Liker;
use Wink\WinkAuthor;

class Author extends WinkAuthor implements LikerContract
{
    use Liker;
}
