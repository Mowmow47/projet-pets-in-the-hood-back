<?php

namespace App\Controller\Api;

use App\Entity\Pet;
Use App\Form\PetType;
use App\Repository\PetRepository;
use App\Service\PictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/{id}", name="read", methods={"GET"})
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
        
        $form = $this->createForm(PetType::class, $pet, ['csrf_protection' => false]);

        $jsonArray = json_decode($request->getContent(), true);
        $form->submit($jsonArray);
 
        
        if ($form->isValid()) {

            $pet->setUser($this->getUser());
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($pet);
            $em->flush();

            return $this->json($pet, Response::HTTP_CREATED, [], [
                'groups' => ['pet_read'],
            ]);
        }

        $errorsString = (string) $form->getErrors(true);

        
        return $this->json([
            'errors' => $errorsString,
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method used to modify a pet profile
     * @Route("/{id}", name="edit", methods={"PATCH"})
     */
    public function edit(Pet $pet, Request $request)
    {
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
        $em = $this->getDoctrine()->getManager();
        $em->remove($pet);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
