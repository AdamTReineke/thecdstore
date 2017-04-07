<?php
// Sets the <title> tag
$page_title = 'the CD store - Checkout';
$bg_color = '#FF6600';

// includes the top of the page
include 'includes/top.php';

// Checkout Page
if (($session_is_signed_in == TRUE) && ($session_is_admin == FALSE)) {

$query = mysql_fetch_assoc(mysql_query("SELECT * FROM `user_info` WHERE `user_numb` = '$session_user_numb'"));
$first_name = $query['first_name'];
$last_name = $query['last_name'];
$address = $query['address'];
$city = $query['city'];
$state = $query['state'];
$zip = $query['zip'];

if (($first_name == "") || ($last_name == "") || ($address == "") || ($city == "") || ($state == "") || ($zip == "")) {
    echo "Your personal information is incomplete.  Please update this information so that we can fill your order correctly.";
echo <<<BODYEND
<table>
<form action='user_info.php' method='POST'>
<tr><td>First Name</td><td><input type='text' name='first_name'></td></tr>
<tr><td>Last Name</td><td><input type='text' name='last_name'></td></tr>
<tr><td>Address</td><td><input type='text' name='address'></td></tr>
<tr><td>City</td><td><input type='text' name='city'></td></tr>
<tr><td>State</td><td><input type='text' name='state' maxlength='2'></td></tr>
<tr><td>Zip Code</td><td><input type='text' name='zip' maxlength='5'></td></tr>
<tr><td colspan=2><input type='submit' value='Update Information -->'></td></tr>
</form>
</table>
BODYEND;


    }
if (($first_name != "") && ($last_name != "") && ($address != "") && ($city != "") && ($state != "") && ($zip != "")) {

  echo "This page would show after a payment screen, once I had a way to accept payment.  I understand this can be done with credit cards through PayPal.  For now, it just accepts the \"paid\" order and inserts it into the database.<br />The data stored is in the format: \$array[item_numb] => quantity<br /><hr />";
  
  // Query for the cart
  $fetch_cart = mysql_query("SELECT * FROM `cart` WHERE `PHPSESSID` = '$PHPSESSID'");
  
  // Save the cart to an array.
  while ($row = mysql_fetch_assoc($fetch_cart)) {
    $item_numb = $row['item_numb'];
    $quantity = $row['quantity'];
    $array[$item_numb] = $quantity;
    }
  
  // Save the main order info
  mysql_query("INSERT INTO `orders` (`phpsessid`, `user_numb`, `status`, `updated`)
    VALUES ('$phpsessid','$session_user_numb','PAID','$time')");
  
  // Get the order_id number.
  $order_id_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `orders` WHERE `phpsessid` = '$phpsessid' AND `updated` = '$time'"));
  $order_id = $order_id_query['order_id'];
  
  // save the individual orders
  foreach ($array as $item_numb => $quantity) {
    $price_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cds` WHERE `item_numb` = '$item_numb'"));
    $price = $price_query['price'];
  
    mysql_query("INSERT INTO `order_items` (`phpsessid`, `order_id`, `user_numb`, `item_numb`, `quantity`, `price`)
      VALUES ('$phpsessid', '$order_id', '$session_user_numb','$item_numb','$quantity','$price')");
    }
  echo "Order published to database.";
  }
}
// Don't let admin accounts place orders
if ($session_is_admin == TRUE) {
  echo "You cannot place orders from admin accounts.  Please sign back in with a regular account.";
  }
// And make people sign in.
if ($session_is_signed_in == FALSE) {
  echo "You need to sign in first.";
  }

// includes the bottom of the page.
include 'includes/bottom.php';


?>