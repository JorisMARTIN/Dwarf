<?php
require_once(dirname(__FILE__) . '/DAO.class.php');
require_once(dirname(__FILE__).'/Page.class.php');

class PageDAO extends DAO {
    function getPage(int $pageId) : Page {
        $query = 'SELECT * FROM "Page" WHERE pageid = :pageId';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':pageId' => $pageId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page")[0];
        } else {
            return NULL;
        }
    }

    function getLastPageId() : int {
        $query = 'SELECT max(pageId) FROM "Page"';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute()) {
            return $tmp->fetchColumn();
        } else {
            return -1;
        }
    }

    function getRangeOfPages(int $firstId, int $lastId) : array {
        $query = 'SELECT * FROM "Page" WHERE pageid >= :firstId AND pageid <= :lastId';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':firstId' => $firstId, ':lastId' => $lastId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page");
        } else {
            return NULL;
        }
    }

    function setCompleted(int $pageId, bool $completed) : bool {
        $query = 'UPDATE "Page" SET completed = :completed WHERE pageId = :pageId';
        return $this->db->prepare($query)->execute([
            ':completed' => $completed ? 't' : 'f',
            ':pageId' => $pageId
        ]);
    }

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
            $result = $tmp->fetchColumn();
            mkdir(dirname(__FILE__).'/../../cdn/frames/page-'.$result, 0644);
            return $result;
        } else {
            return -1;
        }
    }

    function getUserPages(int $userId) : array {
        $query = 'SELECT * FROM "Page" WHERE userid=:userId';
        $tmp = $this->db->prepare($query);
        if($tmp->execute([':userId' => $userId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page");
        } else {
            return NULL;
        }
    }
}
