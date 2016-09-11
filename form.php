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
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Image uploader</title>
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="css/styles.css">
	
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="css/blog-home.css" rel="stylesheet">
</head>
<body onload="initialize();">
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
                    <a class="navbar-brand" href="./wall.php"><span class="logo">THE WALL</span></a>
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
		<div class="row">
			<div id="formParent" class="col-md-6 col-md-offset-3">
				<form id="form" class="form-horizontal" method="post" action="wall.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-1">Name</label>
                        <div class="col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="fa fa-user fa-fw"></span></span>
                                <input type="text" class="form-control" id="name" name="name" 
                            maxlength="20" size="20" value="" required placeholder="Johnny" autofocus>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="title" class="control-label col-xs-1">Title</label>
                        <div class="col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="fa fa-header fa-fw"></span></span>
                                <input type="text" class="form-control" id="title" name="title" 
                            maxlength="20" size="20" value="" required placeholder="Summer Vacation" autofocus>
                            </div>
                        </div>
                    </div>
                      
                    <div class="form-group">
                        <label for="text" class="control-label col-xs-1">Text</label>
                        <div class="col-xs-11">
                            <textarea class="form-control" id="text" name="text" maxlength="140" placeholder="140 characters" required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="sr-only" for="image">Original Image</label>
                        <img id="image" name="image" src="/" width="100%">
                        <input type="file" id="upload" name="upload" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <h3>Filter Photo</h3>
                        <div class="checkbox-inline">
                            <label for="myNostalgia">My Nostalgia</label>
                            <input type="radio" name="filter" id="myNostalgia" value="saturate(40%) grayscale(100%) contrast(45%) sepia(100%)" onclick="applyMyNostalgiaFilter();">
                        </div>
                        <div class="checkbox-inline">
                            <label for="grayscale">Grayscale</label>
                            <input type="radio" name="filter" id="grayscale" value="grayscale(100%)" onclick="applyGrayscaleFilter();">
                        </div>
                        <div class="checkbox-inline">
                            <label for="original">Revert to Original</label>
                            <input type="radio" name="filter" id="lomo" value="lomo" onclick="revertToOriginal();">
                        </div>
                    </div>
        
                    <input type="submit" value="Upload image to wall!" class="btn btn-primary col-md-offset-1">
                    <input type="button" id="resetForm" value="Start over!" class="btn btn-default">
				</form>
			</div>
		</div>
	</div>

	<!-- JavaScript placed at bottom for faster page loadtimes. -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	
	<!-- Latest compiled and minified JavaScript -->
	<script src="js/bootstrap.min.js"></script>
	
	<script src="functions.js"></script>

</body>
</html>
<?php $db->close(); ?>