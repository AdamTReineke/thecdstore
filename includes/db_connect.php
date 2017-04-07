<?
                                  //server     // username  // password
$mysql_connection = mysql_connect("localhost", "thecdstore", "nomusic"); 
if (!$mysql_connection)
  {
    die("couln't connect");
    return false;
  } 
  if (!mysql_select_db("thecdstore"))
  {
    die("couln't select"); 
    return false;
  }
return $mysql_connection;

?>