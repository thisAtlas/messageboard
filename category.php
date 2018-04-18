<?php
session_start();
include 'connect.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Category.php</title>
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
                    <h1 class="header center grey-text text-lighten-5">Topics</h1>
                </div>
            </div>
            <div class="parallax">
                <img src="images/header@0.5x.png" alt="Unsplashed background img 1">
            </div>
        </div>
        <div class="container">
            <div class="section">
                <nav class="white move-up z-depth-1" role="navigation">
                    <div class="nav-wrapper">
                        <ul class="left">
                            <li>
                                <a href="create_topic.php" class="teal-text">Create a new topic</a>
                            </li>
                            <li>
                                <a href="create_cat.php" class="teal-text">Create a new category</a>
                            </li>
                        </ul>
                    </div>
                </nav>
                
                <div class="small-space"></div>
<?php
$getId =$_GET['id']; 
//first select the category based on $_GET['cat_id']
$sqli = "SELECT
            cat_id,
            cat_name,
            cat_description
        FROM
            `categories`
        WHERE
            cat_id = '$getId'";
 
$result = $connection->query($sqli);
 
if(!$result) {
    echo '<blockquote>The category could not be displayed, please try again later.</blockquote>' . mysqli_error($connection);
} else {
    if(mysqli_num_rows($result) == 0) {
        echo '<blockquote>This category does not exist.' . mysqli_error($connection) . '</blockquote>';
    } else {
        //display category data
        while($row = mysqli_fetch_assoc($result))
        {
            echo '<h3 class="teal-text text-lighten-1">Topics in ′' . $row['cat_name'] . '′ category</h3>';
        }
        
        //do a query for the topics
        $sqli = "SELECT  
                    topic_id,
                    topic_subject,
                    topic_date,
                    topic_cat
                FROM
                    `topics`
                WHERE
                    topic_cat = " . mysqli_real_escape_string($connection,$_GET['id']);
         
        $result = $connection->query($sqli);
         
        if(!$result)
        {
            echo '<blockquote>The topics could not be displayed, please try again later.</blockquote>';
        }
        else
        {
            if(mysqli_num_rows($result) == 0) {
                echo '<blockquote>There are no topics in this category yet.
                      Would you like to <a href="create_topic.php" class="teal-text">Create a topic</a>?</blockquote>';
            } else {
                //prepare the table
                echo '<div class="row">
                        <div class="col s6 grey lighten-3"><h4>Topic</h4></div>
                        <div class="col s6 grey lighten-3 center-align"><h4>Date of creation</h4></div>
                      </div>'; 
                     
                while($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="row">
                            <div class="leftpart col s6 side-padded">
                                <h5 class="valign-wrapper"><i class="small material-icons">chevron_right</i>
                                    <a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a>
                                </h5>
                            </div>
                            <div class="rightpart col s6 side-padded">
                                <h5 class="center-align">';
                    echo date('d-m-Y', strtotime($row['topic_date']));
                    echo '      </h5>
                            </div>
                        </div>';
                }
            }
        }
    }
}
?>
            </div>
        </div>
        
        <?php
            include 'footer.php';
        ?>
        
        <!--JavaScript at end of body for optimized loading-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>