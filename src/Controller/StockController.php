<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ZeroStock;
use App\Entity\ProductCollection;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\ZeroStockRepository;
use App\Repository\CollectionRepository;
use App\Service\Stock\StockService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StockController extends AbstractController
{

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    /**
     * @Route("/admin/produits/stock", name="stock")
     */
    public function index(ProductRepository $repoProduct, CollectionRepository $repoCollection, CategoryRepository $repoCategory, EntityManagerInterface $em, ZeroStockRepository $repoZStock, \Swift_Mailer $mailer): Response
    {
        $categories = $repoCategory->getDistinct();
        $collections = $repoCollection->getDistinct();
        $this->update($repoProduct, $em, $mailer, $repoZStock);
        
        // dd($_GET["categorie"]);

            if(isset($_GET["categorie"]))
            {
                $category = $repoCategory->findOneBy(['name' => $_GET["categorie"]]);
                $products = $category->getProducts(); 
            }
            elseif(isset($_GET["collection"]))
            { 
                $collection = $repoCollection->findOneBy(['name' => $_GET["collection"]]);
                $products = $collection->getProducts();   
            }
            else{
                $products = $repoProduct->findAll();
            }
        
        return $this->render('stock/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'collections' => $collections,
            // 'productCat' => $productCat,
            // 'getCollection' => $getCollection,
        ]);
    }

    Public function update($repoProduct, $em, $mailer, $repoZStock)
    {
        
        if(isset($_GET["action"]) && $_GET["action"] == "updateStock"){
            $product = $repoProduct->find($_GET["id-product"]);
            $zStock = $repoZStock->findOneBy(['productId' => $_GET["id-product"]]);
            if ($zStock){
            if(isset($_GET["id-product"])){
                $alertMail = $zStock->getAlertMail();

                if($_GET["stock"] > 0 && count($alertMail) > 0){
                    foreach($alertMail as $one){ 
                   $message = (new \Swift_Message('Hello Email'))
                   ->setFrom('mealia@ionos.com')
                   ->setSubject('Produit disponible en stock')
                   ->setTo($one)
                   ->setBody(
                       $this->renderView(
                           // templates/emails/registration.html.twig
                           'email/reappro-stock.html.twig',
                           ['category' => $product->getCategory()->getName(),
                            'collection' => $product->getCollection()->getName(),
                            'id' => $product->getId()
                           ]
                       ),
                       'text/html'
                   );
                   }
                   $mailer->send($message);

                   $zStock->setAlertMail([]);
                   $em->persist($zStock);
                }
            }
        }
        
            $size = $product->getSize();

            if (isset($_GET['stockSizeOne']) && $_GET['stockSizeOne'] != ""){ 
                $size->setStockSizeOne($_GET['stockSizeOne']);
            }
            if (isset($_GET['stockSizeTwo']) && $_GET['stockSizeTwo'] != ""){
                $size->setStockSizeTwo($_GET['stockSizeTwo']);
            }
            if (isset($_GET['stockSizeThree']) && $_GET['stockSizeThree'] != ""){
                $size->setStockSizeThree($_GET['stockSizeThree']);
            }
            $product->setSize($size);
            $em->persist($product);
            $em->flush();
            $this->stockService->removeZeroStock($product);
       
    }
    // dd('salut');

    }
}
