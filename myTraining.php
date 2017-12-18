<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 10/12/2017
 * Time: 16:56
 */

require_once "checksession.php";
require_once "Service.php";
require_once "vendor/autoload.php";
$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);
$CSRFToken = $easyCSRF->generate('CSRFToken');
$_SESSION["currentpage"] = "myT";

$sessions = Service::get("users/{$_SESSION["userId"]}/trainingsessions?future=false&loadrelated=true");

require_once "templates/head.php";
?>
<body>
<?php require_once 'templates/navigation.php';?>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <table id="availableTrainingTable" class="table table-responsive table-hover tablesorter-bootstrap">
                        <thead>
                        <tr>
                            <th>Training</th>
                            <th class="filter-select filter-exact" data-placeholder="Pick a location">Location </th>
                            <th>Date </th>
                            <th>Hour </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        //iterate every trainingsession
                        foreach ($sessions as $key => $value) {
                            $status = __::filter($value["followingtraining"], function($n) {
                                return $n['userId'] == $_SESSION["userId"];
                            })[0];
                            //only show approved and undeclined sessions
                            if($status["isApproved"] == true && $status["isDeclined"] == false) {
                                ?>
                                <tr class="trainingrow">
                                    <td>
                                        <h3> <?=
                                            $value["training"]["name"];
                                            ?>
                                        </h3>
                                    </td>
                                    <td>
                                        <p>
                                            <?= $value["address"]["locality"] ?>
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
                                            <input type="hidden" name="breadcrumb" value="<?= "myTraining.php" ?>"/>
                                            <button class="btn btn-primary" name="more info">
                                                More info
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="survey.php">
                                            <input type="hidden" name="trainingSessionId" value="<?= $value["trainingSessionId"] ?>"/>
                                            <input type="hidden" name="breadcrumb" value="<?= "myTraining.php" ?>"/>
                                            <button class="btn btn-primary"name="fill out survey">
                                                Fill out survey
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
