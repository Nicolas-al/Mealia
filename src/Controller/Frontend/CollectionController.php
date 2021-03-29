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
     * @Route("/collection", name="collection")
     */
    public function index(CollectionRepository $repoCollection): Response
    {
        $collections = $repoCollection->findAll();

        return $this->render('collection/index.html.twig', [
            'controller_name' => 'CollectionController',
            'collections' => $collections
        ]);
    }

}