<?php

namespace App\Controller\Backend;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

     /**
     * @Route("/admin/category/create", name="add_category")
     */
    public function add(Request $request)
    {
        $newCategory = new Category();
        $formCategory = $this->createForm(CategoryType::class, $newCategory);
        $formCategory->handleRequest($request);
        if($formCategory->isSubmitted() && $formCategory->isValid()){

            $this->em->persist($newCategory);
            $this->em->flush();
            return $this->redirectToRoute('admin');
            
        }

        return $this->render('category/new.html.twig', [
            'controller_name' => 'ProductController',
            'formCategory' => $formCategory->createView(),
        ]);
    }

}
