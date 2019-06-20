<?php

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Factory\PostFactory;
use App\Factory\UserFactory;
use App\Factory\TagFactory;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testSetTitle()
    {
        $post = $this->getNewPost();
        $title = 'Default title for post';
        $post->setTitle($title);

        $this->assertEquals($title, $post->getTitle());
    }

    /**
     * @return Post
     */
    private function getNewPost(): Post
    {
        $factory = new PostFactory();

        return $factory->createNew();
    }

    /**
     * @dataProvider getContents
     */
    public function testSetContent($content)
    {
        $post = $this->getNewPost();
        $post->setContent($content);

        $this->assertEquals($content, $post->getContent());
    }

    /**
     * @return array
     */
    public function getContents()
    {
        return [
            ['Text content'],
            ['<html>Html content</html>'],
            ['{"textType": "json"}'],
        ];
    }

    /**
     * @dataProvider getAuthor
     */
    public function testSetAuthor(User $author)
    {
        $post = $this->getNewPost();
        $post->setAuthor($author);

        $this->assertEquals($author, $post->getAuthor());
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
     * @dataProvider getTag
     */
    public function testAddTag(Tag $tag)
    {
        $post = $this->getNewPost();
        $post->addTag($tag);
        $postTags = $post->getTags()->toArray();

        $this->assertTrue(in_array($tag, $postTags));
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTag()
    {
        $factory = new TagFactory();

        return [
            [$factory->createNew()],
        ];
    }

    /**
     * @dataProvider getTag
     */
    public function testRemoveTag(Tag $tag)
    {
        $post = $this->getNewPost();
        $post->addTag($tag);
        $post->removeTag($tag);

        $this->assertEmpty($post->getTags());
    }

    public function testSetTags()
    {
        // TODO: implement
    }

    public function testGetCreatedAt()
    {
        // TODO: implement
    }

    public function testSetCreatedAt()
    {
        // TODO: implement
    }

    public function testGetUpdatedAt()
    {
        // TODO: implement
    }

    public function testSetUpdatedAt()
    {
        // TODO: implement
    }

    public function testUpdateTimestamps()
    {
        // TODO: implement
    }


    public function testIsPostPublished()
    {
        // TODO: implement
    }

    public function testIsPostCanBeModerate()
    {
        // TODO: implement
    }

    public function testIsPostAvailableForEditing()
    {
        // TODO: implement
    }

    public function testAddComment()
    {
        // TODO: implement
    }

    public function testRemoveComment()
    {
        // TODO: implement
    }

    public function testGetTextStatus()
    {
        // TODO: implement
    }
}