<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $repo)
    {
        $admin = $repo->findOneBy(['id']);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'admin' => $admin,
        ]);
    }
}
