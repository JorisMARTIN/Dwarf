<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');

$userId = tokenToUserId();

if ($userId != -1) {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data)) {
        $frameid = (int) ($data->frameid ?? -1);

        $frameDAO = new FrameDAO();

        $frame = $frameDAO->getFrame($frameid);

        if ($frame != NULL && $frame->isDrawable() && $frame->isFree()) {
            if($frameDAO->claim($frameid)) {
                $out = [
                    'status' => 200
                ];
            } else {
                $out = [
                    'status' => 400,
                    'message' => "Failed to claim $frameid"
                ];
            }
        } else {
            $out = [
                'status' => 400,
                'message' => 'Invalid frameid'
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
