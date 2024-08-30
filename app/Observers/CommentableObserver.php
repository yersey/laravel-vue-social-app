<?php

namespace App\Observers;

use App\Contracts\CommentableModelInterface;

class CommentableObserver
{
    public function deleting(CommentableModelInterface $model)
    {
        $model->comments->each->delete();
    }
}
