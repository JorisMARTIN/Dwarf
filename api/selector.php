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
    $direction = $data->direction;
    $index = $data->index;

    $length = count($loadedIds);
    $i = $index + $direction;

    if (isset($loadedIds[$i])) {
        $page = $pageDAO->getPage($loadedIds[$i]);
        $index = $i;
    } else {
        $page = $pageDAO->getRandomPage($loadedIds);
        if ($page != NULL) {
            
            $id = $page->getId();

            if($direction == -1 && $i == 0) {
                $index = 0;
                array_unshift($loadedIds, $id);
            } else if($direction == 1 && $i >= $length) {
                $index = $length;
                array_push($loadedIds, $id);
            }
            // $loadedIds = array_values($loadedIds);
            // $index = array_search($id, $loadedIds);
        } // ptet gérer le fait que yai plus rien de nouveau à scroller
    }


    // if (empty($loadedIds)) {
    //     $index = 0;
    //     $page = $pageDAO->getRandomPage();
    //     $id = $page ? $page->getId() : -1;
    //     $loadedIds = array($id);
    // } else {

    //     if ($direction == -1 && $index <= 0) {
    //         $index = 0;
    //         $page = $pageDAO->getRandomPage();
    //         $id = $page ? $page->getId() : -1;
    //         array_unshift($loadedIds, $id);
    //     } else if ($direction == 1 && $index >= $length) {
    //         $index = $length;
    //         $page = $pageDAO->getRandomPage();
    //         $id = $page ? $page->getId() : -1;
    //         array_push($loadedIds, $id);
    //     } else {
    //         $index += $direction;
    //         $id = $loadedIds[$index];
    //         $page = $pageDAO->getPage($id);
    //     }
    // }

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
