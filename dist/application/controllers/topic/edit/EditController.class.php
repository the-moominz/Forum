<?php

// Classe dédiée à l'édition du premier message ou du topic
class EditController {
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

       // On instancie un nouvel objet User Session
       $userSession = new UserSession();

       // On vérifie que l'utilisateur est connecté pour accéder à l'intérieur
       if (!$userSession->isAuthenticated()) {
         $http->redirectTo('/user/login');
       }

       // On instancie un nouvel objet TopicModel
       $topicModel = new TopicModel();

       // On utilise la méthode getTopic en fonction de l'ID communiqué par l'URL
       $topic = $topicModel->getTopic($_GET['index']);

       // On retourne le résultat dans un tableau à la vue
       return ['topic' => $topic];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       // On instancie un nouvel objet Topic Model
       $topicModel = new TopicModel();

       // On utilise la méthode updateTopic pour éditer le topic en fonction de son id
       $topicModel->updateTopic($formFields['title'], $formFields['content'], $formFields['topicId']);

       // Une fois la mise à jour effectuée, on retourne sur le topic en question
       $http->redirectTo('/topic?index=' . $formFields['topicId'] . '');
    }
}
