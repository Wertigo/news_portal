<?php
namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Entity\User;
use App\Entity\Post;
use App\Factory\PostFactory;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class Api extends \Codeception\Module
{
    /**
     * @var Post
     */
    private $post = null;

    /**
     * @return int
     *
     * @throws \Codeception\Exception\ModuleException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createTestPost()
    {
        /** @var \App\Kernel $kernel */
        $kernel = $this->getModule('Symfony')->kernel;
        $container = $kernel->getContainer();
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        /** @var PostFactory $postFactory */
        $postFactory = new PostFactory();
        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);
        /** @var ObjectManager $manager */
        $post = $postFactory->createNew();
        $post->setStatus(Post::STATUS_PUBLISHED)
            ->setAuthor($this->getRandomUser($userRepository))
            ->setRating(0)
            ->setTitle('Test post')
            ->setContent('Test content')
        ;
        $entityManager->persist($post);
        $entityManager->flush();
        $this->post = $post;

        return $this->post;
    }

    /**
     * @param UserRepository $userRepository
     * @return mixed
     */
    private function getRandomUser(UserRepository $userRepository)
    {
        $users = $userRepository->findAllActivatedUsers();
        $index = array_rand($users);

        return $users[$index];
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function deleteTestPost()
    {
        /** @var \App\Kernel $kernel */
        $kernel = $this->getModule('Symfony')->kernel;
        $container = $kernel->getContainer();
        /** @var EntityManager $entityManager */
        $manager = $container->get('doctrine.orm.entity_manager');

        if (null !== $this->post) {
            $manager->remove($this->post);
        }
    }
}
