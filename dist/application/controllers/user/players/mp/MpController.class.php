<?php
// Classe qui va traiter l'affichage des MP et l'envoi de MP
class MpController {
  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
       // Instanciation d'un objet User Session
       $userSession = new UserSession();

       // On vérifie les paramètres renvoyés par l'URL en GET
       if (!array_key_exists('index', $_GET) OR !ctype_digit($_GET['index'])) {
         // Si le $_GET['index'] n'existe pas ou que ce n'est pas une valeur numérique, on renvoie ailleurs
         $http->redirectTo('/');
       }

		   // On vérifie que l'utilisateur est bien connecté, sinon on le renvoie à la page de connexion
       if (!$userSession->isAuthenticated()) {
         $http->redirectTo('/user/login');
       }

      // On instancie un nouvel objet UserModel
      $userModel = new UserModel();

      // On utilise la méthode listUsers pour récupérer la liste des utilisateurs et leurs ID
      $userName = $userModel->getUserName($_GET['index']);

      // On retourne le résultat sous forme de tableau pour transmettre à la vue
      return ['userName' => $userName];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       if (isset($formFields['title']) && isset($formFields['content'])) {
       // On instancie un nouvel objet MP Model
       $mpModel = new MPModel();

       // Utilisation de la méthode create pour enregistrer les valeurs du formulaire en base
       $mpModel->create($formFields['senderId'], $formFields['addressee'], $formFields['title'], $formFields['content']);

       // Une fois envoyé, on redirige vers la liste des MPS
       $http->redirectTo('/mp/send?index=' . $formFields['senderId'] . '');


     }
    }
}
