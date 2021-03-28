<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Psr\Log\LoggerInterface;
use App\Entity\User;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/api", name="api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/toto", name="api_news_get", methods={"GET"})
     */
    public function news_get(ArticleRepository $articleRepository) : JsonResponse
    {
        $serializer = new Serializer([new ObjectNormalizer()]);
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $articles = $articleRepository->findRandomArticle(1, 10);
        if(!empty($articles)) {
            $jsonResult = $serializer->normalize(
                $articles,
                'json', 
                [
                    AbstractNormalizer::ATTRIBUTES => ['id', 'titre', 'creation', 'modification', 'contenu', 'image', 'ecrivain', 'categorie'],
                    'groups' => ['ecrivain:read','article:read'],
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    }
                ]
            );
            $response = new JsonResponse($jsonResult, 200);
        } else {
            $response = new JsonResponse('Aucun article trouvé ...', 500);
        }
        return $response;
    }
}
