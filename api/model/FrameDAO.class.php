<?php

require_once(dirname(__FILE__).'/DAO.class.php');
require_once(dirname(__FILE__).'/Frame.class.php');

class FrameDAO extends DAO {

  function getFrame(int $frameId) : Frame {
    $query = 'SELECT * FROM "Frame" WHERE frameId = :frameId';
    $tmp = $this->db->prepare($query);
    if ($tmp) {
      var_dump($tmp);
      $tmp->execute([':frameId' => $frameId]);
      return $tmp->fetchAll(PDO::FETCH_CLASS, 'Frame')[0];
    } else {
      return NULL;
    }
  }

  function putFrame(string $imagePtr, bool $drawable, bool $done, int $width, int $height) : bool {
    $query = 'INSERT INTO "Frame" (creationDate, imagePtr, drawable, done, width, height) VALUES (NOW()::timestamp, :imagePtr, :next, :done, :width, :height)';
    return $this->db->prepare($query)->execute([':imagePtr' => $imagePtr, ':drawable' => $drawable, ':done' => $done, ':width' => $width, ':heigth' => $height]);
  }

  function setImagePtr(int $frameId, string $imagePtr) : bool {
    $query = 'UPDATE "Frame" SET imagePtr = :imagePtr WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([':imageptr' => $imagePtr, ':frameId' => $frameId]);
  }

  function setDrawable(int $frameId, bool $drawable) : bool {
    $query = 'UPDATE "Frame" SET drawable = :drawable WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([':drawable' => $drawable, ':frameId' => $frameId]);
  }

  function setDone(int $frameId, bool $done) : bool {
    $query = 'UPDATE "Frame" SET done = :done WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([':done' => $done, ':frameId' => $frameId]);
  }
}

?>
