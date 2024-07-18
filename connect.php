<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . "/config.php";

// $link = mysqli_connect(SRV_LOCALHOST__IP_ADDRESS, SRV_LOCALHOST__USER_ARBO__USERNAME, SRV_LOCALHOST__USER_ARBO__PASSWORD, SRV_LOCALHOST__DATABASE_BDDUAVFH);

$servername = "127.0.0.1";
$username = "limsuser";
$password = "Malilims@2024";
$dbname = "malilims";

#$link = mysqli_connect("172.0.0.1", "limsuser", "Malilims@2024", "malilims");
$link = mysqli_connect($servername, $username, $password, $dbname);

###
# Data Cleaning - Prevent SQL injections
$_POST = trg_mysql_escape_array($_POST, $link);
$_GET = trg_mysql_escape_array($_GET, $link);




# test if the connection was made
if (!$link){
    header('Location: /error.php?error='.urlencode(mysqli_connect_error()));
    exit();    
}  

mysqli_set_charset($link, "latin1");  

#SESSION TIMEOUT
$inactive = 3600; // 1hour
if( !isset($_SESSION['timeout']) )
$_SESSION['timeout'] = time() + $inactive; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive){  
    session_destroy();
    header("Location: /logout.php");
}
$_SESSION['timeout']=time();

return $link;
