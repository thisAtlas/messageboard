<?php
include 'connect.php';
if(!isset($_SESSION['username'])) {
    $_SESSION['signed_in'] = false;
}
?>

<!DOCTYPE html>
<html>
    <body>
        <footer class="page-footer teal">
            <div class="container">
                <div class="row">
                    <div class="col l8 s12">
                        <h5 class="white-text">Lorem Ipsum</h5>
                        <p class="grey-text text-lighten-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque id mattis nunc. Sed at bibendum diam, non lobortis quam. </p>
                    </div>
                    <div class="col l4 s12">
                        <h5 class="white-text">Admin links</h5>
                        <ul>
                            <li><a class="white-text" href="databaseCreator.php">databaseCreator.php</a></li>
                            <li><a class="white-text" href="create_cat.php">create_cat.php</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    Made by: <a class="white-text text-darken-3" href="">Kasper Kyhl &amp; Magnus Paulsen, 3.b2</a>
                </div>
            </div>
        </footer>
    </body>
</html>