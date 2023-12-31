<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    const OWNER = 'owner';

    protected function supports(string $attribute, $subject): bool
    {
        if ($attribute !== self::OWNER || !$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        // you know $subject is a Task object, thanks to `supports()`
        /** @var Task $subject */
        $task = $subject;

        if ($task->getUser() === null) {
            return $user->isAdmin();
        }

        return $user === $task->getUser();
    }
}