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
use App\Helpers\Helper;

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
            $categorie->setUrl(Helper::generateUrl($categorie->getLibelle()));
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
            $categorie->setUrl(Helper::generateUrl($categorie->getLibelle()));
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
}
