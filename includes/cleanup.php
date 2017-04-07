<?php
// Clean up old sessions...
$rand = rand(1,25);
if ($rand == 25) {
  // this generates a timestamp from 120 minutes ago.
  $oldtime = mktime(date("G"),date("i")-120,date("s"),date("n"),date("j"),date("Y"));
  // this query fetches all sessions older than 120 minutes
  $query = mysql_query("SELECT * FROM `sessions` WHERE `date` <= '$oldtime'");

    // this saves the PHPSESSIDs into an array
    while ($row = mysql_fetch_assoc($query)) {
      $oldsessid[] = $row['PHPSESSID'];
    }
    // and this goes through the array and removes the old sessions from the database.
  if (isset($oldsessid)) {
    foreach ($oldsessid as $old_phpsessid) {
      mysql_query("DELETE FROM `sessions` WHERE `PHPSESSID` = '$old_phpsessid'");
      }
  }
  else {}
  $cleanup1 = TRUE;
}
$rand2 = rand(1,250);
if ($rand2 == 250) {
// Optimize the database
mysql_query("OPTIMIZE TABLE `admin`");
mysql_query("OPTIMIZE TABLE `artists`");
mysql_query("OPTIMIZE TABLE `cart`");
mysql_query("OPTIMIZE TABLE `cds`");
mysql_query("OPTIMIZE TABLE `featured`");
mysql_query("OPTIMIZE TABLE `labels`");
mysql_query("OPTIMIZE TABLE `login_info`");
mysql_query("OPTIMIZE TABLE `order_items`");
mysql_query("OPTIMIZE TABLE `orders`");
mysql_query("OPTIMIZE TABLE `sessions`");
mysql_query("OPTIMIZE TABLE `tracks`");
mysql_query("OPTIMIZE TABLE `user_info`");

$cleanup2 = TRUE;
}
if ($cleanup1 == TRUE) { $cleanup = "Session Cleanup performed.  "; }
if ($cleanup2 == TRUE) { $cleanup = $cleanup + "Database optimized."; }

?>