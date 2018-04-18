<?php
// Forbinder til brugerfalden "localhost" med usernamet "root" og et blankt password
$connection = mysqli_connect('localhost', 'root', '');
if (!$connection){
    die("Database Connection Failed" . mysqli_error($connection));
}

// Databasen "brugere" vælges.
$select_db = mysqli_select_db($connection, 'forum');
if (!$select_db){
    die("Database Selection Failed" . mysqli_error($connection));
}
?>