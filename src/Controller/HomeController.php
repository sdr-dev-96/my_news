<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ArticleRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $artRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'randomArticle'   =>  $artRepo->findRandomArticle()[0],
            'articles'      =>  $artRepo->findAll(),
        ]);
    }

    /**
     * @Route("/profil", name="user_profil", methods={"GET", "POST"})
     */
    public function profil(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
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
}
