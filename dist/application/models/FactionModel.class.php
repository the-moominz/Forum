<?php
// Classe dédiée aux requêtes SQL de la table faction
class FactionModel {

  /**
   * Fonction qui récupère tous les éléments de la table faction
   */
  public function listAll() {

    // Connexion à la base de données
    $database = new Database();

    // Préparation de la requête de sélection
    $query = 'SELECT Id, Name, Description, Photo, Color FROM faction';

    // Exécution de la requête, stockée dans une variable
    $factions = $database->query($query);

    return $factions;
  }

  /**
   * Fonction qui permet d'insérer de nouvelles données dans la base
   * @param string : Nom de la faction
   * @param string : Description de la faction
   */
  public function create($name, $description) {

    // Connexion à la base de données
    $database = new Database();

    // On prépare la requête d'insertion
    $query = 'INSERT INTO faction (Name, Description) VALUES(?, ?)';

    // On exécute ensuite la requête
    $database->executeSql($query, [$name, $description]);
  }

  /**
   * Fonction permettant de récupérer une entrée dans la BDD correspond à l'id de la faction à modifier
   * @param int : Id de la faction à éditer
   */
  public function getFaction($id) {

    // Connexion à la base de données
    $database = new Database();

    // On prépare la requête de sélection
    $query = "SELECT Name, Description FROM faction WHERE Id = ?";

    // On conserve le résultat de la requête unique dans une variable
    $faction = $database->queryOne($query, [$id]);

    return $faction;
  }

  /**
   * Fonction destinée à l'enregistrement des nouvelles valeurs
   * @param string : Nouveau nom de faction
   * @param string : Nouvelle description de faction
   */
   public function updateFaction($name, $description, $id) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête
     $query = "UPDATE faction SET Name = ?, Description = ? WHERE Id = ?";

     // On exécute la requête
     $database->executeSql($query, [$name, $description, $id]);
   }

   /**
    * Fonction destinée à supprimer une faction et donc son entrée dans la base de données
    * @param int : Id de la faction à supprimer
    */
    public function deleteFaction($id) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de suppression
      $query = "DELETE FROM faction WHERE Id = ?";

      // Exécution de la requête
      $database->executeSql($query, [$id]);
    }

}
