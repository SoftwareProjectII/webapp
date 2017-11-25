<?php
    require_once "Service.php";
    require_once 'templates/head.php';

    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST["username"];
        $password = $_POST["password"];

        //salt ophalen
        $user = Service::get("users/{$username}");
        $salt = $user["salt"];

        $hashedpass = hash_pbkdf2( "SHA1", $user["username"], $salt, 1000, 32,true);
        $encodedpass = base64_encode($hashedpass);

        var_dump($encodedpass);
        var_dump($user["password"]);

        if($user["username"] == $encodedpass) {
            echo 'succes';
        }

        if ($user["password"]) {
            $pass = $user["password"];
        }

        if ($pass == $password) {
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
        <?php if (isset($_POST["password"]) && $pass !== $password) {echo 'incorrect email or password';}?><br/>
        <div class="form-group">
            <input class="btn btn-primary btn-block nomargin" type="submit" value="login"/>
        </div>
        <!-- <a href="#" class="forgot">Forgot your email or password?</a> -->
    </form>
</div>
</body>
</html>