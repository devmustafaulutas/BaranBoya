<?php
// Veritabanı bağlantısını çevresel değişkenlerden alın
$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');
$db_name = getenv('DB_NAME');


$db_host = 'localhost';
$db_user = 'root';
$db_pass = '12345678';
$db_name = 'script';

$con = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($con->connect_errno) {
    echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
}

//Your Website URL Goes Here
$url = "http://localhost/vogue1/vogue";

//Set Blog Activation Bonus Here (It must be only Number)
$blog_bonus ="10";
//Set Article Activation Bonus Here (It must be only Number)
$art_bonus="10";
//Set Daily Login Bonus Here (It must be only Number)
$login_bonus="10";
//Set Currency Symbol for daily login bonus Here
$money="$";


?>
