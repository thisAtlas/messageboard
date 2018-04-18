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
        <title>topic.php</title>
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
                    <h1 class="header center grey-text text-lighten-5">Topic</h1>
                </div>
            </div>
            <div class="parallax">
                <img src="images/header@0.5x.png" alt="Unsplashed background img 1">
            </div>
        </div>
        
        <div class="container">
            <div class="section z-depth-3 padded white">
                <div class="row">
                    <div class="col s12">
                        
                                       
<?php
$getId =$_GET['id']; 
//first select the category based on $_GET['cat_id']
$sqli = "SELECT
            topic_id,
            topic_subject
        FROM
            `topics`
        WHERE
            topic_id = " . mysqli_real_escape_string($connection,$_GET['id']);
 
$result = $connection->query($sqli);

if(!$result) {
    echo '<blockquote>The topic could not be displayed, please try again later.<br>'
          . mysqli_error($connection) . '</blockquote>';
}
else
{
    if(mysqli_num_rows($result) == 0) {
        echo '<blockquote>This topic does not exist.<br>'
              . mysqli_error($connection) . '</blockquote>';
    } else {
        //prepare the table
        $row = mysqli_fetch_assoc($result);
        $topic_subject = $row['topic_subject'];
        $topic_id = $row['topic_id'];
        echo '          <div class="row">
                            <h3>Topic: ' . $topic_subject . '</h3>
                        </div>';
            
        // Data om den bestemte topic hentes
        $sqli = "SELECT  
                    posts.post_id,
                    posts.post_topic,
                    posts.post_content,
                    posts.post_date,
                    posts.post_by,
                    users.user_id,
                    users.user_name
                FROM
                    `posts`
                LEFT JOIN
                    `users`
                ON
                    posts.post_by = users.user_id
                WHERE
                    posts.post_topic = " . mysqli_real_escape_string($connection,$_GET['id']);
         
        $result = $connection->query($sqli);
        if(!$result) {
            echo '<blockquote>The topics posts could not be displayed, please try again later.</blockquote>';
        } else {
                while($rows = mysqli_fetch_assoc($result)) {
                    echo '  <div class="row z-depth-1">
                                <div class="leftpart col s12">
                                    <div class="col s12 m6 l6">
                                        <p class="teal-text">Made by user: ' . $rows['user_name'] . '</p>
                                        </div>
                                    <div class="col s12 m6 l6 right-align">
                                        <p class="teal-text">On: ' . $rows['post_date'] . '</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="rightpart col s12">
                                        <div class="col s12 m10 l10">
                                            <h5>' . $rows['post_content'] . '</h5>
                                        </div>
                                        <div class="col s12 m1 l1">';
                    if($_SESSION['user_level'] == 1) {
                        echo '              <form method="post" action="delete.php?did=' . $rows['post_id'] . '">
                                                <input type="submit" value="Delete post" class="teal btn grey-text text-lighten-5"/>
                                            </form>';
                    }
                    echo '              </div>
                                    </div>
                                </div>
                            </div>';
                }
                echo '      <div class="row">
                                <div class="input-field col s12">
                                    <form method="post" action="reply.php?id=' . $row['topic_id'] . '">
                                        <div class="row">
                                            <label for="topic_reply">Reply</label>
                                            <textarea id="topic_reply" name="reply-content" class="materialize-textarea"></textarea>
                                        </div>
                                        <div class="row">
                                            <input type="submit" value="Submit reply" class="teal btn grey-texty text-lighten-5"/>
                                        </div>
                                    </form>
                                </div>
                            </div>';
           }
        }
    }
?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="small-space"></div>
        
        <?php
            //includes the page footer from 'footer.php' so it is identical on all pages.
            include 'footer.php';
        ?>
        
        <!--Scripts-->
        <!--JavaScript at end of body for optimized loading-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>