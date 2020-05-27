<?php

// Classe dédiée à l'affichage de l'intérieur d'une section
class SectionController {
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

       // On instancie un nouvel objet USer Session
       $userSession = new UserSession();

       // Récupération de l'Id du membre
       $userId = $userSession->getUserId();

       // On instancie un nouvel objet SectionModel
       $sectionModel = new SectionModel();

       // On utilise la méthode getSection() pour récupérer les infos à afficher sur la section à l'intérieur de laquelle on est
       $section = $sectionModel->getSection($_GET['index']);

       // On instancie un nouvel objet Topic Model
       $topicModel = new TopicModel();

       // On utilise la méthode listAll pour récupérer les infos à afficher
       $topics = $topicModel->listAll($_GET['index']);

       // Instanciation d'un objet MessagesModel
       $messagesModel = new MessagesModel();

       // On utilise la méthode howManyUnreadTopic pour voir dans la section combien de topics n'ont pas été visité
       $countTopics = $topicModel->howManyUnreadTopic($section['Id']);

       // Si on a tout lu dans la section que $countTopics == 0
       if ($countTopics == 0) {
         // On met à jour la table section_track
         $sectionTrackModel = new SectionTrackModel();

         // Utilisation de la méthode sectionTrack, qui va mettre à jour la table
         $sectionTrackModel->sectionTrack($userId, $section['Id']);

         // Une fois la table section_track mise à jour, on supprime des éléments de la table topic_track pour faire de la place
         $topicTrackModel = new TopicTrackModel();

         $topicTrackModel->deleteReadTopic($userId, $section['Id']);
       }

       // On retourne les infos à la vue dans un tableau
       return ['section' => $section, 'topics' => $topics, 'messagesModel' => $messagesModel, 'userSession' => $userSession];

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
