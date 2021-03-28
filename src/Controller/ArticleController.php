<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

class ArticleController extends AbstractController
{
    /**
	 * @IsGranted("ROLE_USER")
     * @Route("/{id}/{url}.html", name="article_show", methods={"GET"})
     */
    public function showArticle(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
    
    /**
	 * @IsGranted("ROLE_USER")
     * @Route("/{url}", name="categorie_show", methods={"GET"})
     */
    public function showCategorie(Categorie $categorie, Request $request, PaginatorInterface $paginator): Response
    {
        $articles = $paginator->paginate(
            $categorie->getArticles(),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
            'articles'  =>  $articles
        ]);
    }
}
