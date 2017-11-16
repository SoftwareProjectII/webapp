<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 16/11/2017
 * Time: 19:35
 */

class Service
{

    //makes GET request to dataservice.
    //returns FALSE when empty object is retrieved (no data or wrong query)
    //returns data as php array
    public static function Get($get) {
        $url = "10.3.50.22/api/{$get}";
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

/*        //catch data not found
        if ($jsonstring === "") {
            return false;
        }*/

        $result = json_decode($jsonstring, true);

        if ($result == false || $result == NULL) {
            die('error: json_decode = false or null');
        }

        return $result;
    }
}