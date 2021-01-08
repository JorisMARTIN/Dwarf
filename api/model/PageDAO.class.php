<?php
require_once(dirname(__FILE__) . '/DAO.class.php');
require_once(dirname(__FILE__).'/Page.class.php');

class PageDAO extends DAO {

    /**
     * Collect a specific page
     * 
     * @param int $pageId Id of the page
     * 
     * @return Page|NULL Page object | NULL = ❌
     */
    function getPage(int $pageId) : ?Page {
        $query = 'SELECT * FROM "Page" WHERE pageid = :pageId and deleted = false';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':pageId' => $pageId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page")[0];
        } else {
            return NULL;
        }
    }

    /**
     * Collect all deleted pages
     * 
     * @return array|NULL All deleted pages | NULL = ❌
     */
    function getDeletedPages(): array
    {
        $query = 'SELECT * FROM "Page" WHERE deleted = true';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute()) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page");
        } else {
            return NULL;
        }
    }

    /**
     * Collect the last page ID generated
     * 
     * @return int ID of the last page created | -1 = no page in database
     */
    function getLastPageId() : int {
        $query = 'SELECT max(pageId) FROM "Page"';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute()) {
            $res = $tmp->fetchColumn();
            if(!$res) return -1;
            else return $res;
        } else {
            return -1;
        }
    }

    /**
     * Collect a number of pages from a specific id
     * 
     * @param int $nb Number of page
     * @param int $first First ID from which on the collect will start
     * 
     * @return array Page Object array | Empty array = ❌
     */
    function getNPages(int $nb, int $firstId) : array {
        $query = 'SELECT * FROM "Page" WHERE pageid <= :firstId and completed = true and deleted = false ORDER BY pageid DESC LIMIT :nb';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':firstId' => $firstId, ':nb' => $nb])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page");
        } else {
            return array();
        }
    }

    /**
     * The the completed state of a page
     * 
     * @param int $pageId Id of the page
     * @param bool $completed The state to set
     * 
     * @return bool true = ✅ | false = ❌
     */
    function setCompleted(int $pageId, bool $completed) : bool {
        $query = 'UPDATE "Page" SET completed = :completed WHERE pageId = :pageId';
        return $this->db->prepare($query)->execute([
            ':completed' => $completed ? 't' : 'f',
            ':pageId' => $pageId
        ]);
    }

    /**
     * Put a new page in the database
     * 
     * @param string $name The name of the page
     * @param string $description The descritpion of the page
     * @param int $gamemode The gamemode of the page
     * @param int $template The template of the page which will arange all frames
     * @param bool $completed The completed state of the page
     * @param int $userId The id of the user who create the page
     * 
     * @return int The id of the new page | -1 = ❌
     */
    function putPage(string $name, string $description, int $gamemode, int $template, bool $completed, int $userId) : int {
        $query = 'INSERT INTO "Page" (creationDate, name, description, gamemode, template, completed, userId) VALUES (CURRENT_TIMESTAMP, :name, :description, :gamemode, :template, :completed, :userId) RETURNING pageid';
        $tmp = $this->db->prepare($query);
        if($tmp->execute([
            ':name' => $name,
            ':description' => $description,
            ':gamemode' => $gamemode,
            ':template' => $template,
            ':completed' => $completed ? 't' : 'f',
            ':userId' => $userId
        ])) {
            $pageId = $tmp->fetchColumn();
            umask(0);
            mkdir(dirname(__FILE__, 3).'/cdn/frames/page-'.$pageId, 0775, true);
            return $pageId;
        } else {
            return -1;
        }
    }

    /**
     * Collect all pages of a user
     * 
     * @param int $userId The id of the user
     * 
     * @return array|NULL Array of page object | NULL = ❌
     */
    function getUserPages(int $userId) : array {
        $query = 'SELECT * FROM "Page" WHERE userid=:userId';
        $tmp = $this->db->prepare($query);
        if($tmp->execute([':userId' => $userId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page");
        } else {
            return NULL;
        }
    }

    /**
     * Delete à page from the database
     * 
     * @param int $pageId The id of the page to delete
     * 
     * @return bool true = ✅ | false = ❌
     */
    function removePage(int $pageId) : bool {
        //$page = $this->getPage($pageId);
        $path = dirname(__FILE__, 3).'/cdn/frames/page-'.$pageId;
        $files = scandir($path);
        foreach ($files as $file) {
            if (!is_dir($file)) {
                unlink($file);
            }
        }
        rmdir($path);
        $query = 'DELETE FROM "Page" WHERE pageId = :pageId';
        return $this->db->prepare($query)->execute([
        ':pageId' => $pageId
        ]);
    }

    /**
     * Collect a random page
     * 
     * @param array Array of page id not to be returned
     * 
     * @return Page|NULL Page Object | NULL = ❌
     */
    function getRandomPage(array $except = []) : ?Page {
        $exceptCondition = empty($except) ? '' : 'AND pageid NOT IN(' . implode(',', $except) . ')';
        $query = "SELECT * FROM \"Page\" WHERE completed = false $exceptCondition ORDER BY RANDOM() LIMIT 1";

        $tmp = $this->db->prepare($query);
        if ($tmp->execute() && $tmp->rowCount() > 0) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page")[0];
        } else {
            //var_dump($tmp->errorInfo());
            return NULL;
        }
    }
}
