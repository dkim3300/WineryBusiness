<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Insert</title>
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
            <h1 class="my-5">Insert</h1>

            <div class="section">
                <h2>Create a New Wine</h2>
                <p><i>* Required fields</i></p>
                <form method="POST" action="insert.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="insertWine" name="insertWineRequest">
                    <input type="number" class="input-field" placeholder="Vintage*" name="Vintage">
                    <input type="text" class="input-field" placeholder="WineName*" name="WineName">
                    <input type="text" class="input-field" placeholder="WineType" name="WineType">
                    <input type="number" class="input-field" placeholder="AlcoholPercent" name="AlcoholPercent">
                    <input type="text" class="input-field" placeholder="Body" name="Body">
                    <input type="text" class="input-field" placeholder="Acidity" name="Acidity">
                    <input type="text" class="input-field" placeholder="BusinessName*" name="BusinessName">
                    <input type="text" class="input-field" placeholder="PostalCode*" name="PostalCode">
                    <input type="text" class="input-field" placeholder="VarietyName*" name="VarietyName">
                    <input type="submit" name="insertWine" value="Insert" class="submit-button btn btn-primary">
                </form>
            </div>

            <div class="section">
                <h2>Show All Wines</h2>
                <form method="POST" action="insert.php"> <!--refresh page when submitted-->
                    <input type="submit" class="submit-button btn btn-primary" name="showAllWine" value="Show All">
                </form>
            </div>

        </div>

        <div class="table-display">
            <?php
            include 'util/db.php';
            include 'util/print.php';

            if (isset($_POST['showAllWine'])) {
                connectToDB();
                echo '<h3>Wines</h3>';
                $query = "SELECT * FROM Wine ORDER BY BusinessName, PostalCode";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }

            if (isset($_POST['insertWine'])) {
                handlePOSTRequest();
            }

            function handlePOSTRequest() {
                if (connectToDB()) {
                    if (array_key_exists('insertWineRequest', $_POST)) {
                        handleInsertWineRequest();
                    }
                    disconnectFromDB();
                }
            }

            function handleInsertWineRequest() {
                global $db_conn;

                //Getting the values from user and insert data into the table
                $tuple = array (
                    ":bind1" => $_POST['Vintage'],
                    ":bind2" => $_POST['WineName'],
                    ":bind3" => $_POST['WineType'],
                    ":bind4" => $_POST['AlcoholPercent'],
                    ":bind5" => $_POST['Body'],
                    ":bind6" => $_POST['Acidity'],
                    ":bind7" => $_POST['BusinessName'],
                    ":bind8" => $_POST['PostalCode'],
                    ":bind9" => $_POST['VarietyName']
                );
    
                $alltuples = array (
                    $tuple
                );
    
                $success = executeBoundSQL("Insert into Wine values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8, :bind9)", $alltuples);
                
                if ($success) {
                    echo "Successfully inserted new Wine!";
                } else {
                    echo "Sorry! There was an error in inserting the Wine. Please check your input.";
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