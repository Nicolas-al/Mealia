<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MollieController extends AbstractController
{
    
    /**
     * @Route("/mollie-webhook", name="webhook")
     */
    public function webhook(SessionInterface $session, OrderRepository $orderRepo, \Swift_Mailer $mailer, CartService $cartService, ProductRepository $productRepo, EntityManagerInterface $em): Response
    {
        $mollie = new \Mollie\Api\MollieApiClient(); 
        $mollie->setApiKey( "test_tEaK4mWagJFRUzWjSv5zbH8rjdw6tU");
        $payment = $mollie->payments->get($session->get('id_mollie'));
        $order = $orderRepo->findOneBy(['idMollie' => $session->get('id_mollie')]);


        if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
            /*
             * The payment is paid and isn't refunded or charged back.
             * At this point you'd probably want to start the process of delivering the product to the customer.
             */
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo($order->getClientEmail());
            $img = $message->embed(\Swift_Image::fromPath('assets/images/Logo-couleur-vectorisé.png'));

            $message->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'email/confirm-order.html.twig',
                ['name' => $order->getClientName(),
                'fistName' => $order->getClientFirstName(),
                'order_number' => $order->getOrderNumber(),
                'price' => $order->getPrice(),
                'products_ordered' => $order->getProductsOrdereds(),
                'order_createdAt' => $order->getCreatedAt(),
                'img' => $img
                ]
            ),
            'text/html'
            );

            $mailer->send($message);

            return $this->redirectToRoute('order', ['order_number' => $order->getOrderNumber()]);    
        } 
        elseif ($payment->isOpen()) {
            /*
             * The payment is open.
             */
            return $this->redirectToRoute('main');    

        } 
        elseif ($payment->isPending()) {
            /*
             * The payment is pending.
             */
            return $this->redirectToRoute('main');    

        } 
        elseif ($payment->isFailed()) {
            /*
             * The payment has failed.
             */
            $panierWidthData = $cartService->getFullCart();
            if($session->has('panier')){ 
                foreach($panierWidthData as $item){
                    $product = $productRepo->findOneBy([ 'id' => $item['product']->getId()]);
                    $cartService->removeStock($item['product']->getId(), $product);
                }
            }
            $em->remove($order);
            $em->flush();
            return $this->redirectToRoute('main');    

        } 
        elseif ($payment->isExpired()) {
            /*
             * The payment is expired.
             */
            $panierWidthData = $cartService->getFullCart();
            if($session->has('panier')){ 
                foreach($panierWidthData as $item){
                    $product = $productRepo->findOneBy([ 'id' => $item['product']->getId()]);
                    $cartService->removeStock($item['product']->getId(), $product);
                }
            }
            $em->remove($order);
            $em->flush();
            return $this->redirectToRoute('main'); 


        } 
        elseif ($payment->isCanceled()) {
            /*
             * The payment has been canceled.
             */
            $panierWidthData = $cartService->getFullCart();
            if($session->has('panier')){ 
                foreach($panierWidthData as $item){
                    $product = $productRepo->findOneBy([ 'id' => $item['product']->getId()]);
                    $cartService->removeStock($item['product']->getId(), $product);
                }
            }
            $em->remove($order);
            $em->flush();
            return $this->redirectToRoute('main');    

        } 
        elseif ($payment->hasRefunds()) {
            /*
             * The payment has been (partially) refunded.
             * The status of the payment is still "paid"
             */
            return $this->redirectToRoute('main');    

        } 
        elseif ($payment->hasChargebacks()) {
            /*
             * The payment has been (partially) charged back.
             * The status of the payment is still "paid"
             */
        }
    }
}
