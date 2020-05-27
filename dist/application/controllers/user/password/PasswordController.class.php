<?php
// Classe qui gère la recherche de l'utilisateur par son email
class PasswordController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
      $userSession = new UserSession();
      // On vérifie que le membre n'est pas déjà connecté, sinon il ne peut pas venir
      if ($userSession->isAuthenticated()) {
        $http->redirectTo('/');
      }

      return [ '_form' => new UserForm()];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {

      if(!empty($formFields['mail'])) {

        try {

          // On instancie un nouvel objet UserModel
          $userModel = new UserModel();

          // On utilise la méthode findUserWithEmail pour récupérer les information du membre
          $userMail = $userModel->findUserWithEmail($formFields['mail']);

          // On redirige vers le formulaire de nouveau mot de passe en passant en paramètre l'id du membre
          $http->redirectTo('/user/new?index=' . $userMail['Id'] . '');

        } catch(DomainException $exception) {

          // On affiche le message d'erreur
          $form = new UserForm();
          $form->bind($formFields);
          $form->setErrorMessage($exception->getMessage());

          return [ '_form' => $form];
        }
      }
    }

}
