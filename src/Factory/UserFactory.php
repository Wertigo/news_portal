<?php

namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    /**
     * @return User
     *
     * @throws \Exception
     */
    public function createNew(): User
    {
        return (new User())
            ->setRoles([User::ROLE_USER])
            ->setStatus(User::STATUS_REGISTERED)
            ->setActivateToken($this->generateRandomString())
        ;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    private function generateRandomString()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
