<?php

// Contrôleur pour gérer l'inscription d'un membre
class UserController {

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

           // On instancie un nouvel objet UserModel
           $userModel = new UserModel();

           // On fait appel à la méthode signUp() de l'objet UserController pour enregistrer la requête en BDD
           $userModel->signUp($formFields['pseudo'], $formFields['birthDate'], $formFields['mail'], $formFields['password']);

           // Une fois la méthode utilisée et l'utilisateur inscrit, on renvoit à la page d'accueil
           $http->redirectTo('/');

         } catch(DomainException $exception) {

           // On réaffiche le formulaire pré-rempli avec le message d'erreur associé
           $form = new UserForm();
           $form->bind($formFields);
           $form->setErrorMessage($exception->getMessage());

           return [ '_form' => $form];

         }

    }
}
