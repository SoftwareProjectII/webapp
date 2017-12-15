<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 14/12/2017
 * Time: 12:15
 */
require_once "checksession.php";
require_once "Service.php";

//if sign in button pushed => compile data and post to dataservice

if (isset($_POST["trainingSessionId"]) && isset($_SESSION["userId"]) && isset($_SESSION["hasManager"])) {
    switch ($_POST["action"]) {
        case "signin":
            signIn($_POST["trainingSessionId"], $_SESSION["userId"], $_SESSION["hasManager"]);
            break;
        case "cancelsignin":
            cancelSignIn($_POST["trainingSessionId"], $_SESSION["userId"]);
            break;
        case "signout":
            signOut($_POST["trainingSessionId"], $_SESSION["userId"], $_SESSION["hasManager"]);
            break;
        case "cancelsignout":
            cancelSignOut($_POST["trainingSessionId"], $_SESSION["userId"]);
            break;
    }
    redirect();
}

function redirect() {
    header("location: {$_POST["breadcrumb"]}");
    exit();
}

function signIn($TSId, $userId, $hasManager) {
    if ($hasManager == false) {
        $curl_post_data = [
            "userid" => $userId,
            "trainingsessionid" => $TSId,
            "isApproved" => true,
            "isCancelled" => false,
            "isDeclined" => false
        ];
    } else if ($hasManager == true) {
        $curl_post_data = [
            "userid" => $userId,
            "trainingsessionid" => $TSId,
            "isApproved" => false,
            "isCancelled" => false,
            "isDeclined" => false
        ];
    }


    if (Service::get("followingtrainings?userid={$_SESSION["userId"]}&trainingsessionid={$TSId}")) {
        Service::put("followingtrainings?userid={$_SESSION["userId"]}&trainingsessionid={$TSId}", $curl_post_data);
    } else {
        Service::post("followingtrainings", $curl_post_data);
    }
}

function cancelSignIn($TSId, $userId) {
    $curl_put_data = [
        "userid" => $userId,
        "trainingsessionid" => $TSId,
        "isApproved" => false,
        "isCancelled" => true,
        "isDeclined" => false
    ];

    Service::put("followingtrainings?userid={$_SESSION["userId"]}&trainingsessionid={$TSId}", $curl_put_data);
}

function signOut($TSId, $userId, $hasManager) {
    if ($hasManager == false) {
        $curl_put_data = [
            "userid" => $userId,
            "trainingsessionid" => $TSId,
            "isApproved" => false,
            "isCancelled" => true,
            "isDeclined" => false
        ];
    } else if ($hasManager == true) {
        $curl_put_data = [
            "userid" => $userId,
            "trainingsessionid" => $TSId,
            "isApproved" => true,
            "isCancelled" => true,
            "isDeclined" => false
        ];
    }

    Service::put("followingtrainings?userid={$_SESSION["userId"]}&trainingsessionid={$TSId}", $curl_put_data);
}

function cancelSignOut($TSId, $userId) {
    $curl_put_data = [
        "userid" => $userId,
        "trainingsessionid" => $_POST["trainingSessionId"],
        "isApproved" => true,
        "isCancelled" => false,
        "isDeclined" => false
    ];

    Service::put("followingtrainings?userid={$userId}&trainingsessionid={$TSId}", $curl_put_data);
}