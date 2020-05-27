<?php

// Classe pour gérer la page principale du panneau d'administration
class DeleteController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */

       // On vérifie les paramètres renvoyés par l'URL en GET
       if (!array_key_exists('index', $_GET) OR !ctype_digit($_GET['index'])) {
         // Si le $_GET['index'] n'existe pas ou que ce n'est pas une valeur numérique, on renvoie ailleurs
         $http->redirectTo('/');
       }

       // Instanciation d'un nouvel objet DashboardModel
       $dashboardModel = new DashboardModel();

       // On utilise la méthode deleteNews pour supprimer la news correspondante à l'ID
       $dashboardModel->deleteNews($_GET['index']);

       // On redirige l'utilisateur vers la page d'admin
       $http->redirectTo('/admin');
    }



}
