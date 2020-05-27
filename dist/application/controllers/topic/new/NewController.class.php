<?php

// Classe dédiée à gérer le formulaire de création de nouveau topic
class NewController {
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

       // On instancie un nouvel objet SectionModel
       $sectionModel = new SectionModel();

       // On utilise la fonction getSection avec l'ID de l'URL en get pour récupérer les informations spécifiques à la section dans laquelle est postée le nouveau topics
       $section = $sectionModel->getSection($_GET['index']);

       // On retourne les infos dans un tableau pour les transmettre à la vue
       return ['section' => $section];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */
       // On instancie un nouvel objet TopicModel
       $topicModel = new TopicModel();

       // On utilise la fonction createTopic pour entrer le topic dans la base de données
       $topicModel->createTopic($formFields['title'], $formFields['content'], $formFields['userId'], $formFields['sectionId']);

       // Instanciation de l'objet ScoreModel
       $scoreModel = new ScoreModel();

       // On utilise la méthode addPoints pour rajouter des points à l'utilisateur à la création du topic
       $scoreModel->addPoints(10, $formFields['userId']);

       // Instanciation de l'objet Secion Model
       $sectionModel = new SectionModel();

       // On utilise la méthode updateLastReadTopic pour ajouter à la section la date du dernier topic posté
       $sectionModel->updateLastReadTopic($formFields['sectionId']);

       // Une fois l'enregistrement fait en base, on redirige vers la liste des topics
       $http->redirectTo('/section?index=' . $formFields['sectionId'] . '');

    }
}
