<?php
    //get access to database
    require_once './php/db_connect.php';

    function sanitizeString($str)
    {
        //cleans up string
        $str = strip_tags($str);
        $str = htmlentities($str);
        $str = stripslashes($str);
        return $str;
    }

    function redirect($url)
    {
        //redirects url by echoing javascript code
        $string = '<script type="text/javascript">';
        $string .= 'window.location = "' . $url . '"';
        $string .= '</script>';

        echo $string;
    }
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title>Log In</title>
        
        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/signin.css" rel="stylesheet">
    </head>
    <body>
       
    <div class="container">
        <div class="card card-container">
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form method="post" action="index.php" class="form-signin">
                <span id="reauth-email" class="reauth-email"></span>
                <input type='text' maxlength='16' name='userl' class="form-control" placeholder="Username" required autofocus>
                <input type='password' maxlength='16' name='passl' class="form-control" placeholder="Password" required>
              
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Log in</button>
            </form><!-- /form -->
            <p>Don't have an acccount? <a href="./signup.php" class="forgot-password">Register here</a></p>
        
        <?php
            $error = "";
            if (isset($_POST["userl"]))
            {
                //initialize salts
                $salt="@#fgee";
                $salt2="&^fgr123";
                
                //sanitize user inputs
                $userl = sanitizeString($_POST['userl']);
                $passl = sanitizeString($_POST['passl']);

                //hash the password
                $token = hash('ripemd128',"$salt$passl$salt2");
                
                //find userid in database
                $query = "SELECT * FROM `USERS` WHERE `userid`='$userl'";
                $result = $db->query($query);
                
                if(!$result)die($db->error);
                
                else if($result->num_rows)
                {
                    $row = $result->fetch_array(MYSQLI_NUM);

                    $result->close();

                    //check if the password matches
                    if ($token == $row[1])
                    {
                        //start session
                        session_start();
                        $_SESSION["user"] = $userl;
                        $_SESSION["pass"] = $passl;
                        $_SESSION["admin"] = $row[2];

                        $error = 'You are now logged in!';
                        
                        //redirect to the wall
                        redirect("http://lamp.cse.fau.edu/~jbitton2013/hw8/wall.php");
                        exit();
                    }
                    else
                    {
                        //error message if password isn't correct
                        $error = "You entered a invalid username/password combination";
                    }
                }
                else
                {
                    //error message if username doesn't exist
                    $error = "You entered a invalid username/password combination";
                }
            }
        ?>
        
        <p><?php echo "$error"; ?></p>
        </div><!-- /card-container -->
    </div><!-- /container -->
        
         <!-- jQuery -->
         <script src="js/jquery.js"></script>

         <!-- Bootstrap Core JavaScript -->
         <script src="js/bootstrap.min.js"></script>
        
         <!-- JS for Theme -->
         <script src="js/signin.js"></script>
    </body>
</html>