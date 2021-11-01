<?php

namespace App\Controller\Backend;

use App\Form\TypeType;
use App\Entity\Type;
use App\Repository\TypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeController extends AbstractController
{
    /**
     * @Route("/admin/types", name="admin_types")
     */
    public function index(TypeRepository $typeRepo): Response
    {
        $types = $typeRepo->findAll();
       
        
        return $this->render('type/index.html.twig', [
            'controller_name' => 'TypeController',
            'types' => $types
        ]);
    }
    /**
     * @Route("/admin/type/nouveau", name="add_type")
     */
    public function add(Request $request)
    {
        $newType = new Type();
        $formType = $this->createForm(TypeType::class, $newType);
        $formType->handleRequest($request);
        if($formType->isSubmitted() && $formType->isValid()){

            $this->em->persist($newType);

            $this->em->flush();
            // return $this->redirectToRoute('admin');
            
        }

        return $this->render('type/new.html.twig', [
            'controller_name' => 'ProductController',
            'formType' => $formType->createView(),
        ]);
    }

     /**
     * @Route("/admin/type/edit/{id}", name="update_type", methods="GET|POST")
     * @param Type $type
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request , Type $type)
    {
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
         $this->em->flush();
         return $this->redirectToRoute('type');
        }
       return $this->render('type/update.html.twig', [
        'type' => $type,
        'formType' => $form->createView(),
       ]);
    }

    /**
     * @Route("/admin/type/edit/{id}", name="remove_type", methods="DELETE")
     * @param Type $type
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(Type $type, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $type->getId(), $request->get('_token'))){ 
            $this->em->remove($type);
            $this->em->flush();
            }
            return $this->redirectToRoute('admin');
    }
}
