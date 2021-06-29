<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserPasswordType;
use App\Form\Admin\UserType;
use App\Service\PictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id"="\d+"})
     */
    public function edit(User $user, Request $request, PictureUploader $pictureUploader): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();

            if ($picture) {
                try {
                    $pictureFileName = $pictureUploader->upload($picture, 'user');
                    $user->setPicture($pictureFileName);
                } catch (\Exception $e) {
                    throw new UnsupportedMediaTypeHttpException($e);
                }
            }
    
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre profil a bien été mis à jour.');

            return $this->redirectToRoute('admin_user_read', ["id" => $user->getId()]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'id' => $user->getId(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit-password", name="edit_password", requirements={"id"="\d+"})
     */
    public function editPassword(int $id, Request $request, UserPasswordHasherInterface $encoder)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($encoder->hashPassword($user, $user->getPassword()));

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le mot de passe a bien été modifié.');

            return $this->redirectToRoute('admin_user_read', ['id' => $id]);
        }

        return $this->render('admin/user/edit.password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
