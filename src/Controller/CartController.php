<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ZeroStock;
use App\Service\Cart\CartService;
use App\Service\Stock\StockService;
use App\Repository\ProductRepository;
use App\Service\Session\SessionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

class CartController extends AbstractController
{

    public function __construct(StockService $stockService, EntityManagerInterface $em){
        $this->stockService = $stockService;
        $this->em = $em;
    }
   
    /**
     * @Route("/panier", name="cart")
     */
    public function index(CartService $cartService, EntityManagerInterface $em, SessionInterface $session, AjaxController $ajaxController): Response
    {

        $panierWidthData = $cartService->getFullCart();
        // $panierGiftCardWidthData = $cartService->getFullGiftCardCart();
        $total = $cartService->getTotal();

        // $totalGiftCard = $cartService->getTotalGiftCard();
        // $total += $totalGiftCard;
        // dd($panierWidthData);
        $nbProductsPanier = count($panierWidthData);
        
        return $this->render('order/cart/first.html.twig', [
            'items' => $panierWidthData,
            // 'itemsGiftCard' => $panierGiftCardWidthData,
            'total' =>  $total,
            'nbPanier' => $nbProductsPanier,
        ]);
    }

     /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add($id, Product $product, CartService $cartService, EntityManagerInterface $em, Request $request)
    {
    
        $cartService->add($id);
        $cartService->addSessionQuantity();

        $panierWidthData = $cartService->getFullCart();
        ;

        $stockOneSizeCurrent = $product->getSize()->getStockSizeOne();
        $stockTwoSizeCurrent = $product->getSize()->getStockSizeTwo();
        $stockThreeSizeCurrent = $product->getSize()->getStockSizeThree();
        $size = $product->getSize();
        foreach($panierWidthData as $item){
            if ($item['product']->getId() == $id){
                if($request->query->get('size') == 'stockSizeOne'){ 
                    $size->setStockSizeOne($stockOneSizeCurrent - $request->query->get('nbProduct'));
                }
                else if($request->query->get('size') == 'stockSizeTwo'){
                    
                    $size->setStockSizeTwo($stockTwoSizeCurrent - $request->query->get('nbProduct'));
                }
                else if($request->query->get('size') == 'stockSizeThree'){ 
                    $size->setStockSizeThree($stockThreeSizeCurrent - $request->query->get('nbProduct'));
                }
            }
        };
        $product->setSize($size);

        $em->flush();

       $repoZeroStock = $this->em->getRepository(ZeroStock::class);         
       $zeroStock = $repoZeroStock->findOneBy(['productId' => $product->getId()]);
            if ($product->getSize()->getStockSizeOne() + $product->getSize()->getStockSizeTwo() + $product->getSize()->getStockSizeThree() == 0){
                    if(!$zeroStock){ 
                            $newZeroStock = new ZeroStock();
                            $newZeroStock->setProductId($product->getId());
                            $em->persist($newZeroStock);
                            $em->flush();
                        }  
            }
        $cartService->addSessionStock($product->getId());
        $result = [
            $product->getSize()->getStockSizeOne(),
            $product->getSize()->getStockSizeTwo(),
            $product->getSize()->getStockSizeThree()
        ];
            // dd(new Response(json_encode($result)));
        return new Response(json_encode($result));
    }

    /**
     * @Route("/panier/remove/{id}/", name="cart_remove")
     */
    public function remove($id, CartService $cartService, Product $product)
    {
        $cartService->remove($id);
        $cartService->removeQuantity();
        $cartService->removeStock($id, $product);
        $this->stockService->removeZeroStock($product);

        return dd();
    }
}
