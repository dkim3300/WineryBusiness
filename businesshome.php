<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Owner Wine Business Database</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .navigation-buttons {
            display: grid;
            grid-template-columns: 30% 50% 20%;
            gap: 10px;
        }
        .navigation-buttons button, .navigation-buttons label {
            width: 100%;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body class="bg-grey container my-5">

    <div class="title">
        <h1 class="my-5">Welcome to CPSC 304 Wine Business Database!</h1>
        <h2 class="mb-4">As a business owner, below are your options:</h2>
    </div>
    <br >
    <div class=navigation-buttons>
        <label for="insert" class="form-label">Insert:</label>
        <button type="submit" onclick="window.location.href='insert.php'" name="insert" value="insert" class="btn btn-primary">Create a New Wine</button>
    </div>
    <br>
    <div class=navigation-buttons>
        <label for="delete" class="form-label">Delete:</label>
        <button type="submit" onclick="window.location.href='delete.php'" name="delete" value="delete" class="btn btn-primary">Fire A WineGrower</button>
    </div>
    <br>
    <div class=navigation-buttons>
        <label for="update" class="form-label">Update:</label>
        <button type="submit" onclick="window.location.href='update.php'" name="update" value="update" class="btn btn-primary">Update</button>
    </div>
    <br>
    <div class=navigation-buttons>
        <label for="selection" class="form-label">Selection:</label>
        <button type="submit" onclick="window.location.href='selection.php'" name="selection" value="selection" class="btn btn-primary">Selection</button>
    </div>
    <br>
    <div class=navigation-buttons>
        <label for="projection" class="form-label">Projection:</label>
        <button type="submit" onclick="window.location.href='projection.php'" name="projection" value="projection" class="btn btn-primary">Projection</button>
    </div>
    <br >
    <div class=navigation-buttons>
        <label for="join" class="form-label">Join:</label>
        <button type="submit" onclick="window.location.href='join.php'" name="join" value="join" class="btn btn-primary">Find Parcels By Wine Grower Experience</button>
    </div>
    <br>
    <div class=navigation-buttons>
        <label for="aggregategroup" class="form-label">Aggregation With Group By:</label>
        <button type="submit" onclick="window.location.href='aggregategroup.php'" name="aggregategroup" value="aggregategroup" class="btn btn-primary">Find The Size of the Smallest/Largest Vineyards</button>
    </div>
    <br>
    <div class=navigation-buttons>
        <label for="aggregatehaving" class="form-label">Aggregation With Having:</label>
        <button type="submit" onclick="window.location.href='aggregatehaving.php'" name="aggregatehaving" value="aggregatehaving" class="btn btn-primary">Find The Wineries that Produce a Certain Number of Wines Over a Certain MSRP</button>
    </div>
    <br>
    <div class=navigation-buttons>
        <label for="nestedaggregategroup" class="form-label">Nested Aggregation With Group By:</label>
        <button type="submit" onclick="window.location.href='nestedaggregategroup.php'" name="nestedaggregategroup" value="nestedaggregategroup" class="btn btn-primary">
            Find the revenue of the highest revenue winery with revenue over $100,000, 
            for each city for which the average revenue of the wineries with revenue over $100,000 is 
            higher than the average revenue of all wineries across all cities.
        </button>
    </div>
    <br>
    <div class=navigation-buttons>
        <label for="division" class="form-label">Division:</label>
        <button type="submit" onclick="window.location.href='division.php'" name="division" value="division" class="btn btn-primary">Find Wineries that Produce both Merlot and Gewurztraminer Wines</button>
    </div>

    <!-- Reset Database Button -->
    <br>
    <div class="reset">
        <h2>Reset Database</h2>
            <form method="POST" action="businesshome.php"> <!--refresh page when submitted-->
                <input type="submit" class="submit-button btn btn-danger" name="resetDatabase" value="Reset Database to Default">
            </form>
    </div>

    <?php
    include 'util/db.php';
    include 'util/print.php';

    if (isset($_POST['resetDatabase'])) {
        connectToDB();
            
        $sqlScript = file_get_contents('winebusiness.sql');
        $sqlStatements = explode(';', $sqlScript); // Split the script into individual statements
            
        $success = true;
            
        foreach ($sqlStatements as $sqlStatement) {
            $sqlStatement = trim($sqlStatement);
            if (!empty($sqlStatement)) {
                $success = $success && executePlainSQL($sqlStatement);
            }
        }
            
        if ($success) {
            echo "Database successfully reset to default.";
        } else {
            echo "Failed to reset the database.";
        }

        OCICommit($db_conn);
            
        disconnectFromDB();
    }
    ?>

    <!-- Logout Button -->
    <br>
    <div class="reset">
        <h2>Logout</h2>
        <a href="index.php" class="btn btn-secondary">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>