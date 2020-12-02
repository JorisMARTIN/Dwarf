<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
// Renvoie une liste de BDs

$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

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

        $frames = $frameDAO->getFrames($pageId);
        if ($page->getGameMode() == 0) {
            $i = 0;
            while($frames[$i] && $frames[$i]->isDone()) $i++;
            $refIndex = $i - 1;
        } else if ($page->getGameMode() == 1) {
            $i = count($frames) - 1;
            while ($frames[$i] && $frames[$i]->isDone()) $i--;
            $refIndex = $i + 1;
        }

        $frame = $frameDAO->getFrame($frames[$i]->getId());
        $imagePtr = $frames[$refIndex]->getImagePtr();

        $out['page'] = [
            'name' => $page->getName(),
            'description' => $page->getDescription(),
            'gamemode' => ($page->getGameMode() == 0 ? "Normal" : "Reverse"),
            'date' => $page->getCreationDate(),
            'imagePtr' => $imagePtr,
            'frameId' => $frame->getId(),
            'frameWidth' => $frame->getWidth(),
            'frameHeight' => $frame->getHeight()
        ];
    }
} else {
    $out = [];
}

echo json_encode($out);
