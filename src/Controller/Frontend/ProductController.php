<?php

namespace App\Controller\Frontend;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ProductCollection;
use App\Repository\AvisRepository;
use App\Controller\SessionController;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductController extends SessionController
{

    public function __construct(EntityManagerInterface $em, ProductRepository $productRepo)
    {
        $this->em = $em;
        $this->repo = $productRepo;
    }

    /**
     * @Route("/products", name="show_products")
     */
    public function index(ProductRepository $repoProduct, Request $request, SessionInterface $session, PaginatorInterface $paginator): Response
    {

        if($request->query->get('filter') === 'asc-price'){
            $session->set('filter', 'asc-price');
        }
        elseif($request->query->get('filter') == 'desc-price'){
            $session->set('filter', 'desc-price');
        }elseif($request->query->get('filter') == 'none'){
            $session->remove('filter');
        }
        if($session->get('filter') == 'asc-price'){
            $products = $repoProduct->findBy(['price' => 'ASC']);
        }elseif($session->get('filter') == 'desc-price'){
            $products = $repoProduct->findBy(['price' => 'DESC']);
        }elseif($request->query->get('filter') == 'none'){
            $products =  $repoProduct->findAll(); 
        }
        else{
            $products =  $repoProduct->findAll(); 
        }
    
        $nbProducts = $paginator->paginate(
            $products, // Requête contenant les données à paginer (ici nos produits)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            12 // Nombre de résultats par page
        );
        $productsCount = count($products);
        dump($nbProducts);
          
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'nbProducts' => $nbProducts,
            'productsCount' => $productsCount
        ]);
    }
     /**
     * @Route("/produits/categorie/{id}/", name="products_category")
     */
    public function showByCategory(Category $category, ProductRepository $repoProduct, Request $request, SessionInterface $session, PaginatorInterface $paginator)
    {
        // $products = $category->getProducts(); 
        if($request->query->get('filter') === 'asc-price'){
            $session->set('filter', 'asc-price');
        }
        elseif($request->query->get('filter') == 'desc-price'){
            $session->set('filter', 'desc-price');
        }elseif($request->query->get('filter') == 'none'){
            $session->remove('filter');
        }
        if($session->get('filter') == 'asc-price'){
            $products = $repoProduct->findBy(['category' => $category->getId()], ['price' => 'ASC']);
        }elseif($session->get('filter') == 'desc-price'){
            $products = $repoProduct->findBy(['category' => $category->getId()], ['price' => 'DESC']);
        }elseif($request->query->get('filter') == 'none'){
            $products =  $repoProduct->findBy(['category' => $category->getId()]); 
        }
        else{
            $products =  $repoProduct->findBy(['category' => $category->getId()]); 
        }
    
        $nbProducts = $paginator->paginate(
            $products, // Requête contenant les données à paginer (ici nos produits)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            12 // Nombre de résultats par page
        );
        $productsCount = count($products);
        dump($nbProducts);
          
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'nbProducts' => $nbProducts,
            'category' => $category,
            'productsCount' => $productsCount
        ]);
    }

     /**
     * @Route("/products/collection/{id}", name="products_collection")
     */
    public function showByCollection(ProductCollection $collection, ProductRepository $repoProduct, Request $request, SessionInterface $session, PaginatorInterface $paginator)
    {

        if($request->query->get('filter') === 'asc-price'){
            $session->set('filter', 'asc-price');
        }
        elseif($request->query->get('filter') == 'desc-price'){
            $session->set('filter', 'desc-price');
        }elseif($request->query->get('filter') == 'none'){
            $session->remove('filter');
        }
        if($session->get('filter') == 'asc-price'){
            $products = $repoProduct->findBy(['collection' => $collection->getId()], ['price' => 'ASC']);
        }elseif($session->get('filter') == 'desc-price'){
            $products = $repoProduct->findBy(['collection' => $collection->getId()], ['price' => 'DESC']);
        }elseif($request->query->get('filter') == 'none'){
            $products =  $repoProduct->findBy(['collection' => $collection->getId()]); 
        }
        else{
            $products =  $repoProduct->findBy(['collection' => $collection->getId()]); 
        } 

        $nbProducts = $paginator->paginate(
            $products, // Requête contenant les données à paginer (ici nos produits)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            12 // Nombre de résultats par page
        );
        $productsCount = count($products);
        dump($nbProducts);
          
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'nbProducts' => $nbProducts,
            'productsCount' => $productsCount,
        ]);

    }

    /**
     * @Route("/produit/{id}", name="show_product")
     */
    public function showOne(SessionInterface $session ,Product $product, Request $request, AvisRepository $avisRepository, UserInterface $user = null, $id)
    {
        // $session->remove('panier');
        // $session->remove('quantity');
        // $session->remove('stock');

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
            $this->addFlash('success', 'Votre avis a bien été pris en compte !');
        };
        $avis = $avisRepository->findBy(array('product' => $product->getId()), array('createdAt' => 'DESC'));

        $arrayRate = $avisRepository->averageRate($product->getId());
        
        foreach ($arrayRate as $rates){
        }  
      

        //on envoie un email a l'adresse Mealia si l'utilisateur signal un stock vide

        
        // on recupère tout les produits par collection pour compléter la séléction
        $products = $this->repo->findBy(['collection' => $product->getCollection()]);
        dump($products);
        
        return $this->render('product/oneproduct.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'avis' => $avis,
            'rates' => $rates,
            'products' => $products,   
        ]);
    }

    /**
     * @Route("/produit/{id}/stock/verification", name="verify_stock")
     * @Method("GET")
     */
    // verification stock produit

    public function verifyStock($id, Product $product){

        $stock = [
            'stockSizeOne' => $product->getSize()->getStockSizeOne(),
            'stockSizeTwo' => $product->getSize()->getStockSizeTwo(),
            'stockSizeThree' => $product->getSize()->getStockSizeThree(),
        ];
        return new Response(json_encode($stock));
    }
    
}
