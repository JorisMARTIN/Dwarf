<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
// Renvoie une liste de BDs

$pageDAO = new PageDAO();

$data = json_decode(file_get_contents("php://input"));

$lastId = ($data->lastPageLoadedId == -1) ? $pageDAO->getLastPageId() : $data->lastPageLoadedId;

if ($data->lastPageLoadedId == -1) {
    $nextId = $lastId;
} else {
    $nextId = ($lastId - 1 <= 1) ? 1 : $lastId - 1;
}

$willReachEnd = $nextId - 5 < 1;

if ($willReachEnd) {
    $numberToLoad = 0;
    while ($nextId - $numberToLoad >= 2) {
        $numberToLoad++;
    }
} else {
    $numberToLoad = 5;
}

if ($nextId != 1) {
    $pages = $pageDAO->getRangeOfPages($nextId - $numberToLoad, $nextId);
} else {
    $page = [];
}

$lastId = $nextId - $numberToLoad;

$data = [
    'lastPageLoadedId' => $lastId,
    'endReached' => ($lastId == 1),
    'pages' => [],
];

for ($i = 0; $i < count($pages); $i++) {
    $p = $pages[$i];
    $data['pages'][$i] = [
        'name' => $p->getName(),
        'description' => $p->getDescription(),
        'gamemode' => ($p->getGameMode() == 0 ? "Normal" : "Reverse"),
        'date' => $p->getCreationDate(),
    ];
}

echo json_encode($data);
