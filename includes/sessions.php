<?php

session_start();

$time = time();

// If the SID isn't empty, then the session just started, so lets save the sessid to the database.
  if (SID != "") {
    $explode_SID = explode("=", SID);
    $phpsessid = $explode_SID[1];
    $insert_session_query = mysql_query("INSERT INTO `sessions` (`PHPSESSID`) VALUES ('$phpsessid')");
    }
// If it is empty, then fetch the PHPSESSID which will have been saved into a cookie.
  else {
    $phpsessid = $_COOKIE['PHPSESSID'];
    }

// Query the database for the session's other data.
$session_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `sessions` WHERE `PHPSESSID` = '$phpsessid'"));
$session_id = $phpsessid;
$session_username = $session_query['username'];
$session_user_numb = $session_query['user_numb'];
$session_is_signed_in = $session_query['login'];
$session_is_admin = $session_query['admin'];


// Update the session timestamp for the most recent activity.
mysql_query("UPDATE `sessions` SET `date` = '$time' WHERE `PHPSESSID` = '$phpsessid' LIMIT 1");

?>