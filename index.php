<?php
    require_once "Service.php";

    //force https
    if($_SERVER["HTTPS"] != "on") {
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST["username"];//NDavolio
        $password = $_POST["password"]; //1Davolio

        //salt ophalen
        $salt = Service::get("users/{$username}/salt");

        if ($salt !== false) {
            $encsalt = base64_decode($salt);

            //hash the password input by user with salt from user object (got from dataservice)
            $hashedpass = hash_pbkdf2("SHA1", $password, $encsalt, 1000, 32, true);
            $encodedhash = base64_encode($hashedpass);

            //get token with correct credentials
            $postdata = ["username" => $username, "password" => $encodedhash];
            $login = Service::post("token/login", $postdata); // 401: foute credentials, 200: OK, 400: foute variabelen
        }

        // TODO: testing databse response and succesful $_session
        if($login) {
            session_start();
            $_SESSION["token"] = $login["token"];
            $_SESSION["userId"] = $login["userid"];
            $user = Service::get("users/{$login["userid"]}");
            $employee =  Service::get("employees/{$user["empId"]}");
            $_SESSION["name"] = $employee["firstName"] . " " . $employee["lastName"];

            if ($employee["reportsTo"] == null){
                $_SESSION["hasManager"] = false;
            } else if (is_int($employee["reportsTo"])) {
                $_SESSION["hasManager"] = true;
            }

            header("location: availableTraining.php");
            exit();
        }
    }
require_once "templates/head.php";
?>
<body>

<div class="login-clean">
    <form method="POST">
        <h2 class="sr-only">Login Form</h2>
        <div class="illustration"><i class="fa fa-user-circle-o" aria-hidden="true"></i></div>
        <div class="form-group">
            <input class="form-control" type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Password" required> <!-- TODO: hash password in javascrypt?-->
        </div>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && $login == false) {echo 'incorrect email or password';}?><br/>
        <div class="form-group">
            <input class="btn btn-primary btn-block nomargin" type="submit" value="login"/>
        </div>
        <!-- <a href="#" class="forgot">Forgot your email or password?</a> -->
    </form>
</div>
</body>
</html>