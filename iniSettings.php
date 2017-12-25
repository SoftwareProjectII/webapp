<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 21/12/2017
 * Time: 16:55
 */

//ini settings for added security: http://php.net/manual/en/session.security.ini.php

ini_set('session.use_strict_mode', 1); //no uninitialized session id
ini_set('session.use_only_cookies', 1); //no id in URL, only in cookies
ini_set('session.use_trans_sid', 0); // no transparent session id
ini_set('session.cache_limiter', "nocache");
ini_set('session.sid_length', "48");
ini_set('session.sid_bits_per_character', "6");
ini_set('session.hash_function', "sha256");