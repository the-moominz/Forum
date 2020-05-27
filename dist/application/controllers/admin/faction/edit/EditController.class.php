<?php

// Classe dédiée à l'édition d'une faction
class EditController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */

       // On gère l'affichage des valeurs en GET
       if (empty($_POST)) {
         if (!array_key_exists('index', $_GET) OR !ctype_digit($_GET['index'])) {
           // Si le $_GET['index'] n'existe pas ou que ce n'est pas une valeur numérique, on renvoie ailleurs
           $http->redirectTo('/admin/faction');
         }
         // Si on a passé l'étape du $_GET, on utilise une requête SQL
         $factionModel = new FactionModel();

         // On utilise la méthode getFaction pour récupérer le contenu de la faction qu'on veut modifier
         $values = $factionModel->getFaction($_GET['index']);

         return ['values' => $values];
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

       // On instancie un nouveau FactionModel
       $factionModel = new FactionModel();

       // On utilise la méthode updateFaction en indiquant en paramètre les valeurs du formulaire pour précéder à la modification en base
       $factionModel->updateFaction($formFields['name'], $formFields['description'], $formFields['index']);

       // Dès que la requête a fonctionné, on redirige vers la liste des factions
       $http->redirectTo('/admin/faction');


    }

}
