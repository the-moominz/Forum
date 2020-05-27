<?php
// Classe dédiée à la suppression d'un topic
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

       // On instancie un nouvel objet Topic Model
       $topicModel = new TopicModel();

       // On utilise la méthode deleteTopic pour supprimer le topic sélectionné
       $topicModel->deleteTopic($_GET['index']);

       // On récupère l'heure et la date du dernier message (LastPostTimeStamp) du topic qu'on veut supprimer
       $lastPost = $topicModel->getLastPostTime($_GET['section']);

       // Instanciation d'un objet SectionModel
       $sectionModel = new SectionModel();

       // On met à jour la section avec le dernier topic en vigueur
       $sectionModel->updateOneReadTopic($lastPost, $_GET['section']);

       // Une fois supprimé, on redirige vers la liste des topics
       $http->redirectTo('/section?index=' . $_GET['section'] . '');
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
