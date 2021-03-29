<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $productRepo): Response
    {

        $panier = $session->get('panier', []);
       
        $panierWidthData = [];

        foreach($panier as $id => $quantity){ 
            $panierWidthData[] = [
                'product' => $productRepo->find($id),
                'quantity' => $quantity
            ];
        }

        $total  = 0;

        foreach($panierWidthData as $item) {
            $totalItem = $item['product']->getPrice()->getTTC() * $item['quantity'];
            $total += $totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $panierWidthData,
            'total' =>  $total
        ]);
    }

     /**
     * @Route("/panier/add/{id}/", name="cart_add")
     */
    public function add($id, SessionInterface $session)
    {
        
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            var_dump($_GET["nbProduct"]);
            var_dump($panier[$id]);
                if($_GET["nbProduct"] == 1){
                    $nbProduct = 1;   
                    $panier[$id] += $nbProduct;                    
                }
                else{
                    $nbProduct = $_GET["nbProduct"];
                    $panier[$id] += $nbProduct;
                }
            
        } else {
            $panier[$id] = $_GET["nbProduct"];
        }
        $session->set('panier', $panier);
        dd($session->get('panier'));
    }
}
