<?php
// Classe dédiée aux requêtes SQL vers la table topic
class TopicModel {

  /**
   * Fonction dédiée à récupérer le contenu de la table pour l'afficher
   */
  public function listAll($sectionId) {

    // Connexion à la base de données
    $database = new Database();

    // On instancie un nouvel objet User Session
    $userSession = new UserSession();

    // Si le membre n'est pas connecté, on affiche simplement les topics par ordre du dernier message posté
    if (!$userSession->isAuthenticated()) {

      // Préparation de la requête de sélection
      $query = "SELECT Color, user.Id, user.Name, topic.Id, user.Photo, Title, Content, DATE_FORMAT(topic.CreationTimeStamp, 'Le %d/%m/%Y à %H:%i:%s') AS CreationDate, topic.user_Id, section_Id
      FROM topic
      INNER JOIN user ON topic.user_Id = user.Id
      LEFT JOIN faction ON faction.Id = user.faction_Id
      WHERE section_Id = ?
      ORDER BY LastPostTimeStamp DESC";

      // Récupération du contenu de la requête dans une variable
      $topics = $database->query($query, [$sectionId]);

      return $topics;

    } else {
      // Mais s'il est connecté, on fait une requête un peu plus complexe, pour afficher de façon personnalisée les topics
      $userId = $userSession->getUserId();

      $query = "SELECT Color, user.Id, user.Name, topic.Id, user.Photo, topic.Title, topic.Content, DATE_FORMAT(topic.CreationTimeStamp, 'Le %d/%m/%Y à %H:%i:%s') AS CreationDate, topic.user_Id, topic.section_Id,
      ((topic_track.LastReadTimeStamp IS NULL OR topic_track.LastReadTimeStamp < topic.LastPostTimeStamp) AND (section_track.LastReadTimeStamp < topic.LastPostTimeStamp)) AS Unread
      FROM topic
      INNER JOIN user ON topic.user_Id = user.Id
      LEFT JOIN faction ON faction.Id = user.faction_Id
      LEFT JOIN topic_track ON topic_track.topic_Id = topic.Id AND topic_track.user_Id = ?
      LEFT JOIN section_track ON section_track.section_Id = topic.section_Id AND topic_track.user_Id = ?
      WHERE topic.section_Id = ?
      ORDER BY Unread DESC";

      // On stocke le résultat de la requête dans une variable
      $topics = $database->query($query, [$userId, $userId, $sectionId]);

      return $topics;
    }
  }

  /**
   * Fonction dédiée à affaicher les 3 derniers topics postés sur le forum (résultat affiché sur le dashboard)
   */
   public function listLastPosts() {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête
     $query = "SELECT Color, section.Name AS Section, user.Name AS User, topic.Id, Title, DATE_FORMAT(topic.CreationTimeStamp, '%d/%m/%Y à %H:%i:%s') AS CreationDate, user_Id, section_Id
     FROM `topic`
     INNER JOIN user ON user.Id = topic.user_Id
     INNER JOIN section ON section.Id = topic.section_Id
     INNER JOIN faction ON faction.Id = user.faction_Id
     ORDER BY topic.CreationTimeStamp DESC LIMIT 3 ";

     // On stocke le résultat de la requête dans une variable
     $lastPosts = $database->query($query);

     return $lastPosts;
   }

  /**
   * Fonction dédiée à la création d'un nouveau topic
   * @param string : Titre du topic
   * @param string : Contenu du premier message de la conversation
   * @param int : Id de la section (pour le relier à une section bien définie)
   * @param int : Id de l'utilisateur (pour le relier à l'utilisateur en question)
   */
   public function createTopic($title, $content, $userId, $sectionId) {

     // Connexion à la base de données
     $database = new Database();

     // Préparation de la requête d'insertion dans la base de données
     $query = "INSERT INTO topic (Title, Content, CreationTimeStamp, Locked, user_Id, section_Id) VALUES (?, ?, NOW(), 0, ?, ?)";

     // Exécution de la requête
     $database->executeSql($query, [$title, $content, $userId, $sectionId]);

   }

   /**
    * Fonction dédiée à récupérer le contenu d'un topic en fonction de l'ID renseigné
    * @param int : ID envoyé par l'URL GET
    */
    public function getTopic($id) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de sélection
      $query = "SELECT Color, user.Id, user.Name, user.Photo, topic.Id, Title, Content, DATE_FORMAT(topic.CreationTimeStamp, 'le %d/%m/%Y à %H:%i:%s') AS CreationDate, DATE_FORMAT(topic.EditTimeStamp, 'Edité le %d/%m/%Y à %H:%i:%s') AS EditDate, Locked, user_Id, section_Id
      FROM topic
      INNER JOIN user ON topic.user_Id = user.Id
      LEFT JOIN faction ON faction.Id = user.faction_Id
      WHERE topic.Id = ?";

      // On stocke dans une variable le résultat
      $topic = $database->queryOne($query, [$id]);

      return $topic;
    }
    /**
     * Fonction dédiée à la mise à jour d'un topic par un utilisateur
     * @param string : Titre du topic
     * @param string : Contenu du topic
     * @param int : Id du topic
     */
     public function updateTopic($title, $content, $id) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête de mise à jour
       $query = "UPDATE topic SET Title = ?, Content = ?, EditTimeStamp = NOW() WHERE Id = ?";

       // On exécute la requête
       $database->executeSql($query, [$title, $content, $id]);
     }

     /**
      * Fonction dédiée à la suppression d'un topic et des messages associés
      * @param int : Id du topic
      */
    public function deleteTopic($id) {

      // Connexion à la base de données
      $database = new Database();

      // Préparation de la requête de suppression
      $query = "DELETE FROM topic WHERE Id = ?";

      // Exécution de la requête
      $database->executeSql($query, [$id]);
    }

    /**
     * Fonction pour compter le nombre de topics par section
     * @param int : Id de la section
     */
     public function countTopics($id) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête de sélection
       $query = "SELECT COUNT(Id) AS NbTopics FROM topic WHERE section_Id = ?";

       // On stocke dans une variable le résultat
       $topics = $database->queryOne($query, [$id]);

       return $topics['NbTopics'];
     }
     /**
      * Fonction dédiée à verrouiller un topic
      * @param int : Id du topic
      */
      public function lockTopic($id) {

        // Connexion à la base de données
        $database = new Database();

        // Préparation de la requête de mise à jour
        $query = "UPDATE topic SET Locked = 1 WHERE Id = ?";

        // On exécute la requête
        $database->executeSql($query, [$id]);
      }
      /**
       * Fonction dédiée à déverrouiller un topic
       * @param int : Id du topic
       */
       public function unlockTopic($id) {

         // Connexion à la base de données
         $database = new Database();

         // Préparation de la requête de mise à jour
         $query = "UPDATE topic SET Locked = 0 WHERE Id = ?";

         // On exécute la requête
         $database->executeSql($query, [$id]);
       }
    /**
     * Fonction qui permet de mettre à jour le dernier message posté d'un topic
     * @param int : Id du topic à mettre à jour
     */
     public function updateLastReadPost($topicId) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête de mise à jour
       $query = "UPDATE topic SET LastPostTimeStamp = NOW() WHERE Id = ?";

       // On exécute la requête
       $database->executeSql($query, [$topicId]);
     }
    /**
     * Fonction qui permet de récupérer l'Id de la section pour un topic donné
     * @param int : Id du topic
     */
     public function getSectionId($topicId) {

       // Connexion à la base de données
       $database = new Database();

       // Préparation de la requête de sélection
       $query = "SELECT section_Id FROM topic WHERE Id = ?";

       // On stocke le résultat de la variable dans une variable
       $sectionId = $database->queryOne($query, [$topicId]);

       return $sectionId;
     }
    /**
     * Fonction qui va permettre de savoir combien de topics ont été lu
     */
     public function howManyUnreadTopic($sectionId) {

       // Connexion à la base de données
       $database = new Database();

       // Instanciation d'un objet UserSession
       $userSession = new UserSession();

       // On récupère l'id de l'utilisateur
       $userId = $userSession->getUserId();

       // Préparation de la requête de sélection
       $query = "SELECT COUNT(topic.Id) AS Count
       FROM topic
       LEFT JOIN topic_track ON topic_track.topic_Id = topic.Id AND topic_track.user_Id = ?
       LEFT JOIN section_track ON section_track.section_Id = topic.section_Id AND section_track.user_Id = ?
       WHERE (topic_track.LastReadTimeStamp IS NULL OR topic_track.LastReadTimeStamp < LastPostTimeStamp) AND (section_track.LastReadTimeStamp IS NULL OR section_track.LastReadTimeStamp < LastPostTimeStamp) AND topic.section_Id = ?";

       // On stocke le résultat de la requête dans une variable
       $countTopics = $database->query($query, [$userId, $userId, $sectionId]);

       return $countTopics[0]['Count'];
     }
     /**
      * Fonction qui permet de récupérer le dernier LastPostTimeStamp de la table topic
      * @param int : Id de la section
      */
      public function getLastPostTime($sectionId) {

        // Connexion à la base de donnée
        $database = new Database();

        // Préparation de la requête de sélection
        $query = "SELECT LastPostTimeStamp FROM topic WHERE section_Id = ? ORDER BY LastPostTimeStamp DESC LIMIT 1";

        // On stocke le contenu de la requête dans une variable
        $lastPost = $database->query($query, [$sectionId]);

        return $lastPost[0]['LastPostTimeStamp'];
      }

      /**
       * Fonction qui permet de changer le timestamp d'un LastPost d'un topic après suppression d'un message
       * @param time : TimeStamp du dernier message
       * @param int : Id du topic
       */
       public function updateOneReadMessage($lastMessage, $topicId) {

         // Connexion à la base de données
         $database = new Database();

         // Préparation de la requête de mise à jour
         $query = "UPDATE topic SET LastPostTimeStamp = ? WHERE Id = ?";

         // On exécute la requête
         $database->executeSql($query, [$lastMessage, $topicId]);
       }
}
