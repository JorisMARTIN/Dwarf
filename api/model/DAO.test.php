<?php

include_once(dirname(__FILE__).'/FrameDAO.class.php');

$frameDAO = new FrameDAO();
var_dump($frameDAO->getFrame(1));

?>
