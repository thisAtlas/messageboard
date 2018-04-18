<?php
session_start();
include 'connect.php';
if(!isset($_SESSION['user_level'])) {
    $_SESSION['user_level'] = 0;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>create_cat.php</title>
    </head>
    <body>
        <?php
            //Include the entire header.php page which has the <head> tag with all links and scripts, the navigation-header in the <body> and the login/logout modal.
            include 'header.php';
        ?>
        
        <div id="index-banner" class="parallax-container">
            <div class="section no-pad-bot">
                <div class="container">
                        <br><br><br><br><br>
                    <h1 class="header center grey-text text-lighten-5">Create category</h1>
                </div>
            </div>
            <div class="parallax">
                <img src="images/header@0.5x.png" alt="Unsplashed background img 1">
            </div>
        </div>
        
        <div class="container">
            <div class="section z-depth-3 padded padding-much move-up white">
<?php
if($_SESSION['user_level'] == 1) {
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        echo '  <h3 class="teal-text">Create category:</h3>
                <div class="row">
                    <form method="post" action="" class="col s12">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="cat_name" type="text" name="cat_name">
                                <label for="cat_name">Category name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="cat_desc" name="cat_description" class="materialize-textarea"></textarea>
                                <label for="cat_desc">Category description</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="submit" value="Add category" class="teal btn grey-text text-lighten-5"/>
                            </div>
                        </div>
                    </form>
                </div>';
    }
    else
    {
        //the form has been posted, so save it
        $catname = mysqli_real_escape_string($connection,$_POST['cat_name']);
        $catdesc = mysqli_real_escape_string($connection, $_POST['cat_description']);
    
        $sqli = "INSERT INTO `categories` (cat_name, cat_description) VALUES('$catname','$catdesc')";
             
        $result = mysqli_query($connection,$sqli);
        
        if(!$result)
        {
            //something went wrong, display the error
            echo '<blockquote>Error: ' . mysqli_error($connection) . '.<br>
                  <a href="forum.php">Return to overview</a><blockquote>';
        }
        else
        {
            echo
            "<script>
                alert('Category successfully added');
                document.location.href='/messageboard/forum.php';
            </script>";
        }
    }
    
    } else {
    echo '<blockquote>You do not have admin priviliges, and are thus unauthorized to create new categories.<br>
          <a href="forum.php">Return to the front page.</a></blockquote>';
}
?>
            </div>
        </div>
        
        <div class="space"></div>
        
        <?php
            //includes the page footer from 'footer.php' so it is identical on all pages.
            include 'footer.php';
        ?>
        
        <!--Scripts-->
        <!--JavaScript at end of body for optimized loading-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>