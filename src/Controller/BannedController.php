<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BannedController extends AbstractController
{
    #[Route('/banned', name: 'app_banned')]
    public function index(): Response
    {
        // Si l'utilisateur n'est pas banni, le rediriger vers la home
        if (!$this->isGranted('ROLE_BANNED')) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('banned/index.html.twig', [
            'controller_name' => 'BannedController',
        ]);
    }
}
