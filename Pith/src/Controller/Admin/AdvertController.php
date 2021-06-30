<?php

namespace App\Controller\Admin;

use App\Entity\Advert;
use App\Form\Admin\AdvertType;
use App\Repository\AdvertRepository;
use App\Service\PictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advert", name="admin_advert_")
 */
class AdvertController extends AbstractController
{
    /**
     * @Route("", name="browse")
     * @Route("/reported", name="reported")
     * @Route("/awaiting-approval", name="to_approve")
     * @Route("/inactive", name="inactive")
     */
    public function browse(Request $request, AdvertRepository $advertRepository): Response
    {
        $route = $request->attributes->get('_route');
        /**
         * isActive * isReported *
         *     0    *      0     * => Advert awaiting approval
         *     1    *      0     * => Active Advert
         *     1    *      1     * => Reported Advert
         *     0    *      1     * => Inactive/Blocked Advert
         */
        switch($route) {
            case 'admin_advert_browse':
                $criterias = ['isActive' => true, 'isReported' => false];
                $title = 'Liste des annonces';
                break;
            case 'admin_advert_reported':
                $criterias = ['isActive' => true, 'isReported' => true];
                $title = 'Annonces signalées';
                break;
            case 'admin_advert_to_approve':
                $criterias = ['isActive' => false, 'isReported' => false];
                $title = 'Annonces en attente de validation';
                break;
            case 'admin_advert_inactive':
                $criterias = ['isActive' => false, 'isReported' => true];
                $title = 'Annonces inactives';
                break;
        }

        $adverts = $advertRepository->findBy($criterias, ['createdAt' => 'DESC']);

        return $this->render('admin/advert/browse.html.twig', [
            'title' => $title,
            'adverts' => $adverts,
        ]);
    }

    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"})
     */
    public function read(Advert $advert): Response
    {
        return $this->render('admin/advert/read.html.twig', [
            'advert' => $advert,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id"="\d+"})
     */
    public function edit(Advert $advert, Request $request, PictureUploader $pictureUploader): Response
    {
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
    
            $dateOfLoss = $form['dateOfLoss']->getData();
            $dateOfDiscovery = $form['dateOfDiscovery']->getData();

            if($dateOfLoss) {
                $advert->setDateOfLoss($dateOfLoss);
            }
            if($dateOfDiscovery) {
                $advert->setDateOfDiscovery($dateOfDiscovery);
            }

            $picture = $form->get('picture')->getData();

            if ($picture) {
                try {
                    $pictureFileName = $pictureUploader->upload($picture, 'advert');
                    $advert->setPicture($pictureFileName);
                } catch (\Exception $e) {
                    throw new UnsupportedMediaTypeHttpException($e);
                }
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'L\'annonce a bien été mise à jour.');

            return $this->redirectToRoute('admin_advert_read', ["id" => $advert->getId()]);
        }

        return $this->render('admin/advert/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function delete(Advert $advert, Request $request)
    {
        $redirect = $request->query->get('redirect');
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('deleteAdvert', $token)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($advert);
            $em->flush();

            $this->addFlash('success', 'L\'annonce a bien été supprimée.');

            return $this->redirectToRoute($redirect);
        }

        throw $this->createAccessDeniedException('Le token n\'est pas valide.');
    }

    /**
     * @Route("/{id}/approve", name="approve", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function approve(Advert $advert, Request $request)
    {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('approveAdvert', $token)) {

            $advert->setIsActive(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $this->addFlash('success', 'L\'annonce a bien été approuvée.');

            return $this->redirectToRoute('admin_advert_to_approve');
        }

        throw $this->createAccessDeniedException('Le token n\'est pas valide.');
    }

    /**
     * @Route("/{id}/deactivate", name="deactivate", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function deactivate(Advert $advert, Request $request)
    {
        $redirect = $request->query->get('redirect');
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('deactivateAdvert', $token)) {

            $advert->setIsActive(false);
            $advert->setIsReported(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $this->addFlash('success', 'L\'annonce a bien été désactivée.');

            return $this->redirectToRoute($redirect);
        }

        throw $this->createAccessDeniedException('Le token n\'est pas valide.');
    }
}
