<?php
    require_once './php/db_connect.php';
    
    function sanitizeString($str)
    {
        //cleans up string
        $str = strip_tags($str);
        $str = htmlentities($str);
        $str = stripslashes($str);
        return $str;
    }
    
    //inserts userid/pass into database
    function add($db,$us,$pw,$ad)
    {
        //create insert statement
        $insertStmt ="INSERT INTO `USERS` VALUES('$us','$pw','$ad')";

        //queries insert statement
        $result= $db->query($insertStmt);

        if(!$result) die($db->error);
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

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title>Sign Up</title>
        
        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/signin.css" rel="stylesheet">
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
        <!-- Password Strength -->
        <script src="js/password.js"></script>
    </head>
    <body>
      
        <div class="container">
        <div class="card card-container">
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form method="post" action="signup.php" class="form-signin">
                <span id="reauth-email" class="reauth-email"></span>
                <input type='text' maxlength='16' name='user' class="form-control" placeholder="Username" required autofocus>
                <input type='password' maxlength='16' name='password' id='password' class="form-control" placeholder="Password" required>
                <p>Password Strength: <span id="result"></span></p>
                <input type='password' maxlength='16' name='verify' class="form-control" placeholder="Verify Password" required>
                <input type="checkbox" name="admin" value="1"><label> Check if you are an Admin</label>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign Up</button>
            </form><!-- /form -->
        
        <?php
            $error = "";
            if(isset($_POST['user']))
            { 
                //initialize salts
                $salt="@#fgee";
                $salt2="&^fgr123";
                
                //sanitizes user input
                $user = sanitizeString($_POST['user']);
                $pass = $_POST['password'];
                $verify = $_POST['verify'];
                if (isset($_POST['admin']))
                {
                    $admin = $_POST['admin'];
                }
                else
                {
                    $admin = 0;
                }
                
                //hashes password entered
                $token = hash('ripemd128', "$salt$pass$salt2");

                //checks if all fields were entered
                if($user == '' || $pass == '' || $verify == '')
                {
                    $error = "Error: not all fields are entered";
                }
                else
                {
                     //select statement to check whether the username already exists
                     $selectStmt = "SELECT * FROM `USERS` WHERE `userid`='$user';";
                    
                     //queries statement
                     $result = $db->query($selectStmt);

                     if ($result->num_rows)
                     {
                         //error message
                        $error = "Error: that username already exists";
                     }
                     else
                     {
                         //checks if the passwords entered line up
                        if ($pass == $verify)
                        {
                            //adds username and password to the database
                            add($db, $user, $token, $admin);
                            
                            //starts session
                            session_start();
                            $_SESSION["user"] = $user;
                            $_SESSION["pass"] = $pass;
                            $_SESSION["admin"] = $admin;
                            $error = "You are now logged in!";
                            
                            //redirects to the wall
                            redirect("http://lamp.cse.fau.edu/~jbitton2013/hw8/wall.php");
                            exit();
                        }
                        else
                        {
                            //error message if passwords dont line up
                            $error = "Error: passwords do not match";
                        }  
                     }
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
        
    </body>
</html>