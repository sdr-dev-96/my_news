<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/favoris")
 */
class FavorisController extends AbstractController
{
    /**
     * @Route("/new", name="ajax_new_favori", methods={"POST"})
     */
    public function newFavori(Request $request, ArticleRepository $articleRepository): JsonResponse
    {
        $response   = new JsonResponse('Une erreur est survenue !', 500);
        if($this->getUser()) {
            $article = $articleRepository->findOneBy(['id' => $request->request->get('articleId')]);
            $this->getUser()->addFavori($article);
            $this->getDoctrine()->getManager()->flush();
            $response = new JsonResponse('La favori a bien été ajouté !', 200);
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/{id}/delete", name="ajax_delete_favori", methods={"DELETE"})
     */
    public function deleteFavori(Article $article)
    {
        $response = false;
        if($this->getUser() && !empty($article)) {
            $this->getUser()->removeFavori($article);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('home/_favoris.html.twig', [
                'favoris' => $this->getUser()->getFavoris(),
            ]);
        }
        return $response;
    }
}