<body>
<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 19/11/2017
 * Time: 21:39
 */
require_once "Service.php";

session_start();

$TS;

//if sign in button pushed => compile data and post to dataservice
//TODO: change to function like in trainingsessiondetail?
if (isset($_GET["trainingSessionId"]) && isset($_SESSION["user"]["userId"]) && isset($_GET["signin"])) {
    $curl_post_data = [
        "userid" => $_SESSION["user"]["userId"],
        "trainingsessionid" => $_GET["trainingSessionId"],
        "isapproved" => false,
        "iscancelled" => false
    ];

    if (Service::get("followingtrainings/{$_SESSION["user"]["userId"]}/{$_GET["trainingSessionId"]}")) {
        Service::put("followingtrainings/{$_SESSION["user"]["userId"]}/{$_GET["trainingSessionId"]}", $curl_post_data);
    } else {
        Service::post("followingtrainings", $curl_post_data);
    }

}

if (isset($_GET["trainingSessionId"]) && isset($_SESSION["user"]["userId"]) && isset($_GET["cancelsignin"])) {
    $curl_put_data = [
        "userid" => $_SESSION["user"]["userId"],
        "trainingsessionid" => $_GET["trainingSessionId"],
        "isapproved" => false,
        "iscancelled" => true
    ];
    //TODO: url gaat combo zijn: nog te testen
    Service::put("followingtrainings/{$_SESSION["user"]["userId"]}/{$_GET["trainingSessionId"]}", $curl_put_data);
}

if (isset($_GET["trainingSessionId"]) && isset($_SESSION["user"]["userId"]) && isset($_GET["signout"])) {
    $curl_put_data = [
        "userid" => $_SESSION["user"]["userId"],
        "trainingsessionid" => $_GET["trainingSessionId"],
        "isapproved" => true,
        "iscancelled" => true
    ];
    //TODO: url gaat combo zijn: nog te testen
    Service::put("followingtrainings/{$_SESSION["user"]["userId"]}/{$_GET["trainingSessionId"]}", $curl_put_data);
}

if (isset($_GET["trainingSessionId"]) && isset($_SESSION["user"]["userId"]) && isset($_GET["cancelsignout"])) {
    $curl_put_data = [
        "userid" => $_SESSION["user"]["userId"],
        "trainingsessionid" => $_GET["trainingSessionId"],
        "isapproved" => true,
        "iscancelled" => false
    ];
    //TODO: url gaat combo zijn: nog te testen
    Service::put("followingtrainings/{$_SESSION["user"]["userId"]}/{$_GET["trainingSessionId"]}", $curl_put_data);
}

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

if (isset($_GET["trainingSessionId"]) && isset($_SESSION["user"]["userId"])) {
    // get all data to show
    //TODO: get followingtraining object for user in $status if exists. API still under construction so probably doesnt work yet
    $status = Service::get("followingtrainings/{$_SESSION["user"]["userId"]}/{$_GET["trainingSessionId"]}");
    $_SESSION["userTrainingSessions"] = Service::get("users/{$_SESSION["user"]["userId"]}/trainingsessions");
    $TS = Service::get("trainingsessions/loadreldata/{$_GET["trainingSessionId"]}");
    $teacher = Service::get("teachers/{$TS["teacherid"]}");
    $address = Service::get("addresses/{$TS["addressid"]}");
    $faq = Service::get("traininginfos/{$TS["traininginfo"]["trainingid"]}");
    //TODO: display faq?
    var_dump($TS, $teacher, $address, $faq);
} else {
    ?>
        <mark>Something went wrong!</mark>
    <?php
}

?>
<section>
    <?php if($TS["cancelled"] == 1) {

    } else if(!checkForId($TS["trainingSessionId"], $_SESSION["userTrainingSessions"]) || ($status["iscancelled"] == "true" && $status["isapproved"] =="false")) { // not in followingtrainings -> sign in button -> in followingstrainings aproved & iscancelled on false
        ?>
        <form>
            <input type="hidden" name="trainingSessionId" value="<?= $TS["trainingSessionId"] ?>"/>
            <input type="hidden" name="signin" value="true"/>
            <button name="sign in">
                Sign in
            </button>
        </form>
        <?php
    } else if ($status["iscancelled"] == "false" && $status["isapproved"] == "false") { // approved & iscancelled false (manager must approve) -> cancel button -> iscancelled on true
        ?>
        <form>
            <input type="hidden" name="trainingSessionId" value="<?= $TS["trainingSessionId"] ?>"/>
            <input type="hidden" name="cancelsignin" value="true"/>
            <button name="sign in">
                (awaiting confirmation) Cancel sign in
            </button>
        </form>
        <?php
    } else if ($status["iscancelled"] == "false" && $status["isapproved"] == "true") { // approved = true, cancelled = false --> manager approved --> sign out button --> iscancelled = true
        ?>
        <form>
            <input type="hidden" name="trainingSessionId" value="<?= $TS["trainingSessionId"] ?>"/>
            <input type="hidden" name="signout" value="true"/>
            <button name="sign out">
                sign out
            </button>
        </form>
        <?php
    } else if ($status["iscancelled"] == "true" && $status["isapproved"] == "true") { // approved = true & iscancelled true -> signed out
        ?>
        <form>
            <input type="hidden" name="trainingSessionId" value="<?= $TS["trainingSessionId"] ?>"/>
            <input type="hidden" name="cancelsignout" value="true"/>
            <button name="signed out" disabled>
                (awaiting confirmation) Cancel signed out
            </button>
        </form>
        <?php
    }
    ?>

    <h6>
        Training:
    </h6>
    <p>
        <?php
            echo $TS["trainingsinfo"]["name"];
            if ($TS["cancelled"] == 1) {
                ?>
                <mark> Cancelled!</mark>
                <?php
            }
        ?>
    </p>
    <h6>
        Info:
    </h6>
    <p>
        <?= $TS["trainingsinfo"]["infogeneral"];?>
    </p>
    <h6>
        Date:
    </h6>
    <p>
        <?php
            $date = new DateTime($TS["date"]);
            echo $date->format('d M Y');
        ?>
    </p>
    <h6>
        Time:
    </h6>
    <p>
        <?php
            $start = new DateTime($TS["startHour"]);
            $end = new DateTime($TS["endHour"]);
            echo $start->format('H:i') . ' - ' . $end->format('H:i');
        ?>
    </p>
    <h6>
        Price:
    </h6>
    <p>
        <?= $TS["trainingsinfo"]["price"];?>
    </p>
    <h6>
        Payment:
    </h6>
    <p>
        <?= $TS["trainingsinfo"]["infopayment"];?>
    </p>
    <h6>
        Exam:
    </h6>
    <p>
        <?= $TS["trainingsinfo"]["infoexam"];?>
    </p>
    <h6>
        Teacher:
    </h6>
    <p>
        <?= $teacher["firstname"] . $teacher["lastname"] . $teacher["phonenumber"] . $teacher["email"];?>
    </p>
    <h6>
        Location:
    </h6>
    <p>
        <?= $address["locality"] . $address["streetaddress"];?>
    </p>
    <iframe
            width="600"
            height="450"
            frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC_MyMlEb59Fb8IBPy_0hHm4FGP4r8nYxo
    &q=<?= urlencode($address["country"]) . "+" . urlencode($address["locality"]) . "+" . urlencode($address["streetaddress"]);
            ?>">
    </iframe>
</section>
</section>
</body>
