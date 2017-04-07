<?php

echo "<table align='center'>";
echo "<tr><td>Featured Items</td></tr>";

$query = mysql_query("SELECT * FROM `featured` ORDER BY RAND() LIMIT 2");

while ($row = mysql_fetch_assoc($query)) {
  $item_numb = $row['item_numb'];
  $name_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cds` WHERE `item_numb` = '$item_numb'"));
  $item_name = $name_query['item_name'];

echo "<tr><td><a href='browse.php?album=$item_name'><img src='album_art/$item_numb" . "_100.jpg' border=0></a></td></tr>";
  }

echo "</table>";
?>