<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Categorie;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];
        $urls[] = ['loc' => $this->generateUrl('home'), 'changefreq' => 'daily', 'priority' => 1];

        // Catégories
        foreach ($this->getDoctrine()->getRepository(Categorie::class)->findAll() as $categorie) {
            $urls[] = [
                'loc' => $this->generateUrl('categorie_show', [
                    'url' => $categorie->getUrl(),
                ]),
                'changefreq' => 'daily', 'priority' => 2
            ];
        }

        // Articles
        foreach ($this->getDoctrine()->getRepository(Article::class)->findBy(["online" => true]) as $article) {
            $urls[] = [
                'loc' => $this->generateUrl('article_show', [
                    'id' => $article->getId(),
                    'url' => $article->getUrl(),
                ]),
                'lastmod' => $article->getDate()->format('Y-m-d'),
                'changefreq' => 'frequently', 'priority' => 1
            ];
        }
        
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', 
            [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200
        );
        
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
