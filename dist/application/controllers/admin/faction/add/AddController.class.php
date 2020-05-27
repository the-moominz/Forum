<?php
// Class permettant de gérer le formulaire envoyé lors de l'ajout d'une faction
class AddController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
       // Instanciation d'un nouvel objet UserSession
       $userSession = new UserSession();

       // On vérifie que l'utilisateur est bien connecté
       if (!$userSession->isAuthenticated()) {
         // Si non, on le redirige à la page de connexion
         $http->redirectTo('/user/login');
       }

       // On vérifie ensuite si l'utilisateur a les droits d'administration
       if (!$userSession->isAdmin()) {
         // Si non, on le redirige à la page d'accueil
         $http->redirectTo('/');
       }

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       // On teste si le formulaire a bien été envoyé
       if(isset($formFields['name']) && isset($formFields['description'])) {

         try {
           // On instancie un nouvel objet FactionModel
           $factionModel = new FactionModel();

           // ON utilise la fonction create de l'objet FactionModel pour créer une nouvelle faction et l'enregistrer en base
           $factionModel->create($formFields['name'], $formFields['description']);

           // Une fois la faction créee, on retourne sur la page de gestion des factions
           $http->redirectTo('/admin/faction');

         } catch(DomainException $exception) {
           // On pré-remplit le formulaire & on affiche le message d'erreur
         }
       }
    }

}
