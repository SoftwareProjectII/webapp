<?php

/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 19/11/2017
 * Time: 21:39
 */
require_once "startSession.php";
require_once "Service.php";
require_once "vendor/autoload.php";
$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);
$CSRFToken = $easyCSRF->generate('CSRFToken');

// checkforid(int id, array to look in): returns false if no match found
function checkForId($TSId, $array) {
    $t = 0;
    foreach ($array as $key => $value) {
        if ($TSId == $value["trainingSessionId"]) {
            $t++;
        }
    }

    if ($t == 0) {
        return false;
    } else {
        return true;
    }
}

if ((isset($_GET["trainingSessionId"]) || isset($_POST["trainingSessionId"])) && isset($_SESSION["userId"])) {
    if (isset($_GET["trainingSessionId"])) {
        $TSId = $_GET["trainingSessionId"];
    } else if (isset($_POST["trainingSessionId"])) {
        $TSId = $_POST["trainingSessionId"];
    }
    $status = Service::get("followingtrainings?userid={$_SESSION["userId"]}&trainingsessionid={$TSId}");
    $userTrainingSessions = Service::get("users/{$_SESSION["userId"]}/trainingsessions");
    $TS = Service::get("trainingsessions/{$TSId}?loadrelated=true");
} else {
    ?>
        <mark>Something went wrong!</mark>
    <?php
}

require_once "templates/head.php";
?>
<body>
<?php require_once 'templates/navigation.php';?>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-primary" role="button" href="<?= $_GET["breadcrumb"] ?>"> <i class="icon ion-android-arrow-back"></i> BACK</a>
                <?php
                //TODO: show alert with status? make displaystatus function?
                //compare trainingsession datetime with current datetime: show no sign-in/sign-out button when trainingsession has passed
                $date = new DateTime($TS["date"]);
                $now = new DateTime("now");
                if($TS["cancelled"] == true || isset($_GET["nobutton"]) || $date < $now) {
                } else if(!checkForId($TS["trainingSessionId"], $userTrainingSessions) || ($status["isCancelled"] == true && $status["isApproved"] == false && $status["isDeclined"] == false)) { // not in followingtrainings -> sign in button -> in followingstrainings aproved & isCancelled on false
                    ?>
                    <form method="POST" action="handleRequest.php">
                        <input type="hidden" name="trainingSessionId" value="<?= $TS["trainingSessionId"] ?>"/>
                        <input type="hidden" name="action" value="signin"/>
                        <input type="hidden" name="breadcrumb" value="trainingSessionDetail.php?trainingSessionId=<?= $TS["trainingSessionId"];?>&breadcrumb=<?= $_GET["breadcrumb"];?>"/>
                        <input type="hidden" name="CSRFToken" value="<?php echo $CSRFToken;?>">
                        <button class="btn btn-primary float-right" name="sign in">
                            Sign in
                        </button>
                    </form>
                    <?php
                } else if ($status["isCancelled"] == false && $status["isApproved"] == false && $status["isDeclined"] == false) { // approved & isCancelled false (manager must approve) -> cancel button -> isCancelled on true
                    ?>
                    <form method="POST" action="handleRequest.php">
                        <input type="hidden" name="trainingSessionId" value="<?= $TS["trainingSessionId"] ?>"/>
                        <input type="hidden" name="action" value="cancelsignin"/>
                        <input type="hidden" name="breadcrumb" value="trainingSessionDetail.php?trainingSessionId=<?= $TS["trainingSessionId"];?>&breadcrumb=<?= $_GET["breadcrumb"];?>"/>
                        <input type="hidden" name="CSRFToken" value="<?php echo $CSRFToken;?>">
                        <button class="btn btn-primary float-right" name="sign in">
                            (awaiting confirmation) Cancel sign in
                        </button>
                    </form>
                    <?php
                } else if ($status["isCancelled"] == false && $status["isApproved"] == true && $status["isDeclined"] == false) { // approved = true, cancelled = false --> manager approved --> sign out button --> isCancelled = true
                    ?>
                    <form method="POST" action="handleRequest.php">
                        <input type="hidden" name="trainingSessionId" value="<?= $TS["trainingSessionId"] ?>"/>
                        <input type="hidden" name="action" value="signout"/>
                        <input type="hidden" name="breadcrumb" value="trainingSessionDetail.php?trainingSessionId=<?= $TS["trainingSessionId"];?>&breadcrumb=<?= $_GET["breadcrumb"];?>"/>
                        <input type="hidden" name="CSRFToken" value="<?php echo $CSRFToken;?>">
                        <button class="btn btn-primary float-right" name="sign out">
                            sign out
                        </button>
                    </form>
                    <?php
                } else if ($status["isCancelled"] == true && $status["isApproved"] == true && $status["isDeclined"] == false) { // approved = true & isCancelled true -> signed out
                    ?>
                    <form method="POST" action="handleRequest.php">
                        <input type="hidden" name="trainingSessionId" value="<?= $TS["trainingSessionId"] ?>"/>
                        <input type="hidden" name="action" value="cancelsignout"/>
                        <input type="hidden" name="breadcrumb" value="trainingSessionDetail.php?trainingSessionId=<?= $TS["trainingSessionId"];?>&breadcrumb=<?= $_GET["breadcrumb"];?>"/>
                        <input type="hidden" name="CSRFToken" value="<?php echo $CSRFToken;?>">
                        <button class="btn btn-primary float-right" name="signed out">
                            (awaiting confirmation) Cancel signed out
                        </button>
                    </form>
                    <?php
                } else if ($status["isCancelled"] == false && $status["isApproved"] == false && $status["isDeclined"] == true) { // approved = true & isCancelled true -> signed out
                    ?>
                    <mark>Your request to follow this session has been denied by your manager.</mark>
                    <?php
                } else if ($status["isCancelled"] == true && $status["isApproved"] == true && $status["isDeclined"] == true) { // approved = true & isCancelled true -> signed out
                    ?>
                    <mark>Your request to sign out has been denied by your manager.</mark>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Training Info
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="trainingsinfoborder">
                    <h5>
                        <?php
                        echo $TS["training"]["name"];
                        if ($TS["cancelled"] == 1) {
                            ?>
                            <mark> Cancelled!</mark>
                            <?php
                        }
                        ?>
                    </h5>
                    <p>
                        <?= $TS["training"]["infoGeneral"];?>
                    </p>
                    <h5>Date - Hour</h5>
                    <p>
                        <?php
                        $date = new DateTime($TS["date"]);
                        echo $date->format('d M Y');
                        ?> -
                        <?php
                        $start = new DateTime($TS["startHour"]);
                        $end = new DateTime($TS["endHour"]);
                        echo $start->format('H:i') . '-' . $end->format('H:i');
                        ?>
                    </p>
                    <h5>
                        Price:
                    </h5>
                    <p>
                        <?= $TS["training"]["price"];?>
                    </p>
                    <h5>
                        Payment:
                    </h5>
                    <p>
                        <?= $TS["training"]["infoPayment"];?>
                    </p>
                    <h5>
                        Exam:
                    </h5>
                    <p>
                        <?= $TS["training"]["infoExam"];?>
                    </p>
                    <h5>
                        Teacher:
                    </h5>
                    <p>
                        <?= $TS["teacher"]["firstName"] . " " . $TS["teacher"]["lastName"];?>
                        <br/>
                        <?= $TS["teacher"]["phoneNumber"];?>
                        <br/>
                        <?= $TS["teacher"]["email"];?>
                    </p>
                    <h5>
                        Location:
                    </h5>
                    <p>
                        <?= $TS["address"]["locality"];?>
                        <br/>
                        <?= $TS["address"]["streetAddress"];?>
                    </p>
                    <div class="col mapscontainer">
                        <iframe
                                width="600"
                                height="450"
                                frameborder="0" style="border:0"
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC_MyMlEb59Fb8IBPy_0hHm4FGP4r8nYxo&amp;q=<?= urlencode($TS["address"]["country"]) . "+" . urlencode($TS["address"]["locality"]) . "+" . urlencode($TS["address"]["streetAddress"]);
                                ?>">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-primary" role="button" href="<?= $_GET["breadcrumb"] ?>"> <i class="icon ion-android-arrow-back"></i> BACK</a>
            </div>
        </div>
    </div>
</div>
</body>
