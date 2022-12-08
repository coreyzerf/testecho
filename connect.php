<?php
$connection = mysqli_connect('localhost', 'zerfca_admin', 'echolakecamp1956!');
if (!$connection){
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
}
$select_db = mysqli_select_db($connection, 'zerfca_techo');
if (!$select_db){
    die("Database Selection Failed" . mysqli_error($connection));
}
?>