<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 16/11/2017
 * Time: 19:08
 */
require_once "Service.php";
require_once 'templates/head.php';

session_start();

//get all trainingsessions and trainingsessions user already subscribed to
// gebruik voor meer performantie
//$_SESSION["allTraining"] = Service::get("trainingsessions/loadreldata");
$_SESSION["allTrainingSessions"] = Service::get("trainingsessions");
$_SESSION["userTrainingSessions"] = Service::get("users/44/trainingsessions");
//$_SESSION["userTrainingSessions"] = Service::get("users/{$_SESSION["user"]["userId"]}/trainingsessions");

//if sign in button pushed => compile data and post to dataservice
//TODO: change to function like in trainingsessiondetail?
if (isset($_GET["trainingSessionId"]) && isset($_SESSION["user"]["userId"])) {
    $curl_post_data = [
        "userid" => $_SESSION["user"]["userId"],
        "trainingsessionid" => $_GET["trainingSessionId"],
        "isapproved" => false,
        "iscancelled" => false
    ];

    Service::post("followingtrainings", $curl_post_data);
}

?>
<body>
<?php require_once 'templates/navigation.php';?>
<section>
<table style="width:100%;">
    <tr>
        <th>Training</th>
        <th>City</th>
        <th>Date</th>
        <th>Hour</th>
        <th></th>
        <th></th>
    </tr>
    <?php

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

    //iterate every trainingsession
    foreach ($_SESSION["allTrainingSessions"] as $key => $value) {
            // TODO: test value cancelled
            if(!checkForId($value["trainingSessionId"], $_SESSION["userTrainingSessions"]) && $value["cancelled"] == 0) {
                ?>
                <tr class="trainingrow">
                    <td>
                        <h3> <?=
                            // gebruik voor meer performantie
                            // $value["training"]["name"];
                            Service::get("traininginfos/{$value["trainingId"]}")['name'];
                            ?>
                        </h3>
                    </td>
                    <td>
                        <p>
                            <?= Service::get("addresses/{$value["addressId"]}")['locality'] ?>
                        </p>
                    </td>
                    <td>
                        <p>
                            <?php
                            $date = new DateTime($value["date"]);
                            echo $date->format('d M Y');
                            ?>
                        </p>
                    </td>
                    <td>
                        <p>
                            <?php
                            $start = new DateTime($value["startHour"]);
                            $end = new DateTime($value["endHour"]);
                            echo $start->format('H:i') . ' - ' . $end->format('H:i');
                            ?>
                        </p>
                    </td>
                    <td>
                        <form action="trainingSessionDetail.php">
                            <input type="hidden" name="trainingSessionId" value="<?= $value["trainingSessionId"] ?>"/>
                            <button name="sign in">
                                More info
                            </button>
                        </form>
                    </td>
                    <td>
                        <form>
                            <input type="hidden" name="trainingSessionId" value="<?= $value["trainingSessionId"] ?>"/>
                            <button name="sign in">
                                Sign in
                            </button>
                        </form>
                    </td>
                </tr>
                <?php
            };
    }
    ?>

</table>
</section>
</body>
