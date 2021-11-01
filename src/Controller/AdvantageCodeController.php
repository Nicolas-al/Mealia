<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdvantageCodeController extends AbstractController
{
    /**
     * @Route("/carte-cadeau", name="advantage_code")
     */
    public function index(Request $request, CartService $cartService, SessionInterface $session): Response
    {

        // if ($request->isMethod('post')){
            
        //     $cartService->addGiftCard();
        // }
        $session->remove('panierGiftCard');
        return $this->render('advantage_code/index.html.twig', [
            'controller_name' => 'AdvantageCodeController',
        ]);
    }
}
