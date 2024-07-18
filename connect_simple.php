<?php
require_once __DIR__ . "/config.php";

$link = mysqli_connect(SRV_LOCALHOST__IP_ADDRESS, SRV_LOCALHOST__USER_ARBO__USERNAME, SRV_LOCALHOST__USER_ARBO__PASSWORD, SRV_LOCALHOST__DATABASE_BDDUAVFH);

# test if the connection was made
if (!$link){
    header('Location: /error.php');
    exit();    
}  

mysqli_set_charset($link, "latin1");  

#SESSION TIMEOUT
$inactive = 3600;
if( !isset($_SESSION['timeout']) )
$_SESSION['timeout'] = time() + $inactive; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive){  
    session_destroy(); header("location:../logout.php");     
}
$_SESSION['timeout']=time();

return $link;