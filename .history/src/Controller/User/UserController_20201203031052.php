<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/moncompte", name="user_account")
     */
    public function index(): Response
    {
        return $this->render('user_account/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
