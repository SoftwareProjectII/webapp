<?php
    require_once "Service.php";
    require_once "templates/head.php";

    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $token = false;
        $username = $_POST["username"];//NDavolio
        $password = $_POST["password"]; //1Davolio

        //salt ophalen
        $postdata = ["username" => $username];
        $salt = Service::post("user/salt", $postdata);

        if ($salt) {
            $encsalt = base64_decode($salt);

            //hash the password input by user with salt from user object (got from dataservice)
            $hashedpass = hash_pbkdf2( "SHA1", $password, $encsalt, 1000, 32,true);
            $encodedhash = base64_encode($hashedpass);

            //get token with correct credentials
            $postdata = ["username" => $username, "password" => $encodedhash];
            $login = Service::post("token/login", $postdata); // 401: foute credentials, 200: OK, 400: foute variabelen
        }
        // TODO: testing databse response and succesful $_session
        if($login) {
            $_SESSION["token"] = $login["token"];
            $_SESSION["userid"] = $login["userid"];
            header("location: AvailableTraining.php");
            exit();
        }
    }
?>
<body>

<div class="login-clean">
    <form method="POST">
        <h2 class="sr-only">Login Form</h2>
        <div class="illustration"><i class="icon ion-ios-navigate"></i></div>
        <div class="form-group">
            <input class="form-control" type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Password" required> <!-- TODO: hash password in javascrypt?-->
        </div>
        <?php var_dump($token); if ($_SERVER['REQUEST_METHOD'] == 'POST' && $login == false) {echo 'incorrect email or password';}?><br/>
        <div class="form-group">
            <input class="btn btn-primary btn-block nomargin" type="submit" value="login"/>
        </div>
        <!-- <a href="#" class="forgot">Forgot your email or password?</a> -->
    </form>
</div>
</body>
</html>