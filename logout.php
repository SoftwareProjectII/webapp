<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 07/12/2017
 * Time: 18:26
 */

session_start();
require_once "vendor/autoload.php";
$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

try {
    $easyCSRF->check('CSRFToken', $_GET['CSRFToken']);
}
catch(Exception $e) {
    exit();
}

if (isset($_SESSION["userId"])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

?>