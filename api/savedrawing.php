<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');
require_once(dirname(__FILE__) . '/model/PageDAO.class.php');

$userId = tokenToUserId();

if ($userId != -1) {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data)) {

        $frameid = (int) ($data->frameid ?? -1);
        $image_base64 = ($data->img ?? "");

        $frameDAO = new FrameDAO();
        $pageDAO = new PageDAO();

        $frame = $frameDAO->getFrame($frameid);

        if ($frame != NULL) {
            // TODO : check si user a bien le droit de dessiner la frame
            $imagePtr = $frame->getImagePtr();
            $imagePath = dirname(__FILE__, 2).$imagePtr;

            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_base64));

            $file = fopen($imagePath, "w");

            if ($image && $file
                && chmod($imagePath, 0774)
                && fwrite($file, $image)
                && fclose($file)
            ) {
                $frameDAO->setDone($frameid, true);
                $frameDAO->setDrawable($frameid, false);

                //set next frame to drawable
                $frames = $frameDAO->getFrames($frame->getPageId());

                if ($page->getGameMode() == 0) {
                    $i = 0;
                    while ($frames[$i]->isDone()) $i++;
                } else if ($page->getGameMode() == 1) {
                    $i = count($frames) - 1;
                    while ($frames[$i]->isDone()) $i--;
                }

                if(array_key_exists($i, $frames)) {
                    $frameDAO->setDrawable($frames[$i]->getId(), true);
                } else {
                    $pageDAO->setCompleted($frame->getPageId(), true);
                }

                $out = [
                    'status' => 200,
                ];
            } else {
                $out = [
                    'status' => 400,
                    'message' => 'Server failed to save image'
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
