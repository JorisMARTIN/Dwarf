<?php

require_once(dirname(__FILE__).'/model/FrameDAO.class.php');

$userId = tokenToUserId();

if ($userId != -1) {
  $data = json_decode(file_get_contents("php://input"));

  if (isset($data)) {

    $frameid = (int) ($data->frameid ?? -1);
    $image_base64 = ($data->img ?? "");

    $frameDAO = new FrameDAO();

    $frame = $frameDAO->getFrame($frameid);

    $imagePtr = dirname(__FILE__).'/../cdn/frames/'.$frame->getPageId().'/frame-'.$frameid;

    $frameDAO->setDone($frameid, True);

    $image = base64_decode($image_base64);
    $file = fopen($imagePtr, "w");
    $file->fwrite($image);
    $file->fclose();
  }
}
?>
