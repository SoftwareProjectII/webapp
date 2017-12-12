<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 10/12/2017
 * Time: 17:04
 */

require_once "checksession.php";
require_once "Service.php";

if(isset($_POST["answers"])) {
        $curl_post_data = [
            "userid" => $_SESSION["userId"],
            "answers" => $_POST["answers"],
        ];

    $submit = Service::post("surveyanswers/answer", $curl_post_data);

    if($submit) {
        header("Location: myTraining.php"); //send to survey successful
        exit();
    } else {
        echo '<script type="text/javascript">alert("Something went wrong, your answers did not get submitted");</script>';
    }

}

$surveyId = Service::get("trainingsessions/{$_GET["trainingSessionId"]}/survey")["surveyId"];
$questions = Service::get("users/{$_SESSION["userId"]}/survey/questions?surveyid={$surveyId}");

require_once "templates/head.php";
?>
<body>
<?php require_once 'templates/navigation.php';?>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-primary" role="button" href="<?= $_GET["breadcrumb"] ?>"> <i class="icon ion-android-arrow-back"></i> BACK</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Survey
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="trainingsinfoborder">
                    <form method="post">
                        <?php //for every question:
                            if (!$questions) {
                                ?>
                                <mark>Sorry, there was a problem getting the questions or no new questions are available.</mark>
                                <?php
                            } else {
                                foreach ($questions as $key => $value) {
                                    ?>
                                    <br/>
                                    <h5>
                                        <label for="<?=$value["content"];?>">
                                            <?=$value["content"];?>
                                        </label>
                                    </h5>
                                    <input type="text"
                                           id="<?=$value["content"];?>"
                                           name="answers[<?=$value["questionId"];?>]"
                                           value="<?php
                                               if(isset($_POST["answers"])) {
                                                   echo $_POST["answers"][$value["questionId"]];
                                               };
                                           ?>"
                                           required>
                                    <br/>
                                    <?php
                                }
                                ?>
                                <br/><br/>
                                <input class="btn btn-primary" type="submit" value="Submit answers"/>
                            <?php
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
