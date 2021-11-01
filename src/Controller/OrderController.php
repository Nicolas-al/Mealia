<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Adress;
use App\Form\OrderType;
use App\Entity\Delivery;
use App\Form\DeliveryType;
use App\Form\AdressOrderType;
use App\Entity\ProductsOrdered;
use App\Entity\ZeroStock;
use App\Form\OrderInfoSuppType;
use App\Form\PaymentMethodType;
use App\Service\Cart\CartService;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Length;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    public function __construct(EntityManagerInterface $em,  SessionInterface $session)
    {
        $this->em = $em;
        $this->mollie = new \Mollie\Api\MollieApiClient();
    }

     /**
     * @Route("/commande/compte-utilisateur", name="order_user_account")
     */
    public function userAccount(AuthenticationUtils $authenticationUtils, SessionInterface $session, CartService $cartService)
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('delivery');
        }

        if(!$session->has('panier')){
            return $this->redirectToRoute('main');
        }
       

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        $panier = $session->get('panier', []);

        $panierWidthData = $cartService->getFullCart();

        
        $session->set('secondPanier', $panier);

        return $this->render('order/user-account.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/commande/informations-client", name="client_informations")
     */
    public function clientInformations(Request $request, SessionInterface $session, CartService $cartService): Response
    {

        if(!$session->has('panier')){
            return $this->redirectToRoute('main');
        }

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $panier = $session->get('panier', []);


        $panierWidthData = $cartService->getFullCart();

        
        $session->set('secondPanier', $panier);

           // 2) handle the submit (will only happen on POST)
           $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) 
           {

                // $adressSession = $session->get('adress', []);

                $adressData = [];
               
                    $adressData = [
                        'name' => $request->request->get('order')['clientName'],
                        'firstName' => $request->request->get('order')['clientFirstName'],
                        'email' => $request->request->get('order')['clientEmail'],
                        'street' => $request->request->get('order')['adress']['street'],
                        'adressSupplement' => $request->request->get('order')['adress']['adressSupplement'],
                        'city' => $request->request->get('order')['adress']['city'],
                        'zipCode' => $request->request->get('order')['adress']['zipCode'],
                        'country' => $request->request->get('order')['adress']['country'],
                    ];
                    
                $session->set('adress', $adressData);
             
                return $this->redirectToRoute('delivery');
           }

        return $this->render('order/save-client.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/commande/livraison/type", name="delivery")
     */
    public function Delivery(SessionInterface $session, CartService $cartService, Request $request):Response
    {
        if(!$session->has('panier')){
            return $this->redirectToRoute('main');
        }

        $panier = $session->get('panier', []);
        
        $adress = new Adress();
        $form = $this->createForm(DeliveryType::class, $adress);

        // formulaire type de livraison (Colissimo ou Lettre suivie)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $deliveryData = [
                'country' => $request->request->get('delivery')['country'],
                'type' =>   $request->request->get('delivery-type')
                        ];

            $session->set('delivery', $deliveryData);
            if (!$session->get('price_supp')){ 
                if ($deliveryData['type'] == 'Lettre suivie')
                {
                    $session->set('price_supp', '2.16');
                    $session->set('delivery_spawning', '2.16');
                }
                if($deliveryData['type'] == 'Colissimo')
                {
                    $session->set('price_supp', '4.95');
                    $session->set('delivery_spawning', '4.95');
                }
            }

            return $this->redirectToRoute('adress_informations');
        }
        $panierWidthData = $cartService->getFullCart();

        $total  = $cartService->getTotal();

        $nbProductsPanier = count($panierWidthData);

   

        setlocale(LC_TIME, "fr_FR");
        $date = date('Y-m-d');
        $day = date('d');
        $month = date('m');
        $years = date('Y');
        $dateCurrent = strftime("%A %d %B", strtotime($date));
        $dayColissimo1 = (strftime("%d", strtotime($date . '+ 3 days')));
        $monthColissimo1 = (strftime("%m", strtotime($date . '+ 3 days')));
        $yearsColissimo1 = (strftime("%Y", strtotime($date . '+ 3 days')));

        $dayColissimo2 = (strftime("%d", strtotime($date . '+ 8 days')));
        $monthColissimo2 = (strftime("%m", strtotime($date . '+ 8 days')));
        $yearsColissimo2 = (strftime("%Y", strtotime($date . '+ 8 days')));

        $dateColissimo1 = (strftime("%A %d %B", strtotime($date . '+ 3 days')));
        $dateColissimo2 = (strftime("%A %d %B", strtotime($date . '+ 8 days')));
        if ($this->jour_ferie(mktime(0,0,0, $monthColissimo1, $dayColissimo1 ,$yearsColissimo1)))
        {
            $dateColissimo1 = (strftime("%A %d %B", strtotime($date . '+ 4 days')));

        }
        if ($this->jour_ferie(mktime(0,0,0, $monthColissimo2, $dayColissimo2 ,$yearsColissimo2)))
        {
            $dateColissimo2 = (strftime("%A %d %B", strtotime($date . '+ 9 days')));

        }

        return $this->render('order/payment/delivery.html.twig', [
            'items' => $panierWidthData,
            'total' =>  $total,
            'nbPanier' => $nbProductsPanier,
            'form' => $form->createView(),
            'dateCurrent' => $dateCurrent,
            'dateColissimo1' => $dateColissimo1,
            'dateColissimo2' => $dateColissimo2
        ]);
    }

    function jour_ferie($timestamp)
    {
        $jour = date("d", $timestamp);
        $mois = date("m", $timestamp);
        $annee = date("Y", $timestamp);
        $EstFerie = 0;
        // dates fériées fixes
        if($jour == 1 && $mois == 1) $EstFerie = 1; // 1er janvier
        if($jour == 1 && $mois == 5) $EstFerie = 1; // 1er mai
        if($jour == 8 && $mois == 5) $EstFerie = 1; // 8 mai
        if($jour == 14 && $mois == 7) $EstFerie = 1; // 14 juillet
        if($jour == 15 && $mois == 8) $EstFerie = 1; // 15 aout
        if($jour == 1 && $mois == 11) $EstFerie = 1; // 1 novembre
        if($jour == 11 && $mois == 11) $EstFerie = 1; // 11 novembre
        if($jour == 25 && $mois == 12) $EstFerie = 1; // 25 décembre
        // fetes religieuses mobiles
        $pak = easter_date($annee);
        $jp = date("d", $pak);
        $mp = date("m", $pak);
        if($jp == $jour && $mp == $mois){ $EstFerie = 1;} // Pâques
        $lpk = mktime(date("H", $pak), date("i", $pak), date("s", $pak), date("m", $pak)
        , date("d", $pak) +1, date("Y", $pak) );
        $jp = date("d", $lpk);
        $mp = date("m", $lpk);
        if($jp == $jour && $mp == $mois){ $EstFerie = 1; }// Lundi de Pâques
        $asc = mktime(date("H", $pak), date("i", $pak), date("s", $pak), date("m", $pak)
        , date("d", $pak) + 39, date("Y", $pak) );
        $jp = date("d", $asc);
        $mp = date("m", $asc);
        if($jp == $jour && $mp == $mois){ $EstFerie = 1;}//ascension
        $pe = mktime(date("H", $pak), date("i", $pak), date("s", $pak), date("m", $pak),
        date("d", $pak) + 49, date("Y", $pak) );
        $jp = date("d", $pe);
        $mp = date("m", $pe);
        if($jp == $jour && $mp == $mois) {$EstFerie = 1;}// Pentecôte
        $lp = mktime(date("H", $asc), date("i", $pak), date("s", $pak), date("m", $pak),
        date("d", $pak) + 50, date("Y", $pak) );
        $jp = date("d", $lp);
        $mp = date("m", $lp);
        if($jp == $jour && $mp == $mois) {$EstFerie = 1;}// lundi Pentecôte
        // Samedis et dimanches
        $jour_sem = jddayofweek(unixtojd($timestamp), 0);
        if($jour_sem == 0) $EstFerie = 1;
        // ces deux lignes au dessus sont à retirer si vous ne désirez pas faire
        // apparaitre les
        // samedis et dimanches comme fériés.
        return $EstFerie;
    }

    /**
     * @Route("/commande/informations-adresse", name="adress_informations")
     */
    public function adressInformations(SessionInterface $session, Request $request, CartService $cartService,  ProductRepository $productRepo, OrderRepository $orderRepo){
        
        if(!$session->has('panier')){
            return $this->redirectToRoute('main');
        }

        $adressSession = $session->get('adress');
        $adressSession2 = $session->get('adress2');
        $deliverySession = $session->get('delivery');
        $priceSupp = $session->get('price_supp');


        $adress = new Adress();
        $order = new Order();
        $delivery = new Delivery();
        $number = rand(10000, 1000000);
    
        //si il laisse l'adresse déjà enregistré
        $user = $this->getUser();
        $formAdress = $this->createForm(AdressOrderType::class, $adress, array('attr' => array( 'id' => 'adress_form')));

        // si l'utilisateur rempli le formulaire pour une autre adresse de livraison
        $formAdress->handleRequest($request);
        if ($formAdress->isSubmitted() && $formAdress->isValid()) {
        
            $adressData = [];
            
            //on enregistre les données en session
                $adressData = [
                    'name' => $request->request->get('adress_order')['name'],
                    'firstName' => $request->request->get('adress_order')['firstName'],
                    'street' => $request->request->get('adress_order')['street'],
                    'adressSupplement' => $request->request->get('adress_order')['adressSupplement'],
                    'city' => $request->request->get('adress_order')['city'],
                    'zipCode' => $request->request->get('adress_order')['zipCode'],
                    'country' => $request->request->get('adress_order')['country'],
                ];
                
            $session->set('adress2', $adressData);
        }
        
        $panier = $session->get('panier', []);
        $panierWidthData = [];
        foreach($panier as $id => $quantities){ 
            foreach($quantities as $quantity){            
            $product = $productRepo->find($id);
            $productOrdered = new ProductsOrdered();
            $productOrdered->setProductId($product->getId());
            $productOrdered->setName($product->getName());
            $productOrdered->setImage($product->getImage()->getOne());
            if($quantity['size'] == "stockSizeOne"){
                $price = $product->getSize()->getPriceSizeOne();
                $productOrdered->setSizeOne($product->getSize()->getSizeOne());
            }
            elseif($quantity['size'] == "stockSizeTwo"){
                $price = $product->getSize()->getPriceSizeTwo();
                $productOrdered->setSizeTwo($product->getSize()->getSizeTwo());
            }
            elseif($quantity['size'] == "stockSizeThree"){
                $price = $product->getSize()->getPriceSizeThree();
                $productOrdered->setSizeThree($product->getSize()->getSizeThree());
            }
            $panierWidthData[] = [
                'product' => $productRepo->find($id),
                'quantity' => $quantity
            ];
            $productOrdered->setPrice($price);
            $productOrdered->setQuantity($quantity['quantity']);
            $totalPrice = $price * $quantity['quantity'];
            $productOrdered->setTotalPrice($totalPrice);
            $productOrdered->setOrderNumber($order);
            $productOrdered->setUpdatedAt(new \DateTime);
            $this->em->persist($productOrdered);
            };    
        };
        $total  = $cartService->getTotal();
        $total += $priceSupp;
        
        // formulaire qui fait office de redirection vers le recap de la commande, on enregistre toute les données de commande en base
        $formOrderInfoSupp = $this->createForm(OrderInfoSuppType::class, $order, array('attr' => array( 'id' => 'order_info_supp_form')));
        $formOrderInfoSupp->handleRequest($request);
        if ($formOrderInfoSupp->isSubmitted() && $formOrderInfoSupp->isValid()) {
            
            //si un utilisateur est connécté
            if($this->isGranted("ROLE_USER")){

                //si il existe une session $adress2 qui serait différente de l'adresse principal de l'utilisateur
                if ($session->has('adress2'))
                {
                    $adress->setFirstName($adressSession2['firstName']);
                    $adress->setName($adressSession2['name']);
                    $adress->setCountry($adressSession2['country']);
                    $adress->setStreet($adressSession2['street']);
                    $adress->setAdressSupplement($adressSession2['adressSupplement']);
                    $adress->setZipCode($adressSession2['zipCode']);
                    $adress->setCity($adressSession2['city']);
                    $order->setAdress($adress);
                }
                else{
                    $adress->setFirstName($user->getFirstName());
                    $adress->setName($user->getLastName());
                    $adress->setCountry($user->getAdress()->getCountry());
                    $adress->setStreet($user->getAdress()->getStreet());
                    $adress->setAdressSupplement($user->getAdress()->getAdressSupplement());
                    $adress->setZipCode($user->getAdress()->getZipCode());
                    $adress->setCity($user->getAdress()->getCity());
                    $order->setAdress($adress);
                }
                $order->setClientName($user->getLastName());
                $order->setClientFirstName($user->getFirstName());
                $order->setClientEmail($user->getEmail());
                $order->setUser($user);
            }
            //si pas d'utilisateur, alors on enregistre l'adresse de session $adress
            else{

                $adress->setFirstName($adressSession['firstName']);
                $adress->setName($adressSession['name']);
                $adress->setCountry($adressSession['country']);
                $adress->setStreet($adressSession['street']);
                $adress->setAdressSupplement($adressSession['adressSupplement']);
                $adress->setZipCode($adressSession['zipCode']);
                $adress->setCity($adressSession['city']);
                $order->setAdress($adress);

                $order->setClientName($adressSession['name']);
                $order->setClientFirstName($adressSession['firstName']);
                $order->setClientEmail($adressSession['email']);
            }
            $currentDate = date('Y/m/d');

            $order->setOrderNumber($number);
            $order->setCreatedAt(new \DateTime(date('Y-m-d')));
            $order->setStatus('En cours');
            $order->setIdMollie('en attente');
            $order->setPaymentType('en attente');

                

            $order->setComment($request->request->get('order_info_supp')['comment']);
            if(count($request->request->get('order_info_supp')) <= 2){
                $order->setCommentGiftCard("");
            }
            else{
                $order->setCommentGiftCard($request->request->get('order_info_supp')['commentGiftCard']);
            }
           
            if($formOrderInfoSupp->get('giftCard')->getData() == true){
                $order->setGiftCard('1');
                if($priceSupp == '2.16' || $priceSupp == '4.95'){
                $priceSupp += 0.50;
                $session->set('price_supp', $priceSupp);
                }
            }
            else{
                $order->setGiftCard('0');
                
            }
            $order->setGiftCard($formOrderInfoSupp->get('giftCard')->getData());
            $order->setPrice(round($total, 2));
            $delivery->setType($deliverySession['type']);
            $delivery->setStatus('En attente d\'expédition');
            $delivery->setCreatedAt(new \DateTime('00-00-00'));
            $order->setDelivery($delivery);
            $this->em->persist($adress);
            $this->em->persist($order);

            $this->em->flush();
            return $this->redirectToRoute('summary', ['order_number' => $number]);
        }
        
        return $this->render('order/payment/adress-informations.html.twig', [
            'formAdress' => $formAdress->createView(),
            'formSupp' => $formOrderInfoSupp->createView(),
            'adress' => $adressSession,
        ]);
    }





    /**
     * @Route("/commande/recapitulatif/{order_number}", name="summary")
     */
    public function Summary(SessionInterface $session, CartService $cartService, Request $request, $order_number, OrderRepository $orderRepo)
    {

        if(!$session->has('panier')){
            return $this->redirectToRoute('main');
        }

        $session->remove('adress2');

        $panierWidthData = $cartService->getFullCart();


        $total  =  $cartService->getTotal();
        $priceSupp = $session->get('price_supp');
        $deliverySpawning = $session->get('delivery_spawning');
        $avantageCode = $session->get('avantage_code');

        $totalCart = 0;

        foreach($panierWidthData as $item) {
            if($item['size'] == "stockSizeOne"){
                $totalItem = $item['product']->getSize()->getPriceSizeOne() * $item['quantity'];
            }
            elseif($item['size'] == "stockSizeTwo"){
                $totalItem = $item['product']->getSize()->getPriceSizeTwo() * $item['quantity'];
            }
            elseif($item['size'] == "stockSizeThree"){
                $totalItem = $item['product']->getSize()->getPriceSizeThree() * $item['quantity'];
            }
            
            $totalCart += $totalItem;
        }

        $nbProductsPanier = count($panierWidthData);


        if($priceSupp)
        {
            $total += $priceSupp;
        }
        else{
            $total;
        }

        $order = new Order();
        $formPayment = $this->createForm(PaymentMethodType::class, $order);
        $formPayment->handleRequest($request);
        if ($formPayment->isSubmitted() && $formPayment->isValid()) {

            $order = $orderRepo->findOneBy(['orderNumber' => $order_number]);
            $order->setPaymentType($request->request->get('payment_method')['paymentType']);
            $order->setPrice($total);
            $idPayment = $order->getIdMollie();
            $this->mollie->setApiKey( "test_tEaK4mWagJFRUzWjSv5zbH8rjdw6tU");
            
            $payment = $this->mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => sprintf("%.2F", $order->getPrice()),
                ],
                "description" => "My first API payment",
                "redirectUrl" => "https://localhost:8000/commande/confirmation/" . $order_number . "/",
                // "webhookUrl"  => "https://localhost:8000/mollie-webhook/",
            ]);
            $payment = $this->mollie->payments->get($payment->id);
            $order->setIdMollie($payment->id);
            $session->set('id_mollie', $payment->id);
            $this->em->persist($order);
            $this->em->flush();
            // return $this->redirectToRoute('webhook');
            return $this->redirect($payment->getCheckoutUrl(), 303);
        }

        $adressSession = $session->get('adress');
        

        return $this->render('order/payment/summary.html.twig', [
            'items' => $panierWidthData,
            'deliverySpawning' => $deliverySpawning,
            'total' =>  $total,
            'nbPanier' => $nbProductsPanier,
            'adress' => $adressSession,
            'totalCart' => $totalCart,
            'formPayment' => $formPayment->createView()
        ]);

    }

    /**
     * @Route("/commande/confirmation/{order_number}", name="order")
     */
    public function showOrder(SessionInterface $session, $order_number, OrderRepository $orderRepo, \Swift_Mailer $mailer, ProductRepository $productRepo, CartService $cartService)
    {
        $session->remove('price_supp');
       
        $session->remove('adress');
        $session->remove('adress2');
        $session->remove('delivery');
        $session->remove('quantity');

        // on vérifie si un produit à son stock qui est à 0
        $products = $productRepo->findAll();
        foreach($products as $product){
            if ($product->getSize()->getStockSizeOne() == 0 && $product->getSize()->getStockSizeTwo() == 0 && $product->getSize()->getStockSizeThree() == 0){
                $repoZeroStock = $this->em->getRepository(ZeroStock::class);
                $zeroStock = $repoZeroStock->findBy(['productId' => $product->getId()]);                   
                    if(!$zeroStock){ 
                            $newZeroStock = new ZeroStock();
                            $newZeroStock->setProductId($product->getId());
                            $this->em->persist($newZeroStock);
                            $this->em->flush();
                        }  
            }
        }

        $this->mollie->setApiKey( "test_tEaK4mWagJFRUzWjSv5zbH8rjdw6tU");

        $order = $orderRepo->findOneBy(['orderNumber' => $order_number]);
        $payment = $this->mollie->payments->get($order->getIdMollie());

        $payment = $this->mollie->payments->get($payment->id);
        if ($payment->isPaid())
        {
            $i = 5;
            
            $orderInvoiceMax = $orderRepo->findOneByMax();
            $orderInvoiceMax->getInvoiceNumber();
            if ($orderInvoiceMax->getInvoiceNumber() != null){
                $order->setInvoiceNumber($orderInvoiceMax->getInvoiceNumber() + 1);
            }else{
                $order->setInvoiceNumber($i);
            } 

            $this->em->persist($order);
            $this->em->flush();

            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('contact@mealia.fr')
            ->setTo($order->getClientEmail())
            ->attach(\Swift_Attachment::fromPath('/templates/pdf/invoice.html.twig'));
            $img = $message->embed(\Swift_Image::fromPath('assets/images/Logo-couleur-vectorisé.png'));

            $message->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'email/confirm-order.html.twig',
                ['name' => $order->getClientName(),
                'fistName' => $order->getClientFirstName(),
                'order_number' => $order_number,
                'price' => $order->getPrice(),
                'products_ordered' => $order->getProductsOrdereds(),
                'order_createdAt' => $order->getCreatedAt(),
                'img' => $img,
                'order' => $order
                ]
            ),
            'text/html',
            'iso-8859-2'
            );
            $message->setCharset('UTF-8');
            // $message->setEncoder(\Swift_DependencyContainer::getInstance()->lookup('mime.base64contentencoder'));



            $mailer->send($message);
        }
        elseif($payment->isOpen()) {
            /*
             * The payment is open.
             */
            echo "paiement en attente de confirmation";    

        } 
        elseif ($payment->isCanceled()) {
            /*
             * The payment has been canceled.
             */
            echo "le paiement est annulé"; 
            $this->redirectToRoute('main');   
        }
        elseif ($payment->isExpired()) {
            /*
             * The payment is expired.
             */
            echo "le paiement à expiré"; 
            $this->redirectToRoute('main');
        }
        else{
            $panierWidthData = $cartService->getFullCart();
            if($session->has('panier')){ 
                foreach($panierWidthData as $item){
                    $product = $productRepo->findOneBy([ 'id' => $item['product']->getId()]);
                    $cartService->removeStock($item['product']->getId(), $product);
                }
            }
        }
        $session->remove('panier');
        $session->remove('secondPanier');
        $session->remove('stock');
        return $this->render('order/payment/status-payment.html.twig', [
            'order_number' => $order_number
        ]);
    }

    
}
