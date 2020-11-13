<?php

class FrameDAO extends DAO {

  Frame getFrame(int $frameId) {
    $requete = "SELECT * FROM 'Frame' WHERE frameId = ?";
    $db->prepare($requete)->execute([$frameId]);
  }

}

?>
