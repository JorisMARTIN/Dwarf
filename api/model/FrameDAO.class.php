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
      return $result = $tmp->fetchAll(PDO::FETCH_CLASS, 'Frame')[0];
    } else {
      throw new Exception("Erreur lors de la requête vers la base de données pour getFrame");
    }
  }

  function putFrame(string $imagePtr, bool $drawable, bool $done, int $width, int $heigth) : bool {
    $query = 'INSERT INTO "Frame" (creationDate, imagePtr, drawable, done, width, height) VALUES (NOW()::timestamp, :imagePtr, :next, :done, :width, :heigth)';
    return $this->db->prepare($query)->execute([':imageptr' => $imageptr, ':drawable' => $next, ':done' => $done, ':width' => $width, ':heigth' => $height]);
  }

  function setImagePtr(int $frameId, string $imagePtr) : bool {
    $query = 'UPDATE "Frame" SET imagePtr = :imagePtr WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([':imageptr' => $imageptr, ':frameId' => $frameId]);
  }

  function setDrawable(int $frameId, bool $drawable) : bool {
    $query = 'UPDATE "Frame" SET drawable = :drawable WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([':drawable' => $next, ':imageptr' => $imagePtr]);
  }

  function setDone(int $frameId, bool $done) : bool {
    $query = 'UPDATE "Frame" SET done = :done WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([':done' => $done, ':frameId' => $frameId]);
  }
}

?>
