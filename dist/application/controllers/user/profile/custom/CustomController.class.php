<?php

// CLasse dédiée à la gestion du formulaire pour modifier des données en base de données
class CustomController {
  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */

       // On vérifie que le paramètre de l'URL en GET soit bon
       if (!array_key_exists('index', $_GET) OR !ctype_digit($_GET['index'])) {
         // Si le $_GET['index'] n'existe pas ou que ce n'est pas une valeur numérique, on renvoie ailleurs
         $http->redirectTo('/');
       }
       // On instancie un nouvel objet UserSession
       $userSession = new UserSession();

       // On vérifie d'abord que l'id du membre soit le même que celui du profil public pour qu'il soit la seule personne à pouvoir modifier son profil
       if ($userSession->getUserId() != $_GET['index']) {
         $http->redirectTo('/');
       }

       // On instancie un nouvel objet UserModel
       $userModel = new UserModel();

       // On utilise la méthode userProfile pour récupérer les informations à afficher dynamiquement
       $user = $userModel->userProfile($_GET['index']);

       // On instancie un nouvel objet MessagesModel
       $messagesModel = new MessagesModel();

       // On stocke le résultat de la fonction dans une variable
       $userMessages = $messagesModel->userMessages($_GET['index']);

       // On instancie un nouvel objet FactionModel
       $factionModel = new FactionModel();

       // On utilise la méthode listAll pour récupérer toutes les infos liées aux factions
       $factions = $factionModel->listAll();

       // On retourne le résultat dans un tableau à la vue
       return ['user' => $user, 'userMessages' => $userMessages, 'factions' => $factions];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       // On instancie un nouvel objet UserModel
       $userModel = new UserModel();

       // On utilise la fonction updateProfile pour mettre à jour le profil du membre
       $userModel->updateProfile($formFields['origins'], $formFields['gender'], $formFields['biography'], $formFields['relations'], $formFields['faction'], $formFields['index']);

       // On redirige vers la page personnalisation du profil
       $http->redirectTo('/user/profile?index=' . $formFields['index'] . '');
    }
}
