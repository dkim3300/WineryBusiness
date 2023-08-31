<?php


    function printResult($result) { //prints results from a select statement
        // modified from: https://www.php.net/manual/en/function.oci-execute.php
        echo "<div class='overflow-auto'>";
        echo "<table class='text-align-center w-100'>";
        $header = false;

        while ($row = OCI_Fetch_Array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {

            if (!$header) {
                echo "<tr>";
                foreach (array_keys($row) as $col) {
                    echo "<th>" . ($col !== null ? htmlentities($col, ENT_QUOTES) : "&nbsp;") . "</th>\n";
                }
                echo "</tr>";
                $header = true;
            }

            echo "<tr>";
            foreach ($row as $item) {
                echo "<td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
            }
            echo "</tr>";
        }
        echo "</table>";
    }