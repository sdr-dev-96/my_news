<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/categorie")
 */
class CategorieController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->_pathViews .= "categorie/";
    }

    /**
     * @Route("/", name="categorie_index", methods={"GET"})
     */
    public function indexAction(CategorieRepository $categorieRepository): Response
    {
        return $this->render($this->_pathViews . 'categorie_index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET","POST"})
     */
    public function newAction(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $categorie->setUrl($this->generate_url($categorie->getLibelle()));
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_edit', [
                'id'    =>  $categorie->getId()
            ]);
        }

        return $this->render($this->_pathViews . 'categorie_new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="categorie_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie->setUrl($this->generate_url($categorie->getLibelle()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render($this->_pathViews . 'categorie_edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }

    /**
     * Permet de générer une url à partir d'une chaîne de caractères
     * 
     * @author  Roro-Dev    <stefanedr.dev@gmail>
     * 
     * @param   string      $string     * 
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
}
