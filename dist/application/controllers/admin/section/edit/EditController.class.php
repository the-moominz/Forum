<?php
// Classe dédiée à l'édition d'une section
class EditController {
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

       if (empty($_POST)) {
       // On instancie un nouvel objet SectionModel
       $sectionModel = new SectionModel();

       // Utilisation de la méthode getSection pour récupérer les données de la section et les réafficher
       $section = $sectionModel->getSection($_GET['index']);

       // On retourne à la vue le résultat de la requête SQL
       return ['section' => $section];
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

       // On instancie un nouvel objet Section Model
       $sectionModel = new SectionModel();

       // On utilise la méthode updateSection pour mettre à jour la base de données
       $sectionModel->updateSection($formFields['name'], $formFields['description'], $formFields['category'], $formFields['index']);

       // Une fois la modification effectuée, on renvoit vers la liste des sections
       $http->redirectTo('/admin/section');
    }
}
