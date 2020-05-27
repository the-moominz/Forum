<?php
// CLasse qui permet de gérer les requêtes SQL vers la table dashboard

class DashboardModel {

  /**
   * Fonction destinée à récupérer le contenu du contexte
   */
   public function getContext() {

     // Connexion à la base de données
     $database = new Database();

     // préparation de la requête de sélection
     $query = 'SELECT Id, Content FROM dashboard WHERE News = 0';

     // On stocke le résultat de la requête dans une variable
     $context = $database->queryOne($query);

     return $context;
   }
   /**
    * Fonction dédiée à la mise à jour du synopsis
    * @param string : Contenu du synospsis
    * @param int : Id du contexte
    */
    public function updateContext($context, $id) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de mise à jour
      $query = "UPDATE dashboard SET Content = ? WHERE Id = ?";

      // On exécute la requête
      $database->executeSql($query, [$context, $id]);
    }
    /**
     * Fonction dédiée à l'insertion d'une news dans la table
     * @param string : contenu de la nouvelle
     */
     public function addNews($news) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête d'insertion
       $query = "INSERT INTO dashboard (Content, News) VALUES(?, 1)";

       // Exécution de la requête
       $database->executeSql($query, [$news]);
     }
     /**
      * Fonction dédiée à récupérer toutes les nouvelles
      */
      public function getNews() {

        // Connexion à la base de données
        $database = new Database();

        // Préparation de la requête de sélection
        $query = "SELECT Id, Content FROM dashboard WHERE News = 1 ORDER BY Id DESC";

        // On stocke le résultat de la requête dans une variable
        $news = $database->query($query);

        return $news;
      }
     /**
      * Fonction dédiée à récupérer les 3 dernières nouvelles
      */
      public function getLastNews() {

        // Connexion à la base de données
        $database = new Database();

        // Préparation de la requête de sélection
        $query = "SELECT Id, Content FROM dashboard WHERE News = 1 ORDER BY Id DESC LIMIT 3";

        // On stocke le résultat de la requête dans une variable
        $news = $database->query($query);

        return $news;
      }

      /**
       * Fonction dédiée à supprimer une news
       * @param int : Id de la news à supprimer
       */
       public function deleteNews($id) {

         // Connexion à la base de données
         $database = new Database();

         // Préparation de la requête de suppression
         $query = "DELETE FROM dashboard WHERE Id = ?";

         // Exécution de la requête
         $database->executeSql($query, [$id]);
       }

}
