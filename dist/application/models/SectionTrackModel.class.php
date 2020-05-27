<?php
// Classe dédiée aux requêtes SQL vers la table section_track

class SectionTrackModel {

  /**
   * Fonction dédiée à insérer ou mettre à jour une entrée dans la table
   * @param int : Id de l'utilisateur
   * @param int : Id de la section
   */
   public function sectionTrack($userId, $sectionId) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête de sélection
     $query = "SELECT Id FROM section_track WHERE user_Id = ? AND section_Id = ?";

     // On stocke le résultat de la requête dans une variable
     $id = $database->queryOne($query, [$userId, $sectionId]);

     // Si on trouve un enregistrement dans la table
     if (!empty($id)) {
       // On met à jour l'entrée dans la base
       $sql = "UPDATE section_track SET LastReadTimeStamp = NOW() WHERE Id = ?";

       // On exécute la requête
       $database->executeSql($sql, [$id['Id']]);

     } else {
       // Sinon, on ajoute une entrée dans la base
       $sql = "INSERT INTO section_track (LastReadTimeStamp, user_Id, section_Id) VALUES (NOW(), ?, ?)";

       // On exécute la requête
       $database->executeSql($sql, [$userId, $sectionId]);
     }
   }
}
