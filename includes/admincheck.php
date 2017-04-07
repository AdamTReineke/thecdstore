<?php

include "db_connect.php";
include "sessions.php";

if ($session_is_admin != 1) {
  echo "You need to be an administrator.  Please log in.";
  exit;
  }

?>