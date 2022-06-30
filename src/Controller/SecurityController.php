<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="app_register", methods={"POST", "GET"})
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) 
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {            
            $user           = $form->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            $user->setRoles(['ROLE_USER']);
            $entityManager  = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="app_login", methods={"POST", "GET"})
     */
    public function loginAction(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if(in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('article_index');
            }
            return $this->redirectToRoute('home');
        }
        $error          = $authenticationUtils->getLastAuthenticationError();
        $lastUsername   = $authenticationUtils->getLastUsername();
        
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error'         => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logoutAction()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/redirect", name="app_redirect", methods={"GET"})
     */
    public function redirectAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}