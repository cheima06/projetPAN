<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PrestationController extends AbstractController
{
    #[Route('/prestation', name: 'app_prestation')]
    public function index(): Response
    {
        return $this->render('prestation/index.html.twig', [
            'controller_name' => 'PrestationController',
        ]);
    }

    #[Route('/avis', name: 'app_avis')]
    public function avis(): Response
    {
        return $this->render('prestation/index.html.twig', [
            'controller_name' => 'PrestationController',
        ]);
    }

    
    #[Route('/portfolio', name: 'app_portfolio')]
    public function portfolio(): Response
    {
        return $this->render('prestation/index.html.twig', [
            'controller_name' => 'PrestationController',
        ]);
    }


    

}
