<?php

// Contrôleur pour déconnecter le membre
class LogoutController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */

    	 // Création d'une instance de UserSession
    	 $userSession = new UserSession();

       // Instanciation de l'objet UserModel
       $userModel = new UserModel();

       // Utilisation de la méthode UpdateOnlineStatus pour changer le statut et remettre à zéro
       $userModel->updateOnlineStatus($userSession->getUserId());

    	 // Appel d'une méthode pour détruire une session
    	 $userSession->destroy();

    	 // On redirige l'utilisateur vers la page d'accueil
    	 $http->redirectTo('/');
    }

}
