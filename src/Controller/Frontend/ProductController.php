<?php

namespace App\Controller\Frontend;


use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\Product;
use App\Repository\AvisRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/products", name="show_products")
     */
    public function index(ProductRepository $repo): Response
    {
        $products = $repo->findAll();
        
        return $this->render('product/index.html.twig', [
            'products' => $products
            
        ]);
    }

    /**
     * @Route("/product/{id}", name="show_product")
     */
    public function showOne(Product $product, Request $request, AvisRepository $avisRepository, UserInterface $user = null)
    {
        $newAvis = New Avis;
        $form = $this->createForm(AvisType::class, $newAvis);
        $userId = null !== $user ? $this->getUser()->getId() : null;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // Pour laisser un avis il faut avoir un compte utilisateur.
            $newAvis->setProduct($product);
            $newAvis->setCreatedAt(new \DateTime());
            $newAvis->setUser($user);
            $newAvis->setNote($request->request->get('rating'));
            $this->em->persist($newAvis);
            $this->em->flush();
            $this->addFlash('success', 'Votre avis à bien été pris en compte !');
        };
        $avis = $avisRepository->findBy(array('product' => $product->getId()), array('createdAt' => 'DESC'));

        $arrayRate = $avisRepository->averageRate($product->getId());
        
        foreach ($arrayRate as $rates){
        }

        // $user = $user;
        // $allAvis = $avisRepository->findAll();       
        
        return $this->render('product/oneproduct.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'avis' => $avis,
            'rates' => $rates,       
        ]);
    }

    
}
