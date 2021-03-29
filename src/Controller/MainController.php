<?php

namespace App\Controller;

use App\Utils\WordOfTheDayGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(WordOfTheDayGenerator $wordOfTheDayGenerator): Response
    {
        $todaysWord = $wordOfTheDayGenerator->getTodaysWord();

        return $this->render('main/home.html.twig', [

        ]);
    }

    /**
     * @Route("/about-us", name="main_about_us")
     */
    public function aboutUs(): Response
    {
        return $this->render('main/about_us.html.twig', [

        ]);
    }

    /**
     * @Route("/test", name="main_test")
     */
    public function test(): Response
    {
        return $this->render('main/test.html.twig', [

        ]);
    }
}
