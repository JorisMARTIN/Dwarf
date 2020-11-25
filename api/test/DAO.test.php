<?php

require_once(dirname(__FILE__).'/../model/FrameDAO.class.php');
require_once(dirname(__FILE__).'/../model/UserDAO.class.php');
require_once(dirname(__FILE__).'/../model/PageDAO.class.php');
require_once(dirname(__FILE__).'/../includes/debug.inc.php');

$userDAO = new UserDAO();
$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$newUserId = $userDAO->putUser('test@gmail.com', 'test-nickname', 'test-password', '192.168.1.1');
print("Création d'un utilisateur : ".($newUserId != -1 ? "OK" : "FAILED ($newUserId)")."\n");
$newPageId = $pageDAO->putPage('test-page', 'test-description', 0, 0, false, $newUserId);
print("Création d'une planche : ".($newPageId != -1 ? "OK" : "FAILED ($newPageId)")."\n");
$newFrameId = $frameDAO->putFrame(True, False, 100, 100, $newPageId, $newUserId);
print("Création d'une frame : ".($newFrameId != -1 ? "OK" : "FAILED ($newFrameId)")."\n");

$user1 = $userDAO->getUser($newUserId);
print("Récupération d'un utilisateur : ");
if ($user1) {
  print("OK");
  $user1Id = $user1->getId();
  print("\n - Vérification de son identifiant : ".($user1Id === $newUserId ? "OK" : "FAILED"));
  $user1NickName = $user1->getNickName();
  print("\n - Vérification de son pseudo : ".($user1NickName === "test-nickname" ? "OK" : "FAILED"));
  $user1Email = $user1->getEmail();
  print("\n - Vérification de son email : ".($user1Email === "test@gmail.com" ? "OK" : "FAILED"));
  $user1CreationDate = $user1->getCreationDate();
  print("\n - Vérification de sa date de création : ".($user1CreationDate > "2000-01-01" ? "OK" : "FAILED"));
  $user1Ip = $user1->getIps()[0];
  print("\n - Vérification de son adresse ip : ".($user1Ip === '192.168.1.1' ? "OK" : "FAILED"));
} else {
  print("FAILED");
}
print("\n\n");

$page2 = $pageDAO->getPage($newPageId);
print("Récupération d'une planche : ");
if ($page2) {
  print("OK");
} else {
  print("FAILED");
}
print("\n");
$page1 = $pageDAO->getUserPages($newUserId)[0];
print("Récupération d'une planche depuis un utilisateur : ");
if ($page1) {
  print("OK");
  $page1Id = $page1->getId();
  print("\n - Vérification de son identifiant : ".($page1Id === $newPageId ? "OK" : "FAILED"));
  $page1Name = $page1->getName();
  print("\n - Vérification de son nom : ".($page1Name === "test-page" ? "OK" : "FAILED"));
  $page1Description = $page1->getDescription();
  print("\n - Vérification de sa description : ".($page1Description === "test-description" ? "OK" : "FAILED"));
  $page1CreationDate = $page1->getCreationDate();
  print("\n - Vérification de sa date de création : ".($page1CreationDate > "2000-01-01" ? "OK" : "FAILED"));
  $page1GameMode = $page1->getGameMode();
  print("\n - Vérification de son mode de jeu : ".($page1GameMode === 0 ? "OK" : "FAILED"));
  $page1Template = $page1->getTemplateType();
  print("\n - Vérification de son template : ".($page1Template === 0 ? "OK" : "FAILED"));
  $page1Completed = $page1->isCompleted();
  print("\n - Vérification de sa complétion : ".(!$page1Completed ? "OK" : "FAILED"));
  $page1OwnerId = $page1->getOwnerId();
  print("\n - Vérification de l'identifiant de son créateur : ".($page1OwnerId === $newUserId ? "OK" : "FAILED"));
} else {
  print("FAILED");
}
print("\n\n");

$frame2 = $frameDAO->getFrame($newFrameId);
print("Récupération d'une frame : ");
if ($frame2) {
  print("OK");
} else {
  print("FAILED");
}
print("\n");
$frame1 = $frameDAO->getFrames($newPageId)[0];
print("Récupération d'une frame : ");
if ($frame1) {
  print("OK");
  $frame1Id = $frame1->getId();
  print("\n - Vérification de son identifiant : ".($frame1Id === $newPageId ? "OK" : "FAILED"));
  $frame1ImagePtr = $frame1->getImagePtr();
  print("\n - Vérification de son chemin d'accès : ".($frame1ImagePtr === "/cdn/frames/page-".$newPageId."/frame-".$newFrameId.".png" ? "OK" : "FAILED"));
  $frame1CreationDate = $frame1->getCreationDate();
  print("\n - Vérification de sa date de création : ".($frame1CreationDate > "2000-01-01" ? "OK" : "FAILED"));
  $frame1Width = $frame1->getWidth();
  print("\n - Vérification de sa largeur : ".($frame1Width === 100 ? "OK" : "FAILED"));
  $frame1Height = $frame1->getHeight();
  print("\n - Vérification de sa hauteur : ".($frame1Height === 100 ? "OK" : "FAILED"));
  $frame1Done = $frame1->isDone();
  print("\n - Vérification de sa complétion : ".(!$frame1Done ? "OK" : "FAILED"));
  $frame1Drawable = $frame1->isDrawable();
  print("\n - Vérification de sa capacité a être dessinée : ".($frame1Drawable ? "OK" : "FAILED"));
  $frame1PageId = $frame1->getPageId();
  print("\n - Vérification de l'identifiant de sa page : ".($frame1PageId === $newPageId ? "OK" : "FAILED"));
  $frame1OwnerId = $frame1->getOwnerId();
  print("\n - Vérification de l'identifiant de son créateur : ".($frame1OwnerId === $newUserId ? "OK" : "FAILED"));
} else {
  print("FAILED");
}
print("\n\n");

$removed = $frameDAO->removeFrame($newFrameId);
print("Suppression d'une frame : ".($removed ? "OK" : "FAILED")."\n");
$removed = $pageDAO->removePage($newPageId);
print("Suppression d'une page : ".($removed ? "OK" : "FAILED")."\n");
$removed = $userDAO->removeUser($newUserId);
print("Suppression d'une utilisateur : ".($removed ? "OK" : "FAILED")."\n");

?>
