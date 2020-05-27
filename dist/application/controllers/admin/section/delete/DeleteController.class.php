<?php
// Classe dédiée à la suppression d'une section de la base de données

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
         $http->redirectTo('/admin/section');
       }

       // On instancie un nouvel objet SectionModel
       $sectionModel = new SectionModel();

       // On utilise la fonction deleteSection pour supprimer la section voulue
       $sectionModel->deleteSection($_GET['index']);

       // On redirige vers la liste des sections, une fois la suppression effectuée
       $http->redirectTo('/admin/section');

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
