<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/DeletePageDAO.class.php');

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    
    $userDAO = new UserDAO();
    
    $user = $userDAO->getUser(tokenToUserId());
    $pageId = $data->pageId;
    $action = $data->action;
    
    if($user->isAdmin()){

        $deletePageDAO = new DeletePageDAO();
        $pageDAO = new PageDAO();

        switch($action){

            // Suppression d'une page
            case 'delete':
                $reason = $data->reason;

                $deletePageDAO->putDeletePage($pageId, $userId, $reason);
                $out = ['message' => 'Page suprimée'];

            break;

            // Restauration d'une page supporimée
            case 'unDelete':
                $deletePageDAO->removeDeletePage($pageId);
                $out = ['message' => 'Page restaurer'];
            break;

            // Suppression définitive d'une page supprimée
            case 'erase':
                if($pageDAO->removePage($pageId)){
                    $out = ['message' => 'Suppression définitive éxécutée'];
                }else{
                    $out = ['message' => 'Erreur dans la suppression définitive de la page'];
                }
            break;

            default:
                $out = ['message' => 'Action non-valid'];
            break;
        }

    }else{
        $out = ['message' => 'Vous n\'avez pas la permition de faire cela !'];
    }


} else {
    $out = ['message' => 'Error aucune données passée en entré !'];
}

echo json_encode($out);