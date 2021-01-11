<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');

require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
require_once(dirname(__FILE__) . '/model/DeletePageDAO.class.php');


$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    
    $userDAO = new UserDAO();

    $user = $userDAO->getUser(tokenToUserId());
    $action = $data->action;

    if ($user->isAdmin()) {
        if($action == "getDeletedPages"){

            $pageDAO = new PageDAO();
            $deletePageDAO = new DeletePageDAO();
            $frameDAO = new FrameDAO();

            $pages = $pageDAO->getDeletedPages();

            $out = [
                'isAdmin' => 'true',
                'deletedPages' => []
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


                $infos = $deletePageDAO->getDeletePageInfos($p->getId());
                $deleteUser = $userDAO->getUser($infos->getUserIdWhoDelete())->getNickname();
                $deleteReason = $infos->getReason();
                $deleteDate = $infos->getDeleteDate();

                $out['deletedPages'][$i] = [
                    'pageId' => $p->getId(),
                    'name' => $p->getName(),
                    'description' => $p->getDescription(),
                    'gamemode' => ($p->getGameMode() == 0 ? "Normal" : "Reverse"),
                    'date' => $p->getCreationDate(),
                    'images' => $images,
                    'authors' => $authors,
                    'template' => $p->getTemplateType(),
                    'deleteInfos' => [
                        'userWhoDelete' => $deleteUser,
                        'reason' => $deleteReason,
                        'date' => $deleteDate
                    ]
                ];
            }
        }
    } else {
        $out = [
            'isAdmin' => 'false',
            'deletedPages' => []
        ];
    }
} else {
    $out = [];
}

echo json_encode($out);
