<?php
// Classe qui gère la suppression d'un message privé
class DeleteController {
  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
       // Instanciation d'un objet User Session
       $userSession = new UserSession();

       // On vérifie les paramètres renvoyés par l'URL en GET
       if (!array_key_exists('index', $_GET) OR !ctype_digit($_GET['index'])) {
         // Si le $_GET['index'] n'existe pas ou que ce n'est pas une valeur numérique, on renvoie ailleurs
         $http->redirectTo('/');
       }

		   // On vérifie que l'utilisateur est bien connecté, sinon on le renvoie à la page de connexion
       if (!$userSession->isAuthenticated()) {
         $http->redirectTo('/user/login');
       }
       // Instanciation d'un nouvel objet MPModel
       $mpModel = new MPModel();

       // On utilise la méthode DeleteMp qui permet de supprimer le message
       $mpModel->deleteMp($_GET['index']);

       // Une fois le message supprimé, on revient à la boîte d'envoi
       $http->redirectTo('/mp/send?index=' . $_GET['send'] . '');
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
