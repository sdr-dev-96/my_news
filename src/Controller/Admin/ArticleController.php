<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Helpers\Helper;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin/article")
 */
class ArticleController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->_pathViews .= "article/";
    }

    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function indexAction(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $paginator->paginate(
            $articleRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render(
            $this->_pathViews . 'article_index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function newAction(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $article
                ->setEcrivain($this->getUser())
                ->setModification(new \Datetime('now'))
                ->setCreation(new \Datetime('now'));
            $article->setUrl(Helper::generateUrl($article->getTitre()));
            if (!empty($form->get('image')->getData())) {
                $file       = $form->get('image')->getData();
                $fileName   = $file->getClientOriginalName();
                $article->setImage($fileName);
            }
            $entityManager->persist($article);
            $entityManager->flush();

            if (!empty($form->get('image')->getData())) {
                $file->move($this->getParameter('upload_directory') . '/article/' . $article->getId(), $fileName);
            }

            return $this->redirectToRoute('article_edit', [
                'id'    =>  $article->getId()
            ]);
        }

        return $this->render($this->_pathViews . 'article_new.html.twig', [
            'article'   => $article,
            'form'      => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AUTHOR")
     * @Route("/edit/{id}", name="article_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setModification(new \Datetime('now'));
            $article->setUrl(Helper::generateUrl($article->getTitre()));
            if (!empty($form->get('image')->getData())) {
                $file       = $form->get('image')->getData();
                $fileName   = $file->getClientOriginalName();
                $article->setImage($fileName);
            }
            $this->getDoctrine()->getManager()->flush();

            if (!empty($form->get('image')->getData())) {
                $file->move($this->getParameter('upload_directory') . '/article/' . $article->getId(), $fileName);
            }
            $this->addFlash('success', 'L\'article a bien été modifié !');
        }

        return $this->render($this->_pathViews . 'article_edit.html.twig', [
            'article'   => $article,
            'form'      => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="article_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/generateurl", name="article_generate_url", methods={"GET"})
     */
    public function generateArticleUrlAction(ArticleRepository $articleRepository)
    {
        $arrayArticles = $articleRepository->findAll();
        $entityManager = $this->getDoctrine()->getManager();
        $nbArticles = 0;
        foreach ($arrayArticles as $article) {
            $article->setUrl(Helper::generateUrl($article->getTitre()));
            $entityManager->persist($article);
            $entityManager->flush();
            $nbArticles++;
        }
        echo "Nombre d'articles modifiés : " . $nbArticles;
    }
}
