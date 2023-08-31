<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nested Aggregation with Group By</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<?php 
    include 'util/db.php';
    include 'util/print.php';

    $table = (isset($_POST['table']) ? $_POST['table'] : null);
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

    <div class="">
        <h1 class="my-5">Nested Aggregation with Group By</h1>
        
        <div class="section mb-5">
            <h5 class="mb-3">
                Find the revenue of the highest revenue winery with revenue over $100,000, 
                for each city for which the average revenue of the wineries with revenue over $100,000 is 
                higher than the average revenue of all wineries across all cities.
            </h5>
            <form method="GET" action="nestedaggregategroup.php">
                <div class="mb-3">
                    

                    <input type="submit" name="submitFind" value="Find" class="submit-button btn btn-primary">
                </div>
                <div>
                <?php
                    if (isset($_GET['submitFind'])) {
                        connectToDB();
                        $query = "SELECT MAX(w.revenue), pc.city
                                FROM WINERY w, POSTALCODE_CITY pc, POSTALCODE_PROVINCE pp
                                WHERE w.postalcode = pc.postalcode AND w.postalcode = pp.postalcode AND w.revenue > 100000
                                GROUP BY pc.city
                                HAVING AVG(w.revenue) > (SELECT AVG(revenue) FROM WINERY)";

                        $result = executePlainSQL($query);
                        printResult($result);
                        disconnectFromDB();
                    }
                ?>
                </div>
            </form>
        </div>

        <div class="section pt-5">
            <h5>All Wineries</h5>
            <?php       
                connectToDB();
                $query = "SELECT *
                            FROM WINERY w, POSTALCODE_CITY pc, POSTALCODE_PROVINCE pp
                            WHERE w.postalcode = pc.postalcode AND w.postalcode = pp.postalcode";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>

</html>