<?php

namespace App\Tests\Factory;

use App\Entity\User;
use App\Factory\UserFactory;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    /**
     * @param UserFactory $userFactory
     * @throws \Exception
     */
    public function testCreateNew()
    {
        $userFactory = new UserFactory();

        /** @var User $user */
        $user = $userFactory->createNew();

        // Factory create object
        $this->assertNotEmpty($user);

        // Factory create user object
        $this->assertEquals(get_class($user), User::class);

        // New user have registered status
        $this->assertEquals($user->getStatus(), User::STATUS_REGISTERED);

        // New user have activate token
        $this->assertNotEmpty($user->getActivateToken());
    }
}
