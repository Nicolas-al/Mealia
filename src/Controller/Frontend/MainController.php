<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/apropos", name="a_propos")
     */
    public function showPropos(){
        return $this->render('main/apropos.html.twig', [
        ]);
    }

    /**
     * @Route("/points-de-vente", name="points_sale")
     */
    public function showPointsSale(){

        return $this->render('main/points-sale.html.twig', [
        ]);
    }


}
 