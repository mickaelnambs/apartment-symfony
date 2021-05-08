<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BaseController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class BaseController extends AbstractController
{
    protected EntityManagerInterface $em;

    /**
     * BaseController constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param object $object
     * @return boolean
     */
    public function save(object $object): bool
    {
        try {
            if (!$object->getId()) {
                $this->em->persist($object);
            }
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param object $object
     * @return boolean
     */
    public function remove(object $object): bool
    {
        try {
            if ($object) {
                $this->em->remove($object);
            }
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
