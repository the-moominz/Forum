<?php
// Classe qui gère les requêtes SQL vers la table score
class ScoreModel {

  /**
   * Fonction qui permet d'ajouter des points à un utilisateur donné
   * @param int : Valeur des points assignés
   * @param int : Id de l'utilisateur à qui vont aller les points
   */
   public function addPoints($points, $userId) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête d'insertion
     $query = "INSERT INTO score (Points, CreationTimeStamp, user_Id) VALUES (?, NOW(), ?)";

     // Exécution de la requête
     $database->query($query, [$points, $userId]);
   }
   /**
    * Fonction dédiée à la sélection du membre du mois en fonction de ses points
    */
    public function getMonthUser() {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de sélection
      $query = "SELECT sub.Color, sub.UserId, sub.Name, sub.Avatar, MAX(sub.Points) AS MaxPoints
      FROM ( SELECT SUM(s.Points) AS Points, f.Color AS Color, u.Id AS UserId, u.Name AS Name, u.Photo AS Avatar FROM score s
      INNER JOIN user u ON u.Id = s.user_Id
      LEFT JOIN faction f on u.faction_Id = f.Id
      WHERE s.CreationTimeStamp BETWEEN CONCAT('%Y-%m',DATE_FORMAT(NOW(),'-01')) AND LAST_DAY(NOW())
      GROUP BY UserId) AS sub";

      // On stocke le résultat de la requête dans une variable
      $monthUser = $database->queryOne($query);

      return $monthUser;
    }
}
