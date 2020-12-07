<?php

namespace App\Controller\User;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/moncompte/{id}", name="user_account")
     */
    public function index(User $user)
    {
        var_dump($user);
        return $this->render('user_account/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
