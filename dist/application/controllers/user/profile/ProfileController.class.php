<?php

// Classe pour gérer l'affichage du profil membre
class ProfileController {
  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
       $userSession = new UserSession();
       // On vérifie les paramètres renvoyés par l'URL en GET
       if (!array_key_exists('index', $_GET) OR !ctype_digit($_GET['index'])) {
         // Si le $_GET['index'] n'existe pas ou que ce n'est pas une valeur numérique, on renvoie ailleurs
         $http->redirectTo('/');
       }

       // On vérifie que l'utilisateur est bien connecté
       if (!$userSession->isAuthenticated()) {
         // Si non, on le redirige à la page de connexion
         $http->redirectTo('/user/login');
       }

  		 // On instancie un nouvel objet User Model
       $userModel = new UserModel();

       // On utilise la méthode userProfile() pour récupérer les données du membre qui clique sur son profil pour l'afficher
       $user = $userModel->userProfile($_GET['index']);

       // On instancie un nouvel objet MessagesModel
       $messagesModel = new MessagesModel();

       // On stocke le résultat de la fonction dans une variable
       $userMessages = $messagesModel->userMessages($_GET['index']);

       // On retourne le résultat sous forme de tableau à la vue
       return ['user' => $user, 'userMessages' => $userMessages, 'userSession' => $userSession];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       // Instanciation d'un nouvel objet UserModel
       $userModel = new UserModel();

       // Utilisation de la méthode updateStatus pour mettre à jour le rôle de l'utilisateur
       $userModel->updateStatus($formFields['status'], $formFields['userId']);

       // Une fois la modification effectuée, on renvoit sur le profil du membre modifié
       $http->redirectTo('/user/profile?index=' . $formFields['userId'] . '');
    }
}
