<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body class="bg-grey container my-5">

<div class="dropdown-display">

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
    
        <h1 class="my-5">Delete</h1>

        <div class="section">
                <h2>Fire a Wine Grower</h2>
                <form method="POST" action="delete.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="deleteWineGrower" name="deleteWineGrowerRequest">
                    <input type="number" class="input-field" placeholder="WGID" name="WGID">
                    <input type="submit" name="deleteWineGrower" value="Fire" class="submit-button btn btn-primary">
                </form>
            </div>

            <div class="section">
                <h2>Show All WineGrowers and Parcels</h2>
                <form method="POST" action="delete.php"> <!--refresh page when submitted-->
                    <input type="submit" class="submit-button btn btn-primary" name="showAllWineGrowersAndParcels" value="Show All">
                </form>
            </div>
        </div>

        <div class="table-display">
            <?php
            include 'util/db.php';
            include 'util/print.php';

            if (isset($_POST['showAllWineGrowersAndParcels'])) {
                connectToDB();

                echo '<h3>WineGrowers</h3>';
                $query = "SELECT * FROM WineGrower ORDER BY WGID";
                $result = executePlainSQL($query);
                printResult($result);

                echo '<div style="margin-top: 20px;"></div>';
            
                echo '<h3>Parcels</h3>';
                $query = "SELECT * FROM Parcel ORDER BY BusinessName, PostalCode";
                $result = executePlainSQL($query);
                printResult($result);

                disconnectFromDB();
            }

            if (isset($_POST['deleteWineGrower'])) {
                handlePOSTRequest();
            }

            function handlePOSTRequest() {
                if (connectToDB()) {
                    if (array_key_exists('deleteWineGrowerRequest', $_POST)) {
                        handleDeleteWineGrowerRequest();
                    }
                    disconnectFromDB();
                }
            }

            function handleDeleteWineGrowerRequest() {
                global $db_conn;
            
                // Getting the selected WGID from the user
                $wg_id = $_POST['WGID'];
            
                // Define the SQL query to delete rows from WineGrower based on WGID
                $sql = "DELETE FROM WineGrower WHERE WGID = $wg_id";
            
                // Execute the SQL query to delete rows from WineGrower
                $statement = executePlainSQL($sql);
                $rowsDeleted = OCIRowCount($statement);
            
                if ($rowsDeleted > 0) {
                    echo "Delete operation completed. WGID $wg_id has been removed.";
                } else {
                    echo "No rows deleted. WGID $wg_id does not exist in the database.";
                }
            
                OCICommit($db_conn);
            }
            

            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>

</html>
