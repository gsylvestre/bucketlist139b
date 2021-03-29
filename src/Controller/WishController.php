<?php

namespace App\Controller;

use App\Entity\Reaction;
use App\Entity\Wish;
use App\Form\ReactionType;
use App\Form\WishType;
use App\Repository\ReactionRepository;
use App\Repository\WishRepository;
use App\Utils\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/wishes/create", name="wish_create")
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        Censurator $censurator
    ): Response
    {
        //crée un wish vide pour que symfo puisse y injecter les données
        $wish = new Wish();

        //récupère le username du user connecté et l'affiche dans le form
        $username = $this->getUser()->getUsername();
        $wish->setAuthor($username);

        //crée le formulaire
        $wishForm = $this->createForm(WishType::class, $wish);

        //récupère les données soumises (s'il y a lieu)
        $wishForm->handleRequest($request);

        //si le formulaire est soumis et valide....
        if($wishForm->isSubmitted() && $wishForm->isValid()) {
            //hydrate les propriétés manquantes
            $wish->setLikes(0);
            $wish->setDateCreated(new \DateTime());
            $wish->setIsPublished(true);

            //censure le titre et la description
            $censoredTitle = $censurator->purify($wish->getTitle());
            $wish->setTitle($censoredTitle);

            //sauvegarde en bdd
            $entityManager->persist($wish);
            $entityManager->flush();

            //affiche un message sur la prochaine page
            $this->addFlash('success', 'Your wish has been created!');
            //redirige vers la page détail de ce nouveau wish
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render("wish/create.html.twig", [
            'wishForm' => $wishForm->createView()
        ]);
    }


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
    public function detail(
        int $id,
        WishRepository $wishRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        ReactionRepository $reactionRepository
    ): Response
    {
        //todo: requête à la bdd pour aller chercher les infos de ce wish dont nous avons l'id dans l'URL
        $wish = $wishRepository->find($id);

        $reactions = $reactionRepository->findBy(["wish" => $wish], ["dateCreated" => "DESC"], 20);

        $reaction = new Reaction();
        $reactionForm = $this->createForm(ReactionType::class, $reaction);

        $reactionForm->handleRequest($request);
        if ($reactionForm->isSubmitted() && $reactionForm->isValid()){
            $reaction->setWish($wish);
            $reaction->setDateCreated(new \DateTime());
            $entityManager->persist($reaction);
            $entityManager->flush();

            $this->addFlash('success', 'Super intéressant merci !');
            return $this->redirectToRoute('wish_detail', ['id' => $id]);
        }

        return $this->render('wish/detail.html.twig', [
            "wish" => $wish,
            "reactions" => $reactions,
            "reactionForm" => $reactionForm->createView()
        ]);
    }
}
