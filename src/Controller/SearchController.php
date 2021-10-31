<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SearchController extends AbstractController
{

    /** 
     * @Route("/search", name="ajax_search")
     * @Method("GET")
     */
  public function searchAction(Request $request, ProductRepository $repoProduct)
  {
    
      $em = $this->getDoctrine()->getManager();

      $requestString = $request->get('product');

      $entities =  $repoProduct->findEntitiesByName($requestString); 
      if(!$entities) {
          $result['entities']['error'] = "";
      } 
      else{
          $result['entities'] = $this->getRealEntities($entities, $repoProduct);
      }
    
      return new Response(json_encode($result));
  }

  public function getRealEntities($entities, $repoProduct){
    
      foreach ($entities as $entity){
        foreach($entity as $key => $value){
          $product = $repoProduct->findOneBy(['name' => $entity]);
          $id = $product->getId();
          $realEntities[$id] = $value;
        }
        
      }
      return $realEntities;
  }
}
