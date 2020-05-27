<?php

// Classe dédiée à la gestion de réception de l'image uploadée par l'utilisateur
class AvatarController {
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

       // On retourne le résultat sous forme de tableau à la vue
       return ['user' => $user];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       // Gestion de l'envoi d'un fichier au serveur
       if (isset($_FILES['image-file']) && $_FILES['image-file']['error'] == 0) {
         // Si le fichier a bien été envoyé et qu'il n'y a aucun message d'erreur

         if ($_FILES['image-file']['size'] <= 2000000) {
           // Et si le fichier ne dépasse pas 2Mo

           // Récupération de l'extension du fichier envoyé
           $extension = strtolower(substr(strrchr($_FILES['image-file']['name'], '.'), 1));;

           // Nom du fichier
           $nameFile = $formFields['userId'] . "." . $extension;

           // Tableau pour stocker les extensions autorisées
           $authorizedExtensions = array('jpeg', 'jpg', 'png', 'gif');

           if (in_array($extension, $authorizedExtensions)) {
             // On teste si le fichier possède une extension autorisée (fichier image ici)

             $upload = move_uploaded_file($_FILES['image-file']['tmp_name'], WWW_PATH . '/upload/' . $nameFile);

             if ($upload) {
               // Si l'image est bien envoyée
               // On instancie un nouvel objet UserModel
               $userModel = new UserModel();

               // On utilise la méthode UpdateAvatar pour mettre à jour le champ photo dans la bdd
               $userModel->updateAvatar($nameFile, $formFields['userId']);

               // On redirige vers la page d'upload d'avatar
               $http->redirectTo('/user/profile/avatar?index=' . $formFields['userId'] . '');
             }
           }
         }
       } else {
         $http->redirectTo('/user/profile/avatar?index=' . $formFields['userId'] . '');
       }

    }
}
