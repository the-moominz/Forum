<?php

// Classe dédiée à l'édition des messages
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

       // On instancie un nouvel objet MessagesModel
       $messagesModel = new MessagesModel();

       // On utilise la méthode getMessage avec en paramètre le résultat du GET de l'URL
       $message = $messagesModel->getMessage($_GET['index']);

       // On retourne le résultat sous forme de tableau à la vue
       return ['message' => $message];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */
       // On instancie un nouvel objet Messages Model
       $messagesModel = new MessagesModel();

       // On utilise la méthode updateMessage pour mettre à jour le message sélectionné
      $messagesModel->updateMessage($formFields['content'], $formFields['messageId']);

      // On redirige vers le topic en question
      $http->redirectTo('/topic?index=' . $formFields['topicId'] . '');

    }
}
