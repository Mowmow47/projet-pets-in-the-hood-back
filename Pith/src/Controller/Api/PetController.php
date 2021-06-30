<?php

namespace App\Controller\Api;

use App\Entity\Pet;
Use App\Form\PetType;
use App\Repository\PetRepository;
use App\Repository\BreedRepository;
use App\Service\PictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pet", name="api_pet_")
 */
class PetController extends AbstractController
{
    /**
     * Method used to see the list of pet
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(PetRepository $petRepository): Response
    {
        $pets = $petRepository->findAll();

        return $this->json($pets, Response::HTTP_OK, [], [
            'groups' => ['pet_browse'],
        ]);
    }

    /**
     * Method used to see a specific pet
     * @Route("/{id}", name="read", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function read(Pet $pet)
    {
        return $this->json($pet, Response::HTTP_OK, [], [
           'groups' => ['pet_read'],
        ]);
    }

     /**
      * Method used to create a pet profile
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request)
    {
        $pet = new Pet();

        $this->denyAccessUnlessGranted('PET_ADD', $pet);

        $form = $this->createForm(PetType::class, $pet, ['csrf_protection' => false]);

        $jsonArray = json_decode($request->getContent(), true);
        $form->submit($jsonArray);
 
        if ($form->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($pet);
            $em->flush();

            return $this->json($pet, Response::HTTP_CREATED, [], [
                'groups' => ['pet_read'],
            ]);
        }
        
        return $this->json([
            'errors' => (string) $form->getErrors(true),
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method used to modify a pet profile
     * @Route("/{id}", name="edit", methods={"PATCH"})
     */
    public function edit(Pet $pet, Request $request)
    {
        $this->denyAccessUnlessGranted('PET_EDIT', $pet);
        $form = $this->createForm(PetType::class, $pet, ['csrf_protection' => false]);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);
        
        $form->submit($jsonArray);

        if ($form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->json($pet, Response::HTTP_OK, [], [
                'groups' => ['pet_read'],
            ]);
        }

        return $this->json([
            'errors' => (string) $form->getErrors(true),
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method used to delete a pet profile
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Pet $pet)
    {
        $this->denyAccessUnlessGranted('PET_DELETE', $pet);

        $em = $this->getDoctrine()->getManager();
        $em->remove($pet);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/search", name="search", methods={"POST"})
     */
    public function search(Request $request, PetRepository $petRepository): Response
    {
        // Refers the value of the search input
        $searchQuery = $request->query->get('q');
        
        // if the value match the following pattern ???### (? = digit # = letter) 
        // the search input value is a tattoo, otherwise it's the name of a pet.
        if(preg_match('(^\d{3}[A-Z]{3}$)', $searchQuery)) {
            $pets = $petRepository->findByCriteria('tattoo', $searchQuery);
        } else {
            $pets = $petRepository->findByCriteria('name', $searchQuery);
        }

        return $this->json($pets, Response::HTTP_OK, [], [
            'groups' => ['pet_browse'],
        ]);
    }

    /**
     * @Route("/{id}/picture", name="upload_picture", methods={"POST"})
     */
    public function uploadPicture(Pet $pet, Request $request, PictureUploader $pictureUploader)
    {
        $picture = $request->files->get('picture');
        
        if($picture) {

            try {
                $pictureFileName = $pictureUploader->upload($picture, 'pet');  
            } catch (\Exception $e) {
                throw new UnsupportedMediaTypeHttpException($e);
            }
    
            $pet->setPicture($pictureFileName);
    
            $em = $this->getDoctrine()->getManager();
            $em->persist($pet);
            $em->flush();
    
            return new JsonResponse($pictureFileName, Response::HTTP_OK);
        }         
        
        return new JsonResponse(['data' => ['message' => 'Une erreur s\'est produite']], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method used to see the list of breeds
     * @Route("/breed", name="breed_Browse", methods={"GET"})
     */
    public function breedBrowse(BreedRepository $breedRepository): Response
    {
        $breeds = $breedRepository->findAll();

        return $this->json($breeds, Response::HTTP_OK, [], [
            'groups' => ['breed_browse'],
        ]);
    }
}
