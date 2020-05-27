<?php
class AddController {
  public function httpPostMethod(Http $http, array $formFields)
  {
    /*
     * Méthode appelée en cas de requête HTTP POST
     *
     * L'argument $http est un objet permettant de faire des redirections etc.
     * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
     */


     // 1 - Instanciation d'un nouvel objet
     $dashboardModel = new DashboardModel();

     // Gestion de l'insertion de news dans la table dashboard
     // 1 - On utilise la méthode addNews pour ajouter une entrée dans la table
     $dashboardModel->addNews($formFields['news']);

     // 2 - Une fois l'insertion effectuée, on renvoie sur la page d'admin
     $http->redirectTo('/admin');
  }
}
