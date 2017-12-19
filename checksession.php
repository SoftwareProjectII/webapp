<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 26/11/2017
 * Time: 22:25
 */

//starts session and checks if a user is logged in, if not: redirect to login page

session_start();

//TODO: refresh token?
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

//force https
if($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}