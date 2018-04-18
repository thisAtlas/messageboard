<?php
session_start();
include 'connect.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>register.php</title>
    </head>
    <body>
        <?php
            //Include the entire header.php page which has the <head> tag with all links and scripts, the navigation-header in the <body> and the login/logout modal.
            include 'header.php';
        ?>
        
        <div class="space"></div>
        
        <div class="container z-depth-2">
            <div class="section side-padded">
<?php
if($_SESSION['user_level'] == 1) {
    //Denne if funktion forhindrer filen i at blive kaldt direkte.
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo '<blockquote>This file cannot be called directly.<br>
              <a href="forum.php">Click here to go back to the front page</a></blockquote>';
    } else {
        $getDid = $_GET['did'];
        $sqli = "DELETE FROM 
                    `posts`
                WHERE 
                    post_id = '$getDid'";
        $results = $connection->query($sqli);
        if(!$results) {
            //Script der sender brugeren tilbage til den side de var på, hvilket formodentligt var den side de prøvede at slette opslaget fra.
            echo "<script>window.history.back();</script>";
            echo "<script>alert('Could not delete post');</script>";
        } else {
            echo "<script>window.history.back();</script>";        
            echo "<script>alert('Post deleted successfully');</script>";    
        }
    }
    
} else {
    echo '<blockquote>You cannot delete posts without administrator priviliges.</blockquote>';
}
?>
            </div>
        </div>
        
        <!--JavaScript at end of body for optimized loading-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>