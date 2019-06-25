<?php

namespace App\Tests\Service;

use App\Entity\Post;
use App\Factory\PostFactory;
use App\Service\PostService;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostServiceTest extends WebTestCase
{
    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var PostFactory
     */
    private $postFactory;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();
        $this->postService = new PostService(
            $this->createMock(ObjectManager::class),
            $this->createMock(LoggerInterface::class)
        );
        $this->postFactory = new PostFactory();
    }

    /**
     * @dataProvider getPosts
     *
     * @param Post $post
     *
     * @throws \Exception
     */
    public function testSendPostToModerate(Post $post)
    {
        if (Post::STATUS_DRAFT === $post->getStatus()) {
            // draft post change status to moderation check
            $this->assertTrue($this->postService->sendPostToModerate($post));
            $this->assertFalse($post->isPostCanBeModerate());
            $this->assertEquals(Post::STATUS_MODERATION_CHECK, $post->getStatus());
        } elseif (Post::STATUS_MODERATION_CHECK === $post->getStatus()) {
            // post on moderation not change status
            $this->assertFalse($this->postService->sendPostToModerate($post));
            $this->assertEquals(Post::STATUS_MODERATION_CHECK, $post->getStatus());
        } elseif (Post::STATUS_PUBLISHED === $post->getStatus()) {
            // published post not change status
            $this->assertFalse($this->postService->sendPostToModerate($post));
            $this->assertEquals(Post::STATUS_PUBLISHED, $post->getStatus());
        } elseif (Post::STATUS_DECLINED === $post->getStatus()) {
            // declined post not change status
            $this->assertFalse($this->postService->sendPostToModerate($post));
            $this->assertEquals(Post::STATUS_DECLINED, $post->getStatus());
        } else {
            throw new \Exception("Unknown post type {$post->getStatus()}");
        }
    }

    /**
     * @return array
     */
    public function getPosts()
    {
        return [
          [$this->postFactory->createNew()->setStatus(Post::STATUS_DRAFT)],
          [$this->postFactory->createNew()->setStatus(Post::STATUS_MODERATION_CHECK)],
          [$this->postFactory->createNew()->setStatus(Post::STATUS_PUBLISHED)],
          [$this->postFactory->createNew()->setStatus(Post::STATUS_DECLINED)],
        ];
    }

    /**
     * @dataProvider getPosts
     *
     * @param Post $post
     *
     * @throws \Exception
     */
    public function testPublishPost(Post $post)
    {
        $this->postService->publishPost($post);
        $this->assertEquals(Post::STATUS_PUBLISHED, $post->getStatus());
    }

    /**
     * @dataProvider getPosts
     *
     * @param Post $post
     *
     * @throws \Exception
     */
    public function testDeclinePost(Post $post)
    {
        $this->postService->declinePost($post);
        $this->assertEquals(Post::STATUS_DECLINED, $post->getStatus());
    }
}