<?php 

	include("functions.php");

	if($_GET['action'] == 'loginSignup') {

		$error = "";
        
       if (!$_POST['email']) {
            
            $error = "An email address is required.";
            
        } else if (!$_POST['password']) {
            
            $error = "A password is required";
            
        } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
  
            $error = "Please enter a valid email address.";
            
		}
        
        if ($error != "") {
            
            echo $error;
            exit();
            
        }
        

    	if ($_POST['loginActive'] == "0") {
            
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {

            	$error = "That email address is already taken.";
            }
            else {
                $query = "INSERT INTO users (`email`, `password`,`usertype`) VALUES ('". mysqli_real_escape_string($link, $_POST['email'])."', '". mysqli_real_escape_string($link, $_POST['password'])."', '".mysqli_real_escape_string($link, $_POST['usertype'])."')";
                if (mysqli_query($link, $query)) {
                    
                    $_SESSION['id'] = mysqli_insert_id($link);
                    
                    $query = "UPDATE users SET password = '". md5(md5($_SESSION['id']).$_POST['password']) ."' WHERE id = ".$_SESSION['id']." LIMIT 1";
                    mysqli_query($link, $query);
                    
                    echo 1;
                    
                    
                    
                } else {
                    
                    $error = "Couldn't create user - please try again later";
                    
                }
                
            }
            
        } else {
            
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            
            $result = mysqli_query($link, $query);
            
            $row = mysqli_fetch_assoc($result);
                
                if ($row['password'] == md5(md5($row['id']).$_POST['password'])) {
                    
                    echo 1;
                    
                    $_SESSION['id'] = $row['id'];
                    
                } else {
                    
                    $error = "Could not find that username/password combination. Please try again.";
                    
                }

            
        }
        
         if ($error != "") {
            
            echo $error;
            exit();
            
        }

	}


	if($_GET['action'] == 'eventCreate') {


       if (!$_POST['eventName']) {
            
            $error = "An event name is required.";
            
        } else if (!$_POST['place']) {
  
            $error = "An event city is required.";
            
		}
        
        if ($error != "") {
            
            echo $error;
            exit();
            
        }

        $query = "SELECT * FROM events WHERE name = '". mysqli_real_escape_string($link, $_POST['eventName'])."' LIMIT 1";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {

            	$error = "That event name is already taken.";
            }

		if ($error != "") {
            
            echo $error;
            exit();
            
        }

		$query = "INSERT INTO events (`name`, `speakerid`, `place`) VALUES ('". mysqli_real_escape_string($link, $_POST['eventName'])."', '".mysqli_real_escape_string($link, $_SESSION['id'])."', '".mysqli_real_escape_string($link, $_POST['place'])."')";                
		if (mysqli_query($link, $query)) { 
			echo 1;
		}
		else {

			echo "Couldn't create event. Please try again after some time.";
		}	
	}

?>