<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/commentaire")
 */
class CommentaireController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->_pathViews .= "commentaire/";
    }

    /**
     * @Route("/", name="commentaire_index", methods={"GET"})
     */
    public function indexAction(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render($this->_pathViews . 'commentaire_index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commentaire_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request, Commentaire $commentaire): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentaire_index');
        }

        return $this->render($this->_pathViews . 'commentaire_edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commentaire_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Commentaire $commentaire): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commentaire_index');
    }

    /**
     * @Route("/{id}/valid/{etat}", name="commentaire_valid", methods={"PUT"})
     */
    public function validAction(Commentaire $commentaire, int $etat)
    {
        $response   = new JsonResponse(['message' => 'Une erreur est survenue !'], 500);
        if ($this->getUser()) {
            $commentaire->setValid(($etat == 1));
            $this->getDoctrine()->getManager()->flush();
            $response = new JsonResponse([
                'message' => ($etat == 1) ? 'Le commentaire a bien été validé !' : 'Le commentaire a été refusé !'
            ], 200);
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
