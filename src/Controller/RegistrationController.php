<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

    public function __construct( )
    {
        // $this->request = $request;
        // $this->passwordEncoder = $passwordEncoder;
        // $this->authenticator = $authenticator;
        // $this->guardHandler = $guardHandler;
        
    }
    /**
     * @Route("/compte-inscription", name="registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $adress = new Adress();
            $this->saveUser($user,  $passwordEncoder, $request, $adress);


            // on envoi un mail de confirmation à l'utilisateur pour lui signaler qu'il est inscrit
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('mealia@gmail.com')
            ->setTo($request->request->get('registration')['email']);
            $img = $message->embed(\Swift_Image::fromPath('assets/images/Logo-couleur-vectorisé.png'));

            $message->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'email/confirm-inscription.html.twig',
                ['firstName' => $request->request->get('registration')['firstName'],
                'email' => $request->request->get('registration')['email'],
                'img' => $img,
                ]
            ),
            'text/html',
            'iso-8859-2'
            );
            $message->setCharset('UTF-8');
            // $message->setEncoder(\Swift_DependencyContainer::getInstance()->lookup('mime.base64contentencoder'));



            $mailer->send($message);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,          // the User object you just created
                $request,
                $authenticator, // authenticator whose onAuthenticationSuccess you want to use
                'main'          // the name of your firewall in security.yaml
            );
        }
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function saveUser($user, $passwordEncoder, $request, $adress)
    {
            
            // // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $adress->setCountry($request->request->get('registration')['adress']['country']);
            $adress->setCity($request->request->get('registration')['adress']['city']);
            $adress->setStreet($request->request->get('registration')['adress']['street']);
            $adress->setAdressSupplement($request->request->get('registration')['adress']['adressSupplement']);
            $adress->setZipCode($request->request->get('registration')['adress']['zipCode']);
            $adress->setName($request->request->get('registration')['lastName']);
            $adress->setfirstName($request->request->get('registration')['firstName']);
        
            $user->setAdress($adress);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            // // // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            
            return $entityManager->flush();
    }
}
