<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ProductCollection;
use App\Repository\CategoryRepository;
use App\Repository\CollectionRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StockController extends AbstractController
{
    /**
     * @Route("/admin/products/stock", name="stock")
     */
    public function index(ProductRepository $repoProduct, CollectionRepository $repoCollection, CategoryRepository $repoCategory, EntityManagerInterface $em): Response
    {
        $categories = $repoCategory->getDistinct();
        $collections = $repoCollection->getDistinct();
        $this->update($repoProduct, $em);
        
            if(isset($_GET["categorie"]))
            {
                $category = $repoCategory->findOneBy(['name' => $_GET["categorie"]]);
                var_dump($_GET["categorie"]);
                $products = $category->getProducts(); 
            }
            elseif(isset($_GET["collection"]))
            { 
                $collection = $repoCollection->findOneBy(['name' => $_GET["collection"]]);
                var_dump($_GET["collection"]);
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

    Public function update($repoProduct, $em)
    {
        if(isset($_GET["action"]) && $_GET["action"] == "updateStock"){
            $product = $repoProduct->find($_GET["id-product"]);
            $product->setStock($_GET['stock']);
            $em->persist($product);
            $em->flush();
        }
    }
}
