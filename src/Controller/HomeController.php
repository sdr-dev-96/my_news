<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ArticleRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $artRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'randomArticle'   =>  $artRepo->findRandomArticle()[0],
            'articles'      =>  $artRepo->findAll(),
        ]);
    }
}
