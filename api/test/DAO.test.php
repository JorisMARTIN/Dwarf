<?php

require_once(dirname(__FILE__).'/../model/FrameDAO.class.php');
require_once(dirname(__FILE__).'/../model/UserDAO.class.php');
require_once(dirname(__FILE__).'/../model/PageDAO.class.php');
require_once(dirname(__FILE__).'/../includes/debug.inc.php');

$userDAO = new UserDAO();
$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$user1 = $userDAO->getUser(1);
print("Récupération d'un utilisateur : ");
if ($user1) {
  print("OK");
  $user1Id = $user1->getId();
  print("\n - Vérification de son identifiant : ".($user1Id === 1 ? "OK" : "FAILED"));
  $user1NickName = $user1->getNickName();
  print("\n - Vérification de son pseudo : ".($user1NickName === "dwarf" ? "OK" : "FAILED"));
  $user1Email = $user1->getEmail();
  print("\n - Vérification de son email : ".($user1Email === "dwarf@gmail.com" ? "OK" : "FAILED"));
  $user1CreationDate = $user1->getCreationDate();
  print("\n - Vérification de sa date de création : ".($user1CreationDate > "2000-01-01" ? "OK" : "FAILED"));
} else {
  print("FAILED");
}
print("\n\n");
$page2 = $pageDAO->getPage(2);
print("Récupération d'une planche : ");
if ($page2) {
  print("OK");
} else {
  print("FAILED");
}
print("\n");
$page1 = $pageDAO->getUserPages($user1Id)[0];
print("Récupération d'une planche depuis un utilisateur : ");
if ($page1) {
  print("OK");
  $page1Id = $page1->getId();
  print("\n - Vérification de son identifiant : ".($page1Id === 1 ? "OK" : "FAILED"));
  $page1Name = $page1->getName();
  print("\n - Vérification de son nom : ".($page1Name === "Page 1" ? "OK" : "FAILED"));
  $page1Description = $page1->getDescription();
  print("\n - Vérification de sa description : ".($page1Description === "Ceci est la page 1" ? "OK" : "FAILED"));
  $page1CreationDate = $page1->getCreationDate();
  print("\n - Vérification de sa date de création : ".($page1CreationDate > "2000-01-01" ? "OK" : "FAILED"));
  $page1Gamemode = $page1->getGameMode();
  print("\n - Vérification de son mode de jeu : ".($page1Name >= 0 ? "OK" : "FAILED"));
} else {
  print("FAILED");
}
print("\n\n");

$frame1 = $frameDAO->getFrames($page1Id);
print("Récupération d'une frame : ");
if ($frame1) {
  print("OK");
} else {
  print("FAILED");
}
print("\n\n");

$newUserId = $userDAO->putUser('DrStone'.$pageDAO->getLastPageId().'@gmail.com', 'drStone', 'qwerty', '192.168.1.1');
print("Création d'un utilisateur : ".($newUserId != -1 ? "OK" : "FAILED ($newUserId)")."\n");
$newPageId = $pageDAO->putPage('Creation de DrStone', 'Téléphone', 0, 0, false, $newUserId);
print("Création d'une planche : ".($newPageId != -1 ? "OK" : "FAILED ($newPageId)")."\n");
$newFrameId = $frameDAO->putFrame(True, False, 100, 100, $newPageId, $newUserId);
print("Création d'une frame : ".($newFrameId != -1 ? "OK" : "FAILED ($newFrameId)")."\n");

?>
