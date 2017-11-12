<?php
    /**
     * Created by PhpStorm.
     * User: driesc
     * Date: 09/11/2017
     * Time: 16:55
     */

    session_start();

    //$username = $_POST["username"];
    //$password = $_POST["password"];
    //testdata
    $username = "russellwhyte";
    $password = "Russell";

    $url = "http://services.odata.org/V4/(S(idjblwsbvsvghxmo1fqouvau))/TripPinServiceRW/People('{$username}')";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $jsonstring = curl_exec($ch);

    if ($jsonstring === false) {
        $info = curl_getinfo($ch);
        curl_close($ch);
        die('curl_exec error, more info: ' . var_export($info));
    }

    curl_close($ch);

    //catch username not in database
    if ($jsonstring === "") {
        Print '<script>alert("Username not found!");</script>';
        Print '<script>window.location.assign("index.php");</script>';
    }

    $result = json_decode($jsonstring, true);

    if ($result == false || $result == NULL) {
        die('error: json_decode = false or null');
    }

    $pass = $result["FirstName"];

    if ($pass == $password) {
        echo 'login succesful';
        $_SESSION['user'] = $username;
        header("location: availableTraining.php");
    } else {
        Print '<script>alert("Incorrect password!");</script>';
        Print '<script>window.location.assign("index.php");</script>';
    }
?>




