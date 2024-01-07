<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);

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
        var_dump(readfile($file));
        exit;
    }
}
?>