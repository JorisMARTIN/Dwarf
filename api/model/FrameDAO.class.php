<?php

require_once(dirname(__FILE__).'/Frame.class.php');

class FrameDAO extends DAO {

  Frame getFrame(int $frameId) {
    $requete = "SELECT * FROM 'Frame' WHERE frameId = ?";
    $db->prepare($requete)->execute([$frameId]);
  }

}

?>
