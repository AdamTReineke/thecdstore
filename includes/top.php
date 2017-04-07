<?php $time = microtime(); $time = explode(" ", $time); $time = $time[1] + $time[0]; $start = $time; ?>
<?php

  include 'db_connect.php';
  include 'sessions.php';
  include 'cleanup.php';

echo <<<BODYEND
<html>

<head>
  <title>$page_title</title>
</head>

<body bgcolor='333333'>
BODYEND;
echo <<<BODYEND
<table width="700px" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr><td>
    <table border=0 cellpadding=0 cellspacing=0>
      <tr>
        <td>
          <table border="0" cellpadding="0" cellspacing="0">
            <tr><td><img src='images/top_left.gif'></td></tr>
            <tr><td bgcolor='$bg_color'><img src='images/bottom_left.gif'></td></tr>
          </table>
        </td>
        <td><img src='images/center.gif' width='614' heigth='75' alt="The CD Store"></td>
        <td>
          <table border=0 cellpadding=0 cellspacing=0>
            <tr><td><img src='images/top_right.gif'></td></tr>
            <tr><td bgcolor='00ff00'><img src='images/bottom_right.gif'></td></tr>
          </table>
        </td>
      </tr>
    </table>
  </td></tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="150" valign="top" bgcolor='$bg_color' background='images/link_background.gif'>
BODYEND;
include 'links.php';
echo <<<BODYEND
\n        </td>
        <td valign="top" bgcolor='$bg_color'>
BODYEND;

?>