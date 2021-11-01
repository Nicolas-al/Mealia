<?php

namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

Class CartService
{
protected $session;
protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepo, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->productRepo = $productRepo;
        $this->em = $em;
    }

    public function add(int $id)
    {
        $request = Request::createFromGlobals();
        $nbProduct = $request->query->get('nbProduct');
        $size = $request->query->get("size");
        
        $panier = $this->session->get('panier', []);
       
        if (!empty($panier[$id])) {

                if(!empty($panier[$id][$size])){
                    if($nbProduct =! null){
                        if($request->query->get('nbProduct') == 1){

                            $nbProduct = 1; 
                            $panier[$id][$size] = ['quantity' => $panier[$id][$size]['quantity'] += $nbProduct, 'size' => $size];
                        }
                        else{  
                            $panier[$id][$size] = ['quantity' => $panier[$id][$size]['quantity'] += $request->query->get('nbProduct'), 'size' => $size];
                        }
                    }
                }
                else{
                    if($nbProduct =! null){ 
                        if($request->query->get('nbProduct') == 1 ){
                            $nbProduct = 1;
                            $panier[$id][$size] = ['quantity' => $nbProduct, 'size' => $size];
                        }
                        else{                     
                            $panier[$id][$size] = ['quantity' => $request->query->get('nbProduct'), 'size' => $size];
                        }                     
                    }
                }   
        } 
        else {
             $panier[$id][$size] = ['quantity' => $request->query->get('nbProduct'), 'size' => $size];
        }
        
        $this->session->set('panier', $panier);
    }

    // public function addGiftCard(){
    //     $nbGiftCard = $_POST['nb-giftCard'];
    //     $priceGiftCard = $_POST['amount-giftCard'];
    //     // $this->session->remove('panierGiftCard');
        
    //     if ($priceGiftCard == 10){
    //         $id = 1;
    //     }
    //     elseif($priceGiftCard == 25){
    //         $id = 2;
    //     }
    //     elseif($priceGiftCard == 50){
    //         $id = 3;
    //     }
    //     elseif($priceGiftCard == 100){
    //         $id = 4;
    //     }
    //     $panier = $this->session->get('panierGiftCard', []);
      
            
    //     if(!empty($panier[$id]) && $panier[$id]['price'] == $priceGiftCard){
                
    //             if($nbGiftCard =! null){
    //                 if($nbGiftCard == 1){ 
    //                     // $nbGiftCard == 1;
    //                     $panier[$id] += $_POST['nb-giftCard'];
                        
    //                 }
    //                 else{
    //                     $panier[$id] += $_POST['nb-giftCard'];
    //                 }
    //             }
    //     }
    //     else{
    //         $panier[$id] = $_POST['nb-giftCard'];
    //     };

    //     $this->session->set('panierGiftCard', $panier);
    // }

    

    public function remove(int $id)
    {
        $size = $_GET["size"];
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id]))
        {
            unset($panier[$id][$size]);
        }
        $this->session->set('panier', $panier);
    }

    // public function removeGiftCard(int $id){
    //     $panier = $this->session->get('panierGiftCard', []);
    //     if (!empty($panier[$id])){
    //         unset($panier[$id]);
    //     }
    // }

    public function getFullCart(): array
    {
        $panier = $this->session->get('panier', []);
        
        $panierWidthData = [];

        foreach($panier as $id => $sizes){
            foreach($sizes as $size){
            
                $indexArray = array_keys($panier);
                $panierWidthData[] = [
                    'product' => $this->productRepo->find($id),
                    'quantity' => $size['quantity'],
                    'size' => $size['size']
                ];
            
            }
        }
        return $panierWidthData;
        
    }

    // public function getFullGiftCardCart(): array
    // {
    //     $panier = $this->session->get('panierGiftCard', []);
       
    //     $panierGiftCardWidthData = [];

    //     foreach($panier as $id => $quantity){ 
    //         $panierGiftCardWidthData[] = [
    //             'giftCard' => $id,
    //             'quantity' => $quantity,
    //         ];
    //         ;
    //     }
    //     return $panierGiftCardWidthData;
    // }

    public function getTotal()
    {
        $total  = 0;

        foreach($this->getFullCart() as $item) {
            if($item['size'] == "stockSizeOne"){
                $total += $item['product']->getSize()->getPriceSizeOne() * $item['quantity'];
            }
            elseif($item['size'] == "stockSizeTwo"){
                $total += $item['product']->getSize()->getPriceSizeTwo() * $item['quantity'];
            }
            elseif($item['size'] == "stockSizeThree"){
                $total += $item['product']->getSize()->getPriceSizeThree() * $item['quantity'];
            }
        }
        return $total;
    }
    // public function getTotalGiftCard()
    // {
    //     $total  = 0;
        
    //     foreach($this->getFullGiftCardCart() as $item) {
    //         if($item['giftCard'] == 1){
    //             $price = 10;
    //         }
    //         elseif($item['giftCard'] == 2){
    //             $price = 25;
    //         }
    //         elseif($item['giftCard'] == 3){
    //             $price = 50;
    //         }
    //         elseif($item['giftCard'] == 4){
    //             $price = 100;
    //         }
    //         $total += $price * $item['quantity'];
    //     }
    //     return $total;
    // }

    public function addSessionQuantity()
    {
        $panierWidthData = $this->getFullCart();

        $quantity = 0;
        // dd($panierWidthData);
        foreach($panierWidthData as $item){ 
                $quantity += $item['quantity'];

        }
        return $this->session->set('quantity', $quantity);        
    }
    public function removeQuantity(){

        $panierWidthData = $this->getFullCart();

        $quantity = 0;
        foreach($panierWidthData as $item)
        {
            $quantity += $item['quantity'];
        }
        return $this->session->set('quantity', $quantity);
    }

    public function getQuantity()
    {
        $quantity = $this->session->get('quantity');

        return $quantity;
    }
    
    public function addSessionStock($id){

        $request = Request::createFromGlobals();
        $nbProduct = $request->query->get('nbProduct');
        $size = $request->query->get("size");
        $stock = $this->session->get('stock', []);

        if (!empty($stock[$id])) {
            if(!empty($stock[$id][$size])){
                if($nbProduct =! null){ 
                    if($request->query->get('nbProduct') == 1 ){
                        $nbProduct = 1; 
                        $stock[$id][$size] = ['quantity' => $stock[$id][$size]['quantity'] += $nbProduct, 'size' => $size];
                    }
                    else{                       
                        $stock[$id][$size] = ['quantity' => $stock[$id][$size]['quantity'] += $request->query->get('nbProduct'), 'size' => $size];
                    }
                }
            }
            else{
                if($nbProduct =! null){ 
                    if($request->query->get('nbProduct') == 1 ){
                        $nbProduct = 1;
                        $stock[$id][$size] = ['quantity' => $nbProduct, 'size' => $size];
                    }
                    else{                       
                        $stock[$id][$size] = ['quantity' => $request->query->get('nbProduct'), 'size' => $size];

                    }                     
                }
            }   
        } 
        else {
            $stock[$id][$size] = ['quantity' => $request->query->get('nbProduct'), 'size' => $size];
        }

        $this->session->set('stock', $stock);
    }

    public function removeStock(int $id, $product)
    {
        $request = Request::createFromGlobals();
        $size = $request->query->get("size");
        
        $stock = $this->session->get('stock', []);
        if ($stock[$id][$size]['size'] == "stockSizeOne"){
            $productSize = $product->getSize();
            $productSize->setStockSizeOne($productSize->getStockSizeOne() + $stock[$id][$size]['quantity']);
            $product->setSize($productSize);

        }
        if($stock[$id][$size]['size'] == "stockSizeTwo"){
            $productSize = $product->getSize();
            $productSize->setStockSizeTwo($productSize->getStockSizeTwo() + $stock[$id][$size]['quantity']);
            $product->setSize($productSize);

        }
        if($stock[$id][$size]['size'] == "stockSizeThree"){
            $productSize = $product->getSize();
            $productSize->setStockSizeThree($productSize->getStockSizeThree() + $stock[$id][$size]['quantity']);
            $product->setSize($productSize);
        }
        $this->em->persist($product);

        $this->em->flush();

        if (!empty($stock[$id][$size]))
        {
            unset($stock[$id][$size]);
        }
        $this->session->set('stock', $stock);
    }
}