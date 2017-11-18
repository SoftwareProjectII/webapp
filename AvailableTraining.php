<body>
<?php
/**
 * Created by PhpStorm.
 * User: driesc
 * Date: 16/11/2017
 * Time: 19:08
 */
require_once "Service.php";

session_start();

// gebruik voor meer performantie
//$_SESSION["allTraining"] = Service::get("trainingsessions/loadreldata");
$_SESSION["allTraining"] = Service::get("trainingsessions");

?>
<table style="width:100%;">
    <tr>
        <th>Training</th>
        <th>City</th>
        <th>Date</th>
        <th>Hour</th>
    </tr>
    <?php
    foreach ($_SESSION["allTraining"] as $key => $value) {
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
            <button name="sign in">
                Sign in
            </button>
        </td>
    </tr>

    <?php
    }
    ?>

</table>
</body>
