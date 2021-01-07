<?php

namespace App\Controller;

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
    /**
     * @Route("/registration", name="registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

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
}
