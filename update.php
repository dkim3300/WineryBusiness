<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<?php 
    include 'util/db.php';
    include 'util/print.php';
?>

<body class="bg-grey container my-5">
    <!-- Back button -->
    <?php
    session_start();
    if (isset($_SERVER['HTTP_REFERER'])) {
        if (strpos($_SERVER['HTTP_REFERER'], 'businesshome.php') !== false) {
            $_SESSION['source'] = 'business';
        } elseif (strpos($_SERVER['HTTP_REFERER'], 'customerhome.php') !== false) {
            $_SESSION['source'] = 'customer';
        }
    }
    $homePage = isset($_SESSION['source']) && $_SESSION['source'] === 'business' ? 'businesshome.php' : 'customerhome.php';
    ?>
    <a href="<?php echo $homePage; ?>" class="btn btn-secondary">Back to Homepage</a>
    
    <div>
        <div class="dropdown-display">
            <h1 class="my-5">Update</h1>

            <div class="section">
                <h2>Update Wine Tasting</h2>
                <p><i>* Required fields</i></p>
                <form method="POST" action="update.php">
                    <input type="hidden" id="updateWineTasting" name="updateWineTastingRequest">
                    <input type="string" class="input-field" placeholder="Day*" name="dayOfWeek">
                    <input type="string" class="input-field" placeholder="Time*" name="timeOfDay">
                    <input type="string" class="input-field" placeholder="Tasting Type" name="tastingType">
                    <input type="string" class="input-field" placeholder="Liquor License Number*" name="liquorLicenseNum">
                    <input type="submit" name="updateWineTasting" value="Update" class="submit-button btn btn-primary">
                </form>
            </div>
            
            <div class="section">
                <h2>Show All Wine Tastings</h2>
                <form method="POST" action="update.php">
                    <input type="submit" class="submit-button btn btn-primary" name="showAllWineTasting" value="Show All">
                </form>
            </div>


        </div>
        <div class="">
        <?php

            if (isset($_POST['showAllWineTasting'])) {
                connectToDB();
                $query = "SELECT * FROM WineTasting WT";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }

            if (isset($_POST['updateWineTasting'])) {
                global $db_conn;
                connectToDB();
                $dayOfWeek = $_POST['dayOfWeek'];
                $timeOfDay = $_POST['timeOfDay'];
                $tastingType = isset($_POST['tastingType']) && !empty($_POST['tastingType']) ? $_POST['tastingType'] : 'NULL';
                $liquorLicenseNum = filter_var($_POST['liquorLicenseNum'], FILTER_SANITIZE_NUMBER_INT);

                $query = "SELECT WT.dayOfWeek, WT.timeOfDay, WT.tastingType, WT.liquorLicenseNum
                        FROM WineTasting WT
                        WHERE WT.liquorLicenseNum = " . $liquorLicenseNum . " AND dayOfWeek = '" . $dayOfWeek . "' AND timeOfDay = INTERVAL '" . $timeOfDay . "' HOUR TO SECOND";
                $result = executePlainSQL($query);

                $rowCount = oci_fetch_all($result, $res);
                if ($rowCount > 0) {
                    $update_query = "UPDATE WineTasting   
                                    SET tastingType = '" . $tastingType . "'
                                    WHERE liquorLicenseNum = " . $liquorLicenseNum . " 
                                        AND dayOfWeek = '" . $dayOfWeek . "' 
                                        AND timeOfDay = INTERVAL '" . $timeOfDay . "' HOUR TO SECOND";
                    
                    $update_result = executePlainSQL($update_query);
                    echo "Sucessfully updated Wine Tasting - " . $dayOfWeek . " ". $timeOfDay . " - " . $liquorLicenseNum . "!";
                    printResult($update_result);
                } else {
                    echo "Sorry! There was an error in updating Wine Tasting " . $dayOfWeek . " ". $timeOfDay . " - " . $liquorLicenseNum . ", Please confirm that the Wine Tasting exists.";
                }

                OCICommit($db_conn);
                disconnectFromDB();
            }

        ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>

</html>