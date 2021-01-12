<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');

$userDAO = new UserDAO();
$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

// Get current user
$userId = tokenToUserId();

if($userId != -1){

    $user = $userDAO->getUser($userId);
    
    
    // User informations
    $isAdmin = $user->isAdmin();
    
    $email = $user->getEmail();
    $nickname = $user->getNickname();
    $creationDate = $user->getCreationDate();
    $birthdate = $user->getBirthdate();

    $out = [
        'userid' => $userId,
        'email' => $email,
        'nickname' => $nickname,
        'creationdate' => $creationDate,
        'birthdate' => $birthdate,
        'isadmin' => $isAdmin
    ];

    $userPages = $pageDAO->getUserPagesDone($userId);

    if (!$userPages) {
        $out['pages']=[];
    } else {
        for ($i = 0; $i < count($userPages); $i++) {
            $p = $userPages[$i];

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
    }

    $userDraw = $frameDAO->getUserFrames($userId);
    $i = 0;

    if (!$userDAO) {
        $out['frames']=[];
    } else {
        foreach ($userDraw as $f) {
            $p = $pageDAO->getPage($f->getPageId());

            $out['frames'][$i] = [
                'name' => $p->getName(),
                'description' => $p->getDescription(),
                'gamemode' => ($p->getGameMode() == 0 ? "Normal" : "Reverse"),
                'images' => $f->getImagePtr()
            ];
            
            $i++;
        }
    }
}else{
    $out = [
        'status' => 400,
        'message' => 'You are not logged in'
    ];
}
echo json_encode($out);

?>