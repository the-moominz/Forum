<?php
// Classe qui gère la création d'un nouveau mot de passe et la MAJ de la BDD
class NewController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
      $userSession = new UserSession();
      // On vérifie que le membre n'est pas déjà connecté, sinon il ne peut pas venir
      if ($userSession->isAuthenticated()) {
        $http->redirectTo('/');
      }
      // On vérifie les paramètres renvoyés par l'URL en GET
      if (!array_key_exists('index', $_GET) OR !ctype_digit($_GET['index'])) {
        // Si le $_GET['index'] n'existe pas ou que ce n'est pas une valeur numérique, on renvoie ailleurs
        $http->redirectTo('/');
      }

      return [ '_form' => new UserForm()];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {

      try {

      // On instancie un nouvel objet UserModel
      $userModel = new UserModel();

      // On utilise la méthode updatePassword pour mettre à jour le mot de passe dans la bdd
      $userModel->updatePassword($formFields['password'], $formFields['userId']);

      // On redirige vers la page qui indique que c'est un succès
      $http->redirectTo('/user/login');

        } catch(DomainException $exception) {

      // On réaffiche le formulaire pré-rempli avec le message d'erreur associé
      $form = new UserForm();
      $form->bind($formFields);
      $form->setErrorMessage($exception->getMessage());

      return [ '_form' => $form];
      }
    }
}
