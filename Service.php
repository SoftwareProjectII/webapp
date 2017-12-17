<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 16/11/2017
 * Time: 19:35
 */

class Service
{
    static $ip = "10.3.50.22";
    //get data form dataservice
    //returns data as php array
    //TODO: if http code unauthorized: logout
    public static function get($location) {
        $ip = self::$ip;
        $url = "{$ip}/api/{$location}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);
        // set http header token for authorization
        if (!isset($_SESSION["token"])) {
            /*header("Location: index.php");
            exit();*/
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Bearer {$_SESSION["token"]}"]);
        }
        $jsonstring = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //201: created, 409: conflict, 500: internal server error (bad data), 404: not found,  401: foute credentials, 200: OK, 400: foute variabelen

        curl_close($ch);

        //catch request error
        if ($jsonstring === false || $jsonstring == "") {
            return false;
        }

        $result = json_decode($jsonstring, true);

        if ($result == false || $result == NULL) {
            return false;
        }

        if ($httpcode == 200 || $httpcode == 201 || $httpcode == 204) {
            return $result;
        } else {
            return false;
        }
    }

    public static function post($location, $curl_post_data) {
        $ip = self::$ip;
        $data = json_encode($curl_post_data);
        $url = "{$ip}/api/{$location}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/json']);

        // set http header token for authorization
        if (!isset($_SESSION["token"])) {
            //TODO: if token invalid, refresh?
            /*header("Location: index.php");
            exit();*/
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Bearer {$_SESSION["token"]}", 'Content-Type: application/json']);
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);
        $jsonstring = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //201: created, 409: conflict, 500: internal server error (bad data), 404: not found,  401: foute credentials, 200: OK, 400: foute variabelen

        curl_close($ch);

        //catch request error
        if ($jsonstring === false || $jsonstring == "") {
            return false;
        }

        $result = json_decode($jsonstring, true);

        if ($result == false || $result == NULL) {
            return false;
        }

        if ($httpcode == 200 || $httpcode == 201 || $httpcode == 204) {
            return $result;
        } else {
            return false;
        }
    }

    public static function put($location, $curl_put_data) {
        $ip = self::$ip;
        $data = json_encode($curl_put_data);
        $url = "{$ip}/api/{$location}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/json']);
        // set http header token for authorization
        if (!isset($_SESSION["token"])) {
            /*header("Location: index.php");
            exit();*/
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Bearer {$_SESSION["token"]}", 'Content-Type: application/json']);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);

        $result = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //201: created, 409: conflict, 500: internal server error (bad data), 404: not found,  401: foute credentials, 200: OK, 400: foute variabelen

        curl_close($ch);

        if ($httpcode == 204) {
            return true;
        } else {
            return false;
        }
    }
}