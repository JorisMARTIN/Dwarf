<?php
require_once(dirname(__FILE__) . '/includes/endpoint.inc.php');

require_once(dirname(__FILE__).'/model/AuthMethods.php');
require_once(dirname(__FILE__).'/model/PageDAO.class.php');
require_once(dirname(__FILE__).'/model/UserDAO.class.php');

$userId = tokenToUserId();

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
        'name' => $p->name,
        'description' => $p->description,
    ];
}

if($userId != -1) {
    $userDAO = new UserDAO();

    $data['loggedInContent'] = "Hello, " . $userDAO->getUser($userId)->nickname . " !";
}

echo json_encode($data);