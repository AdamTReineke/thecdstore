<?php
include "includes/db_connect.php";

$phpsessid = $_COOKIE['PHPSESSID'];
mysql_query("UPDATE `sessions` SET `username` = '', `login` = '0', `admin` = '0', `user_numb` = '0'
  WHERE `PHPSESSID` = '$phpsessid' LIMIT 1");

echo "You were logged out successfully.  You can now close your browser, or go back to <a href='index.php'>our home page</a>.";

mysql_close($mysql_connection);
?>