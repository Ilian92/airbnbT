<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PropertyController extends AbstractController
{
    #[Route('/property', name: 'app_property')]
    public function index(): Response
    {
        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
        ]);
    }

    #[Route('/property/add', name: 'app_property_create')]
    public function propertyCreate(): Response
    {
        $form = $this->createForm(PropertyType::class);
        
        return $this->render('property/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
