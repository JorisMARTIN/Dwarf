<?php

require_once(dirname(__FILE__).'/DAO.class.php');
require_once(dirname(__FILE__).'/Frame.class.php');

class FrameDAO extends DAO {

  /**
   * Collect a specific frame
   * 
   * @param int $frameId ID of the frame
   * 
   * @return Frame|NULL Frame Object | NULL = ❌
   */
  function getFrame(int $frameId) : ?Frame {
    $query = 'SELECT * FROM "Frame" WHERE frameId = :frameId';
    $tmp = $this->db->prepare($query);
    if ($tmp->execute([':frameId' => $frameId])) {
      return $tmp->fetchAll(PDO::FETCH_CLASS, 'Frame')[0];
    } else {
      return NULL;
    }
  }

  /**
   * Collect all frames of a page
   *
   * @param int $pageId ID of the page
   * 
   * @return array|NULL Array of Frame Object | NULL = ❌
   */
  function getFrames(int $pageId) : array {
    $query = 'SELECT * FROM "Frame" WHERE pageId = :pageId ORDER BY frameid';
    $tmp = $this->db->prepare($query);
    if ($tmp->execute([':pageId' => $pageId])) {
      return $tmp->fetchAll(PDO::FETCH_CLASS, 'Frame');
    } else {
      return NULL;
    }
  }


  /**
   * Collect all frames of a user
   *
   * @param int $userId
   * 
   * @return array|NULL Array of Frame Object | NULL = ❌
   */
  function getUserFrames(int $userId) : array {
    $query = 'SELECT * FROM "Frame" WHERE userId = :userId';
    $tmp = $this->db->prepare($query);
    if ($tmp->execute([':userId' => $userId])) {
      return $tmp->fetchAll(PDO::FETCH_CLASS, 'Frame');
    } else {
      return NULL;
    }
  }

  /**
   * Add a new frame in the database
   * 
   * @param bool $drawable If we can drawn on it
   * @param bool $done If the frame is finish
   * @param int $width The widht of the frame
   * @param int $height The height of the frame
   * @param int $pageId ID of the page which will include this frame
   * @param int $userId ID of the user who created this frame
   * 
   * @return int ID of the new frame | -1 = ❌
   */
  function putFrame(bool $drawable, bool $done, int $width, int $height, int $pageId, ?int $userId) : int {
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
      //var_dump($tmp->errorInfo()); /* Pas de Var_Dump ! */
      return -1;
    }
  }

  /**
   * Change the image url of the frame
   * 
   * @param int $frameId ID of the frame
   * @param string $imagePtr The url on the server of the image of the frame
   * 
   * @return bool true = ✅ | false = ❌
   */
  function setImagePtr(int $frameId, string $imagePtr) : bool {
    $query = 'UPDATE "Frame" SET imagePtr = :imagePtr WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([
      ':imagePtr' => $imagePtr,
      ':frameId' => $frameId
    ]);
  }

  /**
   * Change the drawable state of a frame
   * 
   * @param int $frameId ID of the frame
   * @param bool $drawable The new drawable state
   * 
   * @return bool true = ✅ | false = ❌
   */
  function setDrawable(int $frameId, bool $drawable) : bool {
    $query = 'UPDATE "Frame" SET drawable = :drawable WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([
      ':drawable' => $drawable ? 't' : 'f',
      ':frameId' => $frameId
    ]);
  }

  /**
   * Change the done state of a frame, if the frame is done
   * 
   * @param int $frameId ID of the frame
   * @param bool $done The new done state
   * 
   * @return bool true = ✅ | false = ❌
   */
  function setDone(int $frameId, bool $done) : bool {
    $query = 'UPDATE "Frame" SET done = :done WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([
      ':done' => $done ? 't' : 'f',
      ':frameId' => $frameId
    ]);
  }

  /**
   * Set the author of a frame
   * 
   * @param int $frameId ID of the frame
   * @param int $userId The author ID
   * 
   * @return bool true = ✅ | false = ❌
   */
  function setAuthor(int $frameId, int $userId) : bool {
    $query = 'UPDATE "Frame" SET userid = :userid WHERE frameid = :frameid';
    return $this->db->prepare($query)->execute([
      ':userid' => $userId,
      ':frameid' => $frameId
    ]);
  }

  /**
   * Claim a frame, aka set the TTL as time() + 15min
   * 
   * @param int $frameId ID of the frame
   * 
   * @return bool true = ✅ | false = ❌
   */
  function claim(int $frameId) : bool {
    $query = 'UPDATE "Frame" SET ttl = :ttl WHERE frameid = :frameid';
    return $this->db->prepare($query)->execute([
      ':ttl' => time() + 25 * 60,
      ':frameid' => $frameId
    ]);
  }

  /**
   * Unclaim a frame, aka set the TTL to NULL
   * 
   * @param int $frameId ID of the frame
   * 
   * @return bool true = ✅ | false = ❌
   */
  function unclaim(int $frameId): bool {
    $query = 'UPDATE "Frame" SET ttl = NULL WHERE frameid = :frameid';
    return $this->db->prepare($query)->execute([
      ':frameid' => $frameId
    ]);
  }

  /**
   * Remove a frame from the database
   * 
   * @param int $frameId ID of the frame to delete
   * 
   * @return bool true = ✅ | false = ❌
   */
  function removeFrame(int $frameId) : bool {
    $query = 'DELETE FROM "Frame" WHERE frameId = :frameId';
    return $this->db->prepare($query)->execute([
      ':frameId' => $frameId
    ]);
  }
}

?>
