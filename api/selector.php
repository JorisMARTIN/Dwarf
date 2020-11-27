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
        $page = $pageDAO->getRandomPage();
        $id = $page ? $page->getId() : -1;
        $loadedIds = array($id);
    } else {
        if ($direction == -1 && $index <= 0) {
            $index = 0;
            $page = $pageDAO->getRandomPage();
            $id = $page ? $page->getId() : -1;
            array_unshift($loadedIds, $id);
        } else if ($direction == 1 && $index >= $length) {
            $index = $length;
            $page = $pageDAO->getRandomPage();
            $id = $page ? $page->getId() : -1;
            array_push($loadedIds, $id);
        } else {
            $index += $direction;
            $id = $loadedIds[$index];
            $page = $pageDAO->getPage($id);
        }
    }

    $out = [
        'loadedIds' => $loadedIds,
        'index' => $index,
        'page' => NULL
    ];

    if ($page) {
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