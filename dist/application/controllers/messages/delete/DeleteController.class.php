<?php
// Classe dédiée à la suppression d'un message de la base de données
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

       // On instancie un nouvel objet MessagesModel
       $messagesModel = new MessagesModel();

       // On utilise la méthode deleteMessage pour supprimer le message dont le paramètre est passé dans l'URL
       $messagesModel->deleteMessage($_GET['index']);

       // On utilise la méthode getLastPostMessage pour récupérer le TimeStamp du dernier message d'un topic
       $lastMessage = $messagesModel->getLastPostMessage($_GET['topic']);

       // Instanciation d'un nouvel objet $topicModel
       $topicModel = new TopicModel();

       // On utilise la méthode updateOneReadMessage pour mettre à jour la colonne après suppression d'un message
       $topicModel->updateOneReadMessage($lastMessage, $_GET['topic']);

       // On récupère l'id de la section où le topic a été modifié
       $sectionId = $topicModel->getSectionId($_GET['topic']);

       // On récupère le datetime du dernier message du topic
       $lastPost = $topicModel->getLastPostTime($sectionId);

       // Instanciation d'un nouvel objet SectionModel
       $sectionModel = new SectionModel();
       
       // On met à jour la colonne LastTopic de la table section
       $sectionModel->updateOneReadTopic($lastPost, $sectionId);

       // On redirige l'utilisateur vers le topic en question
       $http->redirectTo('/topic?index=' . $_GET['topic'] . '');
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
