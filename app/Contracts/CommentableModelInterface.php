<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface CommentableModelInterface
{
    public function comments() : MorphMany;
}
