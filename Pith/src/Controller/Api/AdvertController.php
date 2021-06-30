<?php

namespace App\Controller\Api;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use App\Service\PictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/advert", name="api_advert_")
 */
class AdvertController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     * @Route("/{tag}", name="browse_by_tag", methods={"GET"}, requirements={"tag"="\D+"})
     */
    public function browse(AdvertRepository $advertRepository, string $tag = null): Response
    {
        // If a tag is specified in the route parameters, then only the results that matches the tag are retrieved.
        // The condition can be replaced by " $request->attributes->get('_route') == 'api_advert_browse_by_tag' " instead.
        if($tag) {
            $adverts = $advertRepository->findByTag($tag);
        } else {
            $adverts = $advertRepository->findBy(['isActive' => true], ['createdAt' => 'DESC']);
        }

        return $this->json($adverts, Response::HTTP_OK, [], [
            'groups' => ['advert_browse'],
        ]);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Advert $advert): Response
    {
        return $this->json($advert, Response::HTTP_OK,[], [
            'groups' => ['advert_read'],
        ]);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        $advert = new Advert();
        $this->denyAccessUnlessGranted('ADVERT_ADD', $advert);
    
        $form = $this->createForm(AdvertType::class, $advert, ['csrf_protection' => false]);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();
            
            return $this->json($advert, Response::HTTP_CREATED, [], [
                'groups' => ['advert_read'],
            ]);
        }

        $errorsString = (string) $form->getErrors(true);
        return $this->json($errorsString, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="edit",  methods={"PATCH"})
     */
    public function edit(Advert $advert, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ADVERT_EDIT', $advert);

        $form = $this->createForm(AdvertType::class, $advert, ['csrf_protection' => false]);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if($form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            
            return $this->json($advert, Response::HTTP_OK, [], [
                'groups' => ['advert_read'],
            ]);
        }

        $errorsString = (string) $form->getErrors(true);
        return $this->json($errorsString, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}/archive", name="deactivate", requirements={"id"="\d+"},  methods={"PATCH"})
     */
    public function deactivate(Advert $advert, Request $request)
    {
        $this->denyAccessUnlessGranted('ADVERT_DEACTIVATE', $advert);

        $request = $advert->getid();
        
        if ($request) {
            
            $advert->setIsActive(false);
            $advert->setIsReported(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();
        
            return new JsonResponse(['data' => ['message' => 'L\'annoce a été cloturé.']], Response::HTTP_OK);

        }
        
        return new JsonResponse(['data' => ['message' => 'Une erreur s\'est produite']], Response::HTTP_BAD_REQUEST);
    }
    
    /**
     * @Route("/{id}/picture", name="upload_picture", methods={"POST"})
     */
    public function uploadPicture(Advert $advert, Request $request, PictureUploader $pictureUploader)
    {
        $picture = $request->files->get('picture');
        
        if($picture) {

            try {
                $pictureFileName = $pictureUploader->upload($picture, 'advert');  
            } catch (\Exception $e) {
                throw new UnsupportedMediaTypeHttpException($e);
            }
    
            $advert->setPicture($pictureFileName);
    
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();
    
            return new JsonResponse($pictureFileName, Response::HTTP_OK);
        }         
        
        return new JsonResponse(['data' => ['message' => 'Une erreur s\'est produite']], Response::HTTP_BAD_REQUEST);
    }
}