<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wishes/", name="wish_list")
     */
    public function list(): Response
    {
        //todo: requête à la bdd pour aller chercher tous les wishes

        return $this->render('wish/list.html.twig', [

        ]);
    }

    /**
     * @Route("/wishes/{id}", name="wish_detail")
     */
    public function detail(int $id): Response
    {
        dump($id);
        //todo: requête à la bdd pour aller chercher les infos de ce wish dont nous avons l'id dans l'URL

        return $this->render('wish/detail.html.twig', [

        ]);
    }
}
