<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user", name="api_user_")
 */
class UserController extends AbstractController
{
    /**
     * Method used to see the list of users
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->json($users, Response::HTTP_OK, [], [
            //'groups' => ['user_browse'],
        ]);
    }

     /**
     * Method used to see a specific user
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(User $user)
    {
        return $this->json($user, Response::HTTP_OK, [], [
            //'groups' => ['user_read'],
        ]);
    }

    /**
     * Method used to create user profile
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request)
    {
        // We always handle an entity
        $user = new User();
        // We handle a form
        $form = $this->createForm(UserType::class, $user, ['csrf_protection' => false]);

         // Our client sends JSON, we get it with getContent()
         $json = $request->getContent();
         // We decode the JSON to obtain an associative array
         $jsonArray = json_decode($json, true);
 
         // We send our associative array to our form
         // After this step, our $user object will be automatically filled
         // It allows us to automatically associate data received with our $user object
         // In addition, we take advantage of the validation system according to the constraints in our form fields
         $form->submit($jsonArray);
 
        // We check that there is no error in the form
        // we executed the submit () method ourselves
        if ($form->isValid()) {

            // If there is no error in the form, we persist and we flush
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // In an API, we return the serialized object to confirm its addition
            // by specifying a code 201 Created
            return $this->json($user, Response::HTTP_CREATED, [], [
                //'groups' => ['user_read'],
            ]);
        }
        
        // If the form is not valid, we must provide a response with error messages
        // All error messages are in $ form-> getErrors ()
        // with the boolean true, we specify that we want the list of all the errors of all the schamps of the form
        $errorsString = (string) $form->getErrors(true);

        // We can now prepare an appropriate response for the developer or the software with the correct HTTP code
        return $this->json([
            'errors' => $errorsString,
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method used to modify user profile
     * @Route("/{id}", name="edit", methods={"PATCH"})
     */
    public function edit(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user, ['csrf_protection' => false]);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if ($form->isValid()) {
            

            $this->getDoctrine()->getManager()->flush();

            return $this->json($user, Response::HTTP_OK, [], [
                //'groups' => ['user_read'],
            ]);
        }

        return $this->json([
            'errors' => (string) $form->getErrors(true),
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method used to delete user profile
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}