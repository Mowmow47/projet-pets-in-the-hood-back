<?php

namespace App\Controller\Admin;

use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="admin_homepage")
     */
     public function index(): RedirectResponse
     {
        return $this->redirectToRoute('admin_browse');
     }

    /**
     * @Route("/admin", name="admin_browse")
     */
    public function browse(AdvertRepository $advertRepository): Response
    {
        return $this->render('admin/home.html.twig', [
            'awaitingAdverts' => $advertRepository->findBy(['isActive' => false, 'isReported' => false]),
            'reportedAdverts' => $advertRepository->findBy(['isActive' => true, 'isReported' => true]),
            'inactiveAdverts' => $advertRepository->findBy(['isActive' => false, 'isReported' => true]),
        ]);
    }
}
