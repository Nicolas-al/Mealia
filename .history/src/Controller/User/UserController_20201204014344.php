<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="")
     */
    public function index(UserRepository $repo)
    {
        
        return $this->render('user_account/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
