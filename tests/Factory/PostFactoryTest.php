<?php

namespace App\Tests\Factory;

use App\Entity\Post;
use App\Factory\PostFactory;
use PHPUnit\Framework\TestCase;

class PostFactoryTest extends TestCase
{
    public function testCreateNew()
    {
        $factory = new PostFactory();
        $post = $factory->createNew();

        // factory create new object
        $this->assertNotEmpty($post);

        // factory create Post object
        $this->assertEquals(get_class($post), Post::class);

        // new post have status draft
        $this->assertEquals($post->getStatus(), Post::STATUS_DRAFT);

        // new post have rating 0
        $this->assertEquals($post->getRating(), 0);
    }
}