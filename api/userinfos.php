<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
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


    // User Pages
    $pages = $pageDAO->getUserPages($userId);

    $out = [
        'userid' => $userId,
        'email' => $email,
        'nickname' => $nickname,
        'creationdate' => $creationDate,
        'birthdate' => $birthdate,
        'isadmin' => $isAdmin
    ];

    //Get pages where user is the creator
    // $pages = $pageDAO->getUserPages($userId);
    // array_push($out, $pages);

    //Get pages where user participate
    // $frames = $frameDAO->getUserFrames($userId);
    // array_push($out, $frames);

}else{
    $out = [
        'status' => 400,
        'message' => 'You are not logged in'
    ];
}
echo json_encode($out);

?>