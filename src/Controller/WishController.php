<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wishes/", name="wish_list")
     */
    public function list(WishRepository $wishRepository): Response
    {
        //todo: requête à la bdd pour aller chercher tous les wishes
        $wishes = $wishRepository->findBy(["isPublished" => true], ["dateCreated" => "DESC"], 20);

        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes
        ]);
    }

    /**
     * @Route("/wishes/{id}", name="wish_detail")
     */
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        //todo: requête à la bdd pour aller chercher les infos de ce wish dont nous avons l'id dans l'URL
        $wish = $wishRepository->find($id);

        return $this->render('wish/detail.html.twig', [
            "wish" => $wish
        ]);
    }
}
