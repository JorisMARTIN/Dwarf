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
                return $positif = 0;
            }
        } else {
            return $positif = 0;
        }

        $query = 'SELECT count(userId) FROM "Rate" WHERE pageId = :pageId and vote = false';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':pageId' => $pageId])) {
            $res = $tmp->fetchColumn();
            if ($res) {
                $negatif = $res;
            } else {
                return $negatif = 0;
            }
        } else {
            return $negatif = 0;
        }

        return array($positif, $negatif);
    }

    /**
     * Collect all rate of an user
     * 
     * @param int $userId ID of the user
     * 
     * @return array|NULL Array of Rate Object | NULL = ❌
     */
    function getUserVotes(int $userId): ?array
    {
        $query = 'SELECT * FROM "Rate" WHERE userId = :userId';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':userId' => $userId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, 'Rate');
        } else {
            return NULL;
        }
    }

    /**
     * Collect 1 rate of 1 user
     * 
     * @param int $userId ID of the user
     * 
     * @return int 1 = positive vote | 0 = negative | -1 = no vote
     */
    function getUserVotePage(int $userId, int $pageId): int {
        $query = 'SELECT * FROM "Rate" WHERE userid = :userId AND pageid = :pageId';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([
            ':userId' => $userId,
            ':pageId' => $pageId
        ])) {
            $res = $tmp->fetchColumn();
            if (!$res) return -1;
            else return $res ? 1 : 0;
        } else {
            return -1;
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
