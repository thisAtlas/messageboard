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
            //Headeren er grundlæggende for siden, da den indeholder genveje til forskellige funktioner.
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
        //I tilfælde af, at brugeren ikke er logget ind, og prøve at tilgå siden.
        echo '<blockquote>Sorry, you have to be <a href="loginLanding.php">logged in</a> to create a topic.</blockquote>';
    }
    else
    {
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            //Henter data fra tabellen, så det kan bruges i den oprettede drop-down menu.
            $sqli = "SELECT
                        cat_id,
                        cat_name,
                        cat_description
                    FROM
                        categories";
         
            $result = mysqli_query($connection,$sqli);
         
            if(!$result) {
                //Udskrivelse af fejlbesked.
                echo '<blockquote>Error while selecting from database. Please try again later.</blockquote>';
            } else {
                if(mysqli_num_rows($result) == 0) {
                    //Hvis der ikke er nogle kategorier, og brugeren prøver at lave et opslag, vil de få en besked der variere hvis de er admin eller ej.
                    if($_SESSION['user_level'] == 1) {
                        echo '<blockquote>You have not created categories yet.</blockquote>';
                    } else {
                        echo '<blockquote>Before you can post a topic, you must wait for an admin to create some categories.</blockquote>';
                    }
                } else {
                    //Tabellen hvor der kan indtastes data om det ønskede opslag opstilles.
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
                        //Hvis der ikke er noget galt med den modtagne data, oprettes drop-down menuen.
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
                echo '<blockquote>An error occured while creating your topic. Please try again later.</blockquote>';
            } else {
                //Data fra formen parses, og sættes ind i "topics" tabellen.
            
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
                    echo '<blockquote>An error occured while inserting your data. Please try again later.</blockquote>' . mysqli_error($connection);
                } else {
                    // Hvis den første indsættelse af data var succesfuld, startes den anden, der sætte data ind i "posts" tabellen.
                    // Man kan gøre brug af id'et fra det forrigt hentede opslag, når man indsætter data i "posts" tabellen, for at forbinde de to og dermed gøre det nemmere at finde rundt i.
                
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
                        echo '<blockquote>An error occured while inserting your post. Please try again later.' . mysqli_error($connection) . '</blockquote>';
                        //Hvis noget gik galt, sørger "ROLLBACK" for at handlingen bliver afbrud, og ændringerne bliver annulleret.
                        $sqli = "ROLLBACK;";
                        $result = $connection->query($sqli);
                    } else {
                        //Hvis alt går som det skal, sørger "COMMIT" for at dataen indsættes i tabellen.
                        $sqli = "COMMIT;";
                        $result = $connection->query($sqli);
                        
                        
                        //Path til den topic man lige har lavet. Header(''); virker ikke for some reason.
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
            //Footeren inkluderes på alle sider for at skabe symmetri og sammenhæng
            include 'footer.php';
        ?>
        
        <!--Scripts-->
        <!--Javascript loades til sidst på siden, for at forbedre performance-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>