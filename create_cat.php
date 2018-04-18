<?php
session_start();
include 'connect.php';
//Sætter standardværdi for administrator tilladelser, så almene brugere ikke kan lave kategorier hvis de tilgår siden direkte.
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
            //Headeren er grundlæggende for siden, da den indeholder genveje til forskellige funktioner.
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
    //Tjekker om brugeren er administrator, da de ellers ikke skal have adgang til at lave en kategori.
if($_SESSION['user_level'] == 1) {
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        //Tabellen hvori administratoren indtaster data om den ønskede kategori.
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
        //De indtastede data i formen forbindes til variabler, og bliver gennemarbejdet så programmet er klar over at det er en string og ikke kode der kan eksekveres.
        $catname = mysqli_real_escape_string($connection,$_POST['cat_name']);
        $catdesc = mysqli_real_escape_string($connection, $_POST['cat_description']);
        
        //Indsætter den indtastede data i tabellen "categories" for at prøve at oprette den ønskede kategori.
        $sqli = "INSERT INTO `categories` (cat_name, cat_description) VALUES('$catname','$catdesc')";
             
        $result = mysqli_query($connection,$sqli);
        
        if(!$result)
        {
            //Noget gik galt, udskriv fejlen.
            echo '<blockquote>Error: ' . mysqli_error($connection) . '.<br>
                  <a href="forum.php">Return to overview</a><blockquote>';
        }
        else
        {
            //Hvis der derimod ikke opstår nogen fejl, bliver brugen sendt tilbage til forummets oversigt, og får en besked om at oprettelsen lykkedes.
            echo
            "<script>
                alert('Category successfully added');
                document.location.href='/messageboard/forum.php';
            </script>";
        }
    }
    
    } else {
    //Hvis brugeren ikke har administratorprivilegier, og prøver at tilgå siden, vil de blive nægtet adgang og få følgende besked.
    echo '<blockquote>You do not have admin priviliges, and are thus unauthorized to create new categories.<br>
          <a href="forum.php">Return to the front page.</a></blockquote>';
}
?>
            </div>
        </div>
        
        <div class="space"></div>
        
        <?php
            //Footeren inkluderes på alle sider for at skabe symmetri og sammenhæng
            include 'footer.php';
        ?>
        
        <!--Scripts-->
        <!--Javascript loades til sidst på siden, for at forbedre performance-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>