<?php

namespace App\EntityListener;

use App\Entity\Ad;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class AdSlugListener.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class AdSlugListener
{
    protected SluggerInterface $slugger;

    /**
     * AdSlugListener constructor.
     *
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @param Ad $ad
     * @return void
     */
    public function prePersist(Ad $ad)
    {
        if (empty($ad->getSlug())) {
            $ad->setSlug($this->slugger->slug($ad->getTitle())->lower());
        }
    }
}