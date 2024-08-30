<?php

namespace App\Observers;

use App\Contracts\LikeableModelInterface;

class LikeableObserver
{
    public function deleting(LikeableModelInterface $model)
    {
        $model->likes()->delete();
    }
}
