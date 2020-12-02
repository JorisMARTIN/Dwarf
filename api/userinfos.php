<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');

$userDAO = new UserDAO();

// Get current user
$userId = tokenToUserId();

if($userId != -1){

    $user = $userDAO->getUser($userId);
    
    
    // Users informations
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

}else{
    $out = [
        'status' => 400,
        'message' => 'You are not logged in'
    ];
}

echo json_encode($out);

?>