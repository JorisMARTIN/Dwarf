<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
// Renvoie une liste de BDs

$userDAO = new UserDAO();
$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    if ($data->lastPageLoadedId == -1) {
        $lastId = ($pageDAO->getLastPageId() == -1) ? 0 : $pageDAO->getLastPageId();
    } else {
        $lastId = ($data->lastPageLoadedId < 1) ? 0 : $data->lastPageLoadedId - 1;
    }

    $pages = $pageDAO->getNPages(6, $lastId);

    $endReached = count($pages) != 6;


    // Check if the current user is connected and is admin

    $userId = tokenToUserId();

    if ($userId == -1) $userIsAdmin = false;
    else {
        $userIsAdmin = $userDAO->getUser($userId)->isAdmin();
    }

    $out = [
        'endReached' => $endReached,
        'pages' => [],
        'userIsAdmin' => $userIsAdmin
    ];

    for ($i = 0; $i < count($pages); $i++) {
        $p = $pages[$i];

        $user = $userDAO->getUser($p->getOwnerId());

        $images = [];
        $authors = [$user->getNickname()];

        $frames = $frameDAO->getFrames($p->getId());
        foreach ($frames as $frame) {
            $images[] = $frame->getImagePtr();
            $authors[] = $userDAO->getUser($frame->getOwnerId())->getNickname();
        }

        $out['pages'][$i] = [
            'pageId' => $p->getId(),
            'name' => $p->getName(),
            'description' => $p->getDescription(),
            'gamemode' => ($p->getGameMode() == 0 ? "Normal" : "Reverse"),
            'date' => $p->getCreationDate(),
            'images' => $images,
            'authors' => $authors,
            'template' => $p->getTemplateType(),
        ];
    }
} else {
    $out = [];
}

echo json_encode($out);
