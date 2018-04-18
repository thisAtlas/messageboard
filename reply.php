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
    //I tilfælde af at en bruger prøver at tilgå filen direkte, blive de nægtet adgang da denne version ikke er funktionsdygtig.
    echo '<blockquote>This file cannot be called directly.</blockquote>';
}
else
{
    //Der tjekkes om brugeren er logget ind.
    if(!$_SESSION['signed_in']) {
        echo '<blockquote>You must be <a href="loginLanding.php">signed in</a> to post a reply.<br>
        If you do not have an account, you can <a href="register.php">register here</a>.</blockquote>';
    } else {
        //Kommentaren indsættes i databasen.
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
        
       <!--Javascript loades til sidst på siden, for at forbedre performance-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>