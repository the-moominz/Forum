<?php
// Classe qui va traiter l'affichage des MP et l'envoi de MP
class AnswerController {
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

      // On instancie un nouvel objet MPModel
      $mps = new MPModel();

      // On utilise la méthode getMPtitle pour récupérer le titre du MP
      $mpTitle = $mps->getMPTitle($_GET['mp']);

      // On renvoie un tableau avec les informations à transmettre à la vue
      return ['mpTitle' => $mpTitle];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       if (isset($formFields['content'])) {
         try {
       // Instanciation d'un nouvel objet MPModel
       $mpModel = new MPModel();

       // Utilisation de la méthode createAnswer pour insérer une réponse
       $mpModel->createAnswer($formFields['senderId'], $formFields['addressee'], $formFields['title'], $formFields['content'], $formFields['conversationId']);

       // Une fois le message posté, on renvoie sur la liste des MPS reçus
       $http->redirectTo('/mp?index=' . $formFields['senderId'] . '');
       
         } catch(DomainException $exception) {
           // On pré-remplit le formulaire et on renvoie le message d'erreur
         }
     }
    }
}
