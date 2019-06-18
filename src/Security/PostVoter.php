<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    /**
     * @var string
     */
    const UPDATE = 'update';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [static::UPDATE])) {
            return false;
        }

        if (!$subject instanceof Post) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     * @throws \Exception
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case static::UPDATE:
                return $this->canUpdate($subject, $user);
            default:
                throw new \Exception("Unknown attribute $attribute");
        }
    }

    /**
     * @param Post $post
     * @param User $user
     * @return bool
     */
    private function canUpdate(Post $post, User $user): bool
    {
        return $post->getAuthor() === $user;
    }
}