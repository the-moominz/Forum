<?php
// Classe dédiée à l'ajout d'une nouvelle section
class AddController {

  public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
       // Instanciation d'un nouvel objet UserSession
       $userSession = new UserSession();

       // On vérifie que l'utilisateur est bien connecté
       if (!$userSession->isAuthenticated()) {
         // Si non, on le redirige à la page de connexion
         $http->redirectTo('/user/login');
       }

       // On vérifie ensuite si l'utilisateur a les droits d'administration
       if (!$userSession->isAdmin()) {
         // Si non, on le redirige à la page d'accueil
         $http->redirectTo('/');
       }
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

       // On teste si on a bien envoyé des valeurs dans le formulaire
       if (isset($formFields['name']) && isset($formFields['description']) && isset($formFields['category'])) {

         try {
       // On instancie un nouvel objet SectionModel
       $sectionModel = new SectionModel();

       // On utilise la méthode create pour ajouter les valeurs du formulaire à la base de données
       $sectionModel->create($formFields['name'], $formFields['description'], $formFields['category']);

       // Une fois la requête effectuée, on renvoie à la liste des sections
       $http->redirectTo('/admin/section');

        } catch(DomainException $exception) {
          // On pré-remplit le formulaire et on affiche un message d'erreur
        }
     }
    }
}
