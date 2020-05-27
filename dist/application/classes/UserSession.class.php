<?php

// Création d'un objet UserSession, pour gérer la connexion des utilisateurs
class UserSession {

  public function __construct() {

        if(session_status() == PHP_SESSION_NONE) {

            // Démarrage du module PHP de gestion des sessions.
            session_start();

        }

    }

    /* Création de la session utilisateur */
      public function create($userId, $pseudo, $mail, $admin) {

          // Construction de la session utilisateur
          $_SESSION['user'] = [
              'UserId' => $userId,
              'Pseudo' => $pseudo,
              'Email' => $mail,
              'Admin' => $admin
              ];
      }

      /* Teste si l'utilisateur est bien connecté */
      public function isAuthenticated() {

          if (array_key_exists('user', $_SESSION) == true) {

              if (empty($_SESSION['user']) == false) {

                  return true;
              }

              return false;
          }

          return false;
      }

      /* Test si l'utilisateur est admin */
      public function isAdmin() {

          if($this->isAuthenticated() == false) {

              return false;

              }


          if ($_SESSION['user']['Admin'] == 2) {

              return true;

          } else {

              return false;

          }

      }

      /**
       * Fonction qui teste si l'utilisateur est modérateur
       */
       public function isModo() {
         // Si le membre n'est pas connecté, retourne false
         if ($this->isAuthenticated() == false) {
           return false;
         }

         // S'il est connecté et que la valeur associée à Admin est égale à 1, retourne true
         if ($_SESSION['user']['Admin'] == 1) {
           return true;
         } else {
           return false;
         }
       }

      /* On récupère les informations de l'utilisateur pour afficher son nom sur le site */
      public function getFullName() {

          if ($this->isAuthenticated() == false) {

              return null;

          } else {

              return $_SESSION['user']['Pseudo'];

          }

      }

      /* On récupère via une méthode l'id de l'utilisateur */
      public function getUserId() {

          if ($this->isAuthenticated() == false) {

              return null;

          }

          return $_SESSION['user']['UserId'];

      }

      /* Création de la fonction pour déconnecter l'utilisateur */
      public function destroy() {

          $_SESSION = array();
          session_destroy();

      }

}
