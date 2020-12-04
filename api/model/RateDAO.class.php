<?php
require_once(dirname(__FILE__) . '/DAO.class.php');
require_once(dirname(__FILE__).'/Page.class.php');

class RateDAO extends DAO {

    // Fonction retournant le nombre de votes positifs et négatifs pour une page donnée :
    function getVote(int $pageId) : int { 
        $query = 'SELECT count(userId) FROM "Rate" WHERE pageId = :pageId';
        if ($tmp->execute()) {
            $res = $tmp->fetchColumn();
            if(!$res) return -1;
            else return $res;
        } else {
            return -1; 
        }

    }
    
    // Fonction retournant pour un id utilisateur donné les pages id où il a voté :
    function getUserVote(int $userId) : array {
        $query = 'SELECT * FROM "Page", "Rate" WHERE "Page".pageId = "Rate".pageId and "Rate".userId = :userId';
        $tmp = $this->db->prepare($query);
            if ($tmp->execute([':userId' => $userId])) {
                 return $tmp->fetchAll(PDO::FETCH_CLASS, 'Page');
                } else {
                 return NULL;
             }
    }
    
    
    // Fonction enregistrant le vote d'un utilisateur dans la base de donnée
    // bool $vote représente le statut du vote (like ou dislike)
    function putVote(int $userId, int $pageId, bool $vote) : bool { 
        $query = 'INSERT INTO "Rate" (userId, pageId, vote)
                  VALUES (:userId, :pageId, :vote)';

        return $this->db->prepare($query)->execute([
            ':userId' => $userId,
            ':pageId' => $frameId,
            ':vote'   => $vote ? 't' : 'f'
          ]);
    }
    
    function removeVote(int $pageId, int $userId) : bool {
        $query = 'DELETE FROM "Page" WHERE pageId = :pageId and userId = :userId';
        return $this->db->prepare($query)->execute([
        ':pageId' => $pageId,
        ':userId' => $userId
        ]);
    }
    

}

?>