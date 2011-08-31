<?php
ob_clean();
header("Content-Type: application/force-download; name=\"" . $file['Document']['name'] . "\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$file['Document']['size']);
header("Content-Disposition: attachment; filename=\"" . $file['Document']['name'] . "\"");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
print gzuncompress($file['Document']['data']);
exit();
?>