<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
// Renvoie une liste de BDs

$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$data = json_decode(file_get_contents("php://input"));

if ($data->lastPageLoadedId == -1) {
    $lastId = ($pageDAO->getLastPageId() == -1) ? 0 : $pageDAO->getLastPageId();
} else {
    $lastId = ($data->lastPageLoadedId < 1) ? 0 : $data->lastPageLoadedId - 1;
}

$pages = $pageDAO->getNPages(6, $lastId);

$lastLoadedId = $pages[count($pages) - 1]->getId();

$endReached = count($pages) != 6;

$data = [
    'lastPageLoadedId' => $lastLoadedId,
    'endReached' => $endReached,
    'pages' => []
];

for ($i = 0; $i < count($pages); $i++) {
    $p = $pages[$i];
    $data['pages'][$i] = [
        'name' => $p->getName(),
        'description' => $p->getDescription(),
        'gamemode' => ($p->getGameMode() == 0 ? "Normal" : "Reverse"),
        'date' => $p->getCreationDate(),
        'imagePtr' => $frameDAO->getFrames($p->getId())[0]->getImagePtr()
    ];
}

echo json_encode($data);
