<?php

namespace App\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(\Swift_Mailer $mailer, Request $request): Response
    {

        if($request->isMethod('POST')){
        
        $message = (new \Swift_Message())
            ->setFrom($request->request->get('email-contact'))
            ->setTo('contact@mealia.fr')
            ->setSubject($request->request->get('object-contact'))
            ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'email/message.html.twig',
                ['name' => $request->request->get('name-contact'),
                'message' => $request->request->get('message-contact'),
                ]
            ),
            'text/html',
            'iso-8859-2'
            );
            $message->setCharset('UTF-8');
            $mailer->send($message);
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
