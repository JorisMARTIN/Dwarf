<?php

require_once(dirname(__FILE__).'/../model/FrameDAO.class.php');
require_once(dirname(__FILE__).'/../model/UserDAO.class.php');
require_once(dirname(__FILE__).'/../model/PageDAO.class.php');

$userDAO = new UserDAO();
$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$user1 = $userDAO->getUser(1);
$page1 = $pageDAO->getUserPages($user1->getId())[0];
$page2 = $pageDAO->getPage(2);
$frame1 = $frameDAO->getFrames($page1->getPageId());

?>
