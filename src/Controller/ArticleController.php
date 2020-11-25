<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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
                
            if (!empty($form->get('image')->getData())) {
                $file       = $form->get('image')->getData();
                $fileName   = $file->getClientOriginalName();
                $article->setImage($fileName);
            }
            $entityManager->persist($article);
            $entityManager->flush();

            if (!empty($form->get('image')->getData())) {
                $file->move($this->getParameter('upload_directory').'/article/'.$article->getId(), $fileName);
            }

            return $this->redirectToRoute('article_edit', [
                'id'    =>  $article->getId()
            ]);
        }

        return $this->render('article/new.html.twig', [
            'article'   => $article,
            'form'      => $form->createView(),
        ]);
    }

    /**
	 * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
	 * @IsGranted("ROLE_WRITER")
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setModification(new \Datetime('now'));

            if (!empty($form->get('image')->getData())) {
                $file       = $form->get('image')->getData();
                $fileName   = $file->getClientOriginalName();
                $article->setImage($fileName);
            }
            $this->getDoctrine()->getManager()->flush();

            if (!empty($form->get('image')->getData())) {
                $file->move($this->getParameter('upload_directory').'/article/'.$article->getId(), $fileName);
            }
            $this->addFlash('success', 'L\'article a bien été modifié !');
        }

        return $this->render('article/edit.html.twig', [
            'article'   => $article,
            'form'      => $form->createView(),
        ]);
    }

    /**
	 * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }

   
}
