<?php 
ob_start();
session_start();
include("inc/config.php");
unset($_SESSION['correo']);
header("location: index.php"); 
?>