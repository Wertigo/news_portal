<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Post;
use App\Factory\CommentFactory;
use App\Factory\UserFactory;
use App\Factory\PostFactory;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    /**
     * @dataProvider getTexts
     */
    public function testSetText($text)
    {
        $comment = $this->getNewComment();

        $comment->setText($text);
        $this->assertEquals($text, $comment->getText());
    }

    private function getNewComment(): Comment
    {
        $factory = new CommentFactory();
        return $factory->createNew();
    }

    /**
     * @return array
     */
    public function getTexts()
    {
        return [
            ['Simple text'],
            ['<p>Html text</p>'],
            ['{"textType": "json"}'],
        ];
    }

    /**
     * @dataProvider getAuthor
     */
    public function testSetAuthor(User $author)
    {
        $comment = $this->getNewComment();
        $comment->setAuthor($author);

        $this->assertEquals($author, $comment->getAuthor());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAuthor()
    {
        $factory = new UserFactory();

        return [
            [$factory->createNew()],
        ];
    }

    /**
     * @dataProvider getPost
     */
    public function testSetPost(Post $post)
    {
        $comment = $this->getNewComment();
        $comment->setPost($post);

        $this->assertEquals($post, $comment->getPost());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getPost()
    {
        $factory = new PostFactory();

        return [
            [$factory->createNew()],
        ];
    }
}