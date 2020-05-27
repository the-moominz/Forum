<?php

// Classe dédiée aux requêtes SQL de la table message
class MessagesModel {

  /**
   * Fonction dédiée à l'affichage de la liste des posts associé à un topic donné
   * @param int : Id du topic en question pour retrouver les messages associés
   */
   public function listAll($topicId) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête de sélection
     $query = "SELECT Color, user.Id, user.Name, user.Photo, messages.Id, Post, DATE_FORMAT(messages.CreationTimeStamp, 'le %d/%m/%Y à %H:%i:%s') AS CreationDate, DATE_FORMAT(messages.EditTimeStamp, 'Edité le %d/%m/%Y à %H:%i:%s') AS EditDate, messages.user_Id, messages.topic_Id
     FROM messages
     INNER JOIN user ON user.Id = messages.user_Id
     LEFT JOIN faction ON faction.Id = user.faction_Id
     WHERE topic_Id = ?
     ORDER BY CreationDate";

   // On stocke dans une variable le contenu de la requête
   $messages = $database->query($query, [$topicId]);

   return $messages;
 }
/**
 * Fonction qui permet de récupérer l'Id du dernier message posté dans un topic donné
 * @param int : Id du topic
 */
 public function lastTopicPost($topicId) {

   // Connexion à la base de données
   $database = new Database();

   // Préparation de la requête de sélection
   $query = "SELECT sub.id FROM (
        SELECT max(m.Id) AS id, max(m.CreationTimeStamp) AS date
        FROM messages m
        WHERE m.topic_Id = ?) AS sub";

   // On stocke le résultat de la requête dans une variable
   $lastTopicPost = $database->queryOne($query, [$topicId]);

   return $lastTopicPost['id'];
 }
 /**
  *   Fonction qui permet de retrouver l'auteur du dernier message pour une section donnée
  * @param int : Id de la section
  */
  public function lastPost($sectionId) {

    // Connexion à la base de données
    $database = new Database();

    // Préparation de la requête
    $query = "SELECT sub.color, sub.id, sub.name, DATE_FORMAT(date, 'à %H:%i:%s') AS CreationDate, sub.section_id FROM (
        SELECT faction.Id AS faction, faction.Color AS color, user.faction_Id, user.Id AS id, user.Name AS name, max(m.CreationTimeStamp) AS date, topic.section_Id AS section_id
        FROM messages m
        INNER JOIN user ON user.Id = m.user_Id
        INNER JOIN topic ON topic.Id = m.topic_Id
        INNER JOIN faction ON faction.Id = user.faction_Id
        WHERE topic.section_Id = ?) AS sub";

    // On stocke le résultat de la requête dans une variable
    $lastPost = $database->queryOne($query, [$sectionId]);

    return $lastPost;
  }

 /**
  * Fonction dédiée à l'ajout d'un message à la base de donnée
  * @param string : Contenu du nouveau message
  * @param int : Id de l'utilisateur ayant posté le message
  * @param int : Id du topic auquel relier le message
  */
  public function createMessage($post, $userId, $topicId) {

    // Connexion à la base de données
    $database = new Database();

    // Préparation de la requête d'insertion
    $query = "INSERT INTO messages (Post, CreationTimeStamp, user_Id, topic_Id) VALUES (?, NOW(), ?, ?)";

    // On exécute la requête
    $database->executeSql($query, [$post, $userId, $topicId]);
  }
  /**
   * Fonction dédiée à la récupération d'un message en fonction de son ID
   * @param int : ID du message à récupérer
   */
   public function getMessage($id) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête de sélection
     $query = "SELECT Id, Post, topic_Id FROM messages WHERE Id = ?";

     // On stocke le résultat de la requête dans une variable
     $message = $database->queryOne($query, [$id]);

     return $message;
   }
   /**
    * Fonction dédiée à la mise à jour du message en base en fonction de son ID
    * @param string : Contenu du message
    * @param int : ID du message
    */
    public function updateMessage($post, $id) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de mise à jour
      $query = "UPDATE messages SET Post = ?, EditTimeStamp = NOW() WHERE Id = ?";

      // On exécute la requête
      $database->executeSql($query, [$post, $id]);
    }
    /**
     * Fonction dédiée à la suppression d'un message sélectionné
     * @param int : Id du message à supprimer
     */
     public function deleteMessage($id) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête de suppression
       $query = "DELETE FROM messages WHERE Id = ?";

       // Exécution de la requête
       $database->executeSql($query, [$id]);
     }

     /**
      * Fonction destinée à compter le nombre de TOUS les messages sur le forum
      */
      public function allMessages() {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête
      $query = "SELECT COUNT(Id) AS NbMessages FROM messages";

      // On stocke le résultat de la variable dans une requête
      $messages = $database->query($query);

      return $messages;
      }

     /**
      * Fonction servant à compter le nombre de messages en fonction du topic
      * @param int : Id du topic
      */
      public function countMessages($id){

        // Connexion à la base de données
        $database = new Database();

        // Préparation de la requête de sélection
        $query = "SELECT COUNT(Id) AS NbMessages FROM messages WHERE topic_Id = ?";

        // On stocke le résultat de la requête dans une variable
        $messages = $database->queryOne($query, [$id]);

        return $messages['NbMessages'];
      }

      /**
       * Fonction destinée à compter le nombre de messages postés par un utilisateur donné (affichage profil)
       * @param int : ID de l'utilisateur
       */
       public function userMessages($userId) {

         // Connexion à la base de données
         $database = new Database();

         // Préparation de la requête
         $query = "SELECT COUNT(Id) AS NbMessages FROM messages WHERE user_Id = ?";

         // On stocke le résultat de la requête dans une variable
         $userMessage = $database->query($query, [$userId]);

         return $userMessage[0];
       }
       /**
        * Fonction qui va permettre de récupérer la date de création du dernier message de la table messages
        * @param int : Id du topic
        */
        public function getLastPostMessage($topicId) {

          // Connexion à la base de données
          $database = new Database();

          // Préparation de la requête de sélection
          $query = "SELECT CreationTimeStamp FROM messages WHERE topic_Id = ? ORDER BY CreationTimeStamp DESC LIMIT 1";

          // On stocke le résultat de la requête dans une variable
          $lastMessage = $database->query($query, [$topicId]);

          return $lastMessage[0]['CreationTimeStamp'];
        }
}
