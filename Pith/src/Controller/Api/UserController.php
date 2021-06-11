<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="browse", methods={"GET"})
     */
    public function browse(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->json($users, Response::HTTP_OK, [], [
            //'groups' => ['user_browse'],
        ]);
    }

     /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(User $user)
    {
        return $this->json($user, Response::HTTP_OK, [], [
            //'groups' => ['user_read'],
        ]);
    }

    /**
     * Contexte : On fait comme un controleur classique mais on est en API
     * On reçoit toujours des infos mais on n'a pas de formulaire à afficher
     * 
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request)
    {
        // On manipule toujours une entité
        $user = new User();
        // On manipule un formulaire
        $form = $this->createForm(UserType::class, $user, ['csrf_protection' => false]);

         // Notre client envoie du JSON, on le récupère avec getContent()
         $json = $request->getContent();
         // On décode le JSON pour obtenir un tableau associatif
         $jsonArray = json_decode($json, true);
 
         // On envoie notre tableau associatif à notre formulaire
         // La méthode submit va faire un peu comme handleRequest et prendre chacune
         // des clées du tableau pour les associer aux inputs du formulaire
         // Après cette étape, notre objet $user sera automatiquement rempli
         // Ça nous permet d'associer automatiquement/facilement des données reçues à notre objet $user
         // De plus, on profite du système de validations selon les contraintes dans nos champs de formulaire
         $form->submit($jsonArray);
 
          // On vérifie qu'il n'y a pas d'erreur dans le formulaire
        // Pas besoin de $form->isSubmitted(), on est sur que le form est envoyé car
        // on a exécuté nous même la méthode submit()
        if ($form->isValid()) {
             // On calcule le slug du User

            // S'il n'y a pas d'erreur dans le formulaire, on persiste et on flush
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // On est dans une API, on renvoit l'objet sérialisé pour confirmer son ajout
            // en précisant un code 201 Created
            return $this->json($user, Response::HTTP_CREATED, [], [
                //'groups' => ['user_read'],
            ]);
        }

        // Si le formulaire n'est pas valide, on doit fournir une réponse avec les messages d'erreurs
        // Tous les messages d'erreurs sont dans $form->getErrors()
        // avec le booléen true, on précise qu'on veut la liste de toutes les erreurs de tous le schamps du formulaire
        // Il est possible de parser notre en une string
        $errorsString = (string) $form->getErrors(true);

        // On peut maintenant préparer une réponse adaptée pour le développeur/logiciel avec le bon code HTTP
        return $this->json([
            'errors' => $errorsString,
        ], Response::HTTP_BAD_REQUEST);
    }
}
