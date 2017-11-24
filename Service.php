<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 16/11/2017
 * Time: 19:35
 */

class Service
{

    //get data form dataservice
    //returns FALSE when empty object is retrieved (no data or wrong query)
    //returns data as php array
    public static function get($location) {
        $url = "10.3.50.22/api/{$location}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $jsonstring = curl_exec($ch);

        //catch request error
        if ($jsonstring === false) {
            $info = curl_getinfo($ch);
            curl_close($ch);
            die('Error making get request. More info:' . var_export($info));
        }

        curl_close($ch);

        //catch data not found
        if ($jsonstring === "") {
            return false;
        }

        $result = json_decode($jsonstring, true);

        if ($result == false || $result == NULL) {
            die('error: json_decode = false or null' .var_dump($jsonstring));
        }

        return $result;
    }

    public static function post($location, $curl_post_data) {
        $data = json_encode($curl_post_data);
        $url = "10.3.50.22/api/{$location}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/json']);
        $jsonstring = curl_exec($ch);

        //TODO: catch http-codes
        $info = curl_getinfo($ch);
        //201: created, 409: conflict, 500: internal server error (bad data), 404: not found

        //catch request error
        if ($jsonstring === false) {
            $info = curl_getinfo($ch);
            curl_close($ch);
            die('Error making post request. More info:' . var_export($info));
        }

        curl_close($ch);

        //catch data not found
        if ($jsonstring === "") {
            return $info["http_code"];
        }

        $result = json_decode($jsonstring, true);

        if ($result == false || $result == NULL) {
            die('error: json_decode = false or null' .var_dump($jsonstring));
        }

        return $result;
    }

    public static function put($location, $curl_put_data) {
        $data = json_encode($curl_put_data);
        $url = "10.3.50.22/api/{$location}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/json']);
        $jsonstring = curl_exec($ch);

        //TODO: catch http-codes
        $info = curl_getinfo($ch);
        //201: created, 409: conflict, 500: internal server error (bad data), 404: not found

        //catch request error
        if ($jsonstring === false) {
            $info = curl_getinfo($ch);
            curl_close($ch);
            die('Error making put request. More info:' . var_export($info));
        }

        curl_close($ch);

        //catch data not found
        if ($jsonstring === "") {
            return $info["http_code"];
        }

        $result = json_decode($jsonstring, true);

        if ($result == false || $result == NULL) {
            die('error: json_decode = false or null' .var_dump($jsonstring));
        }

        return $result;
    }
}