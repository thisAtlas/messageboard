<?php
session_start();
include 'connect.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1"/>
        <title>register.php</title>
        
        <!-- CSS -->
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link href = "css/materialize.css" type="text/css"rel="stylesheet" media = "screen, projection"/>
        <!--Own external stylesheet-->
        <link href="css/stylesheet.css" type="text/css" rel="stylesheet"/>
        
        <!--jQuery script-->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="crossorigin="anonymous"></script>
        <script>
            //Parallax-funktion
            $(document).ready(function(){
            $('.parallax').parallax();
        });
            //Sidebar navigation-funktion
            $(document).ready(function(){
            $('.sidenav').sidenav();
        });
            //Modal funktion
            $(document).ready(function(){
            $('.modal').modal();
        });
            //Select/form funktion
            $(document).ready(function(){
            $('select').formSelect();
        });
        </script>
    </head>
    <body>
        <div class="space"></div>
        
        <div class="container z-depth-2">
            <div class="section side-padded">
<?php
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    //someone is calling the file directly, which we don't want
    echo '<blockquote>This file cannot be called directly.</blockquote>';
}
else
{
    //check for sign in status
    if(!$_SESSION['signed_in']) {
        echo '<blockquote>You must be <a href="loginLanding.php">signed in</a> to post a reply.<br>
        If you do not have an account, you can <a href="register.php">register here</a>.</blockquote>';
    } else {
        //a real user posted a real reply
        $sqli = "INSERT INTO 
                    `posts`
                         (post_content,
                          post_date,
                          post_topic,
                          post_by) 
                VALUES ('" . mysqli_real_escape_string($connection,$_POST['reply-content']) . "',
                        NOW(),
                        " . mysqli_real_escape_string($connection,$_GET['id']) . ",
                        " . $_SESSION['user_id'] . ")";
                         
        $result = $connection->query($sqli);
                         
        if(!$result) { 
            echo "<script>window.history.back();</script>";
            echo "<script>alert('Your reply has not been saved, please try again later.');</script>";
        } else {
            header('Location: topic.php?id=' . htmlentities($_GET['id']) . '');
        }
    }
}
?>
            </div>
        </div>
        
        <!--JavaScript at end of body for optimized loading-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>