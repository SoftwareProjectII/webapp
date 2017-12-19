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

// destroy session redirect to login page
// http://php.net/manual/en/function.session-destroy.php
// Unset all of the session variables.
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