<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Factory\UserFactory;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetEmail()
    {
        $user = $this->getNewUser();

        // new user have empty email
        $this->assertEmpty($user->getEmail());

        $email = 'test@gmail.com';
        $user->setEmail($email);

        $this->assertEquals($email, $user->getEmail());
    }

    /**
     * @return User
     * @throws \Exception
     */
    private function getNewUser()
    {
        $factory = new UserFactory();

        return $factory->createNew();
    }

    /**
     * @throws \Exception
     */
    public function testGetUsername()
    {
        $user = $this->getNewUser();

        // new user have empty username
        $this->assertEmpty($user->getUsername());

        $username = 'test@gmail.com';
        $user->setEmail($username);

        $this->assertEquals($username, $user->getUsername());
    }

    /**
     * @throws \Exception
     */
    public function testGetRoles()
    {
        $user = $this->getNewUser();

        // new user have role ROLE_USER
        $this->assertEquals([User::ROLE_USER], $user->getRoles());

        $roles = [User::ROLE_USER, User::ROLE_ADMIN];
        $user->setRoles($roles);

        $this->assertEquals($roles, $user->getRoles());
    }
}