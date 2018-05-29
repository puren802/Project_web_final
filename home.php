<?php
 include "config.php";

// Check user login or not
 if(!isset($_SESSION['userid'])){
 header('Location: index.php');
}

// logout
if(isset($_POST['but_logout'])){
 session_destroy();

 // Remove cookie variables
 $days = 30;
 setcookie ("rememberme","", time() - ($days * 24 * 60 * 60 * 1000));

 header('Location: index.php');
}
?>
<h1>Homepage</h1>
<form method='post' action="">
 <input type="submit" value="Logout" name="but_logout">
</form>