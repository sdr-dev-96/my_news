<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\ProfilType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, PaginatorInterface $paginator, ArticleRepository $artRepo, CategorieRepository $catRepository): Response
    {
        $articles = $paginator->paginate(
            $artRepo->findBy(["online" => true]),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('home/index.html.twig', [
            'articles'          =>  $articles,
            'categories'        =>  $catRepository->findAll()
        ]);
    }

    /**
     * @Route("/profil", name="user_profil", methods={"GET", "POST"})
     */
    public function profil(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {            
            $user           = $form->getData();
            if(!empty($user->getPlainPassword())) {
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            }
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('home/profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function sitemap(Request $request): Response
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
            $this->renderView('home/sitemap.html.twig', 
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
