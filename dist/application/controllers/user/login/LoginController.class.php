<?php

// Contrôleur pour gérer la connexion du membre
class LoginController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */

       return [ '_form' => new UserForm()];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       try {

         // On crée une nouvelle instance de l'objet UserModel
         $userModel = new UserModel();

         // On récupère les données envoyées par l'utilisateur pour vérifier qu'il est bien inscrit
         $user = $userModel->checkUserAccount($formFields['pseudo'], $formFields['password']);

         // Vérification de l'existence du tableau $user
         //var_dump($user);

         // On instancie ensuite un nouvel objet UserSession
         $userSession = new UserSession();

         // On utilise ensuite la méthode create de l'objet UserModel pour créer la session utilisateur
         $userSession->create($user['Id'], $user['Pseudo'], $user['Email'], $user['Admin']);

         // Une fois connecté, on enregistre la date et le jour de sa connexion (LastLoginTimeStamp)
         $userModel->updateLoginTimeStamp($user['Id']);

         // Une fois connecté, on redirige l'utilisateur vers la page d'accueil
         $http->redirectTo('/');

       } catch(DomainException $exception) {

         // On réaffiche le formulaire rempli en cas d'erreur
         $form = new UserForm();
         $form->bind($formFields);
         $form->setErrorMessage($exception->getMessage());

         return [ '_form' => $form];

       }
    }

}
