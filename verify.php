<?php
include "includes/db_connect.php";

$username = $_GET['username'];
$code = $_GET['code'];

$query = mysql_fetch_assoc(mysql_query("SELECT * FROM `login_info` WHERE `username` = '$username' and `verified` = '$code'"));

if (count($query) > 1) {
    mysql_query("UPDATE `login_info` SET `verified` = 'YES' WHERE `username` = '$username'");
    echo "Account verified!";
  }
else { echo "Error verifying account.  Please contact the system administrator."; }

mysql_close($mysql_connection);
?>