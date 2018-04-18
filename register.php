<?php
//include inkluderer kode fra en anden php fil
include('connect.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>register.php</title>
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
    echo '      <h3 class="teal-text">Please register an account</h3>
                <div class="row">
                    <form method="POST">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" type="text" name="username" required>
                                <label for="username">Username</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" type="password" name="password" required>
                                <label for="password">Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="mail" type="password" name="password" required>
                                <label for="mail">Email (email@example.com)</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="submit" value="Register" name="submit" class="teal btn grey-text text-lighten-5"/>
                                <input type="submit" value="Register as admin" name="submit" class="teal btn grey-text text-lighten-5"/>
                            </div>
                        </div>
                    </form>
                    <div class="row side-padded">
                        <blockquote>Already have an account? Go to the <a href="loginLanding.php">login page</a> to log in.</blockquote>
                    </div>
                </div>';
    if (isset($_POST['username']) && isset($_POST['password'])){
        if($_POST['submit'] == "Register"){
            $user_level = 0;
        }else{
            $user_level = 1;
        }
        $username = mysqli_real_escape_string($connection,$_POST['username']);
        $password = mysqli_real_escape_string($connection,$_POST['password']);
		$email = mysqli_real_escape_string($connection,$_POST['email']);
        
		// query er en string med den skrevne data
        $query = "INSERT INTO 
                    `users`
                        (user_id,
                         user_name,
                         user_pass,
                         user_mail,
                         user_level)
                  VALUES (NULL,
                         '$username',
                         '$password',
                         '$email',
                         '$user_level')";
        
		// sender dataen "query" via forbindelsen "connection"
		$result = mysqli_query($connection, $query);
        if($result==1){
            $smsg ="User Created Successfully.";
            
            //sender brugeren videre til loginsiden
            header('Location: loginLanding.php');
        }else{
			$fmsg ="User Registration Failed";
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