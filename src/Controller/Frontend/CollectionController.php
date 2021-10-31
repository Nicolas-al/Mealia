<?php

namespace App\Controller\Frontend;

use App\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/boutique/collection", name="collection")
     */
    public function index(CollectionRepository $repoCollection): Response
    {
        $collections = $repoCollection->findAll();

        return $this->render('collection/index.html.twig', [
            'controller_name' => 'CollectionController',
            'collections' => $collections
        ]);
    }

    /**
     * @Route("/boutique/créations-textiles/collections", name="textil_collections")
     */
    public function textilCategories(CollectionRepository $repoCollection): Response
    {

        $collections = $repoCollection->findBy(['type' => '2']);

      
        return $this->render('collection/index.html.twig', [
            'controller_name' => 'CategoryController',
            'collections' => $collections
        ]);
    }

}