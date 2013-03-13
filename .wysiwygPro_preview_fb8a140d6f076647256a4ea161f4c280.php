<?php
if ($_GET['randomId'] != "QpjiQaAJEGmbpBOz6x9zokmgYL7J6u7r_xL2e99S7H1W6glYN8nivx7UXHTg6cg9") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
