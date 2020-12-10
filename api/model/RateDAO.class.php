<?php
require_once(dirname(__FILE__) . '/DAO.class.php');
require_once(dirname(__FILE__) . '/Rate.class.php');

class RateDAO extends DAO
{

    /**
     * Get rates of a Page
     * 
     * @param int $pageId ID of the page
     * 
     * @return array(positive,negative)|NULL positive = Number of 👍, negative = Number of 👎 | NULL = ❌
     */
    function getVotes(int $pageId): array
    {
        $query = 'SELECT count(userId) FROM "Rate" WHERE pageId = :pageId and vote = true';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':pageId' => $pageId])) {
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
        if ($tmp->execute([':pageId' => $pageId])) {
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

    /**
     * Collect all rate of an user
     * 
     * @param int $userId ID of the user
     * 
     * @return array|NULL Array of Rate Object | NULL = ❌
     * 
     * @todo Modif la query : Jointure inutile ??
     */
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


    /**
     * Add a vote of one user
     * 
     * @param int $userId ID of the user who voted
     * @param int $pageId ID of the page rated
     * @param bool $vote The value of the vote. true = like | false = dislike
     * 
     * @return bool true = ✅ | false = ❌
     */
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

    /**
     * Delete a vote of one user on a page
     * 
     * @param int $pageId ID of the page rated
     * @param int $userId ID of the user who voted
     * 
     * @return bool true = ✅ | false = ❌
     */
    function removeVote(int $pageId, int $userId): bool
    {
        $query = 'DELETE FROM "Rate" WHERE pageId = :pageId and userId = :userId';
        return $this->db->prepare($query)->execute([
            ':pageId' => $pageId,
            ':userId' => $userId
        ]);
    }
}
