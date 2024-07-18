<?php
if (session_status() == PHP_SESSION_NONE) session_start();

require 'connect.php';

/** @var mysqli $link */

/*
if(file_exists("clients/".session_id().".png"))
{
    unlink("clients/".session_id().".png");
}
*/ 

if(isset($_SESSION["login"]) && $_SESSION["login"] && isset($_SESSION["mdp"]) && $_SESSION["mdp"]) {
    mysqli_query($link, "UPDATE users_limsgambia SET  statut = 0 WHERE login LIKE BINARY '" . $_SESSION["login"] . "' AND mdp LIKE BINARY '" . $_SESSION["mdp"] . "'");

    /*
    mysqli_query($link, " INSERT INTO gambialims_historique_activities    ( editeur, profile,  date_heure )
                                     VALUES ('" . $_SESSION ['username']
                                     . " - " . $_SESSION ['code_client']
                                     . "', 'Deconnexion', '"
                                             . date ( "Y-m-d H:i:s" ) . "')");
    */
    mysqli_close($link);
}


// Destroy session only if it exists
if (session_status() == PHP_SESSION_ACTIVE) session_destroy();

header('location:index.php');

exit();
