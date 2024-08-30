<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface LikeableModelInterface
{
    public function likes() : MorphMany;
}
