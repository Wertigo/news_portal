<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Factory\PostFactory;
use App\Factory\UserFactory;
use App\Service\UserService;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserServiceTest extends WebTestCase
{
    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var PostFactory
     */
    private $postFactory;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();
        $this->userFactory = new UserFactory();
        $this->userService = new UserService(
            $this->createMock(ObjectManager::class),
            $this->createMock(LoggerInterface::class)
        );
        $this->postFactory = new PostFactory();
    }

    /**
     * @throws \Exception
     */
    public function testActivateAccount()
    {
        $user = $this->userFactory->createNew();
        $this->userService->activateAccount($user);
        $this->assertEquals(User::STATUS_ACTIVE, $user->getStatus());
    }

    /**
     * @throws \Exception
     */
    public function testCalculateRating()
    {
        $user = $this->userFactory->createNew();
        $rating = 0;

        for ($i = 0; $i < 5; ++$i) {
            $post = $this->postFactory->createNew()->setRating(random_int(-100, 100));
            $user->addPost($post);
            $rating += $post->getRating();
        }

        $this->assertEquals($rating, $this->userService->calculateRating($user));
    }
}