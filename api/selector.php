<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
// Renvoie une liste de BDs

$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data)) {
    $loadedIds = $data->loadedIds;
    $direction = $data->direction;
    $length = count($loadedIds);

    if ($length == 0) {
        $index = 0;
        $id = $pageDAO->getRandomPageId();
        $loadedIds = array($id);
    } else {
        $index = max(0, $data->index + $direction);
        if ($direction == -1 && $index <= 0) {
            $index = 0;
            $id = $pageDAO->getRandomPageId();
            array_unshift($loadedIds, $id);
        } else if ($direction == 1 && $index >= $length) {
            $index = $length;
            $id = $pageDAO->getRandomPageId();
            array_push($loadedIds, $id);
        } else {
            $index += $direction;
            $id = $loadedIds[$index];
        }
    }


    $result = $pageDAO->getNPages(1, $index);

    $out = [
        'loadedIds' => $loadedIds,
        'index' => $index,
        'page' => $page
    ];

    if (count($result) == 0) {
        $out['page'] = NULL;
    } else {
        $page = $result[0];
        $out['page'] = [
            'name' => $page->getName(),
            'description' => $page->getDescription(),
            'gamemode' => ($page->getGameMode() == 0 ? "Normal" : "Reverse"),
            'date' => $page->getCreationDate(),
            'imagePtr' => $frameDAO->getFrames($page->getId())[0]->getImagePtr()
        ];
    }


} else {
    $out = [];
}

echo json_encode($out);