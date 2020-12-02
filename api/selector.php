<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
// Renvoie une liste de BDs

$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data) && isset($data->loadedIds)) {
    $loadedIds = $data->loadedIds;

    $out = [
        'loadedIds' => $loadedIds,
        'index' => $index,
        'page' => NULL
    ];

    $page = $pageDAO->getRandomPage($loadedIds);

    if($page == NULL) $page = $pageDAO->getRandomPage();
    
    if ($page != NULL) {
        array_push($loadedIds, $page->getId());
        //todo : envoyer imagePtr de l'image de référence de l'image à dessiner
        //et le frameId / frameWidth / frameHeight de l'image à dessiner

        

        $out['page'] = [
            'name' => $page->getName(),
            'description' => $page->getDescription(),
            'gamemode' => ($page->getGameMode() == 0 ? "Normal" : "Reverse"),
            'date' => $page->getCreationDate(),
            'imagePtr' => $frameDAO->getFrames($page->getId())[0]->getImagePtr()
        ];
    }
    

    
} else {
    $out = [];
}

echo json_encode($out);
