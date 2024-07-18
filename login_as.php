
<?php

if (session_status() == PHP_SESSION_NONE) session_start();

if (isset($_SESSION['username']) === false) {
    header('location: /index.php?continue=' . $_SERVER['REQUEST_URI']);
    exit();
}

if (strpos($_SESSION['profile'], 'comptes') === false) {
    header('location: /index.php');
    exit();
}

require 'connect.php';
require 'functions.php';

$user_id = intval($_GET["access_as_user_id"] ?? 0);
if(!$user_id) die("Veuillez spécificer un utilisateur");

/* Preparation de la requete */
$query =  "SELECT * "
		. "FROM users_limsgambia "
		. "WHERE id = " . $user_id;

/* execution de la requete */
$res = mysqli_query($link, $query);

if(mysqli_num_rows($res)>0){
    if($data = mysqli_fetch_array($res)){
    	$_SESSION["adresse_mail"]       = $data["adresse_mail"];
    	$_SESSION["email_notification"] = $data["email_notification"];
        $_SESSION['profile']            = $data['profile']; 
        $_SESSION['district']           = $data['district'];
        $_SESSION['username']           = $data['nom'];
        $_SESSION['client_compte']      = $data['client_compte'];
        $_SESSION['code_client']        = $data['code_client'];
        $_SESSION['pays']               = $data['pays'];
        $_SESSION['telephone']          = $data['telephone'];
        $_SESSION['laboratoire']        = $data['laboratoire'];
        $_SESSION['tableResuslts']      = "covid_prelevements_min";
        #$_SESSION['tableResusltsDS']    = "covid_results_by_district_min";
        $_SESSION['tableResusltsDS']    = "covid_prelevements_min";
        $_SESSION['tableInvestigation'] = "patients_limsgambia";
        $_SESSION['tableFacturation']   = "view_facturation_min";
        $_SESSION['enrolledDistricts']  = array( "DKO", "DKS", "DKN", "DKC", "STL", "KED", "TOU" );
        
        if($data['access'] === 'non'){
            echo 'access_denied';
            exit();
        }  
        
        else {
        	mysqli_query($link, "	UPDATE users_limsgambia 
		        			SET  nb_connections = nb_connections + 1,
        						 statut = 1,
        						 date_time = '". date("Y-m-d H:i:s")."'
		        			WHERE login LIKE BINARY '".$data["login"]."' AND mdp LIKE BINARY '".$data["mdp"]."'");
            
        	mysqli_query($link, " INSERT INTO gambialims_historique_activities    ( editeur, profile,  date_heure )
								 VALUES ('" . $_SESSION ['username']
					        			. " - " . $_SESSION ['code_client']
					        			. "', 'Connexion', '"
					        			. date ( "Y-m-d H:i:s" ) . "')");
        	
            $_SESSION["login"] = $data["login"];
            $_SESSION["mdp"] = $data["mdp"];
            echo $data['profile'];
            exit();
        }
        
    } 
    
    else {
        echo 'ko';
        exit();
    }
} 

else {
    echo 'ko';
    exit();
}


