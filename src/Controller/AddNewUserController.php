<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\City;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

class AddNewUserController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('add_new_user/index.html.twig', [
            'controller_name' => 'AddNewUserController',
        ]);
    }

    /**
     * @Route("/create", name="createUser")
     */
    public function createUser(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'User created !! ');

            return $this->redirectToRoute('app_main');
        }
        return $this->render('add_city/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update{id}", name="update")
     */
    public function update(Request $request, ManagerRegistry $managerRegistry, $id): Response
    {
        $em = $managerRegistry->getManager();
        $user = $em->getRepository(User::class)->find($id);
        // $city = $em->getRepository(City::class)->findAll();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $em = $managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'User update !! ');

            return $this->redirectToRoute('app_main');
        }

        return $this->render('add_city/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, ManagerRegistry $managerRegistry, $id): Response
    {
        $em = $managerRegistry->getManager();
        $user = $em->getRepository(User::class)->find($id);
        // $em = $managerRegistry->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('notice', 'User deleted !! ');

        return $this->redirectToRoute('app_main');
    }
}
