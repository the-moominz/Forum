<?php

// CLasse dédiée à l'affichage des membres publics (accès aux MP et profils directs)
class PlayersController {
  public function httpGetMethod(Http $http, array $queryFields) {

    // On instancie un nouvel objet UserModel
    $userModel = new UserModel();

    // Si l'utilisateur utilise la barre de recherche, on affiche que les noms voulus
    if (isset($queryFields['search'])) {

      $users = $userModel->searchUser($queryFields['search']);

      if ($users == NULL) {
        // Mais si le résultat de la recherche ne donne rien, tout réafficher normalement 
        $users = $userModel->listUsers();
      }

    } else {

      // On utilise la méthode listUsers pour afficher les éléments de base
      $users = $userModel->listUsers();

    }
    // On retourne le résultat dans un tableau pour l'afficher dans la vue
    return ['users' => $users];
  }

}
