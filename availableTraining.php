<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 16/11/2017
 * Time: 19:08
 */
require_once "checksession.php";
require_once "Service.php";

//if sign in button pushed => compile data and post to dataservice
if (isset($_POST["trainingSessionId"]) && isset($_POST["signin"])) {
    $curl_post_data = [
        "userid" => $_SESSION["userId"],
        "trainingsessionid" => $_POST["trainingSessionId"],
        "isapproved" => false,
        "iscancelled" => false,
        "isDeclined" => false
    ];

    Service::post("followingtrainings", $curl_post_data);
}

//get all trainingsessions and trainingsessions user already subscribed to
$allTrainingSessions = Service::get("trainingsessions?loadrelated=true&future=true");
$userTrainingSessions = Service::get("users/{$_SESSION["userId"]}/trainingsessions?future=true");

require_once "templates/head.php";
?>
<body>
<?php require_once 'templates/navigation.php';?>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <table class="table table-responsive table-hover">
                        <thead>
                        <tr>
                            <th>Training</th>
                            <th>City </th>
                            <th>Date </th>
                            <th>Hour </th>
                            <th> </th>
                            <th> </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        // checkforid(int id, array to look in): returns false if no match found
                        // checks if user already signed in training so they don't appear in list
                        //TODO: return true instead of $t++
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
                        foreach ($allTrainingSessions as $key => $value) {
                            // TODO: test value cancelled
                            if(!checkForId($value["trainingSessionId"], $userTrainingSessions) && $value["cancelled"] == 0) {
                                ?>
                                <tr class="trainingrow">
                                    <td>
                                        <h3> <?=
                                            // gebruik maken van $value en /loadreldata voor meer performantie (minder get requests)
                                            $value["training"]["name"];
                                            //Service::get("traininginfos/{$value["trainingId"]}")['name'];
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
                                            <input type="hidden" name="breadcrumb" value="<?= "availableTraining.php" ?>"/>
                                            <button class="btn btn-primary" name="more info">
                                                More info
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="trainingSessionId" value="<?= $value["trainingSessionId"] ?>"/>
                                            <input type="hidden" name="signin" value="true"/>
                                            <button class="btn btn-primary"name="sign in">
                                                Sign in
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            };
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
