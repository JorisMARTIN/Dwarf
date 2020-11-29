<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
// Renvoie une liste de BDs

$pageDAO = new PageDAO();
$frameDAO = new FrameDAO();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    $loadedIds = $data->loadedIds;
    $index = $data->index;

    if (isset($loadedIds[$index])) {
        $page = $pageDAO->getPage($loadedIds[$index]);
    } else {
        $page = $pageDAO->getRandomPage($loadedIds);

        if ($page != NULL) {
            
            $id = $page->getId();
            $length = count($loadedIds);

            if($index < 0) {
                $index = 0;
                array_unshift($loadedIds, $id);
            } else if($index >= $length) {
                $index = $length;
                array_push($loadedIds, $id);
            }
        } // ptet gérer le fait que yai plus rien de nouveau à scroller
    }

    $out = [
        'loadedIds' => $loadedIds,
        'index' => $index,
        'page' => NULL
    ];

    //todo : envoyer imagePtr de l'image de référence de l'image à dessiner
    // et le frameId / frameWidth / frameHeight de l'image à dessiner
    if ($page) {
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
