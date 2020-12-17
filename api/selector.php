<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
// Renvoie une liste de BDs

$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();
$userDAO = new UserDAO();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data) && isset($data->loadedIds)) {
    $loadedIds = $data->loadedIds;

    $out = [
        'loadedIds' => $loadedIds,
        'page' => NULL
    ];

    $page = $pageDAO->getRandomPage($loadedIds);

    if ($page == NULL && !empty($loadedIds)) $page = $pageDAO->getRandomPage([end($loadedIds)]);

    if ($page != NULL) {
        $pageId = $page->getId();
        array_push($loadedIds, $pageId);
        $out['loadedIds'] = $loadedIds;

        $out['page'] = [
            'name' => $page->getName(),
            'description' => $page->getDescription(),
            'gamemode' => ($page->getGameMode() == 0 ? "Normal" : "Reverse"),
            'date' => $page->getCreationDate(),
            'user' => $userDAO->getUser($page->getOwnerId())->getNickname(),
            'imagePtr' => NULL,
            'frameId' => NULL,
            'frameWidth' => NULL,
            'frameHeight' => NULL
        ];

        $frames = $frameDAO->getFrames($pageId);
        if ($page->getGameMode() == 0) {
            $i = 0;
            while (isset($frames[$i]) && $frames[$i]->isDone()) $i++;
            $refIndex = $i - 1;
        } else if ($page->getGameMode() == 1) {
            $i = count($frames) - 1;
            while (isset($frames[$i]) && $frames[$i]->isDone()) $i--;
            $refIndex = $i + 1;
        }

        if(array_key_exists($i, $frames)) {
            $frame = $frames[$i];

            if (array_key_exists($refIndex, $frames)) {
                $out['page']['imagePtr'] = $frames[$refIndex]->getImagePtr();
                $out['page']['frameAuthor'] = $userDAO->getUser($frames[$refIndex]->getOwnerId())->getNickname();
            }

            $out['page']['frameId'] = $frame->getId();
            $out['page']['frameWidth'] = $frame->getWidth();
            $out['page']['frameHeight'] = $frame->getHeight();
        }
    }
} else {
    $out = [];
}

echo json_encode($out);
