<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\City;
use App\Form\CityType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddCityController extends AbstractController
{
    #[Route('/main', name: 'app_add_city')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $town = $doctrine->getRepository(City::class)->findAll();
        $data = $doctrine->getRepository(User::class)->findAll();
        if (!$data) {
            $this->addFlash('notice', 'No users in database');
        }
        return $this->render('add_city/index.html.twig', [
            // 'controller_name' => 'AddCityController',
            'user' => $data,
            'residence' => $town
        ]);
    }

    /**
     * @Route("addCity", name="addCity")
     */
    public function addCity(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);
        // Validácia obsahu DB -> ak už je mesto zadané, zablokovť odosielanie
        // if(){
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $managerRegistry->getManager();
            $em->persist($city);
            $em->flush();
            $this->addFlash('notice', 'City added !! ');

            return $this->redirectToRoute('app_main');
        }

        // }
        return $this->render('add_city/addCity.html.twig', [
            'city' => $form->createView(),
        ]);
    }

   
}
