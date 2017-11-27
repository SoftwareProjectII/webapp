<?php
    require_once "Service.php";
    require_once "templates/head.php";

    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST["username"];
        $password = $_POST["password"];

        //salt ophalen
        $user = Service::get("users/{$username}");
        $salt = $user["salt"];
        $encsalt = base64_decode($salt);

        //hash the password input by user with salt from user object (got from dataservice)
        $hashedpass = hash_pbkdf2( "SHA1", $password, $encsalt, 1000, 32,true);
        $encodedhash = base64_encode($hashedpass);

        //TODO: check hash with database
        $postdata = ["username" => $username, "password" => $encodedhash];
        $logincheck = Service::post("users/login", $postdata); // 401: foute credentials, 200: OK, 400: foute variabelen

        if($logincheck) {
            $_SESSION[$user] = $user;
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
        <?php if (!$logincheck) {echo 'incorrect email or password';}?><br/>
        <div class="form-group">
            <input class="btn btn-primary btn-block nomargin" type="submit" value="login"/>
        </div>
        <!-- <a href="#" class="forgot">Forgot your email or password?</a> -->
    </form>
</div>
</body>
</html>