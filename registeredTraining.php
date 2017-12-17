<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 12/12/2017
 * Time: 23:09
 */

require_once "checksession.php";
require_once "Service.php";
require_once 'vendor/autoload.php';
$_SESSION["currentpage"] = "registeredT";

$sessions = Service::get("users/{$_SESSION["userId"]}/trainingsessions?future=true&loadrelated=true");

require_once "templates/head.php";
?>
<body>
<?php require_once 'templates/navigation.php';?>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <table id="registeredTrainingTable" class="table table-responsive table-hover tablesorter-bootstrap">
                        <thead>
                        <tr>
                            <th>Training</th>
                            <th class="filter-select filter-exact" data-placeholder="Pick a location">Location</th>
                            <th>Date</th>
                            <th>Hour</th>
                            <th class="sorter-false filter-false"> </th>
                            <th class="filter-select filter-exact" data-placeholder="Pick a status"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        //iterate every trainingsession
                        foreach ($sessions as $key => $value) {
                            $status = __::filter($value["followingtraining"], function($n) {
                                return $n['userId'] == $_SESSION["userId"];
                            })[0];
                            //show all entry's in followingtrainings
                            if(true) {
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
                                            <input type="hidden" name="breadcrumb" value="<?= "registeredTraining.php" ?>"/>
                                            <button class="btn btn-primary" name="more info">
                                                More info
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <?php
                                            //TODO: double check
                                            if ($status["isApproved"] == true && $status["isCancelled"] == false && $status["isDeclined"] == false ||
                                                $status["isApproved"] == true && $status["isCancelled"] == true && $status["isDeclined"] == true
                                            ) {
                                            ?>
                                                <div class="alert alert-success" role="alert">
                                                    Signed up!
                                                </div>
                                            <?php
                                            } else if ($status["isApproved"] == false && $status["isCancelled"] == false && $status["isDeclined"] == false) {
                                                ?>
                                                <div class="alert alert-warning" role="alert">
                                                    Awaiting <span class="text-success">sign in</span> confirmation
                                                </div>
                                                <?php
                                            } else if ($status["isApproved"] == true && $status["isCancelled"] == true && $status["isDeclined"] == false) {
                                                ?>
                                                <div class="alert alert-warning" role="alert">
                                                    Awaiting <span class="text-danger">sign out</span> confirmation
                                                </div>
                                                <?php
                                            } else if ($status["isApproved"] == false && $status["isCancelled"] == false && $status["isDeclined"] == true) {
                                                ?>
                                                <div class="alert alert-danger" role="alert">
                                                    Request denied
                                                </div>
                                                <?php
                                            } else if ($status["isApproved"] == false && $status["isCancelled"] == true && $status["isDeclined"] == false) {
                                                ?>
                                                <div class="alert alert-danger" role="alert">
                                                    Signed out!
                                                </div>
                                                <?php
                                            }
                                        ?>
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
