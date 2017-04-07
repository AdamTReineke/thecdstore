<?php
// Sets the <title> tag
$page_title = 'the CD store - Register';
$bg_color = '##00CC00';

// includes the top of the page
include 'includes/top.php';

if ((isset($_GET['page'])) && ($_GET['page'] == 2))
{
$username = $_POST['username1'];
$password = md5($_POST['password1']);
$confirm_password = md5($_POST['confirm_password']);
$email = $_POST['email'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];

// Check for the username in the database
$check_query = mysql_query("SELECT * FROM `login_info` WHERE `username` = '$username'");
$row = mysql_fetch_assoc($check_query);
if ((count($row) == 1) && (isset($failure) == FALSE)) {
  // Insert the stuff into the 'login_info' table.
  $insert_query = mysql_query("INSERT INTO `login_info` (`username` , `password` , `email` )
    VALUES ('$username' , '$password' , '$email')");
  // Fetch the user_numb
  $numb_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `login_info` WHERE `username` = '$username'"));
  $user_numb = $numb_query['user_numb'];
  // Save any additionally entered data to user_info table.
  $userinfo_query = mysql_query("INSERT INTO `user_info`
    (`user_numb`, `first_name`, `last_name`, `address`, `city`, `state`, `zip`) VALUES
    ('$user_numb', '$first_name', '$last_name', '$address', '$city', '$state', '$zip')");
  }
else {
  // Don't overwrite the failure message, if it was triggered earlier.
  if (isset($failure) == FALSE) {$failure = "The username is already being used.";} }

// Compare the given passwords.  If they don't match, set $failure.
if (strcmp($password, $confirm_password) == 0) { $password = md5($password); }
else {
  // Don't overwrite the failure message, if it was triggered earlier.
  if (isset($failure) == FALSE) {$failure = "Passwords didn't match.";} }

// If a failure message was generated, display it, otherwise, let the user know he's good to go.
if (isset($failure) == FALSE) {
  echo "Registration accepted.";
  
// This is where a registration verification e-mail would be sent with the generated link.
  // Generate a weird md5 hash, based on the time, username, password, and a random number.
  $time = time();
  $rand = rand(100000,999999);
  $verify_code = "$username"."$password"."$time"."$rand";
  $verify = md5($verify_code);
  $verify_query = mysql_query("UPDATE `login_info` SET `verified` = '$verify'
      WHERE `username` = '$username' LIMIT 1");
  
  echo "(Normally, this link would be sent in an e-mail, to verify the e-mail address.)<br />To activate your account, click <a href='verify.php?username=$username&code=$verify'>here.</a>";
  }
else { echo $failure . "  Please go back."; }
}

else {
echo <<<BODYEND
<h3>Register A New User</h3>
<form action='register.php?page=2' name='register' method='POST'>
<table>
<tr><td>Username</td><td align='left'><input type='text' name='username1'></td></tr>
<tr><td>Password</td><td><input type='password' name='password1'></td></tr>
<tr><td>Confirm Password</td><td><input type='password' name='confirm_password'></td></tr>
<tr><td>E-mail Address</td><td><input type='text' name='email'></td></tr>
<tr><td colspan='2'>The following fields are not required, but are recommended.</td></tr>
<tr><td>First Name</td><td><input type='text' name='first_name'></td></tr>
<tr><td>Last Name</td><td><input type='text' name='last_name'></td></tr>
<tr><td>Address</td><td><input type='text' name='address'></td></tr>
<tr><td>City</td><td><input type='text' name='city'></td></tr>
<tr><td>State</td><td><input type='text' name='state' maxlength='2'></td></tr>
<tr><td>Zip Code</td><td><input type='text' name='zip' maxlength='5'></td></tr>
<tr><td colspan='2'><input type='submit' name='register' value='Register!'></td></tr>
</table>
</form>
BODYEND;
}
// includes the bottom of the page.
include 'includes/bottom.php';


?>