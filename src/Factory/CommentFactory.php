<?php

namespace App\Factory;

use App\Entity\Comment;

class CommentFactory
{
    /**
     * @return Comment
     */
    public function createNew(): Comment
    {
        return new Comment();
    }
}
