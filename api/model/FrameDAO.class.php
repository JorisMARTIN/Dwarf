<?php

require_once(dirname(__FILE__).'/Frame.class.php');

class FrameDAO extends DAO {

  function getFrame(int $frameId): Frame {
    $query = "SELECT * FROM 'Frame' WHERE frameId = ':frameId'";
    $tmp = $this->db->prepare($query)->execute(['frameId' => $frameId]);
    if ($tmp) {
      return $result = $tmp->fetchAll(PDO::FETCH_CLASS, 'Frame')[0];
    } else {
      return NULL;
    }
  }

  function putFrame(string $imagePtr, bool $next, bool $done, int $width, int $heigth) : bool {
    $query = "INSERT INTO 'Frame' (creationDate, imagePtr, next, done, width, height) VALUES (NOW()::timestamp, ':imagePtr', ':next', ':done', ':width', ':heigth')";
    return $this->db->prepare($query)->execute(['imageptr' => $imageptr, 'next' => $next, 'done' => $done, 'width' => $width, 'heigth' => $height]);
  }

  function setImagePtr(int frameId, string imagePtr) {
    $query = "UPDATE 'Frame' SET imagePtr = ':imagePtr' WHERE frameId = ':frameId'";
    return $this->db->prepare($query)->execute(['imageptr' => $imageptr, 'frameId' => $frameId]);
  }

  function setNext(int frameId, bool next) {
    $query = "UPDATE 'Frame' SET next = ':next' WHERE frameId = ':frameId'";
    return $this->db->prepare($query)->execute(['next' => $next, 'imageptr' => $imagePtr]);
  }

  function setDone(int frameId) {
    $query = "UPDATE 'Frame' SET done = ':done' WHERE frameId = ':frameId'";
    return $this->db->prepare($query)->execute(['frameId' => $frameId]);
  }
}

?>
