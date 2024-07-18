<?php
    if(isset($_GET['cause']))
    {
        echo '<h1>'.htmlentities($_GET['cause']).'</h1>';
    }
    else
    {
        echo '<h1>ERREUR</h1>';
    }