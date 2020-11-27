<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
// Renvoie une liste de BDs

$userDAO = new UserDAO();
$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data)) {
    if ($data->lastPageLoadedId == -1) {
        $lastId = ($pageDAO->getLastPageId() == -1) ? 0 : $pageDAO->getLastPageId();
    } else {
        $lastId = ($data->lastPageLoadedId < 1) ? 0 : $data->lastPageLoadedId - 1;
    }

    $pages = $pageDAO->getNPages(6, $lastId);

    $lastLoadedId = count($pages) == 0 ? 0 : $pages[count($pages) - 1]->getId();

    $endReached = count($pages) != 6;

    $out = [
        'lastPageLoadedId' => $lastLoadedId,
        'endReached' => $endReached,
        'pages' => []
    ];

    for ($i = 0; $i < count($pages); $i++) {
        $p = $pages[$i];
        $user = $userDAO->getUser($p->getOwnerId());
        $out['pages'][$i] = [
            'name' => $p->getName(),
            'description' => $p->getDescription(),
            'gamemode' => ($p->getGameMode() == 0 ? "Normal" : "Reverse"),
            'date' => $p->getCreationDate(),
            'imagePtr' => $frameDAO->getFrames($p->getId())[0]->getImagePtr(),
            'user' => $user->getNickname()
        ];
    }

} else {
    $out = [];
}

echo json_encode($out);
