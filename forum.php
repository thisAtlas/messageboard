<?php
session_start();
include 'connect.php';
if(!isset($_SESSION['username'])) {
    $_SESSION['signed_in'] = false;
}
if(!isset($_SESSION['user_level'])) {
    $_SESSION['user_level'] = 0;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Messageboard.php</title>
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
                    <h1 class="header center grey-text text-lighten-5">Messageboard</h1>
                </div>
            </div>
            <div class="parallax">
                <img src="images/header@0.5x.png" alt="Unsplashed background img 1">
            </div>
        </div>
        
        <div class="container">
            <div class="section">
<?php
$sqli = "SELECT
            cat_id,
            cat_name,
            cat_description
         FROM
            `categories`";
$results = mysqli_query($connection, $sqli);

if(!$results) {
    echo '<blockquote>No categories could be displayed.</blockquote>';
} else {
    if (mysqli_num_rows($results) == '0') {
        if($_SESSION['user_level'] == 1){
            echo '<blockquote>There does not seem to be anything here.<br>
                  Would you like to <a href="create_cat.php">create a category</a>?</blockquote>';
        } else {
            echo '<blockquote>There does not appears to be any defined categories.<br>
                  Please contact an administrator if this is not intentional.</blockquote>';
        }
    } else {
        echo '  <div class="row">
                    <nav class="white move-up z-depth-3" role="navigation">
                        <div class="nav-wrapper">
                            <ul class="left">
                                <li><a href="create_topic.php" class="teal-text">Create a topic</a></li>
                                <li><a href="create_cat.php" class="teal-text">Create a category</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
                
                <div class="small-space"></div>';
        // Tabellen der viser kategorierne dannes
        echo '  <div class="row">
                    <div class="col s12 z-depth-1">
                        
                        <div class="row grey lighten-3">
                            <div class="col s12 m4">
                                <h4>Category</h4>
                            </div>
                            <div class="col s12 m4">
                                <h4>About</h4>
                            </div>
                            <div class="col s12 m4">
                                <h4>Newest topic</h4>
                            </div>
                        </div>';
        
        while($rows = mysqli_fetch_array($results)) {
            $cat_id = $rows['cat_id'];
            $cat_name = $rows['cat_name'];
            $cat_description = $rows['cat_description'];
            
            $sqli = "SELECT
                        topic_id,
                        topic_subject
                    FROM
                        `topics`
                    WHERE
                        topic_cat = '$cat_id'
                    ORDER BY
                        topic_date DESC
                    LIMIT 1";
            
            $result = $connection->query($sqli);

            echo '      <div class="row">
                            <div class="col s4">
                                <p class="grey-text text-darken-4"><a href="category.php?id=' . $cat_id . '">'. $rows['cat_name'] .'</a></p>
                            </div>
                            <div class="col s4">
                                <p class="grey-text text-darken-4">' . $cat_description . '</p>
                            </div>';
            
            if (mysqli_num_rows($result) == '0') {
                echo '      <div class="col s12 m4">
                                <blockquote class="grey-text text-darken-4">No topics have been posted yet.</blockquote>
                            </div>
                        </div';
            } else {
                $row = mysqli_fetch_array($result);
                $topic_id = $row['topic_id'];
                $topic_subject = $row['topic_subject'];
                
                echo '      <div class="col s4">
                                <p class="grey-text text-darken-4"><a href="topic.php?id=' . $topic_id . '">' . $topic_subject . '</a></p>
                            </div>
                        </div>';
            }
        }
        echo '      </div>
                </div>
            </div>';
    }
}
?>
            </div>
        </div>
        
        <?php
            //includes the page footer from 'footer.php' so it is identical on all pages.
            include 'footer.php';
        ?>
        
        <!--Scripts-->
        <!--JavaScript at end of body for optimized loading-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>