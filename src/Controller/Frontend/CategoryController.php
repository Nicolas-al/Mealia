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
     * @Route("/boutique/categories", name="category")
     */
    public function index(CategoryRepository $repoCategory): Response
    {

        $categories = $repoCategory->findAll();

      
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/boutique/créations-textiles/categories", name="textil_categories")
     */
    public function textilCategories(CategoryRepository $repoCategory): Response
    {

        $categories = $repoCategory->findBy(['type' => '2']);

      
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/boutique/petite-papeterie/categories", name="papeterie_categories")
     */
    public function smallPapeterie(CategoryRepository $repoCategory): Response
    {
        $categories = $repoCategory->findBy(['type' => '1']);
      
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }


}
