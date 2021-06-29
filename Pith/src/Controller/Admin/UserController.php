<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user", name="admin_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"})
     */
    public function read(User $user): Response
    {
        return $this->render('admin/user/read.html.twig', [
            'user' => $user,
        ]);
    }
}
