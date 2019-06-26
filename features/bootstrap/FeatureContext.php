<?php

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo 
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 * 
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     *
     * @throws Exception
     */
    public function loadTestData()
    {
        /** @var UserFactory $userFactory */
        $userFactory = $this->kernel->getContainer()->get(UserFactory::class);
        /** @var ObjectManager $manager */
        $manager = $this->kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        for ($i = 0; $i < 100; $i++) {
            $user = $userFactory->createNew();
            $user->setName("Test user: $i")
                ->setEmail("test_user$i@test.com")
                ->setPassword('test')
            ;
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @AfterScenario
     */
    public function deleteTestData()
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->kernel->getContainer()->get(UserRepository::class);
        $userRepository->createQueryBuilder('u')
            ->where('u.email LIKE :email')
            ->setParameter('email', '%@test.com')
            ->delete()
            ->getQuery()
            ->execute()
        ;
    }
}
