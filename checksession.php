<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 26/11/2017
 * Time: 22:25
 */

//starts session and checks if a user is logged in, if not: redirect to login page

session_start();

if (!isset($_SESSION["user"]["userid"])) {
    header("Location: index.php");
    exit();
}