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

if (isset($_GET["trainingSessionId"]) && isset($_SESSION["user"]["userId"])) {
    // get all data to show
    $TS = Service::get("trainingsessions/loadreldata/{$_GET["trainingSessionId"]}");
    $teacher = Service::get("teachers/{$TS["teacherid"]}");
    $address = Service::get("addresses/{$TS["addressid"]}");
    $faq = Service::get("traininginfos/{$TS["traininginfo"]["trainingid"]}");
    //TODO: display faq?
    var_dump($TS, $teacher, $address, $faq);
} else {
    ?>
        <mark>Something went wrong</mark>
    <?php
}

?>
<section>
    <h6>
        Training:
    </h6>
    <p>
        <?php
            echo $TS["trainingsinfo"]["name"];
            if ($TS["cancelled"] == 1) {
                ?>
                <mark>Cancelled!</mark>
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
    &q=<?= $address["country"] . "+" . $address["locality"] . "+" . $address["streetaddress"];
    //TODO: split streetaddress?
            ?>">
    </iframe>
</section>
</section>
</body>
