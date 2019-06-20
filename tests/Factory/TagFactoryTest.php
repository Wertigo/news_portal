<?php

namespace App\Tests\Factory;

use App\Entity\Tag;
use App\Factory\TagFactory;
use PHPUnit\Framework\TestCase;

class TagFactoryTest extends TestCase
{
    public function testCreateNew()
    {
        $factory = new TagFactory();
        $tag = $factory->createNew();

        // factory create new object
        $this->assertNotEmpty($tag);

        // factory create Tag object
        $this->assertEquals(get_class($tag), Tag::class);

        // Tag object is empty
        $this->assertEmpty($tag->getId());
        $this->assertEmpty($tag->getName());
        $this->assertEmpty($tag->getPosts());
    }
}