<!-- TODO: add active class to navigation link when on that page-->
<div>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean">
        <div class="container"><a class="navbar-brand" href="#">Company Name</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item" role="presentation"><a class="nav-link<?= $_SESSION["currentpage"] == "availableT" ? " active" : "";?>" href="availabletraining.php">Available Training</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link<?= $_SESSION["currentpage"] == "registeredT" ? " active" : "";?>" href="registeredtraining.php">Registered Training</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link<?= $_SESSION["currentpage"] == "myT" ? " active" : "";?>" href="mytraining.php">My Training</a></li>
                    <?php // Dont show myconfirmations tab if employee has no subordinates (is no manager)
                    if (Service::get("employees/{$_SESSION["userId"]}/manages")) {
                        ?>
                        <li class="nav-item" role="presentation"><a class="nav-link<?= $_SESSION["currentpage"] == "myC" ? " active" : "";?>" href="myconfirmations.php">My Confirmations</a></li>
                        <?php
                    }
                    ?>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="logout.php">Logout </a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>