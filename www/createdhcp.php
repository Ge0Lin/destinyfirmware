<?php

    // List of variables to be assigned to whatever incoming value you need to work with
    // Anything assigned as to be in sets of 3's inside the quotes seperated by dashes as you asked for IE 'MAC-IP-pcname'
    $mac1 = "aa:bb:cc:dd:ee:ff";
    $mac2 = "74:D4:35:15:4C:FC";
    $mac3 = "40:b8:37:c4:1b:60";

    $ip1 = "192.168.20.2";
    $ip2 = "192.168.20.3";
    $ip3 = "192.168.20.249";

    $pcname1 = "SamplePC";
    $pcname2 = "Desktop-PC";
    $pcname3 = "JosiahZ5";

    // Create an associative array with all variables in it
    $varArray = array(
        "set1"  =>  $mac1 . "-" . $ip1 . "-" . $pcname1,
        "set2"  =>  $mac2 . "-" . $ip2 . "-" . $pcname2,
        "set3"  =>  $mac3 . "-" . $ip3 . "-" . $pcname3
    );

    // Function for writing the incoming values to the file
    function writeToFile() {
        global $varArray;

        // Open file for writing
        $file = fopen("/etc/config/dhcp.json", "w") or die("Unable to open file!");
        fwrite($file, serialize($varArray));

        // Close file
        fclose($file);
    }

    // Function for reading the values stored in the file
    function readFromFile() {
        // Open file and unserializethe contents
        $varData = unserialize(file_get_contents("/etc/config/dhcp.json22"));

        // Output variable for testing to see if the reading and unserializing is actually happening
        var_dump($varData);
    }

    writeToFile();
    readFromFile();
?>

