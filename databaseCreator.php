<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1"/>
        <title>databaseCreation.php</title>
        
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
        </script>
    </head>
    
    <body>
        <nav class="white" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="forum.php" class="teal-text brand-logo">Messageboard</a>
            </div>
        </nav>
        <div class="container">
            <div class="section">
<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    // Create connection
    $conn = new mysqli($servername, $username, $password);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    // Create database
    $sql = "CREATE DATABASE forum";
    if ($conn->query($sql) === TRUE) {
        echo "<blockquote>Database created successfully.</blockquote>";
    } else {
        echo "<blockquote>" . $conn->error . ".</blockquote>";
    }

    $dbName = "forum";

    $conn = new mysqli($servername, $username, $password, $dbName);

    $sql = "CREATE TABLE `users` (
      `user_id` int(8) NOT NULL,
      `user_name` varchar(30) NOT NULL,
      `user_pass` varchar(255) NOT NULL,
      `user_mail` varchar(255) NOT NULL,
      `user_level` int(8) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

    if ($conn->query($sql) === TRUE) {
        echo "<blockquote>Table users created successfully. </blockquote>";
    } else {
        echo "<blockquote>Error creating table: " . $conn->error . ".</blockquote>";
    }

    $sql1 = "CREATE TABLE `categories` (
      `cat_id` int(8) NOT NULL,
      `cat_name` varchar(255) NOT NULL,
      `cat_description` varchar(255) NOT NULL
      )ENGINE=InnoDB DEFAULT CHARSET=latin1;
    ";
    
    if ($conn->query($sql1) === TRUE) {
        echo "<blockquote>Table categories created successfully.</blockquote>";
    } else {
        echo "<blockquote>Error creating table: " . $conn->error . ".</blockquote>";
    }
    
    $sql2 = "CREATE TABLE `topics` (
      `topic_id` int(8) NOT NULL,
      `topic_subject` varchar(255) NOT NULL,
      `topic_date` datetime NOT NULL,
      `topic_cat` int(8) NOT NULL,
      `topic_by` int(8) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    ";

    if ($conn->query($sql2) === TRUE) {
        echo "<blockquote>Table topics created successfully.</blockquote>";
    } else {
        echo "<blockquote>Error creating table: " . $conn->error . ".</blockquote>";
    }
    
    $sql3 = "CREATE TABLE `posts` (
      `post_id` int(8) NOT NULL,
      `post_content` text NOT NULL,
      `post_date` datetime NOT NULL,
      `post_topic` int(8) NOT NULL,
      `post_by` int(8) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    ";
    
    if ($conn->query($sql3) === TRUE) {
        echo "<blockquote>Table posts created successfully.</blockquote>";
    } else {
        echo "<blockquote>Error creating table: " . $conn->error . ".</blockquote>";
    };
    
    $sqlA = "ALTER TABLE `categories`
      ADD PRIMARY KEY (`cat_id`),
      ADD UNIQUE KEY `cat_name_unique` (`cat_name`);
    ";
    
    $sqlA1 = "ALTER TABLE `posts`
      ADD PRIMARY KEY (`post_id`),
      ADD KEY `post_by` (`post_by`),
      ADD KEY `post_topic` (`post_topic`);
    ";
    
    $sqlA2 = "ALTER TABLE `topics`
      ADD PRIMARY KEY (`topic_id`),
      ADD KEY `topic_cat` (`topic_cat`),
      ADD KEY `topic_by` (`topic_by`);
    ";
    
    $sqlA3 = "ALTER TABLE `users`
      ADD PRIMARY KEY (`user_id`),
      ADD UNIQUE KEY `user_name_unique` (`user_name`);
    ";
    
    $Alterations = [$sqlA , $sqlA1 , $sqlA2 , $sqlA3 ];

    foreach($Alterations as $k => $sqlIA){
        $query1 = $conn->query($sqlIA);
    }
    if(!$query1){
        echo "<blockquote>Alterations were unsuccesfull: " . $conn->error . "</blockquote>";
    } else {
        echo "<blockquote>Tables successfully altered.</blockquote>";
    }
    
    $sqlI = "ALTER TABLE `categories`
      MODIFY `cat_id` int(8) NOT NULL AUTO_INCREMENT;";
    
    $sqlI1 = "ALTER TABLE `posts`
      MODIFY `post_id` int(8) NOT NULL AUTO_INCREMENT;";

    $sqlI2 = "ALTER TABLE `topics`
      MODIFY `topic_id` int(8) NOT NULL AUTO_INCREMENT;";

    $sqlI3 = "ALTER TABLE `users`
      MODIFY `user_id` int(8) NOT NULL AUTO_INCREMENT;";

    $Increments = [$sqlI, $sqlI1, $sqlI2, $sqlI3];

    foreach($Increments as $k => $sqlII){
        $query2 = $conn->query($sqlII);
    }
    if(!$query2){
        echo "<blockquote>Increments were unsuccesfull: " . $conn->error . ".</blockquote>";
    } else {
        echo "<blockquote>Tables successfully incremented.</blockquote>";
    }


    $sqlA4 = "ALTER TABLE `posts`
      ADD FOREIGN KEY (`post_topic`) REFERENCES `topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD FOREIGN KEY (`post_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
    ";

    $sqlA5 = "ALTER TABLE `topics`
      ADD FOREIGN KEY (`topic_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD FOREIGN KEY (`topic_cat`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE;
    ";

    $Alter2 = [$sqlA4, $sqlA5];
    foreach($Alter2 as $k => $sqlIA2){
        $query3 = $conn->query($sqlIA2);
    }
    if(!$query3){
        echo "<blockquote>Second round of alterations were unsuccesfull: " . $conn->error . ".</blockquote>";
    } else {
        echo "<blockquote>Tables successfully altered for the second time.</blockquote>";
    }

    $conn->close();
?>
            </div>
        </div>
        
        <div class="space"></div>
        <div class="space"></div>
        
        <?php
            //Footeren inkluderes på alle sider for at skabe symmetri og sammenhæng
            include 'footer.php';
        ?>
        
        <!--Javascript loades til sidst på siden, for at forbedre performance-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>
