<?php
    require_once "php/db_connect.php";
    require_once "php/functions.php";

    //start session
    session_start();
    
    //check if user is actually logged in
    if (!$_SESSION["user"])
    {
        //redirect to error page if not
        header("location:error.php");
    }

    function redirect($url)
    {
        //redirects url by echoing javascript code
        $string = '<script type="text/javascript">';
        $string .= 'window.location = "' . $url . '"';
        $string .= '</script>';

        echo $string;
    }

    if(isset($_POST['name']) && isset($_POST['title']) && isset($_POST['text']))
    {
        $time = $_SERVER['REQUEST_TIME'];
        $file_name = $time . '.jpg';
        $target_dir = "users/";
        $target_file = $target_dir . basename($file_name);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        $name = sanitizeString($db, $_POST['name']);
        $title = sanitizeString($db, $_POST['title']);
        $text = sanitizeString($db, $_POST['text']);
        if (isset($_POST['filter']))
        {
            $filter = sanitizeString($db, $_POST['filter']);
        }
        else
        {
            $filter = "";
        }

        if ($name !== "" && $title !== "" && $text !== "")
        {
            $time = $_SERVER['REQUEST_TIME'];
            $file_name = $time . '.jpg';

            $check = getimagesize($_FILES["upload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            if ($uploadOk)
            {
                if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }   
            }

            SavePostToDB($db, $name, $title, $text, $time, $file_name, $filter);
        } 
    }

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Image sharing wall</title>
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="css/styles.css">
	
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    
     <!-- Custom CSS -->
    <link href="css/blog-home.css" rel="stylesheet">
    
</head>
<body>
        <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
             <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><span class="logo">THE WALL</span></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="./form.php">Upload</a>
                        </li>
                        <li>
                            <a href="https://twitter.com/">Twitter</a>
                        </li>
                        <li>
                            <a href="https://www.tumblr.com/">Tumblr</a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/">Instagram</a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    
    <div class="container">
        
       <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        
        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <h1 class="page-header">
                    The Wall
                    <small>feel free to post pictures!</small>
                </h1>
    
                <?php
                    $admin = $_SESSION["admin"];
                    echo getPostcards($db, $admin);
                ?>
                <?php
                    if(isset($_POST['delete']))
                    {
                        $timeS = $_POST['postid'];
                        
                        $query = "DELETE FROM `WALL` WHERE `TIME_STAMP`='$timeS';";
                        
                        if($db->query($query))
                        {
                            echo "";
                        }
                    }
                ?>
                
            </div>
            
            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">

                <!-- Side Widget Well -->
                <div class="well">
                    <h4 align="center">About</h4>
                    <p>Post pictures of random things that you're passionate about! Whether it be animals or places you want to travel, all is welcome!</p>
                </div>
                
    
            
                <!-- Side Widget Well -->
                <div class="well">
                    <p>
                        <form method="post" action="wall.php">
                            <input type="submit" button class="btn btn-lg btn-primary btn-block btn-signin" name="btn" value="Log Out">
                        </form>
                    
                        <?php
                            //checks if log out button is pressed
                           if (isset($_POST['btn']))
                            {
                                //changes value of session
                                $_SESSION["user"] = array();
                               
                                //destroys session
                                session_destroy();

                                //redirects to login page
                                redirect("http://lamp.cse.fau.edu/~jbitton2013/hw8/");
                                exit();
                            }
                        ?>
                    </p>
                </div>
            </div>
    </div>
        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p align="center">Copyright &copy; Joanna Bitton 2016</p>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    
    <!-- Functions -->
    <script src="functions.js"></script>
</body>
</html>


<?php $db->close(); ?>