<?php
if (session_status() == PHP_SESSION_NONE) session_start();

$query =  "SELECT * FROM users_limsgambia WHERE ";
