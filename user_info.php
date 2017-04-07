<?php
// Sets the <title> tag
$page_title = 'the CD store - Update User Information';
$bg_color = '#FF6600';

if (count($_POST) > 0) {
  include 'includes/db_connect.php';
  include 'includes/sessions.php';

  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $zip = $_POST['zip'];

  mysql_query("UPDATE `user_info` SET
    `first_name` = '$first_name',
    `last_name` = '$last_name',
    `address` = '$address',
    `city` = '$city',
    `state` = '$state',
    `zip` = '$zip'
    WHERE `user_numb` = '$session_user_numb'");
  mysql_close($mysql_connection);

  $referrer = $_SERVER['HTTP_REFERER'];
  header("Location: $referrer");
  }
else {

// includes the top of the page
include 'includes/top.php';

  $query = mysql_fetch_assoc(mysql_query("SELECT * FROM `user_info` WHERE `user_numb` = '$session_user_numb'"));
  
  $first_name = $query['first_name'];
  $last_name = $query['last_name'];
  $address = $query['address'];
  $city = $query['city'];
  $state = $query['state'];
  $zip = $query['zip'];
  
  if ($session_is_signed_in == TRUE) {
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
      echo "Personal information saved.";
      }
    }
  else {
    echo "You need to be signed in to update your user info.";
    }
// includes the bottom of the page.
include 'includes/bottom.php';

}

?>