<?php
session_start();
include 'connect.php';

//Disse if-funktioner sætter nogle standardværdier til de forskellige session-baserede varaibler, da siden er afhængig af dem.
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
            //Headeren er grundlæggende for siden, da den indeholder genveje til forskellige funktioner.
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

///Der hentes data om de eksisterende kategorier.                
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
    //Hvis data modtages fra tabellen, og der ingen kategorier er, vil brugeren blive bedt om at kontakte en admin medmindre de er en selv, i hvilket tilfælde de kan oprette kategorier.
    if (mysqli_num_rows($results) == '0') {
        if($_SESSION['user_level'] == 1){
            echo '<blockquote>There does not seem to be anything here.<br>
                  Would you like to <a href="create_cat.php">create a category</a>?</blockquote>';
        } else {
            echo '<blockquote>There does not appears to be any defined categories.<br>
                  Please contact an administrator if this is not intentional.</blockquote>';
        }
    } else if($_SESSION['user_level'] == 1){
        //Dette er tilfældet hvis brugeren er administrator, da de har adgang til at lave nye kategorier.
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
           
            //Der hentes data om opslag i de eksisterende kategorier, og sorterer opslag efter date, så de nyeste opslag indenfor hver kategori kan vises.
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
            
            // Der laves en dynamisk tabel med dynamiske hyperlinks, der ændrer sig efter mængden af kategorier, og hvilken kategori der referes til henholdsvis.
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
                //Der oprettes et link til det nyeste opslag indenfor hver kategori.
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
    } else {
        // Den samme tabel dannes for den almene bruger, bortsat fra mangel på en knap til at lave nye kategorier.
        echo '  <div class="row">
                    <nav class="white move-up z-depth-3" role="navigation">
                        <div class="nav-wrapper">
                            <ul class="left">
                                <li><a href="create_topic.php" class="teal-text">Create a topic</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
                
                <div class="small-space"></div>';
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
            //Footeren inkluderes på alle sider for at skabe symmetri og sammenhæng
            include 'footer.php';
        ?>
        
        <!--Scripts-->
        <!--Javascript loades til sidst på siden, for at forbedre performance-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>