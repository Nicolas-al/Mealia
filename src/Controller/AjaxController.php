<?php

namespace App\Controller;

use App\Entity\GiftCard;
use App\Entity\ZeroStock;
use App\Repository\AvoirRepository;
use App\Repository\GiftCardRepository;
use App\Repository\ProductRepository;
use App\Repository\ZeroStockRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    /**
     * @Route("/ajax", name="ajax")
     */
    public function index(Request $request, CartService $cartService, ProductRepository $productRepo, EntityManagerInterface $em, SessionInterface $session)
    {

        if($request->query->get('inactivité') === 'oui'){
            $panierWidthData = $cartService->getFullCart();
            if($session->has('panier')){ 
            foreach($panierWidthData as $item){
                $product = $productRepo->findOneBy([ 'id' => $item['product']->getId()]);
                $cartService->removeStock($item['product']->getId(), $product);
            }
            $session->clear();
        }
        };

     return dd();
    }

    /**
     * @Route("/ajax/produit/{id}/zero-stock/signalement", name="ajax-stock-signal")
     */
    public function zeroStockSignal($id, ProductRepository $productRepo, \Swift_Mailer $mailer, Request $request, ZeroStockRepository $repoZeroStock, EntityManagerInterface $em){
        $product = $productRepo->find($id);
        $zeroStock = $repoZeroStock->findOneBy(['productId' => $id]);
        if($request->query->get('email')){

        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('mealia@sfr.fr')
        ->setTo('recipient@example.com')
        ->setSubject('stock alert client')
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'email/zero-stock.html.twig',
                ['email' => $request->query->get('email'),
                'category' => $product->getCategory()->getName(),
                'collection' => $product->getCollection()->getName()
                ]
            ),
            'text/html'
        );

        $mailer->send($message);

        // on ajoute l'adresse mail indiquée sur le produit zero stock si celle-ci n'existe pas
        // dd($zeroStock->getAlertMail());

        // dump(count($arrayAlertMail));
        if($zeroStock){
            $arrayAlertMail = $zeroStock->getAlertMail();

            if (count($arrayAlertMail) > 0){
                $email = $request->query->get('email');
                dump($email);
                if (!in_array($email, $arrayAlertMail)){
                    $arrayAlertMail[] = $request->query->get('email');
                    $zeroStock->setAlertMail($arrayAlertMail);
                }
                
            }else{
                dump('else');
                $arrayAlertMail[] = $request->query->get('email');
                dump($arrayAlertMail);
                $zeroStock->setAlertMail($arrayAlertMail);
            }
        }
        else{
            $zeroStock = new ZeroStock();
            $zeroStock->setAlertMail([$request->query->get('email')]);
            $zeroStock->setProductId($id);


    }
        $em->persist($zeroStock);
        $em->flush();

        return dd();
        } 

        return dd();
    }

    /**
     * @Route("/ajax/code-avantage", name="ajax_avantage_code")
     */
    public function vérifyAvantageCode(GiftCardRepository $giftCardRepo, AvoirRepository $avoirRepo, Request $request, CartService $cartService, SessionInterface $session){

        $giftCard = $giftCardRepo->findOneBy(['code' => $request->query->get('code')]);
        $avoir = $avoirRepo->findOneBy(['code' => $request->query->get('code')]);
        // $session->remove('avantage_code');
        $avantageCode = $session->get('avantage_code');
        $total  =  $cartService->getTotal();
        $totalAmount = $avantageCode += $giftCard->getAmount();

        // dd($session->get('avantage_code'));
        if($giftCard ){
            if($session->has('avantage_code')){ 
                // $code = array_sum($avantageCode += $giftCard->getAmount());
                // var_dump($avantageCode);  
                // var_dump($giftCard->getAmount());
                // var_dump($avantageCode += $giftCard->getAmount());
                $session->set('avantage_code', $totalAmount);
                return new JsonResponse(['reponse' => 'code valide']);
                
            }
            else{
                
                $session->set('avantage_code', $giftCard->getAmount());
                return new JsonResponse(['reponse' => 'code valide']);
            }
        }
        elseif($avoir){
            if($avantageCode){
                $session->set('avantage_code', $avantageCode + $avoir->getAmount());
                return new JsonResponse(['reponse' => 'code valide']);
            }
            else{
                $session->set('avantage_code', $avoir->getAmount());
                return new JsonResponse(['reponse' => 'code valide']);
            }
        }
        else{
            return new JsonResponse(['reponse' => 'code non valide']);

        }
        return dd();
    }

}
