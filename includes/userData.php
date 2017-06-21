<?php
include_once 'db_connect.php';

$email = 'manager@example.com';
$query = "SELECT userPriv, employeeID FROM members WHERE email = '".$email."'";
if ($stmt2 = $mysqli->prepare($query)) {
    $stmt2->execute();
    $stmt2->bind_result($name, $code);
    while ($stmt2->fetch()) {
        printf ("%s (%s)\n", $name, $code);
    }
    $stmt2->close();
}
?>