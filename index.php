<?php

    require_once "Service.php";

    if (session_id() == "") {
        session_start();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST["email"];
        $password = $_POST["password"];
        // TODO: testdata
        //$email = "3";
        // $password = "test";

        $user = Service::Get("users/{$email}");

        if ($user["password"]) {
            $pass = $user["password"];
        }

        if ($pass == $password) {
            $_SESSION['user'] = $email;
            header("location: AvailableTraining.php");
            exit();
        }

    }
?>
<!DOCTYPE html>
<html lang="nl">
<head>
	<title></title>
	<meta charset="utf-8">
    <!-- build:css css/style.min.css -->
	<link rel="stylesheet" href="css/style.css" type="text/css">
    <!-- endbuild -->
    <!-- build:js js/main.min.js -->
    <script type="text/javascript" src="js/script.js"></script>
    <!-- endbuild -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<form action="index.php" method="POST">
    <label for="email">Email:</label><input type="text" name="email" id="email" required="required"/> <br/>
    <label for="password">Password:</label><input type="password" name="password" id="password" required="required"/> <br/> <!-- TODO: hash password in javascrypt?-->
    <?php if (isset($_POST["password"]) && $pass !== $password) {echo 'incorrect email or password';}?><br/>
    <input type="submit" value="Login"/>
</form>
</body>
</html>