<?php
    /**
     * Created by PhpStorm.
     * User: driesc
     * Date: 09/11/2017
     * Time: 16:55
     */


//    $username = $_POST["username"];
    $username = "russellwhyte";
    $password = $_POST["password"];


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
    $result = json_decode($jsonstring);

    if ($result == false || $result == NULL) {
        die('error: decode = false or null');
    }

    var_export($result);



