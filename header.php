<?php
if(!isset($_SESSION['username'])) {
    $_SESSION['signed_in'] = false;
}
?>

<!Doctype html>
<html>
    <head>
        <meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1"/>
        <title>header.php</title>
        
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
        <nav class="white" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="forum.php" class="teal-text brand-logo">Messageboard</a>
                <ul class="right hide-on-med-and-down">
                        <?php
                            if($_SESSION['signed_in'] === TRUE) {
                                echo '<li><a href="logout.php" class="btn red">Log out</a></li>';
                            } else {
                                echo '<li><a href = "register.php" class="teal-text">Register</a></li>
                                <li><a href="#modalLogin" class="btn modal-trigger">Log in</a></li>';
                            }
                        ?>
                </ul>
                
                <ul id="nav-mobile" class="sidenav">
                    <?php
                        //Knapperne i headeren ændres afhængigt af om brugeren er logget ind.
                        //Hvis brugeren er logget ind, har de mulighed for at logge ud.
                        //Hvis brugeren ikke er logget ind, har de mulighed for at lave en konto og logge ind.
                        if($_SESSION['signed_in'] === TRUE) {
                            echo '<li><a href="logout.php" class="btn red">Log out</a></li>';
                        } else {
                            echo '<li><a href = "register.php" class="teal-text">Register</a></li>
                            <li><a href="#modalLogin" class="btn modal-trigger">Log in</a></li>';
                        }
                    ?>
                </ul>
                <a href="" data-target="nav-mobile" class="sidenav-trigger">
                    <i class="material-icons teal-text">menu</i>
                </a>
            </div>
        </nav>
        
        <div id="modalLogin" class="modal">
            <div class="modal-content">
                <?php
                    include 'login.php';
                ?>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close btn-flat">Close</a>
            </div>
        </div>
    </body>
</html>