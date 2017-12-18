<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 10/12/2017
 * Time: 21:38
 */

require_once "checksession.php";
require_once "Service.php";
require_once "vendor/autoload.php";
$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);
$CSRFToken = $easyCSRF->generate('CSRFToken');

$_SESSION["currentpage"] = "myC";

if(isset($_POST["userId"]) && isset($_POST["trainingSessionId"])) {
    if (isset($_POST["accept-signin"])) {
        $curl_put_data = [
            "userid" => $_POST["userId"],
            "trainingsessionid" => $_POST["trainingSessionId"],
            "isApproved" => true,
            "isCancelled" => false,
            "isDeclined" => false
        ];
    } else if (isset($_POST["decline-signin"])) {
        $curl_put_data = [
            "userid" => $_POST["userId"],
            "trainingsessionid" => $_POST["trainingSessionId"],
            "isApproved" => false,
            "isCancelled" => false,
            "isDeclined" => true,
        ];
    } else if (isset($_POST["accept-signout"])) {
        $curl_put_data = [
            "userid" => $_POST["userId"],
            "trainingsessionid" => $_POST["trainingSessionId"],
            "isApproved" => false,
            "isCancelled" => true,
            "isDeclined" => false,
        ];
    } else if (isset($_POST["decline-signout"])) {
        $curl_put_data = [
            "userid" => $_POST["userId"],
            "trainingsessionid" => $_POST["trainingSessionId"],
            "isApproved" => true,
            "isCancelled" => true,
            "isDeclined" => true,
        ];
    }
    Service::put("followingtrainings?userid={$_POST["userId"]}&trainingsessionid={$_POST["trainingSessionId"]}", $curl_put_data);
}

$requests = Service::get("employees/{$_SESSION["userId"]}/manages/trainings?future=true"); // get all followingtrainingsobjects with employeeids

require_once "templates/head.php";
?>
<body>
<?php require_once 'templates/navigation.php';?>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <table id="myConfirmationsTrainingTable" class="table table-responsive table-hover tablesorter-bootstrap">
                        <thead>
                        <tr>
                            <th>Employee</th>
                            <th class="filter-select filter-exact" data-placeholder="Pick an action">Action</th>
                            <th>Training</th>
                            <th class="filter-select filter-exact" data-placeholder="Pick a location">Location</th>
                            <th>Date</th>
                            <th>Hour</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($requests as $key => $value) {
                            if($value["followingtraining"]["isApproved"] == false && $value["followingtraining"]["isCancelled"] == false && $value["followingtraining"]["isDeclined"] == false ||
                                $value["followingtraining"]["isApproved"] == true && $value["followingtraining"]["isCancelled"] == true && $value["followingtraining"]["isDeclined"] == false
                            ) {
                                ?>
                                <tr class="trainingrow">
                                    <td>
                                        <p>
                                            <?=
                                            $value["fName"] . " " . $value["lName"];
                                            ?>
                                        </p>
                                    </td>
                                    <td>
                                            <?php
                                            if ($value["followingtraining"]["isApproved"] == false) {
                                                ?>
                                                <p class="text-success">sign in</p>
                                                <?php
                                            } else if ($value["followingtraining"]["isApproved"] == true) {
                                                ?>
                                                <p class="text-danger">sign out</p>
                                                <?php
                                            };
                                            ?>
                                    </td>
                                    <td>
                                        <p>
                                            <?= $value["followingtraining"]["trainingSession"]["training"]["name"]; ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            <?= Service::get("addresses/{$value["followingtraining"]["trainingSession"]["addressId"]}")['locality']; ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            <?php
                                            $date = new DateTime($value["followingtraining"]["trainingSession"]["date"]);
                                            echo $date->format('d M Y');
                                            ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            <?php
                                            $start = new DateTime($value["followingtraining"]["trainingSession"]["startHour"]);
                                            $end = new DateTime($value["followingtraining"]["trainingSession"]["endHour"]);
                                            echo $start->format('H:i') . ' - ' . $end->format('H:i');
                                            ?>
                                        </p>
                                    </td>
                                    <td>
                                        <form action="trainingSessionDetail.php">
                                            <input type="hidden" name="trainingSessionId" value="<?= $value["followingtraining"]["trainingSession"]["trainingSessionId"] ?>"/>
                                            <input type="hidden" name="breadcrumb" value="<?= "myConfirmations.php" ?>"/>
                                            <input type="hidden" name="nobutton" value="true"/>
                                            <button class="btn btn-primary" name="more info">
                                                More info
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="trainingSessionId" value="<?= $value["followingtraining"]["trainingSession"]["trainingSessionId"] ?>"/>
                                            <input type="hidden" name="userId" value="<?= $value["followingtraining"]["userId"] ?>"/>
                                            <input type="hidden" name="CSRFToken" value="<?php echo $CSRFToken;?>">
                                            <button class="btn btn-primary btn-success" name="accept-<?php if ($value["followingtraining"]["isApproved"] == false) {echo "signin";} else {echo "signout";}?>">
                                                Accept
                                            </button>
                                            <button class="btn btn-primary btn-danger"name="decline-<?php if ($value["followingtraining"]["isApproved"] == false) {echo "signin";} else {echo "signout";}?>">
                                                Decline
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
