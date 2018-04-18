<?php
//session_start();
include('connect.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1"/>
        <title>Login.php</title>
        
        <!-- CSS -->
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link href = "css/materialize.css" type="text/css"rel="stylesheet" media = "screen, projection"/>
        <!--Own external stylesheet-->
        <link href="css/stylesheet.css" type="text/css" rel="stylesheet"/>
        
        <!--jQuery script-->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="crossorigin="anonymous"></script>
        <!--Scripts-->
        
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
        </script>
    </head>
    <body>
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
                    <a href="register.php" class="waves-effect waves-light teal btn grey-text text-lighten-5 z-depth-2">Register here</a>
                </form>
            </div>';
    }
    if (isset($_POST['username']) and isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
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
            header('Location: forum.php');
            /*echo '<blockquote>Wrong username or password.<br>
                  Please check your login credentials and <a href="forum.php">try again</a>.</blockquote>';
            */
        } else {
            //Resultaterne fra tabellen hvor de førnævnte krav er opfyldte stilles op i et array.
            while($row =mysqli_fetch_assoc($result)){
                
                //De forskellige data fra arrayet forbindes til 'session' variabler, der gælder så længe sessionen kører.
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['user_name'];
                $_SESSION['user_level'] = $row['user_level'];
            }
            $_SESSION['signed_in'] = true;
            
            echo "<script>alert('Login successful.');</script>";
            echo "<script>document.location.href='/messageboard/forum.php';</script>";
             
        }	
    }
?>
        </div>
        
        <!--JavaScript at end of body for optimized loading-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>