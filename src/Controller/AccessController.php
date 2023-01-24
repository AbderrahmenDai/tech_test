<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccessController extends AbstractController
{
    #[Route('/access/user/{id}', name: 'access_user')]
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

    #[Route('/access/group/{id}', name: 'access_group')]
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

    #[Route('/access/create', name: 'access_create')]
    public function createAccess(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(AccessRightType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accessRight = $form->getData();
            $em->persist($accessRight);
            $em->flush();

            return $this->redirectToRoute('access_list');
        }

        return $this->render('access/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function checkAccess(string $username, string $module, string $function)
    {
    $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);
     if($user) {
        $accessControl = $this->getDoctrine()->getRepository(Access::class)->findOneBy(['user' => $user, 'module' => $module, 'function' => $function]);
        if($accessControl && $accessControl->getCanAccess()) {
            return true;
        }
        $group = $user->getGroup();
        if($group) {
            $accessControl = $this->getDoctrine()->getRepository(Access::class)->findOneBy(['group' => $group, 'module' => $module, 'function' => $function]);
            if($accessControl && $accessControl->getCanAccess()) {
                return true;
            }
        }
     }
    return false;
    }
}

