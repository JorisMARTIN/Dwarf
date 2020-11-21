<?php

require_once(dirname(__FILE__).'/DAO.class.php');
require_once(dirname(__FILE__).'/Frame.class.php');

class FrameDAO extends DAO {
  function getFrame(int $frameId) : ?Frame {
    $query = 'SELECT * FROM "Frame" WHERE frameId = :frameId';
    $tmp = $this->db->prepare($query);
    if ($tmp->execute([':frameId' => $frameId])) {
      return $tmp->fetchAll(PDO::FETCH_CLASS, 'Frame')[0];
    } else {
      return NULL;
    }
  }

  function getFrames(int $pageId) : array {
    $query = 'SELECT * FROM "Frame" WHERE pageId = :pageId ORDER BY frameid';
    $tmp = $this->db->prepare($query);
    if ($tmp->execute([':pageId' => $pageId])) {
      return $tmp->fetchAll(PDO::FETCH_CLASS, 'Frame');
    } else {
      return NULL;
    }
  }

  function putFrame(bool $drawable, bool $done, int $width, int $height, int $pageId, int $userId) : int {
    $query = 'INSERT INTO "Frame" (creationDate, drawable, done, width, height, pageId, userId)
              VALUES (CURRENT_TIMESTAMP, :drawable, :done, :width, :height, :pageId, :userId)
              RETURNING frameid';
    $tmp = $this->db->prepare($query);
    if($tmp->execute([
      ':drawable' => $drawable ? 't' : 'f',
      ':done' => $done ? 't' : 'f',
      ':width' => $width,
      ':height' => $height,
      ':pageId' => $pageId,
      ':userId' => $userId
    ])) {
      $frameId = $tmp->fetchColumn();
      $this->setImagePtr($frameId, "/cdn/frames/page-$pageId/frame-$frameId.png");
      return $frameId;
    } else {
      var_dump($tmp->errorInfo());
      return -1;
    }
  }

  function setImagePtr(int $frameId, string $imagePtr) : bool {
    $query = 'UPDATE "Frame" SET imagePtr = :imagePtr WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([
      ':imagePtr' => $imagePtr,
      ':frameId' => $frameId
    ]);
  }

  function setDrawable(int $frameId, bool $drawable) : bool {
    $query = 'UPDATE "Frame" SET drawable = :drawable WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([
      ':drawable' => $drawable ? 't' : 'f',
      ':frameId' => $frameId
    ]);
  }

  function setDone(int $frameId, bool $done) : bool {
    $query = 'UPDATE "Frame" SET done = :done WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([
      ':done' => $done ? 't' : 'f',
      ':frameId' => $frameId
    ]);
  }
}

?>
