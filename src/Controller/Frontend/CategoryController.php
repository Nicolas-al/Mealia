<?php

namespace App\Controller\Frontend;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{


    /**
     * @Route("/boutique/category", name="category")
     */
    public function index(CategoryRepository $repoCategory): Response
    {

        $categories = $repoCategory->findAll();

      
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }


}
