<?php
session_start();
$host = "localhost"; /* Host name */
$user = "root"; /* User */
$password = "qwerqwer"; /* Password */
$dbname = "test"; /* Database name */

$con = mysqli_connect($host, $user, $password,$dbname);

// Check connection
if (!$con) {
 die("Connection failed: " . mysqli_connect_error());
}

if( isset($_SESSION['userid']) ){
 header('Location: home.php');
 exit;
}else if( isset($_COOKIE['rememberme'] )){
 
 // Decrypt cookie variable value
 $userid = decryptCookie($_COOKIE['rememberme']);
 
 $sql_query = "select count(*) as cntUser,id from test_join where id='".$userid."'";
 $result = mysqli_query($con,$sql_query);
 $row = mysqli_fetch_array($result);

 $count = $row['cntUser'];

 if( $count > 0 ){
  $_SESSION['userid'] = $userid; 
  header('Location: home.php');
  exit;
 }
}

// Encrypt cookie
function encryptCookie( $value ) {
 $key = 'youkey';
 $newvalue = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $key ), $value, MCRYPT_MODE_CBC, md5( md5( $key ) ) ) );
 return( $newvalue );
}

// Decrypt cookie
function decryptCookie( $value ) {
 $key = 'youkey';
 $newvalue = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $key ), base64_decode( $value ), MCRYPT_MODE_CBC, md5( md5( $key ) ) ), "\0");
 return( $newvalue );
}

// On submit
if(isset($_POST['but_submit'])){

 $uname = mysqli_real_escape_string($con,$_POST['txt_uname']);
 $password = mysqli_real_escape_string($con,$_POST['txt_pwd']);
 
 if ($uname != "" && $password != ""){

  $sql_query = "select count(*) as cntUser,id from test_join where userID='".$uname."' and password='".$password."'";
  $result = mysqli_query($con,$sql_query);
  $row = mysqli_fetch_array($result);

  $count = $row['cntUser'];

  if($count > 0){
   $userid = $row['id'];
   if( isset($_POST['rememberme']) ){

    // Set cookie variables
    $days = 30;
    $value = encryptCookie($userid);
    setcookie ("rememberme",$value,time()+ ($days * 24 * 60 * 60 * 1000));
   }
 
   $_SESSION['userid'] = $userid; 
   header('Location: home.php');
   exit;
  }else{
   echo "Invalid username and password";
  }

 }

}
?>