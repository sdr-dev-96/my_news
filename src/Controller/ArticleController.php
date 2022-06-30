<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/{url}.html", name="article_show", methods={"GET"})
     */
    public function showArticleAction(Article $article): Response
    {
        return $this->render('article/article_show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/c/{url}", name="categorie_show", methods={"GET"})
     */
    public function showCategorieAction(Categorie $categorie, Request $request, PaginatorInterface $paginator, CategorieRepository $catRepository): Response
    {
        $articles = $paginator->paginate(
            $categorie->getArticles(),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('categorie/categorie_show.html.twig', [
            'categorie' => $categorie,
            'categories' => $catRepository->findAll(),
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/ajax_new_favori", name="ajax_new_favori", methods={"POST"})
     */
    public function newFavoriAction(Request $request, ArticleRepository $articleRepository): JsonResponse
    {
        $response   = new JsonResponse('Une erreur est survenue !', 500);
        if ($this->getUser()) {
            $article = $articleRepository->findOneBy(['id' => $request->request->get('articleId')]);
            $this->getUser()->addFavori($article);
            $this->getDoctrine()->getManager()->flush();
            $response = new JsonResponse('La favori a bien été ajouté !', 200);
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/ajax_delete_favori/{id}", name="ajax_delete_favori", methods={"DELETE"})
     */
    public function deleteFavoriAction(Article $article)
    {
        $response = false;
        if ($this->getUser() && !empty($article)) {
            $this->getUser()->removeFavori($article);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('article/_articles.html.twig', [
                'articles'  =>  $this->getUser()->getFavoris(),
                'container' =>  ''
            ]);
        }
        return $response;
    }
}
