<?php
include('connect.php');
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>LoginLanding.php</title>
    </head>
    <body>
        <?php
            //Headeren er grundlæggende for siden, da den indeholder genveje til forskellige funktioner.
            include 'header.php';
        ?>
        
        <div class="space"></div>
        
        <div class="container z-depth-2">
            <div class="section side-padded">
<?php
    //Der tjekkes om der allerede er en inganværende session.
    if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
        echo '<blockquote>
                You are already logged in. To log in, please <a href="logout.php">log out</a> first.<br>
                Otherwise, please proceed to the <a href="forum.php">front page</a>.
              </blockquote>';
    } else if($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo '
            <h3 class="teal-text">Please log in:</h3>
            <div class="row">
                <form method="post" action="" class="col s12">
                    <div class="row">
                        <div class="input-field col s12 m12 l12">
                            <input id="username" type="text" name="username" class="validate" required/>
                            <label for="username">Username</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m12 l12">
                            <input id="password" type="password" name="password" class="validate" required/>
                            <label for="password">Password</label>
                        </div>
                    </div>
                        <div class="row">
                        <div class="input-field col">
                            <input type="submit" class="teal btn grey-text text-lighten-5"/>
                        </div>
                    </div>
                    <h5 class="teal-text">Dont have an account?</h5>
                    <a href="register.php" class="waves-effect waves-light teal btn grey-text text-lighten-5 z-depth-2">Register here.</a>
                </form>
            </div>';
    }
    if (isset($_POST['username']) and isset($_POST['password'])) {
        $username = mysqli_real_escape_string($connection,$_POST['username']);
        $password = mysqli_real_escape_string($connection,$_POST['password']);
        
        //Der tjekkes om den kombination af username og password existerer.
        $query = "SELECT 
                    user_id,
                    user_name,
                    user_level
                  FROM 
                    `users` 
                  WHERE 
                    user_name='$username' 
                  AND
                    user_pass='$password'";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            $_SESSION['signed_in'] = false;  
            echo '<blockquote>Something went wrong.<br>
                  Please <a href="login.php">try again</a></blockquote>';
        } else if (mysqli_num_rows($result) == 0) {
        } else {
            //Resultaterne fra tabellen hvor de førnævnte krav er opfyldte stilles op i et array.
            while($row =mysqli_fetch_assoc($result)){
                
                //De forskellige data fra arrayet forbindes til 'session' variabler, der gælder så længe sessionen kører.
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['user_name'];
                $_SESSION['user_level'] = $row['user_level'];
            }
            $_SESSION['signed_in'] = true;
            
            header('Location: forum.php');        
            /*Javascript alert der kan aktiveres for at tjekke at login.php har kørt.
             *echo "<script>alert('login success i guess');</script>";
             */
        }	
    }
?>
            </div>
        </div>
        
        <div class="space"></div>
        
        <?php
            //Footeren inkluderes på alle sider for at skabe symmetri og sammenhæng
            include 'footer.php';
        ?>
        
        <!--Javascript loades til sidst på siden, for at forbedre performance-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>