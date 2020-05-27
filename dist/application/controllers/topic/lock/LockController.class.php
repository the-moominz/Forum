<?php
// Classe qui va permettre de déverrouiller un topic
class LockController {
  public function httpGetMethod(Http $http, array $queryFields) {

    // On vérifie les paramètres renvoyés par l'URL en GET
    if (!array_key_exists('index', $_GET) OR !ctype_digit($_GET['index'])) {
      // Si le $_GET['index'] n'existe pas ou que ce n'est pas une valeur numérique, on renvoie ailleurs
      $http->redirectTo('/');
    }

    // On instancie un nouvel objet Topic Model
    $topicModel = new TopicModel();

    // On fait appel à la méthode lockTopic
    $topicModel->lockTopic($_GET['index']);

    // On redirige vers le topic en question
    $http->redirectTo('/topic?index=' . $_GET['index'] . '');
  }
}
