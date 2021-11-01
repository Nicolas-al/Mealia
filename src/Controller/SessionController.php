<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    // private $session;

    // public function __construct(EntityManagerInterface $em, SessionInterface $session)
    // {
    //     $this->em = $em;
    //     $this->session = $session;
    // }
    
    // public function template()
    // {

    //     $productRepo = $this->em->getRepository(Product::class);
    //     if($this->session->has('panier')){
    //         $panier = $this->session->get('panier', []);
    //     }
    //     else{
    //         $idCartProduct = 0;
    //     }

    //     $panierWidthData = [];

    //     foreach($panier as $id => $quantity){ 
    //         $panierWidthData[] = [
    //             'product' => $productRepo->find($id),
    //             'quantity' => $quantity
    //         ];
    //         ;
    //     }

    //     foreach($panierWidthData as $item) {
    //         $idCartProduct = $item['product']->getId();
    //     }
     
    // }
}
