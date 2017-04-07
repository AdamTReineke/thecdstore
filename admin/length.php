<?php

$length = $_GET['length'];

list($minutes, $seconds) = explode(":", $length);

if (strlen($minutes) < 2) {
  $minutes = "0$minutes";
  }

if (strlen($seconds) < 2) {
  $seconds = "0$seconds";
  }

$length = "$minutes:$seconds";

    
    echo "<pre>$length</pre>";
