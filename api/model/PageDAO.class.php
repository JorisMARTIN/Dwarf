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
            ':completed' => $completed,
            ':pageId' => $pageId
        ]);
    }
}
