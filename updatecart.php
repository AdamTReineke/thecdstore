<?php
include 'includes/db_connect.php';
include 'includes/sessions.php';

foreach ($_POST as $item_numb => $quantity) {
  if ($quantity > 0) {
    mysql_query("UPDATE `cart` SET `quantity` = '$quantity' WHERE `PHPSESSID` = '$PHPSESSID' AND `item_numb` = '$item_numb'");
    }
    else {
    mysql_query("DELETE FROM `cart` WHERE `PHPSESSID` = '$PHPSESSID' AND `item_numb` = '$item_numb'");
    }
  }

mysql_close($mysql_connection);

header("Location: cart.php?updated=TRUE");
?>