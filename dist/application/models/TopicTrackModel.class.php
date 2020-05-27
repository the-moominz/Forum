<?php
// Classe dédiée aux requêtes SQL vers la table topic_track

class TopicTrackModel {

  /**
   * Fonction qui va permettre d'insérer ou de mettre à jour une entrée dans la table topic_track
   * @param int : ID de l'utilisateur
   * @param int : Id du topic
   * @param int : Id de la section
   */
   public function addTopicTrack($userId, $topicId, $sectionId) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête de sélection
     $query = "SELECT Id FROM topic_track WHERE user_Id = ? AND topic_Id = ?";

     // On stocke le résultat de la requête dans une variable
     $id = $database->queryOne($query, [$userId, $topicId]);

     // Si on trouve un enregistrement
     if (!empty($id)) {
       // On met à jour l'entrée dans la base
       $sql = "UPDATE topic_track SET LastReadTimeStamp = NOW() WHERE Id = ?";

       // On exécute la requête de mise à jour
       $database->executeSql($sql, [$id['Id']]);
     } else {
       // Sinon, on insère une nouvelle entrée dans la base de données
       $sql = "INSERT INTO topic_track (LastReadTimeStamp, user_Id, topic_Id, section_Id) VALUES(NOW(), ?, ?, ?)";

       // On exécute la requête d'insertion
       $database->executeSql($sql, [$userId, $topicId, $sectionId]);
     }
   }

   /**
    * Fonction qui va supprimer les enregistrements (pour vider et alléger la table)
    * @param int : Id de l'utilisateur
    * @param int : Id de la section
    */
    public function deleteReadTopic($userId, $sectionId) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de suppression
      $query = "DELETE FROM topic_track WHERE user_Id = ? AND section_Id = ?";

      // On exécute la requête
      $database->executeSql($query, [$userId, $sectionId]);
    }

}
