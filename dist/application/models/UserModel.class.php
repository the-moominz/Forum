<?php

class UserModel {

  // Fonctions privées
  /**
   * @param string : Mot de passe rentré par l'utilisateur
   */
  private function hashPassword($password) {
    /* Fonction pour hacher et saler le mot de passe avant de l'intégrer en base de données */
    $salt = '$2y$11$'.substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);

    // Utilisation de la fonction crypt()
    return crypt($password, $salt);
  }

  private function verifyPassword($password, $hashedPassword) {
    /* Fonction pour vérifier le mot de passe à la connexion et vérifier qu'il est le bon */
    // Si le message en clair est le même que la version hachée, ça renvoit true
        return crypt($password, $hashedPassword) == $hashedPassword;
  }

  // Fonctions publiques
  /**
   * Fonction pour gérer l'inscription et l'enregistrement des données de l'utilisateur
   * @param string : Pseudo de l'utilisateur
   * @param int : Date de naissance du personnage de l'utilisateur (format Y-m-d)
   * @param string : Adresse mail de l'utilisateur
   * @param string : Mot de passe de l'utilisateur
   */
  public function signUp($pseudo, $birthDate, $mail, $password) {

    // Connexion à la BDD
    $database = new Database();

    // On effectue une requête pour récupérer l'email envoyé
    $sql = 'SELECT Id FROM user WHERE Email = ?';

    // On stocke l'email, s'il existe, dans une variable $check
    $check = $database->queryOne($sql, [$mail]);

    // Et s'il n'existe pas, on peut enregistrer le nouvel utilisateur dans la BDD
    if (empty($check)) {

      // On hash le mot de passe
      $passwordHash = $this->hashPassword($password);

      // On prépare une requête pour insérer les données dans la BDD
      $query = "INSERT INTO user (Name, Email, Password, BirthDate, CreationTimeStamp, Online) VALUES (?, ?, ?, ?, NOW(), 0)";

      // On exécute la requête
      $database->executeSql($query, [$pseudo, $mail, $passwordHash, $birthDate]);


    } else {

      // Si l'e-mail existe déjà
      throw new DomainException('Il existe déjà un compte utilisateur avec cet e-mail.');
    }
  }

  /**
   * Fonction dédiée à la vérification du compte de l'utilisateur lors de sa connexion
   * @param string : Pseudo de l'utilisateur
   * @param string : Mot de passe de l'utilisateur
   */
  public function checkUserAccount($pseudo, $password) {

    // Connexion à la base de données
    $database = new Database();

    // On récupère dans la BDD l'utilisateur grâce au pseudo transmis dans le formulaire
    $query = 'SELECT Id, Name, Email, Password, Admin FROM user WHERE Name = ?';

    // On exécute ensuite la requête, qu'on stocke dans une variable
    $userName = $database->queryOne($query, [$pseudo]);

    // Est-ce que l'utilisateur est déjà en base de données ?
    if (empty($userName)) {
      // S'il n'y est pas
      throw new DomainException("Il n'existe aucun compte utilisateur avec ce pseudo.");
    }

    // Si on arrive à cette étape du programme, on vérifie ensuite le mot de passe relié au pseudo
    if ($this->verifyPassword($password, $userName['Password']) == false) {
      // Si le mot de passe n'est pas le bon
      throw new DomainException('Le mot de passe est incorrect.');
    }

    // Si le script parvient à passer toutes ces étapes, c'est que l'utilisateur est bien identifié dans la base de donnéee
    $user = array('Id' => $userName['Id'], 'Pseudo' => $userName['Name'], 'Email' => $userName['Email'], 'Admin' => $userName['Admin']);

    // On retourne le contenu du tableau $user
    return $user;
  }

  /**
   * Fonction dédiée à la mise à jour de la table user pour vérifier la dernière connexion de l'utilisateur
   * @param int : ID de l'utilisateur
   */
  public function updateLoginTimeStamp($Id) {

    // Connexion à la base de données
    $database = new Database();

    // On prépare la requête pour la mise à jour
    $query = "UPDATE user SET LastLoginTimeStamp = NOW(), Online = 1 WHERE Id = ?";

    // On exécute la requête
    $database->executeSql($query, [$Id]);
  }
  /**
   * Fonction destinée à remettre la colonne Online de la table user à 0 (utilisateur déconnecté)
   * @param int : Id de l'utilisateur à modifier
   */
   public function updateOnlineStatus($userId) {

     // Connexion à la base de donneés
     $database = new Database();

     // Préparation de la requête de mise à jour
     $query = "UPDATE user SET Online = 0 WHERE Id = ?";

     // On exécute la requête
     $database->executeSql($query, [$userId]);
   }
  /**
   * Fonction dédiée à récupérer le contenu de la table user pour en afficher le contenu en back office
   */
  public function listUsers() {

    // Connexion à la base de données
    $database = new Database();

    // On prépare la requête pour sélectionner les champs de la table user
    $query = "SELECT faction.Name AS Faction, faction.Color AS Color, user.Id, user.Name AS Name, Email, DATE_FORMAT(CreationTimeStamp, 'Le %d/%m/%Y à %H:%i:%s') AS InscriptionDate, DATE_FORMAT(LastLoginTimeStamp, 'Le %d/%m/%Y à %H:%i:%s') AS LoginDate, Admin, faction_Id
    FROM user
    LEFT JOIN faction ON faction.Id = user.faction_Id
    ORDER BY Name";

    // On exécute la requête pour stocker dans une variable le résultat
    $users = $database->query($query);

    return $users;
  }

  /**
   * Fonction dédiée à récupérer toutes les données nécessaires à l'affichage du profil en fonction de l'id du membre
   */
   public function userProfile($id) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête de sélection
     $query = "SELECT faction.Name AS Faction, faction.Color AS Color, user.Id AS Id, user.Name, user.Photo, Gender, Origins, Relationships, DATE_FORMAT(BirthDate, '%d/%m/%Y') AS Birthday, Biography, DATE_FORMAT(CreationTimeStamp, 'Le %d/%m/%Y à %H:%i:%s') AS InscriptionDate, Admin, faction_Id
     FROM user
     LEFT JOIN faction ON user.faction_Id = faction.Id
     WHERE user.Id = ?";

     // On stocke dans une variable le résultat de la requête
     $user = $database->queryOne($query, [$id]);

     return $user;
   }

   /**
    * Fonction dédiée à mettre à jour le profil de l'utilisateur en base de données
    */
  public function updateProfile($origins, $gender, $biography, $relationships, $faction, $id) {

    // Connexion à la base de données
    $database = new Database();

    // Requête de mise à jour
    $query = "UPDATE user SET Origins = ?, Gender = ?, Biography = ?, Relationships = ?, faction_Id = ? WHERE Id = ?";

    // Exécution de la requête
    $database->executeSql($query, [$origins, $gender, $biography, $relationships, $faction,  $id]);
  }

  /**
   * Fonction dédiée à l'édition du profil en ajoutant une nouvelle image
   * @param string : Nom de la photo à ajouter en base
   * @param int : Id de l'utilisateur pour modifier son entrée en base
   */
   public function updateAvatar($photo, $id) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête de mise à jour de la table
     $query = "UPDATE user SET Photo = ? WHERE Id = ?";

     // Exécution de la requête
     $database->executeSql($query, [$photo, $id]);
   }
   /**
    * Fonction dédiée à modifier le statut de l'utilisateur (son rôle : membre, modo, admin)
    * @param int : Valeur du formulaire (0 = membre, 1 = modo, 2 = admin)
    * @param int : Id de l'utilisateur
    */
    public function updateStatus($status, $id) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de mise à jour
      $query = "UPDATE user SET Admin = ? WHERE Id = ?";

      // Exécution de la requête
      $database->executeSql($query, [$status, $id]);
    }
   /**
    * Fonction destinée à compter le nombre de membres inscrits, afin de l'afficher en stats
    */
    public function countUsers() {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête
      $query = "SELECT COUNT(Id) AS NbUsers FROM user";

      // On stocke le résultat de la requête dans une variable
      $users = $database->query($query);

      return $users;
    }

    /**
     * Fonction dédiée à calculer le nombre de membres par faction
     * @param int : ID de la faction
     */
     public function countUsersFaction($factionId) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête
       $query = "SELECT COUNT(Id) AS NbUsers FROM user WHERE faction_Id = ?";

       // On stocke la requête dans une variable
       $userFaction = $database->queryOne($query, [$factionId]);

       return $userFaction['NbUsers'];
     }
     /**
      * Fonction qui récupère dans la base le nom du dernier utilisateur inscrit
      */
      public function lastUser() {

        // Connexion à la base de données
        $database = new Database();

        // Préparation de la requête de sélection
        $query = "SELECT Color, user.Id, user.Name FROM `user`
        LEFT JOIN faction ON faction.Id = user.faction_Id
        WHERE user.Id = ( SELECT MAX(user.Id) FROM user )";

        // On stocke le résultat de la requête dans une variable
        $lastUser = $database->queryOne($query);

        return $lastUser;
      }
    /**
     * Fonction qui va afficher le résultat de la recherche d'utilisateur
     * @param string : série de caractères que va rentrer l'utilisateur pour faire une recherche
     */
    public function searchUser($search) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de sélection
      $query = "SELECT faction.Name AS Faction, faction.Color AS Color, user.Id, user.Name AS Name, Email, DATE_FORMAT(CreationTimeStamp, 'Le %d/%m/%Y à %H:%i:%s') AS InscriptionDate, DATE_FORMAT(LastLoginTimeStamp, 'Le %d/%m/%Y à %H:%i:%s') AS LoginDate, Admin, faction_Id
      FROM user
      LEFT JOIN faction ON faction.Id = user.faction_Id
      WHERE user.Name LIKE ?
      ORDER BY user.Name";

      // On stocke le résultat de la requête dans une variable
      $users = $database->query($query, ['%' . $search . '%']);

      return $users;
    }
    /**
     * Fonction qui récupère le nom et l'id des membres en ligne
     */
     public function getOnlineUsers() {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête de sélection
       $query = "SELECT Color, user.Id, user.Name FROM user
       INNER JOIN faction ON faction.Id = user.faction_Id
       WHERE Online = 1";

       // On stocke le résultat de la requête dans une variable
       $onlineUsers = $database->query($query);

       return $onlineUsers;
     }

     /**
      *  Fonction qui va permettre de retrouver les informations d'un utilisateur avec son email
      * @param string : email du membre
      */
      public function findUserWithEmail($mail) {

        // Connexion à la base de données
        $database = new Database();

        // Préparation de la requête de sélection
        $query = "SELECT Id FROM user WHERE Email = ?";

        // On stocke le résultat de la requête dans une variable
        $userMail = $database->queryOne($query, [$mail]);

        if (empty($userMail)) {

          // Si on ne retrouve pas le mail de l'utilisateur dans la base de données
          throw new DomainException("Il n'existe aucun utilisateur avec cet e-mail.");
        }

        // Si on arrive ici, c'est que l'identification est correct
        $user = array('Id' => $userMail['Id']);

        return $user;
      }
      /**
       * Fonction qui permet de mettre à jour le mot de passe du membre
       * @param string : Nouveau mot de passe
       * @param int : Id de l'utilisateur
       */
       public function updatePassword($password, $id) {

         // Connexion à la base de données
         $database = new Database();

         $sql = "SELECT Password FROM user WHERE Id = ?";

         $userPassword = $database->queryOne($sql, [$id]);

         if ($userPassword != $password) {

           // Préparation de la requête de mise à jour
           $query = "UPDATE user SET Password = ? WHERE Id = ?";

           $hashedPassword = $this->hashPassword($password);

           // Exécution de la requête
           $database->executeSql($query, [$hashedPassword, $id]);

         } else {

           throw new DomainException('Vous possédiez déjà ce mot de passe');

         }
       }
    /**
     * Fonction dédiée à récupérer le nom du destinataire (MP via la page des membres)
     * @param int : Id du destinataire
     */
     public function getUserName($id) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête de sélection
       $query = "SELECT Name FROM user WHERE Id = ?";

       // On stocke le résultat de la requête dans une variable
       $userName = $database->queryOne($query, [$id]);

       return $userName;
     }
}
