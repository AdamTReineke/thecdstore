<?php
include "../includes/admincheck.php";


if (($_GET['step'] == 1) || (isset($_GET['step']) == FALSE)) {
  $query = mysql_query("SELECT * FROM `cds` WHERE `album_art` = '0'");
  
  while ($row = mysql_fetch_assoc($query)) {
    $item_numb = $row['item_numb'];
    $item_name = $row['item_name'];
    $items[$item_numb] = $item_name;
  }
  
  if (count($items) == 0) { echo "All CDs have album art.  Good job!"; }
  else {
    echo "<form action='albumart.php?step=2' method='POST'><select name='cds'>";
    foreach ($items as $item_numb => $item_name) {
      echo "<option value='$item_numb'>$item_name</option>\n";
      }
    echo "</select><input type='submit' value='Next -->'></form>";
    }
}

if ($_GET['step'] == 2) {
  $item_numb = $_POST['cds'];
  $query = mysql_fetch_array(mysql_query("SELECT * FROM `cds` WHERE `item_numb` = '$item_numb'"));
  $item_name = $query['item_name'];

  echo "<form method='POST' enctype='multipart/form-data' action='albumart.php?step=3'>";
  echo "Upload Album Art for $item_name:<br />";
  echo "<input type='hidden' name='MAX_FILE_SIZE' value='300000' /><input type='hidden' name='item_numb' value='$item_numb' />";
  echo "<input type='file' name='album_art' /><br />";
  echo "<input type='submit' value='Upload -->'>";
  }

if ($_GET['step'] == 3) {
  $item_numb = $_POST['item_numb'];
  $uploaddir = "../album_art/";
  $uploadfile = $uploaddir . $item_numb . "_full.jpg";

  move_uploaded_file($_FILES['album_art']['tmp_name'], $uploadfile);

  // BEGIN RESIZE SCRIPT (http://www.4wordsystems.com/php_image_resize.php)
  
  // PART 1 [300px version]
     // Create an Image from it so we can do the resize
    $src = imagecreatefromjpeg($uploadfile);
    
    // Capture the original size of the uploaded image
    list($width,$height)=getimagesize($uploadfile);
    
    // For our purposes, I have resized the image to be
    // 600 pixels wide, and maintain the original aspect
    // ratio. This prevents the image from being "stretched"
    // or "squashed". If you prefer some max width other than
    // 600, simply change the $newwidth variable
    $newwidth=300;
    $newheight=($height/$width)*300;
    $tmp=imagecreatetruecolor($newwidth,$newheight);
    
    // this line actually does the image resizing, copying from the original
    // image into the $tmp image
    imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
    
    // now write the resized image to disk. I have assumed that you want the
    // resized, uploaded image file to reside in the ./images subdirectory.
    $filename = "../album_art/".$item_numb . "_300.jpg";
    imagejpeg($tmp,$filename,100);
    
    imagedestroy($src);
    imagedestroy($tmp);
    
    // NOTE: PHP will clean up the temp file it created when the request
    // has completed.


  // PART 2 [100px version]
     // Create an Image from it so we can do the resize
    $src = imagecreatefromjpeg($uploadfile);
    
    // Capture the original size of the uploaded image
    list($width,$height)=getimagesize($uploadfile);
    
    // For our purposes, I have resized the image to be
    // 600 pixels wide, and maintain the original aspect
    // ratio. This prevents the image from being "stretched"
    // or "squashed". If you prefer some max width other than
    // 600, simply change the $newwidth variable
    $newwidth=100;
    $newheight=($height/$width)*100;
    $tmp=imagecreatetruecolor($newwidth,$newheight);
    
    // this line actually does the image resizing, copying from the original
    // image into the $tmp image
    imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
    
    // now write the resized image to disk. I have assumed that you want the
    // resized, uploaded image file to reside in the ./images subdirectory.
    $filename = "../album_art/".$item_numb . "_100.jpg";
    imagejpeg($tmp,$filename,100);
    
    imagedestroy($src);
    imagedestroy($tmp);
    
    // NOTE: PHP will clean up the temp file it created when the request
    // has completed.

mysql_query("UPDATE `cds` SET `album_art` = '$item_numb' WHERE CONCAT( `item_numb` ) = '$item_numb' LIMIT 1");
  }
?>
