<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\ProductsOrdered;
use Mollie\Api\MollieApiClient;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductsOrderedRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/payment", name="payment")
     */
    public function index(SessionInterface $session, ProductRepository $productRepo): Response
    {
        $panier = $session->get('panier', []);
        $number = rand(10000, 1000000);

        $order = new Order();
        $order->setOrderNumber($number);
        $order->setClientName('montagner');
        $order->setClientFirstName('phillipe');
        $order->setCreatedAt(New \DateTime());
        $order->setStatus('En cours');


        
        var_dump($number);
        var_dump($number);
        var_dump($number);


        $panierWidthData = [];
       

        foreach($panier as $id => $quantity){ 
            

            $product = $productRepo->find($id);
            // dump($product->getCategory()->getName() . ' ,' . $product->getCollection()->getName());
            $productOrdered = new ProductsOrdered();

            $productOrdered->setName($product->getCategory()->getName() . ' ,' . $product->getCollection()->getName());
            $productOrdered->setQuantity($quantity);
            
            
            $totalPrice = $product->getPrice()->getTTC() * $quantity;
            
            // $order->addProductsOrdered();

            $panierWidthData[] = [
                'product' => $productRepo->find($id),
                'quantity' => $quantity
            ];
            
            
            $productOrdered->setPrice($totalPrice);
            $productOrdered->setOrderNumber($order);
            $this->em->persist($productOrdered);
            
            
        }
        
        
        $this->em->persist($order);
        $this->em->flush();

           
            

        
        
      
        
        

        // $mollie->setApiKey( "test_tEaK4mWagJFRUzWjSv5zbH8rjdw6tU");

        // $payment = $mollie->payments->create([
        //     "amount" => [
        //         "currency" => "EUR",
        //         "value" => "10.00"
        //     ],
        //     "description" => "My first API payment",
        //     "redirectUrl" => "https://webshop.example.org/order/12345/",
        //     "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
        // ]);

         return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
    /**
     * @Route("/order/{id}", name="order")
     */
    public function showOrder(MollieApiClient $mollie)
    {

        


        $mollie->setApiKey( "test_tEaK4mWagJFRUzWjSv5zbH8rjdw6tU");

        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => "10.00"
            ],
            "description" => "My first API payment",
            "redirectUrl" => "https://webshop.example.org/order/12345/",
            "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
        ]);

        // return $this->render('payment/index.html.twig', [
        //     'controller_name' => 'PaymentController',
        // ]);
    }
}
