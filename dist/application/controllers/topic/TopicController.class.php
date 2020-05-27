<?php
// Classe dédiée au traitement PHP de la page de l'intérieur d'un topic
class TopicController {
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

       // On récupère l'ID du membre connecté
       $userId = $userSession->getUserId();

       // On instancie un nouvel objet Topic Model
       $topicModel = new TopicModel();

       // On utilise la méthode listAll de Topic Model pour afficher les informations du topic en fonction des paramètres de l'URL en GET
       $topic = $topicModel->getTopic($_GET['index']);

       // On instancie un nouvel objet Messages Model
       $messagesModel = new MessagesModel();

       // On utilise la fonction listAll pour récupérer les messages associés au topic
       $messages = $messagesModel->listAll($_GET['index']);

       // On utilise la fonction lastTopicPost pour récupérer l'Id du dernier message posté dans le topic
       $lastPostId = $messagesModel->lastTopicPost($topic['Id']);

       // Instanciation d'un objet TopicTrack
       $topicTrackModel = new TopicTrackModel();

       if ($userSession->isAuthenticated()) {
         // On utilise la méthode addTopicTrack (que si le membre est co) pour déclarer le topic comme lu par le membre en question
         $topicTrackModel->addTopicTrack($userId, $_GET['index'], $topic['section_Id']);
       }

       // On retourne à la vue les données dans un tableau
       return ['topic' => $topic, 'messages' => $messages, 'userSession' => $userSession];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       // On vérifie que les éléments du formulaire ont bien été envoyé
       if (isset($formFields['message'])) {

         try {
       // On instancie un nouvel objet MessagesModel
       $messagesModel = new MessagesModel();

       // On utilise la fonction createMessage pour envoyer les valeurs du formulaire à la base
       $messagesModel->createMessage($formFields['message'], $formFields['userId'], $formFields['topicId']);

       // Instanciation de l'objet ScoreModel
       $scoreModel = new ScoreModel();

       // On utilise la méthode addPoints pour rajouter des points à l'utilisateur à la création du topic
       $scoreModel->addPoints(5, $formFields['userId']);

       // On instancie un nouvel objet TopicModel
       $topicModel = new TopicModel();

       // On utilise la méthode updateLastReadPost pour mettre à jour sur le topic la date du dernier message
       $topicModel->updateLastReadPost($formFields['topicId']);

       // On instancie un nouvel objet Section Model
       $sectionModel = new SectionModel();

       // On récupère l'id de la section du topic dans lequel le message a été posté
       $sectionId = $topicModel->getSectionId($formFields['topicId']);

       // On utilise la méthode updateLastReadTopic pour mettre également à jour la section et lui dire le dernier topic actif
       $sectionModel->updateLastReadTopic($sectionId['section_Id']);

       // On redirige vers le topic
       $http->redirectTo('/topic?index=' . $formFields['topicId'] . '');

        } catch(DomainException $exception) {
         // On pré-remplit le formulaire et on envoie un message d'erreur
       }
     }
    }
}
