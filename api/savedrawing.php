<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');
require_once(dirname(__FILE__) . '/model/AuthMethods.php');

require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');

$userId = tokenToUserId();

if ($userId != -1) {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data)) {

        $frameid = (int) ($data->frameid ?? -1);
        $image_base64 = ($data->img ?? "");

        $frameDAO = new FrameDAO();

        $frame = $frameDAO->getFrame($frameid);

        if ($frame != NULL) {
            // TODO : check si user a bien le droit de dessiner la frame
            $imagePtr = $frame->getImagePtr();
            $imagePath = dirname(__FILE__, 2).$imagePtr;

            $frameDAO->setDone($frameid, true);

            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_base64));
            
            $file = fopen($imagePath, "w");

            if ($image && $file
                && chmod($imagePath, 0774)
                && fwrite($file, $image)
                && fclose($file)
            ) {
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
