<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Entity\Adress;
use App\Form\AdressType;
use App\Form\NewslatterType;
use App\Form\UpdateDataType;
use App\Form\RegistrationType;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/compte", name="user")
     */
    public function index(): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

     /**
     * @Route("/compte/suivi-de-commandes", name="order_tracking")
     */
    public function orderTracking(OrderRepository $repoOrder): Response
    {
        $order = $repoOrder->findOneBy([], ['id' => 'desc']);

        return $this->render('user/order-tracking.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    // public function invoice(): Response
    // {
    //     return $this->render('user/order-tracking.html.twig', [
    //         'controller_name' => 'UserController',
    //     ]);
    // }



     /**
     * @Route("/compte/informations-personnelles", name="account_info_perso")
     */
    public function showInfoPerso(): Response
    {
        return $this->render('user/info-perso.html.twig', []);
    }

     /**
     * @Route("/compte/informations-personnelles/modifier", name="account_update_info_perso")
     */
    public function updateInfoPerso(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        { 
            // nouvelle date de naissance;
            $date = $request->request->get('registration')['dateOfBirth']['year'] . '-' . $request->request->get('registration')['dateOfBirth']['month'] . '-' . $request->request->get('registration')['dateOfBirth']['day'];

            // // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            
            $user = $this->getUser();
         
            $user->setFirstName($request->request->get('registration')['firstName']);
            $user->setLastName($request->request->get('registration')['lastName']);
            $user->setEmail($request->request->get('registration')['email']);
            $user->setPhone($request->request->get('registration')['phone']);
            $user->setDateOfBirth(\DateTime::createFromFormat('Y-m-d', $date));
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            
            $entityManager->flush();
        }
        return $this->render('user/update-info-perso.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/compte/informations-personnelles/adresse/modifier", name="account_update_adress_perso")
    */
    public function updateAdressPerso(Request $request)
    {
        $adress = new Adress();
        $formAdress = $this->createForm(AdressType::class, $adress);
        $formAdress->handleRequest($request);
        if ($formAdress->isSubmitted() && $formAdress->isValid()) 
        { 

            $user = $this->getUser();
            $adress = $user->getAdress();

            $adress->setCountry($request->request->get('adress')['country']);
            $adress->setCity($request->request->get('adress')['city']);
            $adress->setStreet($request->request->get('adress')['street']);
            $adress->setAdressSupplement($request->request->get('adress')['adressSupplement']);
            $adress->setZipCode($request->request->get('adress')['zipCode']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('user/update-adress-perso.html.twig', [
            'formAdress' => $formAdress->createView(),
        ]);

    }

    /**
    * @Route("/compte/newsletter-et-données-personnelles", name="account_newsletter")
    */
    public function newsletter(Request $request)
    {

        $user = $this->getUser();

        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        { 
            if ($user->getNewsletter() == 0){
                $user->setNewsletter(1);
            }else
            {
                $user->setNewsletter(0);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        
            return $this->redirectToRoute('account_newsletter', ['_fragment' => 'block_newsletter']);

        }
        return $this->render('user/newsletter.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/compte/newsletter-et-donnees-personnelles/demande", name="form_update_data")
    */
    public function formData(Request $request, \Swift_Mailer $mailer)
    {
        $user = $this->getUser();

        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('message', TextareaType::class)
            ->getForm();

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        { 
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom($user->getEmail())
            ->setTo('contact@mealia.fr')
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'email/update-data.html.twig',
                    ['name' => $user->getLastName(),
                    'firstName' => $user->getFirstName(),
                    'email' => $user->getEmail(),
                    'message' => $request->request->get('form')['message']
                    ]
                ),
                'text/html'
                );
    
                $mailer->send($message);

        }

        return $this->render('user/form-data.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/compte/avis", name="account_notice")
    */
    public function avis()
    {

        return $this->render('user/notice.html.twig');

    }

    /**
    * @Route("/compte/deconnexion", name="account_logout")
    */
    public function logout()
    {
        return $this->render('user/logout.html.twig');
    }

}
