<?php

namespace App\Controller\Backend;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Entity\ProductCollection;
use App\Repository\AvisRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /** 
     * @Route("/admin/products", name="admin_products")
     */
    public function show(ProductRepository $repo): Response
    {
        $products = $repo->findAll();



        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);
    }

    /**
     * @Route("admin/product/{id}", name="show_admin_product")
     */
    public function showOne(Product $product, AvisRepository $avisRepository)
    {
        
        $avis = $avisRepository->findBy(array('product' => $product->getId()), array('createdAt' => 'DESC'));

        $arrayRate = $avisRepository->averageRate($product->getId());
        
        foreach ($arrayRate as $rates){
        }

        // $user = $user;
        // $allAvis = $avisRepository->findAll();       
        
        return $this->render('product/oneproduct.html.twig', [
            'product' => $product,
            'avis' => $avis,
            'rates' => $rates,       
        ]);
    }

    /** 
     * @Route("/admin/product/create", name="add_product")
     */
    public function new(Request $request): Response
    {

        // form product
        $newProduct = new Product();
        $form = $this->createForm(ProductType::class, $newProduct);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $collection = new ProductCollection();
            $dataCategory = $request->request->get('product')['category'];
            $dataCollection = $request->request->get('product')['collection'];
            $repoCategory = $this->getDoctrine()->getManager()->getRepository(Category::class);
            $category = $repoCategory->find($dataCategory);
            $repoCollection = $this->getDoctrine()->getManager()->getRepository(ProductCollection::class);
            $collection = $repoCollection->find($dataCollection);
            $collection->setName($collection->getName());
            $category->setName($category->getName());
            $collection->addProduct($newProduct);
            $category->addProduct($newProduct); 
            $newProduct->setCreatedAt(new \DateTime());
            $this->em->persist($collection);
            $this->em->persist($category);             
            $this->em->persist($newProduct);
            
            $this->em->flush();
            
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/product/new.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/edit/{id}", name="update_product", methods="GET|POST")
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request ,Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
         $this->em->flush();
         return $this->redirectToRoute('admin_products');
        }
       return $this->render('admin/product/update.html.twig', [
        'product' => $product,
        'form' => $form->createView(),
       ]);
    }

    /**
     * @Route("/admin/product/edit/{id}", name="remove_product", methods="DELETE")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(Product $product, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token'))){ 
        $this->em->remove($product);
        $this->em->flush();
        }
        return $this->redirectToRoute('adnin_products');
    }
}