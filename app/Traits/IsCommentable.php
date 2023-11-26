<?php

namespace App\Traits;

use App\Models\Comment;

trait IsCommentable
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function comment()
    {
        return $this->morphOne(Comment::class, 'commentable');
    }
}
