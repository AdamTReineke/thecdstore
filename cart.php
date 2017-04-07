<?php
// Sets the <title> tag
$page_title = 'the CD store - Shopping Cart';
$bg_color = '#FF6600';

// includes the top of the page
include 'includes/top.php';

// Add $_GET to cart, if its set
if (isset($_GET['add'])) {
  $item_numb = $_GET['add'];
  $check_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cart` WHERE `PHPSESSID` = '$phpsessid' AND `item_numb` = '$item_numb'"));
  if (count($check_query) == 1) {
    $query = mysql_query("INSERT INTO `cart` (`PHPSESSID`, `item_numb`, `quantity`) VALUES ('$PHPSESSID','$item_numb','1')");
    echo "Item added to cart.<br /><hr />";
    }
  else {
    $quantity = $check_query['quantity'];
    $newquantity = $quantity + 1;
    mysql_query("UPDATE `cart` SET `quantity` = '$newquantity' WHERE `PHPSESSID` = '$phpsessid' AND `item_numb` = '$item_numb'");
    }
  }
if ($_GET['updated'] == TRUE) {
  echo "Quantities updated.<br /><hr />";
  }
else {}

$query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cart` WHERE `PHPSESSID` = '$PHPSESSID'"));
if (count($query) == 1) {
  echo "Your shopping cart is empty.";
  }

else {
// Query for the cart
$fetch_cart = mysql_query("SELECT * FROM `cart` WHERE `PHPSESSID` = '$PHPSESSID'");

  // Save the cart to an array.
  while ($row = mysql_fetch_assoc($fetch_cart)) {
    $item_numb = $row['item_numb'];
    $quantity = $row['quantity'];
    $array[$item_numb] = $quantity;
    }

  // Disable the checkout button if they aren't signed in.
  if ($session_is_signed_in != TRUE) { $disabled = "DISABLED"; }

  // New way to display cart.
  echo "<table><tr>";
  echo "<td><table><tr><td valign='top'><form action='checkout.php' method='POST'>";
  // Display item name and prepare for checkout
  foreach ($array as $item_numb => $quantity) {
    $fetch_name = mysql_fetch_assoc(mysql_query("SELECT `item_name` FROM `cds` WHERE `item_numb` = '$item_numb'"));
    $item_name = $fetch_name['item_name'];
    echo "<input type='hidden' name='$item_numb' value='$quantity'>$item_name<br />";
    }
  echo "<input type='submit' value='Checkout -->' $disabled></form></td><td valign='top'><form action='updatecart.php' method='POST'>";
  // Display item quantity and prepare for update
  foreach ($array as $item_numb => $quantity) {
    $item_name = $fetch_name['item_name'];
    echo "<input type='text' size=2 name='$item_numb' value='$quantity'><br />";
    }
  echo "<input type='submit' value='Update'></form></td><td valign='top'>";
  // Display price and subtotal
  $subtotal = 0;
  foreach ($array as $item_numb => $quantity) {
    $query = mysql_fetch_array(mysql_query("SELECT `price` FROM `cds` WHERE `item_numb` = '$item_numb'"));
    $price = $query['price'];
    $combinedprice = $query['price'] * $quantity;
    $subtotal = $subtotal + $combinedprice;
    echo "$$combinedprice ($quantity x $price)<br />";
    }
  echo "<b>$$subtotal</b>";
  echo "</td></tr><tr><td colspan=3>";

  // Print the error message if they aren't signed in.
  if (isset($disabled)) { echo "You need to sign in before you can checkout."; }

  echo "</td></tr></table></td></tr></table>";
}

// includes the bottom of the page.
include 'includes/bottom.php';
?>