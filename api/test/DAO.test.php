<?php

include_once(dirname(__FILE__).'/../model/FrameDAO.class.php');
include_once(dirname(__FILE__).'/../model/UserDAO.class.php');
include_once(dirname(__FILE__).'/../model/PageDAO.class.php');

$frameDAO = new FrameDAO();
var_dump($frameDAO->getFrame(1));

$userDAO = new UserDAO();
var_dump($userDAO->getUser(1));

$pageDAO = new PageDAO();
var_dump($pageDAO->getPage(1));

?>
