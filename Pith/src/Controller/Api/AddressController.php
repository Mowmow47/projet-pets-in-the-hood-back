<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/address", name="api_address_")
 */
class AddressController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findAll();

        return $this->json($address, Response::HTTP_OK, []);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Address $address): Response
    {
        return $this->json($address, Response::HTTP_OK, []);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address, ['csrf_protection' => false]);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();
            
            return $this->json($address, Response::HTTP_CREATED, []);
        }

        $errorsString = (string) $form->getErrors(true);
        return $this->json($errorsString, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="edit", methods={"PATCH"})
     */
    public function edit(Address $address, Request $request): Response
    {
        $form = $this->createForm(AddressType::class, $address, ['csrf_protection' => false]);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if($form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            
            return $this->json($address, Response::HTTP_OK, []);
        }

        $errorsString = (string) $form->getErrors(true);
        return $this->json($errorsString, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Address $address)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
