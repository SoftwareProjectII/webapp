<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 26/11/2017
 * Time: 22:25
 */
require_once "iniSettings.php";

//starts session and checks if a user is logged in, if not: redirect to login page

//force https: https://blog.flaunt7.com/force-https-php-htaccess/
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

//secure session cookies
session_set_cookie_params(0,"/",$_SERVER['SERVER_NAME'],true,true);
session_start();

//regenerate id every 3 minutes against session fixation attacks
//https://paragonie.com/blog/2015/04/fast-track-safe-and-secure-php-sessions
if (!isset($_SESSION['canary'])) {
    session_regenerate_id(true);
    $_SESSION['canary'] = time();
}

if ($_SESSION['canary'] < time() - 180) {
    session_regenerate_id(true);
    $_SESSION['canary'] = time();
}

//log user out if no token present
if (!isset($_SESSION["token"])) {
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    header("Location: index.php");
    exit();
}