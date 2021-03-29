<?php


namespace App\Utils;


use Doctrine\ORM\EntityManagerInterface;

class Censurator
{
    const BAD_WORDS = ["shit", "purée", "putain", "viagra"];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    public function purify(string $text): string
    {
        //si on utilisait la bdd...
        //$this->entityManager->getRepository()

        foreach(self::BAD_WORDS as $badWord){
            $replacement =  mb_substr($badWord, 0, 1) . str_repeat("*", mb_strlen($badWord)-1);
            $text = str_ireplace($badWord, $replacement, $text);
        }

        return $text;
    }
}