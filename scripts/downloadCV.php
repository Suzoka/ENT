<?php
if (isset($_GET['file'])) {
    $filename = basename($_GET['file']);
    $file = '../docs/cv/'.$filename.'.pdf';
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
?>