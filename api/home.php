<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__).'/model/PageDAO.class.php');

// Renvoie une liste de BDs

$pageDAO = new PageDAO();

$lastId = $pageDAO->getLastPageId();
$pages = $pageDAO->getRangeOfPages($lastId - 5, $lastId);

$data = [
    'pages' => []
];

for ($i = 0; $i < count($pages); $i++) {
    $p = $pages[$i];
    $data['pages'][$i] = [
        'name' => $p->getName(),
        'description' => $p->getDescription(),
    ];
}

echo json_encode($data);