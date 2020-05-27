<?php

// Classe dédiée aux requêtes concernant la table "section"
class SectionModel {

  /**
   * Fonction dédiée à la récupération de toutes les données de la table section
   */
  public function listAll() {

    // Connexion à la base de données
    $database = new Database();

    // Préparation de la requête de sélection
    $query = "SELECT Id, Name, Description, Category FROM section ORDER BY Category";

    // Exécution de la requête et récupération dans une variable
    $sections = $database->query($query);

    return $sections;
  }

  /**
   * Fonction dédiée à la création d'une nouvelle section
   * @param string : Nom de la nouvelle section
   * @param string : Description de la nouvelle section
   * @param string : Nom de la catégorie associée
   */
   public function create($name, $description, $category) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête d'insertion
     $query = "INSERT INTO section (Name, Description, Category) VALUES(?, ?, ?)";

     // Exécution de la requête
     $database->executeSql($query, [$name, $description, $category]);
   }

   /**
    * Fonction dédiée à la récupération de l'entrée dans la BDD liée à l'id envoyé en GET
    * @param int : Id de la section sélectionnée
    */
    public function getSection($id) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de sélection
      $query = "SELECT Id, Name, Description, Category FROM section WHERE Id = ?";

      // Exécution de la requête et sauvegarde dans une variable
      $section = $database->queryOne($query, [$id]);

      return $section;
    }

    /**
     * Fonction dédiée à l'édition de l'entrée et à la modification de la base de données
     * @param string : Nom de la section
     * @param string: Description de la section
     * @param string : Catégorie
     * @param int : Id de la section
     */
    public function updateSection($name, $description, $category, $id) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête
      $query = "UPDATE section SET Name = ?, Description = ?, Category = ? WHERE Id = ?";

      // Exécution de la requête
      $database->executeSql($query, [$name, $description, $category, $id]);
    }

    /**
     * Fonction dédiée à la suppression de la section sélectionnée
     * @param int : ID de la section
     */
     public function deleteSection($id) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête
       $query = "DELETE FROM section WHERE Id = ?";

       // Exécution de la requête
       $database->executeSql($query, [$id]);
     }

     /**
      * Fonction qui va mettre à jour la date et l'heure du dernier topic mis à jour
      * @param int : Id de la section à mettre à jour
      */
      public function updateLastReadTopic($sectionId) {

        // Connexion à la base de données
        $database = new Database();

        // Préparation de la requête de mise à jour
        $query = "UPDATE section SET LastTopicTimeStamp = NOW() WHERE Id = ?";

        // Exécution de la requête
        $database->executeSql($query, [$sectionId]);
      }

      /**
       * Fonction qui va mettre à jour la date et l'heure du dernier topic, en cas de suppression d'un topic
       * @param time : DateTime du dernier message du topic supprimé
       * @param int : Id de la section affectée
       */
       public function updateOneReadTopic($lastPost, $sectionId) {

         // Connexion à la base de données
         $database = new Database();

         // Préparation de la requête de mise à jour
         $query = "UPDATE section SET LastTopicTimeStamp = ? WHERE Id = ?";

         // On exécute la requête
         $database->executeSql($query, [$lastPost, $sectionId]);
       }
}
