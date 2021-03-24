<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wishes/{page}", name="wish_list", requirements={"page": "\d+"})
     */
    public function list(WishRepository $wishRepository, int $page = 1): Response
    {
        //todo: requête à la bdd pour aller chercher tous les wishes
        $result = $wishRepository->findWishList($page);
        $wishes = $result['result'];

        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes,
            "totalResultCount" => $result['totalResultCount'],
            "currentPage" => $page,
        ]);
    }

    /**
     * @Route("/wishes/detail/{id}", name="wish_detail")
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
