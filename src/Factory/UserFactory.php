<?php

namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    /**
     * @return User
     */
    public function createNew(): User
    {
        return (new User)
            ->setRoles([User::ROLE_USER])
            ->setStatus(User::STATUS_REGISTERED)
        ;
    }
}