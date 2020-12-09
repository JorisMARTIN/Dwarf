<?php

require_once(dirname(__FILE__).'/DAO.class.php');
require_once(dirname(__FILE__).'/DeletePage.class.php');

class DeletePageDAO extends DAO {

    /**
     * Get inforamtions about a deleted page
     * 
     * @param int $pageId Id of the page
     * 
     * @return DeletedPag|NULL DeletePage objet | NULL = ❌
     */
    function getDeletedPageInformation(int $pageId) : ?DeletePage {
        $query = 'SELECT * FROM "DeletePage" WHERE pageid = :pageId';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':pageId' => $pageId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "DeletePage")[0];
        } else {
            return NULL;
        }
    }


    /**
     * Add a page to the relation "DeletePage" and set the deleted state in "Page" relation to true with a trigger
     * 
     * @param int $pageId Id of the page to delete
     * @param int $userId Id of the user who deleted the page
     * @param string $reason The reason for why the page will be deleted (can be "")
     * 
     * @return bool true = ✅ | false = ❌
     */
    function putDeletePage(int $pageId, int $userId, string $reason): bool
    {
        $query = 'INSERT INTO "DeletePage" (pageId, userId, reason, deleteDate) VALUES (:pageId, :userId, :reasonn, CURRENT_TIMESTAMP)';
        $tmp = $this->db->prepare($query);
        return ($tmp->execute([
            ':pageId' => $pageId,
            ':userId' => $userId,
            ':reason' => $reason
        ]));
    }

    /**
     * Remove a page from the "DeletePage" relation and set the deleted state to false in "Page" relation with a trigger
     * 
     * @param int $pageId Id of the page to un-delete
     * 
     * @return bool true = ✅ | false = ❌
     */
    function removeDeletePage(int $pageId) : bool {
        $query = 'DELETE FROM "DeletePage" WHERE pageId = :pageId';
        $tmp = $this->db->prepare($query);
        return ($tmp->execute([':pageId' => $pageId]));
    }


    
}

?>