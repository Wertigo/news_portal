<?php

namespace App\Factory;

use App\Entity\Tag;

class TagFactory
{
    /**
     * @return Tag
     */
    public function createNew(): Tag
    {
        return new Tag();
    }
}
