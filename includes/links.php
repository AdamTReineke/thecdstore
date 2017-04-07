<?php

// $session = mysql_fetch_assoc(mysql_query("SELECT * FROM `sessions` WHERE `PHPSESSID` = '$phpsessid'"));

echo "<table cellpadding=0 cellspacing=0>\n";
echo "\t<tr><td><img src='images/link_top.gif' width='150' height='26' border='0'></td></tr>\n";
echo "\t<tr><td><a href='index.php'><img src='images/link_home.gif' alt='the CD store' width='150' heigth='22' border='0'></a></td></tr>\n";
echo "\t<tr><td><a href='register.php'><img src='images/link_login.gif' alt='login / register' width='150' heigth='31' border='0'></a></td></tr>\n";


if ($session_is_admin == 1) {
  echo "\t<tr><td><a href='admin/portal.php'><img src='images/link_admin.gif' alt='administrator portal' width='150' heigth='31' border='0'></a></td></tr>\n";
  echo "\t<tr><td><a href='admin/insert.php'><img src='images/link_admin_addcd.gif' alt='add a cd' width='150' heigth='31' border='0'></a></td></tr>\n";
  echo "\t<tr><td><a href='admin/albumart.php'><img src='images/link_admin_addalbumart.gif' alt='add album art' width='150' heigth='31' border='0'></a></td></tr>\n";
  echo "\t<tr><td><a href='admin/orders.php'><img src='images/link_admin_orders.gif' alt='view the orders' width='150' heigth='31' border='0'></a></td></tr>\n";
  }

echo "\t<tr><td><a href='browse.php'><img src='images/link_browse_all.gif' alt='browse cds' width='150' heigth='31' border='0'></a></td></tr>\n";
echo "\t<tr><td><a href='browse.php?by=artist'><img src='images/link_browse_artist.gif' width='150' heigth='31' border='0'></a></td></tr>\n";
echo "\t<tr><td><a href='browse.php?by=album'><img src='images/link_browse_album.gif' width='150' heigth='31' border='0'></a></td></tr>\n";
echo "\t<tr><td><a href='search.php'><img src='images/link_search.gif' width='150' heigth='31' border='0'></a></td></tr>\n";
echo "\t<tr><td><a href='cart.php'><img src='images/cart.gif' width='150' heigth='31' border='0'></a></td></tr>\n";

echo "</table>";

?>