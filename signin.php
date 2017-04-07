<?php
include "includes/db_connect.php";
include "includes/sessions.php";

if (isset($_POST['username']) && isset($_POST['password'])) {

$username = $_POST['username'];
$password = md5($_POST['password']);
$login = 0;
$admin = 0;
$referrer = $_SERVER['HTTP_REFERER'];

$redirect1 = "<html>
<head>
<script type=\"text/javascript\">
<!--
function delayer(){
window.location = \"$referrer\"
}
//-->
</script>
</head>
<body onLoad=\"setTimeout('delayer()', 3000)\">
<h2 >";
$redirect2 = "</h2>
</body>
</html>";


// Check to see if used an admin username...
$check_admin = strncmp($username, "admin.", 6);

// If they did provide the admin syntax, do this.
if ($check_admin == 0) { 
  // First, trim the admin syntax from their username
  $username = str_replace("admin.", "", $username);
  // Query the admin table for info
  $login_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `admin` WHERE `username` = '$username' AND `password` = '$password'"));
  // If info is found, lets log 'em in!
  if (count($login_query) > 1) {
    $login = 1; $admin = 1;  // Set login status to TRUE, and admin status to TRUE.
    $user_numb = $login_query['user_numb'];
    echo $redirect1;  // First part of redirect javascript.
    echo "Admin login accepted.  Redirecting...";
    echo $redirect2;  // 2nd part of redirect javascript.
  }
  // If not, don't.  Heh.
  else { echo "Administrator login not accepted.  Please go back and try again."; }
  }

// If they didn't, proceed normally.
else {
  $login_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `login_info` WHERE `username` = '$username' AND `password` = '$password'"));
  if ((count($login_query) > 1) && ($login_query['verified'] == "YES")) {
    $login = 1; $admin = 0;  // Set login status to TRUE, but admin status to FALSE.
    $user_numb = $login_query['user_numb'];
    echo $redirect1;  // First part of redirect javascript.
    echo "Login accepted.  Redirecting...";
    echo $redirect2;  // 2nd part of redirect javascript.
  }
  elseif ($login_query['verified'] != "YES") {
    echo "You need to verify your account before you can use it.";
  }
  // If not, don't.  Heh
  else { echo "Login info not accepted.  Please go back and try again."; }
}

$phpsessid = $_COOKIE['PHPSESSID'];
if ($login == 1) {
mysql_query("UPDATE `sessions` SET `username` = '$username', `login` = '$login', `admin` = '$admin', `user_numb` = '$user_numb'
  WHERE `PHPSESSID` = '$phpsessid' LIMIT 1");
  }

}
else { header("Location: ."); }
// disconnect from database
mysql_close($mysql_connection);
?>