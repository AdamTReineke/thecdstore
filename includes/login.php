<?php

echo "<table cellpadding='3'><tr><td>";

if ($session_username != "") {
  echo "Welcome $session_username. <a href='logout.php'>[Logout.]</a>";
}

else {
echo "<form method='POST' name='login' action='signin.php'>
Username:<br /><input type='text' name='username' size='19' /><br />
Password:<br /><input type='password' name='password' size='19'/><br />
<input type='submit' name='login' value='Sign In'>&nbsp;<a href='register.php'>Register</a>
</form>";
}
echo "</td></tr></table>";

//<INPUT TYPE="IMAGE" SRC="butup.gif" ALT="Submit button"
//WIDTH="143" WIDTH="39" BORDER="0">
?>