<?php

namespace App\Controller\Backend;

use App\Entity\Size;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Entity\ProductCollection;
use App\Repository\AvisRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\ZeroStockRepository;
use App\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, ProductRepository $productRepo, CategoryRepository $categoryRepo, CollectionRepository $collecRepo)
    {
        $this->em = $em;
        $this->repo = $productRepo;
        $this->catRepo = $categoryRepo;
        $this->collRepo = $collecRepo;
    }

    /** 
     * @Route("/admin/produits", name="admin_products")
     */
    public function show(ProductRepository $repoProduct): Response
    {

        $products = $repoProduct->findAll();


        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
    /**
     * @Route("/admin/produits/categorie/{id}/", name="admin_products_category")
     */
    public function showByCategory(Category $category, ProductRepository $repoProduct, Request $request, SessionInterface $session, PaginatorInterface $paginator)
    {
        if($request->query->get('filter') === 'asc-price'){
            $session->set('filter', 'asc-price');
        }
        elseif($request->query->get('filter') == 'desc-price'){
            $session->set('filter', 'desc-price');
        }elseif($request->query->get('filter') == 'aucun'){
            $session->remove('filter');
        }
        if($session->get('filter') == 'asc-price'){
            $products = $repoProduct->findBy(['category' => $category->getId()], ['price' => 'ASC']);
        }elseif($session->get('filter') == 'desc-price'){
            $products = $repoProduct->findBy(['category' => $category->getId()], ['price' => 'DESC']);
        }else{
            $products =  $repoProduct->findBy(['category' => $category->getId()]); 
        }
    
        $nbProducts = $paginator->paginate(
            $products, // Requête contenant les données à paginer (ici nos produits)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            12 // Nombre de résultats par page
        );
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'nbProducts' => $nbProducts,
            'category' => $category
        ]);
    }
     /**
     * @Route("/admin/produits/collection/{id}/", name="admin_products_collection")
     */
    public function showByCollection(ProductCollection $collection)
    {

        $products = $collection->getProducts(); 
          
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);

    }

    /**
     * @Route("admin/produit/{id}", name="show_admin_product")
     */
    public function showOne(Product $product, AvisRepository $avisRepository)
    {
        
        $avis = $avisRepository->findBy(array('product' => $product->getId()), array('createdAt' => 'DESC'));

        $arrayRate = $avisRepository->averageRate($product->getId());
        
        foreach ($arrayRate as $rates){
        }
 
        $products = $this->repo->findBy(['category' => $product->getCategory()]);
    
        
        return $this->render('product/oneproduct.html.twig', [
            'product' => $product,
            'avis' => $avis,
            'rates' => $rates, 
            'products' => $products     
        ]);
    }

    /** 
     * @Route("/admin/Produit/nouveau", name="add_product")
     */
    public function new(Request $request): Response
    {
        // form product
        $newProduct = new Product();
        
        $form = $this->createForm(ProductType::class, $newProduct);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if ($request->files->get('product')['image']['twoFile'] == null){
                $image = new Image();
                $image->setOneFile(null);
                $newProduct->setImage($image);
            }
            // dd($request);
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
            $newProduct->setDescription($request->request->get('product')['description']);
            $size = new Size();
            $size->setSizeOne($request->request->get('product')['size']['sizeOne']);
            if ($request->request->get('product')['size']['stockSizeOne'] == ""){
                $size->setStockSizeOne(null);
            }
            else{
                $size->setStockSizeOne($request->request->get('product')['size']['stockSizeOne']);
            }
            if ($request->request->get('product')['size']['priceSizeOne'] == ""){
                $size->setPriceSizeOne(null);
            }
            else{
                $size->setPriceSizeOne($request->request->get('product')['size']['priceSizeOne']);
            }
            $size->setSizeTwo($request->request->get('product')['size']['sizeTwo']);
            if ($request->request->get('product')['size']['stockSizeTwo'] == ""){
                $size->setstockSizeTwo(null);
            }
            else
            {
                $size->setstockSizeTwo($request->request->get('product')['size']['stockSizeTwo']);
            }
            if ($request->request->get('product')['size']['priceSizeTwo'] == ""){
                $size->setPriceSizeTwo(null);
            }
            else
            {
                $size->setPriceSizeTwo($request->request->get('product')['size']['priceSizeTwo']);
            }
            $size->setSizeThree($request->request->get('product')['size']['sizeThree']);
            if ($request->request->get('product')['size']['stockSizeThree'] == ""){
                $size->setstockSizeThree(null);
            }
            else{
                $size->setstockSizeThree($request->request->get('product')['size']['stockSizeThree']);
            }
            if ($request->request->get('product')['size']['priceSizeThree'] == ""){
                $size->setPriceSizeThree(null);
            }
            else
            {
                $size->setPriceSizeThree($request->request->get('product')['size']['priceSizeThree']);
            }
            $newProduct->setSize($size);
            // on ajoute le nom automatiquement sans que l'admin n'est besoin de le rentrer puissqu'il s'agit 
            // du nom de la category + le nom de la collection
            $categoryName = $this->catRepo->findOneBy(['id' => $dataCategory])->getName();
            $collectionName = $this->collRepo->findOneBy(['id' => $dataCollection])->getName();
            $newProduct->setName($categoryName . " " . $collectionName);
            
            $this->em->persist($collection);
            $this->em->persist($category);             
            $this->em->persist($newProduct);
            
            $this->em->flush();
            
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/products/new.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/produit/modifier/{id}", name="update_product", methods="GET|POST")
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request ,Product $product, ZeroStockRepository $repoZStock, $id, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $zStock = $repoZStock->findOneBy(['productId' => $id]);
            if($zStock != null){
            $alertMail = $zStock->getAlertMail();
            
           
            // si les stock du produits sont superieur a 0 et le produit à reçu des demandes 
            // client de reaprovisionnement alors on envoi un mail à tout les clients qui ont fait la demande
            if ($request->request->get('product')['stock'] > 0 && count($alertMail) > 0){
                
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
        
            $this->em->persist($zStock);

            }
        }
  
         $this->em->flush();
         return $this->redirectToRoute('products_sheets');
    }
       return $this->render('admin/products/update.html.twig', [
        'product' => $product,
        'form' => $form->createView(),
       ]);
    }
};