<?php

require_once(dirname(__FILE__).'/../model/FrameDAO.class.php');
require_once(dirname(__FILE__).'/../model/UserDAO.class.php');
require_once(dirname(__FILE__).'/../model/PageDAO.class.php');
require_once(dirname(__FILE__).'/../includes/debug.inc.php');

$userDAO = new UserDAO();
$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$user1 = $userDAO->getUser(1);
if ($user1) {
  print("Récupération d'un utilisateur : OK");
  $user1Id = $user1->getId();
  print(" - Vérification de son identifiant : ".($user1Id === 1 ? "OK" : "FAILED"));
  $user1NickName = $user1->getNickName();
  print(" - Vérification de son pseudo : ".($user1NickName === "dwarf" ? "OK" : "FAILED"));
  $user1Email = $user1->getEmail();
  print("Vérification de son email : ".($user1Email === "dwarf@gmail.com" ? "OK" : "FAILED"));

$page1 = $pageDAO->getUserPages($user1Id)[0];
$page2 = $pageDAO->getPage(2);
$frame1 = $frameDAO->getFrames($page1->getId());

var_dump($page1);
var_dump($page2);
var_dump($frame1);

var_dump($pageDAO->putPage('Creation', 'Je suis', 0, 0, false, 1));
var_dump($frameDAO->putFrame(True, False, 100, 100, 1, 1));

?>
