<?php
// Classe qui va traiter les requêtes SQL de la table mp
class MPModel {

  /**
   * Fonction dédiée à lister tous les MP reçus
   * @param int : ID du destinataire
   */
   public function listAllReceivedMp($id) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête de sélection
   $query = "SELECT DISTINCT sub.*, user.Name AS SenderName FROM (SELECT user.Id AS userID, Name, mp.Id, Sender,Addressee, Title, Content, DATE_FORMAT(mp.CreationTimeStamp, '%d/%m/%Y à %H:%i:%s') AS CreationDate, Consulted, Conversation_Id
    FROM `mp`
    INNER JOIN user ON user.Id = mp.Addressee
    WHERE Addressee = ?) AS sub
    INNER JOIN user ON user.Id = sub.Sender
    GROUP BY Title
    ORDER BY CreationDate DESC";

     // On stocke dans une variable le résultat de la requête
     $mps = $database->query($query, [$id]);

     return $mps;
   }
   /**
    *  Fonction dédiée à lister tous les MP envoyés
    * @param int : Id de l'expéditeur
    */
    public function listAllSendMp($id) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de sélection
      $query = "SELECT user.Id, Name, mp.Id, Sender, Addressee, Title, Content, DATE_FORMAT(mp.CreationTimeStamp, '%d/%m/%Y à %H:%i:%s') AS CreationDate, Consulted, Conversation_Id
        FROM `mp`
        INNER JOIN user ON user.Id = mp.Addressee
        WHERE Sender = ?
        ORDER BY mp.CreationTimeStamp DESC";

        // On stocke le résultat de la requête dans une variable
        $mps = $database->query($query, [$id]);

        return $mps;
    }

    /**
     * Fonction dédiée à récupérer le titre d'un MP en fonction de son ID
     * @param int : ID transmis par l'URl en get
     */
     public function getMPTitle($mpId) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête de sélection
       $query = "SELECT user.Id AS UserId, Name, mp.Id AS MpId, Sender, Addressee, Title, Conversation_Id FROM mp
       INNER JOIN user ON user.Id = mp.Sender
       WHERE mp.Id = ?";

       // On stocke le résultat de la requête dans une variable
       $mpTitle = $database->queryOne($query, [$mpId]);

       return $mpTitle;
     }

     /**
      * Fonction dédiée à récupérer tous les MP selon un Id de conversation donné
      * @param int : Id de conversation, qui permettent de regrouper les messages par conversation (sujet)
      */
      public function getConversationMps($id) {

        // Connexion à la base de données
        $database = new Database();

        // Préparation de la requête de sélection
        $query = "SELECT user.Id AS UserId, Name, Photo, mp.Id AS MpId, Sender, Addressee, Title, Content, DATE_FORMAT(mp.CreationTimeStamp, '%d/%m/%Y à %H:%i:%s') AS CreationDate, Conversation_Id
        FROM mp
        INNER JOIN user ON user.Id = Sender
        WHERE Conversation_Id = ?";

        // On stocke le résultat de la requête dans une variable
        $mps = $database->query($query, [$id]);

        return $mps;
      }
   /**
    * Fonction dédiée à enregistrer le détail d'un MP en base de données
    * @param int : Id de l'expéditeur
    * @param int : Id du destinataire
    * @param string : Sujet du MP
    * @param string : Contenu du MP
    */
    public function create($sender, $addressee, $title, $content){

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête d'insertion
      $query = "INSERT INTO mp (Sender, Addressee, Title, Content, CreationTimeStamp) VALUES(?, ?, ?, ?, NOW())";

      // Exécution de la requête
      $database->executeSql($query, [$sender, $addressee, $title, $content]);

      // Préparation d'une requête de mise à jour pour sélectionner directement l'ID du message crée et ainsi lui attribuer une valeur à cconversation_Id
      $query = "UPDATE mp
            SET Conversation_Id = (SELECT mp2.Id
                            FROM (SELECT * FROM mp) AS mp2
                      		WHERE mp2.CreationTimeStamp = (
                          	SELECT MAX(mp3.CreationTimeStamp) FROM (SELECT * FROM mp) AS mp3))
            WHERE Id = (SELECT mp2.Id
                            FROM (SELECT * FROM mp) AS mp2
                      		WHERE mp2.CreationTimeStamp = (
                          	SELECT MAX(mp3.CreationTimeStamp) FROM (SELECT * FROM mp) AS mp3))";

      // Exécution de la requête
      $database->executeSql($query);
    }
    /**
     * Fonction qui permet d'insérer dans la base de données une réponse en MP
     * @param int : Id de l'expéditeur
     * @param int : Id du destinataire
     * @param string : Sujet du MP
     * @param string : Contenu du MP
     * @param int : Id de la conversation
     */
     public function createAnswer($sender, $addressee, $title, $content, $conversationId) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête d'insertion
       $query = "INSERT INTO mp (Sender, Addressee, Title, Content, CreationTimeStamp, Conversation_Id) VALUES(?, ?, ?, ?, NOW(), ?)";

       // Exécution de la requête
       $database->executeSql($query, [$sender, $addressee, $title, $content, $conversationId]);
     }
     /**
      * Fonction qui permet de changer le statut du message (lu/non lu)
      * @param int : ID des messages de la conversation à changer
      */
      public function readMessages($id) {

        // Connexion à la base de données
        $database = new Database();

        // Préparation de la requête de mise à jour
        $query = "UPDATE mp SET Consulted = 1 WHERE Conversation_Id = ?";

        // Exécution de la requête
        $database->executeSql($query, [$id]);
      }
      /**
       * Fonction dédiée à la suppression d'un message privé
       * @param int : Id du MP à supprimer
       */
       public function deleteMp($id) {

         // Connexion à la base de données
         $database = new Database();

         // Préparation de la requête de suppression
         $query = "DELETE FROM mp WHERE Id = ?";

         // Exécution de la requête
         $database->executeSql($query, [$id]);
       }
}
