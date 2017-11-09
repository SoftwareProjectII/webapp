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
<form action="login.php" method="POST">
    <label for="username">Username:</label><input type="text" name="username" id="username" required="required"/> <br/>
    <label for="password">Password:</label><input type="password" name="password" id="password" required="required"/> <br/> <!-- TODO: hash password in javascrypt?-->
    <input type="submit" value="Login"/>
</form>
</body>
</html>