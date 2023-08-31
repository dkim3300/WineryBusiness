<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

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

        <h1 class="my-5">Join</h1>
            
        <div class="section">
            <h2>Find all Parcels with WineGrowers over X years of Experience</h2>
            <form method="POST" action="join.php"> <!--refresh page when submitted-->
                <input type="hidden" id="findParcel" name="findParcelRequest">
                <input type="number" class="input-field" placeholder="Minimum Years" name="YearsExperience">
                <input type="submit" name="findParcel" value="Find Parcels" class="submit-button btn btn-primary">
            </form>
        </div>

        <div class="table-display">
            <?php
            include 'util/db.php';
            include 'util/print.php';

            if (isset($_POST['findParcel'])) {
                handlePOSTRequest();
            }

            function handlePOSTRequest() {
                if (connectToDB()) {
                    if (array_key_exists('findParcel', $_POST)) {
                        handleFindParcelRequest();
                    }
                    disconnectFromDB();
                }
            }

            function handleFindParcelRequest()
            {
                global $db_conn;

                //Getting the values from user and insert data into the table
                $wineGrowerExp = $_POST['YearsExperience'];

                // SQL query
                $sql = "SELECT P.latitude, P.longitude, W.WGID, W.WGName, W.YearsExperience
                        FROM Parcel P, Winegrower W
                        WHERE P.WGID = W.WGID AND 
                        W.YearsExperience > $wineGrowerExp";

                $statement = executePlainSQL($sql);

                // display the results
                echo "<h3> All Parcels Satisfying Experience Constraint</h3>";

                echo '<table class="table table-bordered">';
                echo '<thead><tr><th>Latitude</th><th>Longitude</th><th>WGID</th><th>Name</th><th>YearsExperience</th></tr></thead>';
                echo '<tbody>';

                // Fetch and display the query results
                while ($row = oci_fetch_assoc($statement)) {
                    echo '<tr>';
                    echo '<td>' . $row['LATITUDE'] . '</td>';
                    echo '<td>' . $row['LONGITUDE'] . '</td>';
                    echo '<td>' . $row['WGID'] . '</td>';
                    echo '<td>' . $row['WGNAME'] . '</td>';
                    echo '<td>' . $row['YEARSEXPERIENCE'] . '</td>';
                    echo '</tr>';
                }
                            
                echo '</tbody>';
                echo '</table>';

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