<?php

// Contrôleur pour la page d'accueil
class UserController {

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

       // Récupération de la liste des utilisateurs en passant par UserModel
       $userModel = new UserModel();

       // Utilisation de la méthode listUsers() qui permet de retourner le contenu de la table user
       $users = $userModel->listUsers();

       // On retourne le résultat dans un tableau pour le communiquer à la vue
       return ['users' => $users];
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
