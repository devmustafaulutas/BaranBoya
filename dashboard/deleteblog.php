<?php
require __DIR__ . '/init.php';

header("refresh:2;url=blog");
session_start();
$todelete = mysqli_real_escape_string($con, $_GET["id"]);
$result = mysqli_query($con, "DELETE FROM blog WHERE id='$todelete'");
if ($result) {
    print "<center> Blog deleted<br/>Redirecting in 2 seconds...</center>";
} else {
    print "<center>Action could not be performed, check back again<br/>Redirecting in 2 seconds...</center>";
}
?>