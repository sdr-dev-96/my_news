<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

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
    public function indexAction(ArticleRepository $articleRepository): Response
    {
        return $this->render(
            $this->_pathViews . 'article_index.html.twig',
            ['articles' => $articleRepository->findAll()]
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
            $article->setUrl($this->generate_url($article->getTitre()));
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
            $article->setUrl($this->generate_url($article->getTitre()));
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
     * Permet de générer une url à partir d'une chaîne de caractères
     * 
     * @author  Roro-Dev    <stefanedr.dev@gmail>
     * 
     * @param   string      $string
     * 
     * @return  string
     */
    private function generate_url(string $string): string
    {
        $url = "";
        if (!empty($string)) {
            $unwanted_array = array(
                'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
                'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
                'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
                'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', ' ' => '-', '--' => '-'
            );
            $string = strtr($string, $unwanted_array);
            $url = urlencode($string);
            $url = strtolower($url);
        }
        return $url;
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
            $article->setUrl($this->generate_url($article->getTitre()));
            $entityManager->persist($article);
            $entityManager->flush();
            $nbArticles++;
        }
        echo "Nombre d'articles modifiés : " . $nbArticles;
    }
}
