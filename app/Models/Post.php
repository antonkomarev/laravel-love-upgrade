<?php

namespace App\Models;

use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableContract;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Wink\WinkPost;

class Post extends WinkPost implements ReactableContract
{
    use Reactable;
}
