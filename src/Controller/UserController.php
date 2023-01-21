<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Group;
use App\Form\UserType;
use App\Form\GroupType;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private $em;

     public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);

    }

    
      #[Route('/user/new', name:"user_new", methods:'{"GET","POST"}')]
     public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

     
    #[Route("/user/{id}", name:"user_show", methods:'{"GET"}')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    
    #[Route("/user/{id}/edit", name:"user_edit", methods:'{"GET","POST"}')]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    public function execute($functionName, $username)
    {
        $function = $this->em->getRepository(User::class)->findOneBy(['name' => $functionName]);
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $username]);

        // check if the function and the user exist in the database
        if (!$function || !$user) {
            throw new \Exception('Function or user not found');
        }

        // check if the user has access to the function
        if ($user->hasAccessToFunction($function)) {
            return true;
        } else {
            return false;
        }
    }

}
