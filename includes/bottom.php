<?php

ECHO <<<BODYEND
</td>
          <td width="152" valign="top" bgcolor='ff0000' background='images/right_bg.gif'>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td background='images/right_bg.gif' bgcolor='00ff00' height=130 valign='top'>
BODYEND;
include "login.php";
ECHO <<<BODYEND
\n                </td>
              </tr>
              <tr><td><img src='images/black.gif' width='152' height='3' border=0></td></tr>
            </table>
BODYEND;
include "featured.php";
ECHO <<<BODYEND
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr><td><img src='images/black.gif' width='700' height='3' border=0></td></tr>
  <tr><td bgcolor='00ff00' background='images/footer_bg.gif' align="center">home - login - register - browse - search - checkout - logout - contact</td></tr><tr><td background='images/footer_bg.gif' bgcolor='00ff00' align="center">site (c) 2006 Adam Reineke</td></tr>
  <tr><td><img src='images/black.gif' width='700' height='3' border=0></td></tr>

BODYEND;

// disconnect from database
mysql_close($mysql_connection);

$time = microtime(); $time = explode(" ", $time); $time = $time[1] + $time[0]; $finish = $time; $totaltime = ($finish - $start); printf ("<tr><td align='center'>Page generated in %f seconds.</td></tr><tr><td align='center'>Session ID: $phpsessid</td></tr><tr><td align='center'>$cleanup</td>
</tr>
</table></body></html>", $totaltime);
?>