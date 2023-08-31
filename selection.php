<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selection</title>
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
        <h1 class="my-5">Selection</h1>
        
        <div class="section">
            <h3 class="mb-3">Select a Table</h3>
            <form method="POST">
                <div class="mb-3">
                    <select name="table" class="form-select d-flex mb-3 w-50" onchange="this.form.submit()">
                        <option value="">-- Choose a table --</option>
                        <option value="winery" <?php echo ($table == 'winery') ? 'selected' : ''; ?> >Winery</option>
                        <option value="wine" <?php echo ($table == 'wine') ? 'selected' : ''; ?> >Wine</option>
                        <option value="tastingroom" <?php echo ($table == 'tastingroom') ? 'selected' : ''; ?> >Tasting Room</option>
                        <option value="winetasting" <?php echo ($table == 'winetasting') ? 'selected' : ''; ?> >Wine Tasting</option>
                        <option value="vineyard" <?php echo ($table == 'vineyard') ? 'selected' : ''; ?> >Vineyard</option>
                        <option value="grape" <?php echo ($table == 'grape') ? 'selected' : ''; ?> >Grape</option>
                        <option value="winegrower" <?php echo ($table == 'winegrower') ? 'selected' : ''; ?> >Winegrower</option>
                        <option value="bottle" <?php echo ($table == 'bottle') ? 'selected' : ''; ?> >Bottle</option>
                    </select>
                    
                    <h5> Select Attributes: </h5>
                    
                        <?php
                        connectToDB();
                        if ($table) {
                            $query = "SELECT column_name FROM all_tab_columns WHERE table_name='".strtoupper($table)."' ORDER by column_id";
                            $result = executePlainSQL($query);

                            echo "<div class='checkboxes d-flex my-3'>";

                            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                                $attribute_name = $row[0];
                                $checked = isset($_POST[$attribute_name]) ? 'checked' : '';
                                echo "<div class='mx-2'>
                                        <label for='$attribute_name'>  $attribute_name </label>
                                        <input type='checkbox' name='$attribute_name' $checked>
                                    </div>";     
                            }
                            echo "</div>
                                <div class='w-50 d-flex'>
                                    <input type='string' class='input-field' placeholder='Field 1' name='field-1'>
                                    <input type='string' class='input-field' placeholder='Condition (=, >, < etc.)' name='condition-1'>
                                    <input type='string' class='input-field' placeholder='' name='var-1'>
                                    <select name='data-type-1' class='form-select' style='width: 200px;'>
                                        <option value='number'>Number</option>
                                        <option value='string'>String</option>
                                    </select>
                                </div>
                                <div class='w-50 d-flex'>
                                    <input type='string' class='input-field' placeholder='Field 2' name='field-2'>
                                    <input type='string' class='input-field' placeholder='Condition (=, >, < etc.)' name='condition-2'>
                                    <input type='string' class='input-field' placeholder='' name='var-2'>
                                    <select name='data-type-2' class='form-select' style='width: 200px;'>
                                        <option value='number'>Number</option>
                                        <option value='string'>String</option>
                                    </select>
                                </div>";   
                        }
                        disconnectFromDB();
                        ?>

                    </div>

                    <input type="submit" name="submitSelect" value="Submit" class="submit-button btn btn-primary">
                </div>
                <div class="">
                    <?php 
                        if (isset($_POST['submitSelect'])) {
                            $field_1 = (isset($_POST['field-1']) ? $_POST['field-1'] : "");
                            $cond_1 = (isset($_POST['condition-1']) ? $_POST['condition-1'] : "");
                            $var_1 = (isset($_POST['var-1']) ? $_POST['var-1'] : "");
                            $data_type_1 = (isset($_POST['data-type-1']) ? $_POST['data-type-1'] : "");

                            $field_2 = (isset($_POST['field-2']) ? $_POST['field-2'] : "");
                            $cond_2 = (isset($_POST['condition-2']) ? $_POST['condition-2'] : "");
                            $var_2 = (isset($_POST['var-2']) ? $_POST['var-2'] : "");
                            $data_type_2 = (isset($_POST['data-type-2']) ? $_POST['data-type-2'] : "");


                            $selected_attributes = "";
                            foreach ($_POST as $key => $value) {
                                if ($key !== 'table' && $key !== 'submitSelect' && $value == 'on') {
                                    $attribute_name = str_replace('_', '', $key);
                                    $selected_attributes .= $attribute_name . ', ';
                                }
                            }
                            $selected_attributes = rtrim($selected_attributes, ', ');

                            $query = "SELECT " . $selected_attributes . 
                                     " FROM " . $_POST['table'] .
                                     (!empty($field_1) && !empty($cond_1) && !empty($var_1) ? " WHERE " . $field_1 . $cond_1 . ($data_type_1 == 'string' ? "'" : "") . $var_1 . ($data_type_1 == 'string' ? "'" : "") : "") .
                                     (!empty($field_2) && !empty($cond_2) && !empty($var_2) ? " AND " . $field_2 . $cond_2 . ($data_type_2 == 'string' ? "'" : "") . $var_2 . ($data_type_2 == 'string' ? "'" : "") : "");

                            
                            echo "<p>" .
                                (!empty($field_1) && !empty($cond_1) && !empty($var_1) ? $field_1 . " " . $cond_1 . " " . $var_1 : "") .
                                (!empty($field_2) && !empty($cond_2) && !empty($var_2) ? " and " . $field_2 . " " . $cond_2 . " " . $var_2 : "")
                                . "</p>";

                            $result = executePlainSQL($query);
                            printResult($result);
                        }
                        $selected_attributes = "";
                    ?>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>

</html>