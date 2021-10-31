<?php

namespace App\Controller\Backend;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
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
     * @Route("/admin/categories", name="admin_category")
     */
    public function index(CategoryRepository $repoCategory): Response
    {
        $categories = $repoCategory->findAll();

        return $this->render('admin/category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }

     /**
     * @Route("/admin/categorie/nouvelle", name="add_category")
     */
    public function add(Request $request)
    {
        $newCategory = new Category();
        $formCategory = $this->createForm(CategoryType::class, $newCategory);
        $formCategory->handleRequest($request);
        if($formCategory->isSubmitted() && $formCategory->isValid()){

            $this->em->persist($newCategory);
            var_dump($request->request->get('category'));

            $this->em->flush();
            // return $this->redirectToRoute('admin');
            
        }

        return $this->render('category/new.html.twig', [
            'controller_name' => 'ProductController',
            'formCategory' => $formCategory->createView(),
        ]);
    }

     /**
     * @Route("/admin/category/edit/{id}", name="update_category", methods="GET|POST")
     * @param Category $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request , Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
         $this->em->flush();
         return $this->redirectToRoute('category');
        }
       return $this->render('category/update.html.twig', [
        'category' => $category,
        'formCategory' => $form->createView(),
       ]);
    }

    /**
     * @Route("/admin/category/edit/{id}", name="remove_category", methods="DELETE")
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(Category $category, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->get('_token'))){ 
            $this->em->remove($category);
            $this->em->flush();
            }
            return $this->redirectToRoute('admin_products');
    }

}
