<?php

namespace App\Controller\Frontend;

use App\Repository\OrderRepository;
use App\Service\Stock\StockService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{

    public function __construct(StockService $stockService, ProductRepository $repoProduct){
        $this->stockService = $stockService;
        $this->repoProduct = $repoProduct;
    }
    
    /**
     * @Route("/", name="main")
     */
    public function index(OrderRepository $orderRepo): Response
    {

        $orderInvoiceMax = $orderRepo->findOneByMax();
        dump($orderInvoiceMax);
        $orderInvoiceMax->getInvoiceNumber();
        dump($orderInvoiceMax->getInvoiceNumber() + 1 . '/' . date('Y'));

        $products = $this->repoProduct->findAll();
        foreach($products as $product){
            $this->stockService->removeZeroStock($product);
        }


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

    /**
     * @Route("/mentions-légales", name="legal_notices")
     */
    public function legalNotices(){
        return $this->render('main/legal-notices.html.twig', [
            ]);
    }

    /**
     * @Route("/CGV", name="cgv")
     */
    public function Cgv(){
        return $this->render('main/CGV.html.twig', [
            ]);
    }

    public function template()
    {
        
        return $this->render('template.html.twig', [
            ]);
    }


}
 