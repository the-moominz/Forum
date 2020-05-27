<?php

// Contrôleur pour la page d'accueil
class HomeController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */

		 // Préparation de l'affichage des sections sur la page d'accueil
		 $sectionModel = new SectionModel();

		 // Utilisation de la méthode list All de Section Model pour récupérer les données de la base et les afficher sur la vue
		 $sections = $sectionModel->listAll();

		 // Préparation de l'affichage des factions sur la page d'accueil
		 $factionModel = new FactionModel();

		 // Utilisation de la méthode list All de Faction Model pour récupérer les données de la base et les afficher sur la vue
		 $factions = $factionModel->listAll();

     // On instancie un nouveau objet Topic Model
     $topicModel = new TopicModel();

     // On utilise la méthode listLastPosts pour récupérer la liste des 3 derniers messages postés
     $lastPosts = $topicModel->listLastPosts();

     // On instancie un nouvel objet Messages Model
     $messagesModel = new MessagesModel();

     // On instancie un nouvel objet UserModel
     $userModel = new UserModel();

     // On utilise la méthode lastUser pour récupérer le nom et l'Id du dernier membre inscrit
     $lastUser = $userModel->lastUser();

     // On utilise la méthode getOnlineUsers pour récupérer le nom et l'id des membres connectés
     $onlineUsers = $userModel->getOnlineUsers();

     // On instancie un nouvel objet User Session
     $userSession = new UserSession();

     // On instancie un nouvel objet DashboardModel
     $dashboardModel = new DashboardModel();

     // On utilise la méthode getContext pour récupérer le contenu du Synopsis
     $context = $dashboardModel->getContext();

     // On utilise la méthode getLastNews pour récupérer les 3 dernières nouvelles
     $news = $dashboardModel->getLastNews();

     // Instanciation de l'objet ScoreModel
     $scoreModel = new ScoreModel();

     // On utilise la méthode getMonthUser pour récupérer les informations sur le membre du mois
     $monthUser = $scoreModel->getMonthUser();



		 //On stocke la variable $sections dans un tableau pour le retourner à la vue
		 return ['sections' => $sections,
      'factions' => $factions,
      'lastPosts' => $lastPosts,
      'lastUser' => $lastUser,
      'context' => $context,
      'news' => $news,
      'onlineUsers' => $onlineUsers,
      'monthUser' => $monthUser, 
      'topicModel' => $topicModel,
      'userModel' => $userModel,
      'messagesModel' => $messagesModel];

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
