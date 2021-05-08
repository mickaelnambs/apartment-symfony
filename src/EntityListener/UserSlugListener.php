<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class UserSlugListener.
 */
class UserSlugListener
{
    protected SluggerInterface $slugger;

    /**
     * UserSlugListener constructor.
     *
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @param User $user
     * @return void
     */
    public function prePersist(User $user): void
    {
        if (empty($user->getSlug())) {
            $user->setSlug($this->slugger->slug($user->getFirstName())->lower());
        }
    }
}