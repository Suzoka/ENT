<?php
session_start();
if (isset($_SESSION["login"])) {
    $filename = basename("cs".$_SESSION["login"]);
    $file = '../docs/cs/'.$filename.'.txt';
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
?>