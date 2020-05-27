<?php
// Classe qui va traiter l'affichage des MP et l'envoi de MP
class NewController {
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

      // On vérifie que l'ID utilisateur est le même que celui du membre connecté
      if ($userSession->getUserId() != $_GET['index']) {
        $http->redirectTo('/');
      }

      // On instancie un nouvel objet UserModel
      $userModel = new UserModel();

      // On utilise la méthode listUsers pour récupérer la liste des utilisateurs et leurs ID
      $users = $userModel->listUsers();

      // On retourne le résultat sous forme de tableau pour transmettre à la vue
      return ['users' => $users];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       if (isset($formFields['title']) && isset($formFields['addressee']) && isset($formFields['content'])) {
         try {
       // On instancie un nouvel objet MP Model
       $mpModel = new MPModel();

       // Utilisation de la méthode create pour enregistrer les valeurs du formulaire en base
       $mpModel->create($formFields['senderId'], $formFields['addressee'], $formFields['title'], $formFields['content']);

       // Une fois envoyé, on redirige vers la liste des MPS
       $http->redirectTo('/mp/send?index=' . $formFields['senderId'] . '');

         } catch(DomainException $exception) {
           // On pré-remplit le formulaire et on renvoie le message d'erreur
         }
     }
    }
}
