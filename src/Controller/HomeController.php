<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;

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
    public function profil(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(RegisterType::class, $user);
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
