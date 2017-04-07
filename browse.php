<?php
// Sets the <title> tag
$page_title = 'the CD store - Browse';
$bg_color = '#FFFF00';

// includes the top of the page
include 'includes/top.php';

if (count($_GET) == 0) {
echo "Browse by <a href='browse.php?by=artist'>Artist</a> or <a href='browse.php?by=album'>Album</a>.<br />";
}

// BROWSE BY ARTIST
if ($_GET['by'] == 'artist') {
$query = mysql_query("SELECT * FROM `artists` ORDER BY `artist_name` ASC");
$i = 0;
while ($row = mysql_fetch_assoc($query)) {
  $artists[$i] = $row['artist_id'];
  $name = $row['artist_name'];
  echo "<a href=\"browse.php?artist=$artists[$i]\">$name</a><br />";
  $i++;

}
}

// BROWSE BY SPECIFIC ARTIST
if (isset($_GET['artist'])) {
$artist = $_GET['artist'];
// WARNING -- SECURITY ISSUE -- YOU SHOULD VALIDATE INPUT
$query = mysql_fetch_assoc(mysql_query("SELECT * FROM `artists` WHERE `artist_id` = '$artist'"));

$artist_name = $query['artist_name'];
echo "<h1>$artist_name</h1>";


if (count($query) == 1) {
  echo "The artist '$artist' could not be found in our database.";
  }
else {
// Get the artist_id from the query, then search for all tracks by that artist.
$artist_id = $query['artist_id'];
$query = mysql_query("SELECT * FROM `tracks` WHERE `artist_id` = '$artist_id'");

// Go through each row fetched by the query.
while ($row = mysql_fetch_assoc($query)) {
  // Save the item_numb...
  $album_id = $row['item_numb'];
  // Save the item name; query database first...
  $album_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cds` WHERE `item_numb` = '$album_id'"));
  $album = $album_query['item_name'];

  // Save the track_numb...
  $track_numb = $row['track_numb'];
  // Saves the track name...
  $track = $row['track_name'];

  // Saves the track name to $array in:  $array[cd_name][track_numb]
  $array[$album_id][$track_numb] = $track;
  }

if (count($array) == 0) {
  // Let them know we didn't find any tracks matching that artist.
  echo "Sorry, this artist hasn't recorded any tracks in our database.";
  }
else {
  // Echo the CDs
  foreach (array_keys($array) as $album) {
    $album_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cds` WHERE `item_numb` = '$album'"));
    $album_name = $album_query['item_name'];
    echo "<h2><a href='browse.php?album=$album'>$album_name</a></h2>";
    foreach ($array[$album_id] as $track_numb => $track) {
      echo "Track $track_numb: $track<br />";
      }
    }
  }
}
}

// BROWSE BY ALBUM
if ($_GET['by'] == 'album') {
$query = mysql_query("SELECT * FROM `cds` ORDER BY `item_name` ASC");
$i = 0;
while ($row = mysql_fetch_assoc($query)) {
  $albums[$i] = $row['item_numb'];
  $name = $row['item_name'];
  echo "<a href=\"browse.php?album=$albums[$i]\">$name</a><br />";
  $i++;

}
}

// BROWSE BY SPECIFIC ALBUM
if (isset($_GET['album'])) {
$album = $_GET['album'];

// Again, security risk.  validate input.
$query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cds` WHERE `item_numb` = '$album'"));
$item_name = $query['item_name'];

if (count($query) == 1) {
  echo "The album you requested could not be found in our database.";
  }
else {
$item_numb = $query['item_numb'];
$price = $query['price'];
$desc = $query['desc'];
$label_id = $query['label_id'];
$album_art = $query['album_art'];

// Save the artist name.
$album_artist = $query['album_artist'];
if ($album_artist != "VAR") {
$album_artist_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `artists` WHERE `artist_id` = '$album_artist'"));
$album_artist = $album_artist_query['artist_name'];
$album_artist_id = $album_artist_query['artist_id'];

}
$query = mysql_query("SELECT * FROM `tracks` WHERE `item_numb` = '$item_numb'");

while ($row = mysql_fetch_assoc($query)) {
  $track_numb = $row['track_numb'];
  $track_name = $row['track_name'];
  $track_length = $row['track_length'];
  // Modify the length, by stripping the leading zero.
  $track_length = ltrim($track_length, "0");

  $artist_id = $row['artist_id'];
  
  $array[$track_numb][track_name] = $track_name;
  $array[$track_numb][track_length] = $track_length;
  $array[$track_numb][artist_id] = $artist_id;
  }

if (count($array) == 0) {
  // Let them know this was an empty CD.
  echo "Sorry, but we were unable to find any tracks for this CD in our database.";
  }
else {
  // Print the album title
echo "<table><tr><td><img src='album_art/" . $album_art . "_100.jpg'></td><td>";
  echo "<h2>" . stripslashes($item_name) ."</h2>";
  // Print the album artist
  if ($album_artist != "VAR") {echo "<h3>by <a href='browse.php?artist=$album_artist_id'>$album_artist</a></h3>"; }
  // Or various artist
  if ($album_artist == "VAR") {echo "<h3>by Various Artists</h3>"; }
  echo "</td></tr></table>";
  echo "<a href='cart.php?add=$item_numb'>Add To Shopping Cart</a><br />";
  foreach (array_keys($array) as $track_numb) {
  $track_name = $array[$track_numb][track_name];
  $track_length = $array[$track_numb][track_length];
  $artist_id = $array[$track_numb][artist_id];

  $track_artist_query = mysql_fetch_array(mysql_query("SELECT * FROM `artists` WHERE `artist_id` = '$artist_id'"));
  $track_artist = $track_artist_query['artist_name'];

    if ($album_artist != "VAR") { echo "$track_numb: $track_name ($track_length)<br />";}
    if ($album_artist == "VAR") { echo "$track_numb: $track_name (<a href='browse.php?artist=$artist_id'>$track_artist</a>, $track_length)<br />";}
    }
  }
  
}
}

// includes the bottom of the page.
include 'includes/bottom.php';
?>