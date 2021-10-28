<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/user")
 */
class UserController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->_pathViews .= "user/";
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function indexAction(UserRepository $userRepository): Response
    {
        return $this->render($this->_pathViews . 'user_index.html.twig', [
            'users' => $userRepository->findAll(),
            'roles' => User::_userRoles()
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setCreation(new \Datetime('now'));
            $user->setRoles([$user->getRoleChoice()]);
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_edit', [
                'id'    =>  $user->getId()
            ]);
        }

        return $this->render($this->_pathViews . 'user_new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="user_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles([$user->getRoleChoice()]);
            $user->setModification(new \Datetime('now'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render($this->_pathViews . 'user_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
