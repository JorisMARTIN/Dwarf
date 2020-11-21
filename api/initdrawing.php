<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');
require_once(dirname(__FILE__) . '/model/AuthMethods.php');

require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');

$TEMPLATES = [
    [
        [
            'w' => 100,
            'h' => 100
        ], [
            'w' => 100,
            'h' => 100
        ], [
            'w' => 100,
            'h' => 100
        ], [
            'w' => 100,
            'h' => 100
        ]
    ]
];

$userId = tokenToUserId();

if ($userId != -1) {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data)) {
        $title = (string) ($data->title ?? "Comic page");
        $description = (string) ($data->description ?? "Default description");
        $gamemode = (int) ($data->gamemode ?? 0);
        $template = (int) ($data->template ?? 0);

        $pageDAO = new PageDAO();
        $frameDAO = new FrameDAO();

        $pageId = $pageDAO->putPage($title, $description, $gamemode, $template, false, $userId);

        if ($pageId != -1) {
            $tmpSize = count($TEMPLATES[$template]);
            for ($i = 0; $i < $tmpSize; $i++) {
                $box = $TEMPLATES[$template][$i];
                $fid = $frameDAO->putFrame(true, false, $box['w'], $box['h'], $pageId, $userId);
                if ($gamemode == 0) {
                    if ($i == 0) {
                        $frameId = $fid;
                    }
                } elseif ($gamemode == 1) {
                    if ($i == $tmpSize) {
                        $frameId = $fid;
                    }
                }
            }
            
            $page = $pageDAO->getPage($pageId);

            $out = [
                'status' => 200,
                'frameId' => $frameId,
                'pageName' => $page->getName(),
                'gameMode' => $page->getGameMode(),
                'description' => $page->getDescription()
            ];
        } else {
            $out = [
                'status' => 400,
                'message' => 'Server failed to put in database'
            ];
        }
    } else {
        $out = [
            'status' => 400,
            'message' => 'No data provided'
        ];
    }
} else {
    $out = [
        'status' => 400,
        'message' => 'You are not logged in'
    ];
}

echo json_encode($out);
