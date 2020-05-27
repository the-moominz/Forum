<?php

// Classe pour gérer la suppression d'une faction en fonction de son ID
class DeleteController {
  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */

       if (!array_key_exists('index', $_GET) OR !ctype_digit($_GET['index'])) {
         // Si le $_GET['index'] n'existe pas ou que ce n'est pas une valeur numérique, on renvoie ailleurs
         $http->redirectTo('/admin/faction');
       }

       // On instancie un nouvel objet FactionModel
       $factionModel = new FactionModel();

       // On utilise la méthode deleteFaction pour supprimer la faction sélectionnée
       $factionModel->deleteFaction($_GET['index']);

       // On redirige sur la liste des factions une fois la suppression effectuée
       $http->redirectTo('/admin/faction');

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
