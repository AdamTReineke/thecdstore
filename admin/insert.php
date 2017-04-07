<?php $time = microtime(); $time = explode(" ", $time); $time = $time[1] + $time[0]; $start = $time; ?>
<?php

include "../includes/admincheck.php";

// The safeinput Function
function safeinput($string, $type = TEXT) {
  if ($type == TEXT) {
    $string = stripslashes($string);              // Remove the inserted slashes
    $string = htmlentities($string, ENT_QUOTES);  // Convert the special chars to HTML entities
    return $string;
    }
  if ($type == INT) {
    settype($string, "float");  // Set the string type to float (thus removing any letters)
    return $string;
    }
  if ($type == MONEY) {
    settype($string, "float");            // Set the string type to float
    $string = number_format($string, 2);  // Format the number as currency -- 2 decimal places.
    return $string;
    }
  if ($type == TIME) {
    list($minutes, $seconds) = explode(":", $string);
    if (strlen($minutes) < 2) {
      $minutes = "0$minutes";
      }
    if (strlen($seconds) < 2) {
      $seconds = "0$seconds";
      }
    $string = "$minutes:$seconds";
    return $string;
    }
  }

// Step 1
if (($_GET['step'] == 1) || (isset($_GET['step']) == FALSE)) {

echo <<<BODYEND
<html>
  <head>
    <title>Step 1</title>
  </head>

<body>
  <form name="form1" method="post" action="new_insert.php?step=2">
    <table>
      <tr>
        <td>Item Name:</td>
        <td><input name="item_name" type="text" size="36" ></td>
      </tr>
      <tr>
        <td>Item Description: </td>
        <td><textarea name="desc" cols="27" rows="4" wrap="VIRTUAL"></textarea></td>
      </tr>
      <tr>
        <td>Price</td>
        <td><input name="price" type="text" size="36"></td>
      </tr>
      <tr>
        <td>Number of Tracks</td>
        <td><input name="numb_tracks" type="text" size="36"></td>
      </tr>
      <tr>
        <td>Album Artist (VAR for Various Artists)</td>
        <td><input name="album_artist" type="text" size="36"></td>
      </tr>
      <tr>
        <td>Record Label</td>
        <td><input name="label_name" size="36"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit" value="Continue -->" type="submit"></td>
      </tr>
    </table>
  </form>
</body>
</html>
BODYEND;
}

// Step 2
if ($_GET['step'] == 2) {
  $item_name = safeinput($_POST['item_name']);
  $desc = safeinput($_POST['desc']);
  $price = safeinput($_POST['price'], MONEY);
  $numb_tracks = safeinput($_POST['numb_tracks'], INT);
  $album_artist = safeinput($_POST['album_artist']);
  $label_name = safeinput($_POST['label_name']);

echo <<<BODYEND
<html><head><title>Step 2</title>
<style type="text/css"><!-- .style1 {font-family: "Courier New", Courier, mono} --></style>
</head><body><span class="style1">
Title: <b>$item_name</b><br />
Description: $desc<br />
Price: $$price<br />
Tracks: $numb_tracks<br />
Artist: $album_artist<br />
Label: $label_name<br />
</span>
<hr>
BODYEND;

// Form stuff
echo "<form name='form2' method='POST' action='new_insert.php?step=3'>\n";
echo "<input type='hidden' name='item_name' value='$item_name'>\n";
echo "<input type='hidden' name='desc' value='$desc'>\n";
echo "<input type='hidden' name='price' value='$price'>\n";
echo "<input type='hidden' name='numb_tracks' value='$numb_tracks'>\n";
echo "<input type='hidden' name='album_artist' value='$album_artist'>\n";
echo "<input type='hidden' name='label_name' value='$label_name'>\n";

// Generate all the track entry forms.
if ($numb_tracks > 0) {
echo "<table border=1><tr><td>Track<br />Number</td><td>Name</td><td>Artist</td><td>Length (mm:ss)</td></tr>\n";
$i = 1;
while ($numb_tracks >= $i):
  echo "<tr><td>Track $i</td>";
  echo "<td><input type='text' name='track_name[$i]'></td>";
  echo "<td><input type='text' name='track_artist[$i]' value='$album_artist'></td>";
  echo "<td><input type='text' name='track_length[$i]'></td>";
  echo "</tr>\n";
  $i++;
endwhile;
}
else { echo "A CD needs to have some tracks!  Go back."; exit; }

echo "</table><input type='submit' value='Continue -->'></form></body></html>";
}

// Step 3
if ($_GET['step'] == 3) {
  $item_name = safeinput($_POST['item_name']);
  $desc = safeinput($_POST['desc']);
  $price = safeinput($_POST['price'], MONEY);
  $numb_tracks = safeinput($_POST['numb_tracks'], INT);
  $album_artist = safeinput($_POST['album_artist']);
  $label_name = safeinput($_POST['label_name']);

//    LABEL QUERY
//
//  Query the database for the label_id
$label_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `labels` WHERE `label_name` = '$label_name'"));
  // And insert it if it isn't found.
  if (count($label_query) == 1) {
    mysql_query("INSERT INTO `labels` (`label_id`, `label_name`) VALUES ('','$label_name')");
    // Query again for the id
    $label_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `labels` WHERE `label_name` = '$label_name'"));
  }
// Save the label_id to a variable
$label_id = $label_query['label_id'];


// ALBUM ARTIST QUERY
//
// Query for the album artist so it can be used later..

if ($album_artist == "VAR") { // If its a various artist CD, the artist ID number will be VAR
}
// If not...
else {
  // Find him.
  $album_artist_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `artists` WHERE `artist_name` = '$album_artist'"));
  // If he isn't there
  if (count($album_artist_query) == 1) {
    // Put him there
    mysql_query("INSERT INTO `artists` (`artist_id`, `artist_name`) VALUES ('', '$album_artist')");
    // Then query the database for the artist_id number again
    $album_artist_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `artists` WHERE `artist_name` = '$album_artist'"));
  }
// Finally, save the id number to a variable.
$album_artist = $album_artist_query['artist_id'];
  }

//    ITEM QUERIES
//
// Query the database to insert the item's entry

// Check first
$item_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cds` WHERE `item_name` = '$item_name'"));
  if (count($item_query) != 1) {
    echo "ERROR:  A CD with that name is already in the database.<br />";
    exit;
  }
  // Then insert
  else {
    mysql_query("INSERT INTO `cds` (`item_numb`, `price`, `item_name`, `desc`, `numb_tracks`, `label_id`, `album_art`, `album_artist`)
    VALUES ( '','$price','$item_name','$desc','$numb_tracks','$label_id','$album_art','$album_artist')");
    $item_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `cds` WHERE `item_name` = '$item_name'"));
    $item_numb = $item_query['item_numb'];
  }

//    ARTIST QUERIES
//
$i = 1;
while ($numb_tracks >= $i) {
  // Query the database for the track's artist entry
  $track_artist[$i] = safeinput($track_artist[$i]);
  $artist_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `artists` WHERE `artist_name` = '$track_artist[$i]'"));
    // If the artist isn't in the database, put him there and grab the ID number again.
    if (count($artist_query) == 1) {
      mysql_query("INSERT INTO `artists` (`artist_id`, `artist_name`) VALUES ('', '$track_artist[$i]')");
      $artist_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `artists` WHERE `artist_name` = '$track_artist[$i]'"));
    }
  // Save the id number.
  $artist_id = $artist_query['artist_id'];
  
  $track_name[$i] = safeinput($track_name[$i]);
  $track_length[$i] = safeinput($track_length[$i], TIME);
  
  // Final query inserts the CD into the database.
  mysql_query("INSERT INTO `tracks` (`track_id`,`item_numb`,`artist_id`,`track_numb`,`track_name`,`track_length`)
  VALUES ('','$item_numb','$artist_id','$i','$track_name[$i]','$track_length[$i]')");
  
  $i++;
}

echo "Inserted successfully.  We recommend that you <a href='albumart.php'>add album art</a> for this CD before you forget.";
}
?>
<?php $time = microtime(); $time = explode(" ", $time); $time = $time[1] + $time[0]; $finish = $time; $totaltime = ($finish - $start); printf ("<!--\n\tPage generated in %f seconds.\n-->\n</html>", $totaltime); ?>
