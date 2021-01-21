<?php

namespace App\Controller\Backend;

use App\Entity\ProductCollection;
use App\Form\ProductCollectionType;
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
     * @Route("/collection", name="collection")
     */
    public function index(): Response
    {
        return $this->render('collection/index.html.twig', [
            'controller_name' => 'CollectionController',
        ]);
    }

    /**
     * @Route("/admin/collection/create", name="add_collection")
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

}
