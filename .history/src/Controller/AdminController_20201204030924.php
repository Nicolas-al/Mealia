<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserInterface $user)
    {
        $admin = $user;

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'admin' => $admin,
        ]);
    }
}
