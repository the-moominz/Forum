<?php

// Classe pour gérer la page principale du panneau d'administration
class AdminController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */

       // Instanciation d'un nouvel objet UserSession
       $userSession = new UserSession();

       // On vérifie que l'utilisateur est bien connecté
       if (!$userSession->isAuthenticated()) {
         // Si non, on le redirige à la page de connexion
         $http->redirectTo('/user/login');
       }

       // On vérifie ensuite si l'utilisateur a les droits d'administration
       if (!$userSession->isAdmin()) {
         // Si non, on le redirige à la page d'accueil
         $http->redirectTo('/');
       }

       // On instancie un nouvel objet DashboardModel
       $dashboardModel = new DashboardModel();

       // On utilise la méthode getContext pour récupérer le contenu du contexte
       $context = $dashboardModel->getContext();

       // On utilise la méthode getNews() pour récupérer toutes les news et leur Id
       $news = $dashboardModel->getNews();

       // On retourne le résultat dans un tableau pour le transmettre à la vue
       return ['context' => $context, 'news' => $news];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       // Gestion de la modification du synopsis
       // 1 - Instanciation d'un nouvel objet
       $dashboardModel = new DashboardModel();

       // 2 - On utilise la méthode updateContext, pour mettre à jour le contexte
       $dashboardModel->updateContext($formFields['synopsis'], $formFields['contextId']);

       // 3 - Une fois la mise à jour effectuée, on renvoie sur la page d'admin
       $http->redirectTo('/admin');

       // Gestion de l'insertion de news dans la table dashboard
       // 1 - On utilise la méthode addNews pour ajouter une entrée dans la table
       $dashboardModel->addNews($formFields['news']);

       // 2 - Une fois l'insertion effectuée, on renvoie sur la page d'admin
       $http->redirectTo('/admin');
    }

}
