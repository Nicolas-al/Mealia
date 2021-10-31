<?php

namespace App\Controller\Backend;

use App\Entity\Order;
use App\Form\OrderStatusType;
use Mollie\Api\MollieApiClient;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function __construct(OrderRepository $orderRepo, EntityManagerInterface $em){
        $this->orderRepo = $orderRepo;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserInterface $user, OrderRepository $orderRepo, ProductRepository $productRepo)
    {
        $currentDate = new \DateTime();

        $admin = $user;
        $products = $productRepo->findAll();
        $nbProducts = count($products);
        $orderCurrent = $this->orderRepo->findBy(['createdAt' => $currentDate]);
        $orders = $this->orderRepo->findAll();
        $nbOrderCurrentDay = count($orderCurrent);


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'admin' => $admin,
            'nbOrder' => $nbOrderCurrentDay,
            'nbProducts' => $nbProducts
        ]);
    }

    public function checkDateOrder() {
        
        $currentDate = new \DateTime();

        $orderCurrent = $this->orderRepo->findBy(['createdAt' => $currentDate]);
        $orders = $this->orderRepo->findAll();

        $date = date_format($currentDate, 'Y-m-d');
        // var_dump($date);
        $date7Days = strftime("%Y-%m-%d", strtotime($date . '- 7 days'));
        $dateOneMonth = strftime("%Y-%m-%d", strtotime($date . '- 1 month'));
        $dateThreeMonth = strftime("%Y-%m-%d", strtotime($date . '- 3 month'));

        dump($date7Days);
        foreach($orders as $order){
            $order->getCreatedAt();
            $dateOrder = date_format($order->getCreatedAt(), 'Y-m-d');
            // toutes les dates comprises entre le jour actuelles et 7 jours avant
            if ($dateOrder <= $date && $dateOrder > $date7Days) {
                // var_dump($dateOrder);
                $date = new \DateTime($dateOrder);
                dump($date);
                $orderC = $this->orderRepo->findBy(['createdAt' => $date]);
                $nbOrder = count($orderC); 
                dump($orderC);  
            }
            // toutes les dates comprises entre le jour actuelles et 1 mois avant
            // if ($dateOrder <= $date && $dateOrder > $dateOneMonth) {
            //     $dateOrder;
            // }
            // // toutes les dates comprises entre le jour actuelles et 3 mois avant
            // if ($dateOrder <= $date && $dateOrder > $dateThreeMonth) {
            //     $dateOrder;
            // }
        }

        

    }

    /**
     * @Route("/admin/fiches-produits", name="products_sheets")
     */
    public function productsSheets(ProductRepository $productRepo, Request $request)
    {
        $products = $productRepo->findAll();
        $form = $this->createFormBuilder()
            ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            { 
                if ($request->request->has('disable'))
                {
                    $keys = $request->request->keys();
                    // var_dump($request->request->keys());
                    foreach($keys as $key){
                        foreach($products as $product){
                            if ($product->getId() == $key){
                                $product->setOnline(0);                            
                            }
                        }
                    }
                }
                if ($request->request->has('renew'))
                {
                    $keys = $request->request->keys();
                    // var_dump($request->request->keys());
                    foreach($keys as $key){
                        foreach($products as $product){
                            if ($product->getId() == $key){
                                $product->setOnline(1);
                            }
                        }
                    }
                    
                }
                if ($request->request->has('delete'))
                {
                    $keys = $request->request->keys();
                    // var_dump($request->request->keys());
                    foreach($keys as $key){
                        foreach($products as $product){
                            if ($product->getId() == $key){
                                $this->remove($product, $request);
                            }
                        }
                    }
                    
                    
                }
                $this->em->flush();
                return $this->redirectToRoute('products_sheets');
            }
         
        return $this->render('admin/products/sheets.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    public function remove($product, $request)
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token'))){ 
        $this->em->remove($product);
        return $this->em->flush();
        }
    }

    /**
     * @Route("/admin/fiches-produits/produit/{id}", name="product_sheet")
     */
    public function productSheet($id, ProductRepository $productRepo, Request $request)
    {
        $products = $productRepo->findBy(['id' => $id]);

        $form = $this->createFormBuilder()
            ->getForm();
            $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        { 
            if ($request->request->has('disable'))
            {
                $keys = $request->request->keys();
                // var_dump($request->request->keys());
                foreach($keys as $key){
                    foreach($products as $product){
                        if ($product->getId() == $key){
                            $product->setOnline(0);                            
                        }
                    }
                }
            }
            if ($request->request->has('renew'))
            {
                $keys = $request->request->keys();
                    // var_dump($request->request->keys());
                    foreach($keys as $key){
                        foreach($products as $product){
                            if ($product->getId() == $key){
                                $product->setOnline(1);
                            }
                        }
                    }            
            }
            if ($request->request->has('delete'))
            {
                $keys = $request->request->keys();
                    // var_dump($request->request->keys());
                    foreach($keys as $key){
                        foreach($products as $product){
                            if ($product->getId() == $key){
                                $this->remove($product, $request);
                            }
                        }
                    }           
            }
        $this->em->flush();
        return $this->redirectToRoute('product_sheets');
        }

        return $this->render('admin/products/sheets.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/commandes", name="order_admin")
     */
    public function Order(OrderRepository $orderRepo, Request $request, \Swift_Mailer $mailer){

        $orders = $orderRepo->findAll();
        setlocale(LC_TIME, "fr_FR");
        $dateOrders = $orderRepo->findCreatedAt();
        foreach($orders as $order){

            $dateOrder = $order->getCreatedAt();
            $date = date_format($dateOrder, 'Y-m-d');
            $dateOrder = strftime("%A %d %B", strtotime($date));
            $productsOrdered = $order->getProductsOrdereds();   
            $dateArray = [$dateOrder];
        }
        // $form = $this->createFormBuilder()
        // ->getForm();

        // if ($request->request->count() > 0) 
        // { 
        //     $orderSelect = $orderRepo->findOneBy(['id' => $request->request->get('input_delivery')]);
        //     $delivery = $orderSelect->getDelivery();
        //     $delivery->setStatus($request->request->get('delivery'));
        //     $this->em->persist($delivery);
        //     $this->em->flush();
        // }

        if($request->isMethod('post')){
            dump($request->request->get('tracking-number'));
            $order = $orderRepo->findOneBy(['id' => $request->request->get('id-order-tracking')]);
            $trackingNumber = $request->request->get('tracking-number');



            // on modifie le statut d'expedition de la commande à expédié
            $delivery = $order->getDelivery();
            $delivery->setStatus('Expédiée');
            $this->em->persist($delivery);
            $this->em->flush();


            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('contact@mealia.fr')
            ->setTo($order->getClientEmail());
            $img = $message->embed(\Swift_Image::fromPath('assets/images/Logo-couleur-vectorisé.png'));

            $message->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'email/order-delivery.html.twig',
                [
                'products_ordered' => $order->getProductsOrdereds(),
                'img' => $img,
                'order' => $order,
                'trackingNumber' => $trackingNumber
                ]
            ),
            'text/html',
            'iso-8859-2'
            );
            $message->setCharset('UTF-8');
            // $message->setEncoder(\Swift_DependencyContainer::getInstance()->lookup('mime.base64contentencoder'));



            $mailer->send($message);
            // if($request)
        }


        return $this->render('admin/order.html.twig', [
            'orders' => $orders,
            'date' => $dateOrder,
            'productsOrdered' => $productsOrdered,
            // 'form' => $form->createView()
        ]);

    }

    public function getDatesOrders(){
        $orders = $this->orderRepo->findAll();
        setlocale(LC_TIME, "fr_FR");
        $dateOrders = $this->orderRepo->findCreatedAt();
        
        return $dateOrders;

        // foreach($orders as $order){

        //     $dateOrder = $order->getCreatedAt();
        //     $date = date_format($dateOrder, 'Y-m-d');
        //     $dateOrder = strftime("%A %d %B", strtotime($date));

        //     return $dateOrder;
        // }
    }

    /**
     * @Route("/admin/commandes/order/refund/{id}", name="refund_order")
     */
    // public function refundOrder($id){
    //     $order = $this->orderRepo->findOneBy(['id' => $id]);
    //     $idMollie = $order->getIdMollie();


    //     $mollie = new \Mollie\Api\MollieApiClient();
    //     $mollie->setApiKey( "test_tEaK4mWagJFRUzWjSv5zbH8rjdw6tU");

    //     $paymentId = $idMollie;
    //     $payment = $mollie->payments->get($paymentId);

    //     return $refund = $payment->refund([
    //         "amount" => [
    //             "currency" => "EUR",
    //             "value" => sprintf("%.2F", $order->getPrice()), // You must send the correct number of decimals, thus we enforce the use of strings
    //         ],
    //     ]);

        
    // }

}
