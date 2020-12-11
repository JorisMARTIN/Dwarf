<?php

require_once(dirname(__FILE__).'/../model/FrameDAO.class.php');
require_once(dirname(__FILE__).'/../model/UserDAO.class.php');
require_once(dirname(__FILE__).'/../model/PageDAO.class.php');
require_once(dirname(__FILE__).'/../includes/debug.inc.php');
require_once(dirname(__FILE__).'/../model/RateDAO.class.php');

$userDAO = new UserDAO();
$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();
$rateDAO = new RateDAO();

$newUserId = $userDAO ->putUser('test.test@gmail.com', 'test-nickname', 'test-password', '192.168.1.1', "2000-01-01");
print("Création d'un utilisateur : ".($newUserId != -1 ? "OK" : "FAILED ($newUserId)")."\n");
$newPageId = $pageDAO->putPage('test-page', 'test-description', 0, 0, false, $newUserId);
print("Création d'une planche : ".($newPageId != -1 ? "OK" : "FAILED ($newPageId)")."\n");
$newFrameId = $frameDAO->putFrame(True, False, 100, 100, $newPageId, $newUserId);
print("Création d'une frame : ".($newFrameId != -1 ? "OK" : "FAILED ($newFrameId)")."\n");
$isRateCreated = $rateDAO ->putVote($newUserId, $newPageId, true);
print("Création d'un vote : " . ($isRateCreated ? "OK" : "FAILED")."\n");
print("\n");

try {
  $user1 = $userDAO->getUser($newUserId);
  print("Récupération d'un utilisateur (getUser) : ");
  if ($user1) {
    print("OK");
    $user1Id = $user1->getId();
    print("\n - Vérification de son identifiant : ".($user1Id === $newUserId ? "OK" : "FAILED"));
    $user1NickName = $user1->getNickName();
    print("\n - Vérification de son pseudo : ".($user1NickName === "test-nickname" ? "OK" : "FAILED"));
    $user1Email = $user1->getEmail();
    print("\n - Vérification de son email : ".($user1Email === "test.test@gmail.com" ? "OK" : "FAILED"));
    $user1CreationDate = $user1->getCreationDate();
    print("\n - Vérification de sa date de création : ".($user1CreationDate > "2000-01-01" ? "OK" : "FAILED"));
    $user1BirthDate = $user1->getBirthDate();
    print("\n - Vérification de sa date de création : ".($user1BirthDate == "2000-01-01" ? "OK" : "FAILED"));
    $user1Ip = $user1->getIps()[0];
    print("\n - Vérification de son adresse ip : ".($user1Ip === '192.168.1.1' ? "OK" : "FAILED"));
    $user1isAdmin = $user1->isAdmin();
    print("\n - Vérification de son statut : ".(!$user1isAdmin ? "OK" : "FAILED"));
    $user1AjoutIp = $userDAO->addIp($newUserId, '192.168.1.42');
    print("\n - Ajout d'une adresse IP : ");
    if ($user1AjoutIp) {
      $user1 = $userDAO->getUser($newUserId);
      print($user1->getIps()[1] === '192.168.1.42' ? "OK" : "FAILED");
    } else {
      print("FAILED");
    }
    $user1login = $userDAO->logUser($user1->getEmail(), 'test-password');
    print("\n - Authentification d'un utilisateur : ".($user1login != -1 ? "OK" : "FAILED"));
  } else {
    print("FAILED");
  }
  print("\n\n");

  $page1 = $pageDAO->getUserPages($newUserId)[0];
  print("Récupération d'une planche depuis un utilisateur (getUserPages) : ");
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
    $completed = $pageDAO->setCompleted($newPageId, true);
    print("\n - modification de sa complétion : ");
    if ($completed) {
      $page1Completed2 = $page1->isCompleted();
      print(($page1Completed2 ? "OK" : "FAILED"));
    } else {
      print("FAILED");
    }
  } else {
    print("FAILED");
  }
  print("\n");
  $page2 = $pageDAO->getPage($newPageId);
  print("Récupération d'une planche (getUser) : ");
  if ($page2) {
    print("OK");
  } else {
    print("FAILED");
  }
  $lastPageId = $pageDAO->getLastPageId();
  print("Id de la dernière page : ");
  if ($newPageId = $lastPageId) {
    print("OK");
  } else {
    print("FAILED");
  }
  $newPageId2 = $pageDAO->putPage('test-page2', 'test-description2', 0, 0, false, $newUserId);
  $newPageId3 = $pageDAO->putPage('test-page3', 'test-description3', 0, 0, false, $newUserId);
  $newPageId4 = $pageDAO->putPage('test-page4', 'test-description4', 0, 0, false, $newUserId);
  $newPageId5 = $pageDAO->putPage('test-page5', 'test-description5', 0, 0, false, $newUserId);
  $pages = $pageDAO->getNPages(5, $newPageId);
  print("Récupération de 5 pages : ");
  if (count($pages) === 5) {
    print("OK");
  } else {
    print("FAILED");
  }
  $page = $pageDAO->getRandomPage([]);
  print("Récupération d'une page aléatoire : ");
  if ($page) {
    print("OK");
  } else {
    print("FAILED");
  }
  $page = $pageDAO->getRandomPage(range(0, $lastPageId));
  print("Récupération d'une page aléatoire : ");
  if (!$page) {
    print("OK");
  } else {
    print("FAILED");
  }
  print("\n\n");


  $frame1 = $frameDAO->getFrames($newPageId)[0];
  print("Récupération d'une frame : ");
  if ($frame1) {
    print("OK");
    $frame1Id = $frame1->getId();
    print("\n - Vérification de son identifiant : ".($frame1Id === $newFrameId ? "OK" : "FAILED"));
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
  print("\n");
  $frame2 = $frameDAO->getFrame($newFrameId);
  print("Récupération d'une frame (getFrame) : ");
  if ($frame2) {
    print("OK");
  } else {
    print("FAILED");
  }
  print("\n\n");

  $rate1 = $rateDAO->getUserVotes($newUserId)[0];
  print("Récupération d'un vote (getUserVotes) : ");
  if ($rate1) {
    print("OK");
    $rate1PageId = $rate1->getPageId();
    print("\n - Vérification de l'identifiant de la page : ".($rate1PageId === $newPageId ? "OK" : "FAILED"));
    $rate1UserId = $rate1->getUserId();
    print("\n - Vérification de l'identifiant de l'utilisateur : ".($rate1UserId === $newUserId ? "OK" : "FAILED"));
    $rate1Vote = $rate1->getVote();
    print("\n - Vérification du vote : ".($rate1Vote ? "OK" : "FAILED"));
  } else {
    print("FAILED");
  }
  print("\n\n");
} catch (Exception $e) {
  echo 'Exception : ',  $e->getMessage(), "\n";
} catch (Throwable $e) {
  echo 'Levée : ',  $e->getMessage(), "\n";
} catch (Error $e) {
  echo 'Erreur : ',  $e->getMessage(), "\n";
}

$removed = $rateDAO->removeVote($newPageId, $newUserId);
print("Suppression d'un vote : ".($removed ? "OK" : "FAILED")."\n");
$removed = $frameDAO->removeFrame($newFrameId);
print("Suppression d'une frame : ".($removed ? "OK" : "FAILED")."\n");
$removed = $pageDAO->removePage($newPageId);
print("Suppression d'une page : ".($removed ? "OK" : "FAILED")."\n");
$pageDAO->removePage($newPageId2);
$pageDAO->removePage($newPageId3);
$pageDAO->removePage($newPageId4);
$pageDAO->removePage($newPageId5);
$removed = $userDAO->removeUser($newUserId);
print("Suppression d'une utilisateur : ".($removed ? "OK" : "FAILED")."\n");


?>
