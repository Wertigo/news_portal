<?php

namespace App\Tests\Factory;

use App\Entity\Comment;
use App\Factory\CommentFactory;
use PHPUnit\Framework\TestCase;

class CommentFactoryTest extends TestCase
{
    public function testCreateNew()
    {
        $factory = new CommentFactory();
        $comment = $factory->createNew();

        // factory create object
        $this->assertNotEmpty($comment);

        // factory create object of class Comment
        $this->assertEquals(get_class($comment), Comment::class);

        // new Comment object - is empty
        $this->assertEmpty($comment->getId());
        $this->assertEmpty($comment->getAuthor());
        $this->assertEmpty($comment->getPost());
        $this->assertEmpty($comment->getText());
    }
}