<?php

// Contrôleur pour la page d'accueil
class FactionController {

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

       // Récupération de la liste des factions en instanciant l'objet FactionModel
       $factionModel = new FactionModel();

       // Utilisation de la méthode listAll pour afficher l'intégralité de la table
       $factions = $factionModel->listAll();

       // On instancie un nouvel objet UserModel
       $userModel = new UserModel();

       // On retourne sous forme de tableau la variable $factions pour l'envoyer à la vue
       return ['factions' => $factions, 'userModel' => $userModel];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */


    }

}
