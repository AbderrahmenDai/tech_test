<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccessController extends AbstractController
{
    #[Route('/access/user/{id}', name: 'app_access')]
    public function index(Request $request, User $user)
    {
        $form = $this->createForm(AccessRightsType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Access rights for user ' . $user->getUsername() . ' have been updated.');
            return $this->redirectToRoute('access_rights_user', ['id' => $user->getId()]);
        }

        return $this->render('access_rights/user.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);

    }

    #[Route('/access/group/{id}', name: 'app_access')]
     public function groupAccessRights(Request $request, Group $group)
    {

        $form = $this->createForm(AccessRightsType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();

            $this->addFlash('success', 'Access rights for group ' . $group->getName() . ' have been updated.');
            return $this->redirectToRoute('access_rights_group', ['id' => $group->getId()]);
        }

        return $this->render('access_rights/group.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }
}

