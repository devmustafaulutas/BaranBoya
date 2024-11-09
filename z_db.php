<?php
// $con = new mysqli("localhost", "truvaayv_root", "kerem.1234", "truvaayv_scriptdb");
$con = new mysqli("localhost", "musta", "12345678", "script");
if ($con->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

//Your Website URL Goes Here
// $url="https://ornek.truva-software.com/vogue";
$url="http://localhost/vogue";


//Set Blog Activation Bonus Here (It must be only Number)
$blog_bonus ="10";
//Set Article Activation Bonus Here (It must be only Number)
$art_bonus="10";
//Set Daily Login Bonus Here (It must be only Number)
$login_bonus="10";
//Set Currency Symbol for daily login bonus Here
$money="$";
?>
