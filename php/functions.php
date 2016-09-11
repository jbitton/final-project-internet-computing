<?php
require_once "db_connect.php";
function sanitizeString($_db, $str)
{
    $str = strip_tags($str);
    $str = htmlentities($str);
    $str = stripslashes($str);
    return mysqli_real_escape_string($_db, $str);
}


function SavePostToDB($_db, $_user, $_title, $_text, $_time, $_file_name, $_filter)
{
	/* Prepared statement, stage 1: prepare query */
	if (!($stmt = $_db->prepare("INSERT INTO WALL(USER_USERNAME, STATUS_TITLE, STATUS_TEXT, TIME_STAMP, IMAGE_NAME, FILTER) VALUES (?, ?, ?, ?, ?, ?)")))
	{
		echo "Prepare failed: (" . $_db->errno . ") " . $_db->error;
	}

	/* Prepared statement, stage 2: bind parameters*/
	if (!$stmt->bind_param('ssssss', $_user, $_title, $_text, $_time, $_file_name, $_filter))
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	/* Prepared statement, stage 3: execute*/
	if (!$stmt->execute())
	{
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
}

function getPostcards($_db, $_admin)
{
    $query = "SELECT USER_USERNAME, STATUS_TITLE, STATUS_TEXT, TIME_STAMP, IMAGE_NAME, FILTER FROM WALL ORDER BY TIME_STAMP DESC";
    //$query2 = "SELECT admin FROM USERS WHERE userid='$_user''";
    
    if(!$result = $_db->query($query))
    {
        die('There was an error running the query [' . $_db->error . ']');
    }
    $server_root="";
    $output="";
    
    /*$result2 = $_db->query($query2);
    
    $admin2 = $result2->fetch_assoc();*/
    
    while($row = $result->fetch_assoc())
    {
        $output = $output . '<div class="panel panel-default"><div class="panel-heading">"' . $row['STATUS_TITLE']
        . '" posted by ' . $row['USER_USERNAME'] 
        . '</div><div class="body"><img class="img-responsive" src="' . $server_root . 'users/' . $row['IMAGE_NAME'] . '" width="300px" alt="" style="-webkit-filter:' . $row['FILTER'] . ';">' . '<hr>' . '<p>' . $row['STATUS_TEXT'] . '<div class="fb-like" data-href="https://developers.facebook.com/docs/plugins/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div></p></div></div><hr>' ;
        
        if ($_admin == 1)
        {
            $output = $output . '<form method="post" action=""><input type="hidden" name="postid" value="' . $row['TIME_STAMP'] . '"/><input type="submit" class="btn btn-primary col-md-offset-1" id="delete" name="delete" value="Delete Post"/></form><br><br>';
        }
    }
    
    return $output;
}
?>