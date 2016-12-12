<?php
	
	session_start();
	
	if ($_GET["logout"]==1 AND $_SESSION['id']) {
		session_destroy();
		$message="You have been logged out. Have a nice day!";
	}
	
	include("connection.php");
	
	if ($_POST['submit']=="Sign Up") {
		
		if (!$_POST['email']) $error.="<br />Please enter your email";
			else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $error."<br />Please enter a valid email address";
			
		if (!$_POST['password']) $error.="<br />Please enter your password";
			else {
				if (strlen($_POST['password'])<8) $error.="<br />Please enter a password with at least 8 characters";
				if (!preg_match('`[A-Z]`', $_POST['password'])) $error.="<br />Please include at least one capital letter in your password";
			}
		
		if ($error) $error = "There were error(s) in your signup details:".$error;
		else {
			
			$query="SELECT * FROM `users` WHERE email='".mysqli_real_escape_string($link, $_POST['email'])."'";
			
			$result = mysqli_query($link, $query);
			
			$results = mysqli_num_rows($result);
			
			if ($results) $error = "That email address is already registered. Do you want to log in?";
			else {
				
				$query = "INSERT INTO `users` (`email`, `password`) VALUES('".mysqli_real_escape_string($link, $_POST['email'])."', '".md5(md5($_POST['email']).$_POST['password'])."')";
				
				mysqli_query($link, $query);
				
				$error = "You've been signed up!";
				
				$_SESSION['id']=mysqli_insert_id($link);
				
				header("Location:mainpage.php");
				
			}
		}
	}
	
	if ($_POST['submit']=="Log In") {
		
		$query = "SELECT * FROM users WHERE email='".mysqli_real_escape_string($link, $_POST['loginemail'])."' AND password='".md5(md5($_POST['loginemail']).$_POST['loginpassword'])."' LIMIT 1";
		
		$result = mysqli_query($link, $query);
		
		$row = mysqli_fetch_array($result);
		
		if($row) {
			
			$_SESSION['id']=$row['id'];
			
			header("Location:mainpage.php");
			
		} else {
			
			$error = "We could not find a user with that email and password. Please try again.";
			
		}
		
	}
	
?>