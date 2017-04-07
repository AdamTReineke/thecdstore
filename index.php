<?php
// Sets the <title> tag
$page_title = 'the CD store - Home';
$bg_color = '#0066FF';

// includes the top of the page
include 'includes/top.php';

echo <<<BODYEND
Welcome to the CD store!  We are pleased to offer a wide selection of music at great prices.  If you're a returing customer, we recommend that you login on the right.  To get started, just select a link from the left!<br />
<br />
Known Issues:<br />
None of the user input is verified, meaning that any weird data would be accepted.<br />

BODYEND;



// includes the bottom of the page.
include 'includes/bottom.php';


?>