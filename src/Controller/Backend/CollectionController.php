<?php

namespace App\Controller\Backend;

use App\Entity\ProductCollection;
use App\Form\ProductCollectionType;
use App\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CollectionController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/admin/collection", name="admin_collection")
     */
    public function index(CollectionRepository $repoCollection): Response
    {
        $collections = $repoCollection->findAll();

        return $this->render('admin/collection/index.html.twig', [
            'controller_name' => 'CollectionController',
            'collections' => $collections
        ]);
    }

    /**
     * @Route("/admin/collection/nouvelle", name="add_collection")
     */
    public function add(Request $request)
    {
        $newCollection = new ProductCollection();
        $formCollection = $this->createForm(ProductCollectionType::class, $newCollection);
        $formCollection->handleRequest($request);
        if($formCollection->isSubmitted() && $formCollection->isValid()){

            $this->em->persist($newCollection);
            $this->em->flush();
            return $this->redirectToRoute('admin');
            
        }

        return $this->render('collection/new.html.twig', [
            'controller_name' => 'ProductController',
            'formCollection' => $formCollection->createView(),
        ]);
    }

     /**
     * @Route("/admin/collection/modifier/{id}", name="update_collection", methods="GET|POST")
     * @param ProductCollection $collection
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request , ProductCollection $collection)
    {
        $form = $this->createForm(ProductCollectionType::class, $collection);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
         $this->em->flush();
            return $this->redirectToRoute('collection');
        }
       return $this->render('collection/update.html.twig', [
        'collection' => $collection,
        'formCollection' => $form->createView(),
       ]);
    }

    /**
     * @Route("/admin/collection/edit/{id}", name="remove_collection", methods="DELETE")
     * @param ProductCollection $collection
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(ProductCollection $collection, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $collection->getId(), $request->get('_token'))){ 
            $this->em->remove($collection);
            $this->em->flush();
            }
            return $this->redirectToRoute('admin_products');
    }


}
