<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aggregate With Having</title>
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
            <h1 class="my-5">Aggregation With Having</h1>
            <div class="section">
                <h2>Find The Wineries that Produce at Least X Number of Wines Over a Certain MSRP</h2>
                <form method="POST" action="aggregatehaving.php"> <!--refresh page when submitted-->
                    <select name="numberOfWines" id="numberOfWines">
                        <option value="">-- Choose Minimum Number of Wines --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <input type="number" class="input-field" placeholder="Price" name="MSRP">
                    <input type="submit" name="findWineries" value="Find Wineries Matching Criteria" class="submit-button btn btn-primary">
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
                    if (array_key_exists('findWineries', $_POST)) {
                        handleFindWineriesRequest();
                    }
                    disconnectFromDB();
                }
            }

            function handleFindWineriesRequest() {
                global $db_conn;
                
                $selectedMinumumNumWines = $_POST['numberOfWines'];
                $selectedMSRP = $_POST['MSRP'];

                echo "<h3> Wineries that Produce at least $selectedMinumumNumWines wines over $$selectedMSRP</h3>";
            
                // Define the SQL query to find the smallest vineyard per city
                $sql = "SELECT W.BusinessName, W.PostalCode, Count(*)
                        FROM Wine W, Bottle B
                        WHERE W.Vintage = B.Vintage AND W.WineName = B.WineName AND B.MSRP > $selectedMSRP
                        GROUP BY W.BusinessName, W.PostalCode
                        HAVING Count(*) >= $selectedMinumumNumWines";
            
                // Execute the SQL query 
                $statement = executePlainSQL($sql);
            
                echo '<table class="table table-bordered">';
                echo '<thead><tr><th>Business Name</th><th>Postal Code</th><th>Number of Wines</th></tr></thead>';
                echo '<tbody>';
                
                // Fetch and display the query results
                while ($row = oci_fetch_assoc($statement)) {
                    echo '<tr>';
                    echo '<td>' . $row['BUSINESSNAME'] . '</td>';
                    echo '<td>' . $row['POSTALCODE'] . '</td>';
                    echo '<td>' . $row['COUNT(*)'] . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
        
                OCICommit($db_conn);
            }

            function handleFindLargestVinyardRequest() {
                global $db_conn;
                echo "<h3> Largest Sizes of Vineyards By City & Province</h3>";
            
                // Define the SQL query to find the largest vineyard per city
                $sql = "SELECT PC.City, PP.Province, MAX(V.Acres)
                        FROM Vineyard V, PostalCode_City PC, PostalCode_Province PP
                        WHERE V.PostalCode = PC.PostalCode AND V.PostalCode = PP.PostalCode
                        GROUP BY PC.City, PP.Province";
            
                // Execute the SQL query 
                $statement = executePlainSQL($sql);
            
                echo '<table class="table table-bordered">';
                echo '<thead><tr><th>City</th><th>Province</th><th>Vineyard Size (Acres)</th></tr></thead>';
                echo '<tbody>';
                
                // Fetch and display the query results
                while ($row = oci_fetch_assoc($statement)) {
                    echo '<tr>';
                    echo '<td>' . $row['CITY'] . '</td>';
                    echo '<td>' . $row['PROVINCE'] . '</td>';
                    echo '<td>' . $row['MAX(V.ACRES)'] . '</td>';
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
