<?php
require_once(dirname(__FILE__) . '/DAO.class.php');
require_once(dirname(__FILE__).'/Page.class.php');

class PageDAO extends DAO {
    function getPage(int $pageId) : ?Page {
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
            $res = $tmp->fetchColumn();
            if(!$res) return -1;
            else return $res;
        } else {
            return -1;
        }
    }

    function getNPages(int $nb, int $firstId) : array {
        $query = 'SELECT * FROM "Page" WHERE pageid <= :firstId ORDER BY pageid DESC LIMIT :nb';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':firstId' => $firstId, ':nb' => $nb])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page");
        } else {
            return array();
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
            $pageId = $tmp->fetchColumn();
            umask(0);
            mkdir(dirname(__FILE__, 3).'/cdn/frames/page-'.$pageId, 0775, true);
            return $pageId;
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

    function removePage(int $pageId) : bool {
        $query = 'DELETE FROM "Page" WHERE pageId = :pageId';
        return $this->db->prepare($query)->execute([
        ':pageId' => $pageId
        ]);
    }

    function getRandomPageId() : int {
        $query = 'SELECT $pageid FROM "Page" ORDER BY RANDOM() LIMIT 1';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute()) {
            return $tmp->fetchColumn();
        } else {
            return -1;
        }
    }
}
