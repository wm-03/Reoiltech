<?php
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
?>

<?php
    

        $token = md5(rand());
        $now = time();

        $statement = $pdo->prepare("UPDATE usuario SET token=? WHERE correo=?");
        $statement->execute(array($token,));
        
        $message = '<p>'.'Da click aquí para cambiar la contraseña'.'<br> <a href="'.'https://reoiltech.urbanglammx.com/reset-password.php?email='.$_POST['cust_email'].'&token='.$token.'">Click Aquí</a>';
        
        $to      = $_POST['cust_email'];
        $subject = "verify@urbanglammx.com";
        $headers = "From: noreply@" . BASE_URL . "\r\n" .
                   "Reply-To: noreply@" . BASE_URL . "\r\n" .
                   "X-Mailer: PHP/" . phpversion() . "\r\n" . 
                   "MIME-Version: 1.0\r\n" . 
                   "Content-Type: text/html; charset=ISO-8859-1\r\n";

        mail($to, $subject, $message, $headers);

        $success_message = $forget_password_message;
?>