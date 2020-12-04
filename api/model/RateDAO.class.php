<?php
require_once(dirname(__FILE__) . '/DAO.class.php');
require_once(dirname(__FILE__) . '/Page.class.php');

class RateDAO extends DAO
{

    // Fonction retournant pour un pageId donnée le nombre de votes positifs et négatifs :
    function getVotes(int $pageId): array
    {
        $query = 'SELECT count(userId) FROM "Rate" WHERE pageId = :pageId and vote = true';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute()) {
            $res = $tmp->fetchColumn();
            if ($res) {
                $positif = $res;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }

        $query = 'SELECT count(userId) FROM "Rate" WHERE pageId = :pageId and vote = false';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute()) {
            $res = $tmp->fetchColumn();
            if ($res) {
                $negatif = $res;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }

        return array($positif, $negatif);
    }

    // Fonction retournant pour un userId donné les votes de cet utilisateur :
    function getUserVotes(int $userId): array
    {
        $query = 'SELECT "Rate".* FROM "Page", "Rate" WHERE "Page".pageId = "Rate".pageId and "Rate".userId = :userId';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':userId' => $userId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, 'Rate');
        } else {
            return NULL;
        }
    }


    // Fonction enregistrant le vote d'un utilisateur dans la base de donnée
    // bool $vote représente le statut du vote (like ou dislike)
    function putVote(int $userId, int $pageId, bool $vote): bool
    {
        $query = 'INSERT INTO "Rate" (userId, pageId, vote)
                  VALUES (:userId, :pageId, :vote)';

        return $this->db->prepare($query)->execute([
            ':userId' => $userId,
            ':pageId' => $pageId,
            ':vote'   => $vote ? 't' : 'f'
        ]);
    }

    function removeVote(int $pageId, int $userId): bool
    {
        $query = 'DELETE FROM "Rate" WHERE pageId = :pageId and userId = :userId';
        return $this->db->prepare($query)->execute([
            ':pageId' => $pageId,
            ':userId' => $userId
        ]);
    }
}
