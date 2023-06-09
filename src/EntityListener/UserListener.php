<?php
declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
    private $hasher;

    public function  __construct(UserPasswordHasherInterface $providedHasher)
    {
        $this->hasher = $providedHasher;
    }
    public function prePersist(User $user)
    {
        $this->encodePassword($user);
    }
    public function preUpdate(User $user)
    {
        $this->encodePassword($user);
    }

    /**
     * Encode password bassed on plain password
     *
     * @param User $user
     * @return void
     */
    public function encodePassword(User $user)
    {
        if($user->getPlainPassword() === null)
        {
            return;
        }

        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $user->getPlainPassword()
            )
        );

        $user->setPlainPassword('');
    }
}