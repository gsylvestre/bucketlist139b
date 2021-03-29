<?php

namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class WordOfTheDayGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
    }

    public function generateNew()
    {
        //$this->entityManager->getRepository()
        //$this->entityManager->persist();
    }

    public function getTodaysWord()
    {
        return "tabarnak";
    }
}