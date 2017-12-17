<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 07/12/2017
 * Time: 18:26
 */

session_start();

if (isset($_SESSION["userId"])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

?>