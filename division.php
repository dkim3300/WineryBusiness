<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aggregate With Group By</title>
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

        <div class="dropdown-display">
            <h1 class="my-5">Division</h1>
            <div class="section">
                <h2>Find all Wineries that have Produced a Merlot Wine and Gewurztraminer Wine</h2>
                <form method="POST" action="division.php"> <!--refresh page when submitted-->
                    <input type="hidden" name="findWineries">
                    <input type="submit" name="findWineriesMerlotAndGewur" value="Find Winery with Merlot and Gewurztraminer" class="submit-button btn btn-primary">
                </form>
            </div>
        </div>

        <div class="table-display">
            <?php
            include 'util/db.php';
            include 'util/print.php';

            if (isset($_POST['findWineries'])) {
                handlePOSTRequest();
            }

            function handlePOSTRequest() {
                if (connectToDB()) {
                    if (array_key_exists('findWineriesMerlotAndGewur', $_POST)) {
                        handleFindWineriesMerlotAndGewur();
                    }
                    disconnectFromDB();
                }
            }

            function handleFindWineriesMerlotAndGewur() {
                global $db_conn;

                // Define SQL Query to find wineries that produce all wines
                $sql = "SELECT DISTINCT w.BusinessName, w.PostalCode
                FROM Wine w
                WHERE EXISTS (
                    SELECT *
                    FROM Wine m
                    WHERE m.BusinessName = w.BusinessName
                    AND m.PostalCode = w.PostalCode
                    AND m.VarietyName = 'Gewurztraminer'
                )
                AND EXISTS (
                    SELECT *
                    FROM Wine m
                    WHERE m.BusinessName = w.BusinessName
                    AND m.PostalCode = w.PostalCode
                    AND m.VarietyName = 'Merlot'
                )";
            
                // Execute the SQL query 
                $statement = executePlainSQL($sql);

                // display just the wineries
                echo "<h3> All wineries that Have Produced Wines from Merlot and Gewurztraminer Grapes</h3>";

                echo '<table class="table table-bordered">';
                echo '<thead><tr><th>BusinessName</th><th>PostalCode</th></tr></thead>';
                echo '<tbody>';

                // Fetch and display the query results
                while ($row = oci_fetch_assoc($statement)) {
                    echo '<tr>';
                    echo '<td>' . $row['BUSINESSNAME'] . '</td>';
                    echo '<td>' . $row['POSTALCODE'] . '</td>';
                    echo '</tr>';
                }
                            
                echo '</tbody>';
                echo '</table>';

                $statement = executePlainSQL("SELECT BusinessName, PostalCode, VarietyName FROM WINE ORDER BY VarietyName ASC");

                // display all winery details
                echo "<h3> All Grapes Used by Each Winery</h3>";

                echo '<table class="table table-bordered">';
                echo '<thead><tr><th>BusinessName</th><th>PostalCode</th><th>Grape</th></tr></thead>';
                echo '<tbody>';

                // Fetch and display the query results
                while ($row = oci_fetch_assoc($statement)) {
                    echo '<tr>';
                    echo '<td>' . $row['BUSINESSNAME'] . '</td>';
                    echo '<td>' . $row['POSTALCODE'] . '</td>';
                    echo '<td>' . $row['VARIETYNAME'] . '</td>';
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