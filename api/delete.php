<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/DeletePageDAO.class.php');

$deletePageDAO = new DeletePageDAO();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    $userId = tokenToUserId();
    $pageId = $data->pageId;
    $action = $data->action;

    // Cas pour supprimer un page
    if($action == 'delete'){
        $reason = $data->reason;
        
        $deletePageDAO->putDeletePage($pageId, $userId, $reason);
        $out = ['message' => 'Page deleted'];

    // Cas pour restaurer une page
    }else if($action == 'unDelete'){

        $deletePageDAO->removeDeletePage($pageId);
        $out = ['message' => 'Page unDeleted'];
    // Cas ou l'action est non spécifier ou non valid
    }else{
        $out = ['message' => 'Action non-valid'];
    }

} else {
    $out = ['message' => 'Error !'];
}

echo json_encode($out);