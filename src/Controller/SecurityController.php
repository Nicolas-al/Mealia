<?php

namespace App\Controller;

use App\Form\Password\NewPassType;
use App\Form\Password\ResetPassType;
use Swift_Mailer;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/oubli-pass", name="app_forgotten_password")
     */
    public function forgottenPass(Request $request, UserRepository $userRepo, \Swift_Mailer $mailer, 
                                  TokenGeneratorInterface $tokerGenerator, EntityManagerInterface $em)
    {
        $form = $this->createForm(ResetPassType::class);

        // on traite le formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            // on vérifie si un utilisateur à cet email
            $user = $userRepo->findOneByEmail($data['email']);

            if(!$user){
                $this->addFlash('erreur' , 'cet adresse n\'existe pas');

                $this->redirectToRoute('app_login');
            }
            //On génère un token
            $token = $tokerGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $em->persist($user);
                $em->flush();
            }
            catch(\Exception $e){
                $this->addFlash('attention', 'une erreur est survenue : ' . $e->getMessage());
                $this->redirectToRoute('app_login');
            }

            // on génère l'URL réinitialisation du mot de passe
            $url = $this->generateUrl('app_reset_password', ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL);

            // On créer l'email
            $msg = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom('mealia.thelittleshop@gmail.com')
            ->setTo($user->getEmail());
            $msg->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'email/forgotten-password.html.twig', [
                        'url' => $url
                    ]),
                    
                    'text/html',
                    'iso-8859-2'
                    );
            // on envoi l'email
            $mailer->send($msg);

            $this->addFlash('message', 'un email de réinitialisation de mot de passe vous à été envoyé');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgotten-password.html.twig', [
            'formEmail' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset-pass/{token}", name="app_reset_password")
     */
    public function resetPass($token, Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepo, EntityManagerInterface $em){
        
        // on cherche l'utilisateur avec le token
        $user = $userRepo->findOneBy(['resetToken' => $token]);

        if (!$user){
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('app_login');
        }

        // on créer le formulaire de nouveau mot de passe
        $form = $this->createForm(NewPassType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // on supprime le token
            $user->setResetToken(null);

            // on crypt le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('new_pass')['plainPassword']['first']));
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'modifié avec succès');

            return $this->redirectToRoute('app_login');
        }else{
            return $this->render('security/reset-password.html.twig',[
                'token' => $token,
                'formNewPass' => $form->createView()
            ]);
        }
    }
    
}
