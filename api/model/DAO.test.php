<?php

include_once(dirname(__FILE__).'/FrameDAO.class.php');
include_once(dirname(__FILE__).'/UserDAO.class.php');

$frameDAO = new FrameDAO();
var_dump($frameDAO->getFrame(1));

$userDAO = new UserDAO();
var_dump($userDAO->getUser(1));

?>
