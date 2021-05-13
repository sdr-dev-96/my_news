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
use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/api", name="api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route(name="api_login", path="/login_check")
     * @return JsonResponse
     */
    public function api_login(): JsonResponse
    {
        $user = $this->getUser();

        return new Response([
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * @Route("/articles", name="api_news_get", methods={"GET"})
     */
    public function news_get(ArticleRepository $articleRepository) : JsonResponse
    {
        $serializer = new Serializer([new ObjectNormalizer()]);
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $articles = $articleRepository->findBy(['online' => true], null, 2);
        if(!empty($articles)) {
            $json = $serializer->normalize(
                $articles,
                'json', 
                [
                    AbstractNormalizer::ATTRIBUTES => Article::_apiFields(),
                    'groups' => ['ecrivain:read','article:read'],
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    }
                ]
            );
            $response = new JsonResponse($json, 200);
        } else {
            $response = new JsonResponse('Aucun article trouvé ...', 500);
        }
        return $response;
    }

    /**
     * @Route("/categories", name="api_categories_get", methods={"GET"})
     */
    public function categorie_get(CategorieRepository $categorieRepository) : JsonResponse
    {
        $serializer = new Serializer([new ObjectNormalizer()]);
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $categories = $categorieRepository->findAll();
        if(!empty($categories)) {
            $json = $serializer->normalize(
                $categories,
                'json',
                [
                    AbstractNormalizer::ATTRIBUTES => Categorie::_apiFields(),
                    'groups' => ['categorie:read'],
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    }
                ]
            );
            $response = new JsonResponse($json, 200);
        } else {
            $response = new JsonResponse('Aucune catégorie trouvée ...', 500);
        }
        return $response;
    }
}
