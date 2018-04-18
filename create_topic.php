<?php
session_start(); 
include 'connect.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>create_topic.php</title>
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
                    <h1 class="header center grey-text text-lighten-5">Create topic</h1>
                </div>
            </div>
            <div class="parallax">
                <img src="images/header@0.5x.png" alt="Unsplashed background img 1">
            </div>
        </div>
        
        <div class="container">
            <div class="section z-depth-3 padded padding-much move-up white">
<?php
    echo '<h3 class="teal-text">Create topic:</h3>';
    if($_SESSION['signed_in'] == false)
    {
        //If the user is not signed in
        echo '<blockquote>Sorry, you have to be <a href="loginLanding.php">logged in</a> to create a topic.</blockquote>';
    }
    else
    {
        //the user is signed in
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            //the form hasn't been posted yet, display it
            //retrieve the categories from the database for use in the dropdown
            $sqli = "SELECT
                        cat_id,
                        cat_name,
                        cat_description
                    FROM
                        categories";
         
            $result = mysqli_query($connection,$sqli);
         
            if(!$result) {
                //the query failed, uh-oh :-(
                echo '<blockquote>Error while selecting from database. Please try again later.</blockquote>';
            } else {
                if(mysqli_num_rows($result) == 0) {
                    //there are no categories, so a topic can't be posted
                    if($_SESSION['user_level'] == 1) {
                        echo '<blockquote>You have not created categories yet.</blockquote>';
                    } else {
                        echo '<blockquote>Before you can post a topic, you must wait for an admin to create some categories.</blockquote>';
                    }
                } else {
                    echo '
                        <div class="row">
                            <form method="post" action="" class="col s12">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="topic_sub" type="text" name="topic_subject">
                                        <label for="topic_sub">Topic subject</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="topic_cat">
                                            <option value="" disabled selected>Choose category</option>';
                    while($row = mysqli_fetch_assoc($result)) {
                        echo '
                                            <option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
                    }
                    echo '              </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea id="topic_body" name="post_content" class="materialize-textarea"></textarea>
                                        <label for="topic_body">Body</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="submit" value="Create topic" class="teal btn grey-text text-lighten-5"/>
                                    </div>
                                </div>
                            </form>
                        </div>';
                }
            }
        } else {
            //start the transaction
            $query  = "BEGIN WORK;";
            $result = $connection->query($query);
         
            if(!$result) {
                //Damn! the query failed, quit
                echo '<blockquote>An error occured while creating your topic. Please try again later.</blockquote>';
            } else {
                //the form has been posted, so save it
                //insert the topic into the topics table first, then we'll save the post into the posts table
            
                $subject = mysqli_real_escape_string($connection,$_POST['topic_subject']);
                $category = mysqli_real_escape_string($connection,$_POST['topic_cat']);
                $user_id = $_SESSION['user_id'];
            
                $sqli = "INSERT INTO `topics`(
                                        topic_subject,
                                        topic_date,
                                        topic_cat,
                                        topic_by)
                                    VALUES(
                                        '$subject',
                                    NOW(),
                                        '$category',
                                        '$user_id'
                                    )";
                
                $result = mysqli_query($connection,$sqli);
                if(!$result) {
                    //something went wrong, display the error
                    echo '<blockquote>An error occured while inserting your data. Please try again later.</blockquote>' . mysqli_error($connection);
                } else {
                    //the first query worked, now start the second, posts query
                    //retrieve the id of the freshly created topic for usage in the posts query
                
                    $topicid = mysqli_insert_id($connection);
                    $pContent = mysqli_real_escape_string($connection,$_POST['post_content']);
                
                
                    $sqli = "INSERT INTO `posts`(
                                        post_content,
                                        post_date,
                                        post_topic,
                                        post_by)
                                    VALUES(
                                        '$pContent',
                                    NOW(),
                                        '$topicid',
                                        '$user_id'
                                    )";
                    $result = $connection->query($sqli);
                 
                    if(!$result) {
                        //something went wrong, display the error
                        echo '<blockquote>An error occured while inserting your post. Please try again later.' . mysqli_error($connection) . '</blockquote>';
                        $sqli = "ROLLBACK;";
                        $result = $connection->query($sqli);
                    } else {
                        $sqli = "COMMIT;";
                        $result = $connection->query($sqli);
                        
                        //after a lot of work, the query succeeded!
                        
                        //Path til den topic man lige har lavet. Header(''); virker ikke for some reason.
                        
                        //header('topic.php?id="'. $topicid . '"');
                        echo '<blockquote>You have successfully created <a href="topic.php?id='. $topicid . '">your new topic</a>.</blockquote>';
                    }
                }
            }
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