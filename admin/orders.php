<?php $time = microtime(); $time = explode(" ", $time); $time = $time[1] + $time[0]; $start = $time; ?>
<?php

include "../includes/admincheck.php";

if (count($_GET) < 1) {
// Select all orders that haven't been shipped.
$query = mysql_query("SELECT * FROM `orders` WHERE `status` != 'SHIPPED' ORDER BY `updated` ASC");

// Write the table, with colums for ...
echo "<table border=1>";
echo "<tr><td>Order ID</td><td>User</td><td>Date</td><td>Status</td><td>PHPSESSID</td></tr>";

while ($row = mysql_fetch_assoc($query)) {
  $order_id = $row['order_id'];
  $order_sessid = $row['phpsessid'];
  $user_numb = $row['user_numb'];
  $status = $row['status'];
  $updated = $row['updated'];
  
  echo "<tr>";
  // Order ID..
  echo "<td><a href='orders.php?order_id=$order_id'>$order_id</a></td>";
  // User..
  $user_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `login_info` WHERE `user_numb` = '$user_numb'"));
  $username = $user_query['username'];
  echo "<td>$username</td>";
  // Date..
  echo "<td>".date("M d, Y g:ia","$updated")."</td>";
  // Status..
  echo "<td>$status</td>";
  // PHPSESSID..
  echo "<td>$order_sessid</td></tr>";

  }
echo "</table>";
}

if (isset($_GET['shipped'])) {
  $order_id = $_GET['shipped'];
  $time = time();
  $query = "UPDATE `orders` SET `status` = 'SHIPPED', `updated` = '$time' WHERE CONCAT( `order_id` ) = '$order_id' LIMIT 1";
  mysql_query($query);
  echo "Order shipped.  <a href='orders.php'>Back to order screen.</a>";
  }

if (isset($_GET['order_id'])) {
  $order_id = $_GET['order_id'];

  $order_query = mysql_query("SELECT * FROM `orders` WHERE `order_id` = '$order_id'");
  while ($row = mysql_fetch_assoc($order_query)) {
    $date = $row['updated'];
    $status = $row['status'];
    }

  $order_query = mysql_query("SELECT * FROM `order_items` WHERE `order_id` = '$order_id'");
  while ($row = mysql_fetch_assoc($order_query)) {
    $item_numb = $row['item_numb'];
    $user_numb = $row['user_numb'];
    $quantity = $row['quantity'];
    $price = $row['price'];
    $array[$item_numb][quantity] = $quantity;
    $array[$item_numb][price] = $price;
  }

  $user_query = mysql_query("SELECT * FROM `user_info` WHERE `user_numb` = '$user_numb'");
  while ($row = mysql_fetch_assoc($user_query)) {
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $address = $row['address'];
    $city = $row['city'];
    $state = $row['state'];
    $zip = $row['zip'];
  }
echo "<pre>
ORDER ID: $order_id
STATUS: $status [<a href='orders.php?shipped=$order_id'>MARK ORDER AS SHIPPED</a>]

SHIPPING INFORMATION:
  $first_name $last_name
  $address
  $city, $state  $zip

<table border=1><tr><td>ITEM NAME</td><td>QUANTITY</td><td>ITEM PRICE</td><td>SUBTOTAL</td></tr>
";

$total_subtotal = 0;
foreach ($array as $item_numb => $item) {
  $quantity = $item['quantity'];
  $price = $item['price'];
  $subtotal = $quantity * $price;
  $total_subtotal = $subtotal + $total_subtotal;
  $query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cds` WHERE `item_numb` = '$item_numb'"));
  $item_name = $query['item_name'];
  echo "<tr><td>$item_name</td><td>$quantity</td><td>$$price</td><td>$$subtotal</td></tr>";
  }
echo "<tr><td></td><td></td><td>Subtotal</td><td>$total_subtotal</td></tr></table>";
}
?>
<?php $time = microtime(); $time = explode(" ", $time); $time = $time[1] + $time[0]; $finish = $time; $totaltime = ($finish - $start); printf ("<!--\n\tPage generated in %f seconds.\n-->\n</html>", $totaltime); ?>