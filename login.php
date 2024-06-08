<?php
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';
$rol="usu";

$statement = $pdo->prepare("SELECT * FROM configuracion WHERE idConfiguracion=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $favicon = $row['favIcon'];
    $logo = $row['logo'];
    $fondo = $row['fondoPag'];
}
?>

<?php
if (isset($_POST['form1'])) {

    $valid = 1;

    if(empty($_POST['nomUsu'])) {
        $valid = 0;
        $error_message = "El nombre de usuario no puede estar vacio"."<br>";
    }

	if(empty($_POST['telUsu'])) {
        $valid = 0;
        $error_message = "El teléfono no puede estar vacio"."<br>";
    }
    
    function validarNumeroTelefono($numero) {
        $patron = '/^\d{10}$/';
        return preg_match($patron, $numero);
    }
    if (validarNumeroTelefono($_POST['telUsu'])==0) {
        $valid = 0;
        $error_message = "Cumple con un número adecuado"."<br>";
    }

    if(empty($_POST['emaUsu'])) {
        $valid = 0;
        $error_message = "El email no puede estar vacio"."<br>";
    } else {
        if (filter_var($_POST['emaUsu'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message = "El email es invalido"."<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM usuario WHERE correo=?");
            $statement->execute(array($_POST['emaUsu']));
            $total = $statement->rowCount();
            if($total) {
                $valid = 0;
                $error_message = "El email ya existe"."<br>";
            }
        }
    }

    if( !empty($_POST['passUsu']) && !empty($_POST['rePassUsu']) ) {
        if($_POST['passUsu'] != $_POST['rePassUsu']) {
            $valid = 0;
            $error_message = "Las contraseñas no coinciden"."<br>";
        }
    }

    if($valid == 1) {
        $token = md5(time());

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO usuario (
                                        nombreUsuario,
                                        correo,
                                        contrasenia,
										telefono,
										rol,
										fotoUsuario,
										estado,
										token
                                    ) VALUES (?,?,?,?,?,?,?,?)");
        $statement->execute(array(
                                        strip_tags($_POST['nomUsu']),
                                        strip_tags($_POST['emaUsu']),
                                        MD5($_POST['passUsu']),
										strip_tags($_POST['telUsu']),
										$rol,
										'usr.png',
										'0',
										$token
                                    ));
        unset($_POST['passUsu']);
        
        // Send email for confirmation of the account
        $to = $_POST['emaUsu'];
        
        $subject = "verify@urbanglammx.com";
        $verify_link = 'https://reoiltech.urbanglammx.com/verificar.php?email='.$to.'&token='.$token;
        $message = '<!DOCTYPE html>
                    <html lang="en">
                    
                    <head>
                        <meta charset="UTF-8">
                        <title>Confirmación de Correo Electrónico</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f4f4f4;
                                margin: 0;
                                padding: 0;
                            }
                    
                            .container {
                                width: 80%;
                                margin: 20px auto;
                                background-color: #fff;
                                border-radius: 8px;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                overflow: hidden;
                            }
                    
                            header {
                                background-color: #4caf50;
                                color: #fff;
                                text-align: center;
                                padding: 20px 0;
                            }
                    
                            .content {
                                padding: 20px;
                            }
                    
                            .confirm-btn {
                                display: inline-block;
                                background-color: #4caf50;
                                color: #fff;
                                text-decoration: none;
                                padding: 10px 20px;
                                border-radius: 5px;
                                margin-top: 20px;
                            }
                    
                            .confirm-btn:hover {
                                background-color: #45a049;
                            }
                    
                            footer {
                                text-align: center;
                                padding: 10px 0;
                                background-color: #ddd;
                            }
                    
                            img {
                                max-width: 100%;
                                height: auto;
                                display: block;
                                margin: 20px auto;
                            }
                        </style>
                    </head>
                    
                    <body>
                        <div class="container">
                            <header>
                                <h1>¡Confirma tu correo electrónico!</h1><br>
                            </header>
                            <div class="content">
                                <p>¡Hola!</p><br>
                                <p>Para completar el proceso de registro, necesitamos verificar tu dirección de correo electrónico.</p><br>
                                <img src="https://codigoworpress.com/wp-content/uploads/2021/04/1618879157_Como-saber-si-se-lee-un-correo-electronico.jpg" alt="Imagen de confirmación" /><br><br>
                                <p>Por favor, haz clic en el botón de abajo para confirmar tu correo electrónico:</p><br>
                                '.'<a href="'.$verify_link.'">'.'Verificar'.'</a>'.
                                '<p>Gracias por tú visita,</p>
                                 <p>De parte del equipo<br> DreamTeam-ReOilTech</p>
                            </div>
                            <footer>
                                <p>Este correo es generado automáticamente, por favor no respondas a este mensaje.</p>
                            </footer>
                        </div>
                    </body>
                    </html>';

        $headers = "From: noreply@" . BASE_URL . "\r\n" .
                   "Reply-To: noreply@" . BASE_URL . "\r\n" .
                   "X-Mailer: PHP/" . phpversion() . "\r\n" . 
                   "MIME-Version: 1.0\r\n" . 
                   "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
        // Sending Email
        mail($to, $subject, $message, $headers);

        
        $success_message = "Registro exitoso!!<br>Se ha envíado un correo de confirmación, no olvides revisar la carpeta de spam";
    }
}
?>

<?php
if(isset($_POST['form2'])) {
        
    if(empty($_POST['emaUsu2']) || empty($_POST['passUsu2'])) {
        $error_message = "Llena todos los campos".'<br>';
    } else {
        
        $email = strip_tags($_POST['emaUsu2']);

        $statement = $pdo->prepare("SELECT * FROM usuario WHERE correo=?");
        $statement->execute(array($email));
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
            $id = $row['idUsuario'];
            $row_password = $row['contrasenia'];
			$cust_status = $row['estado'];
            $rol = $row['rol'];
        }
        if($total==0) {
            $error_message = "No existe el usuario".'<br>';
        } else {
            //using MD5 form
            if( md5($_POST['passUsu2'])!=$row_password) {
                $error_message = "La contraseña es incorrecta".'<br>';
            } else {
                if($cust_status == 0) {
                    $error_message = "El usuario no esta activo, debes confirmar tú dirección de correo".'<br>';
                } else {
                    $statement2 = $pdo->prepare("SELECT * FROM ubicacion WHERE Usuario_idUsuario=?");
                    $statement2->execute(array($id));
                    $total2 = $statement2->rowCount();
                    if ($rol=="usu") {
                        if ($total2==0) {
                            header("location: ".BASE_URL."addlocation.php");
                            $_SESSION['correo']=$email;
                        } else {
                            header("location: ".BASE_URL."dashboard.php");
                            $_SESSION['correo']=$email;
                        }                        
                    }
                    if ($rol=="adm" || $rol=="root") {
                        header("location: ".BASE_URL."admin/index.php");
                        $_SESSION['correo']=$email;
                    } 
                    if ($rol=="rec") {
                        header("location: ".BASE_URL."recolector/index.php");
                        $_SESSION['correo']=$email;
                    }
                }
            }
            
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ReOilTech</title>
	<link rel="stylesheet" type="text/css" href="css/login2.css">
    <link rel="icon" type="image/png" href="img/<?php echo $favicon; ?>">
    <style>
        body {
                background-image: url('img/<?php echo $fondo;?>');
                background-size: cover;
                background-attachment: fixed;
                text-align: center;
                padding: 0px;
            }
            
            .imgL{
                height: 10rem;
                width: 20rem;
            }
            
            @media (max-width: 576px) {
                .btn{
                  padding: 13px 34px;
                }
                input{
                    padding: 8px 8px;
                    width: 160%;
                }
                .imgL{
                    height: 9rem;
                    width: 14rem;
                }
                a{
                    font-size: 11px;
                }
                h1{
                    font-size: 28px;
                }
            }

    </style>

</head>
<body>
	
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="" method="post">
			<br><h1>Crear una cuenta</h1>
			<?php
                    if(isset($error_message) && $error_message !='') {
                        echo "<div class='error' style='padding: 2px;background:#f1f1f1;margin-bottom:0px;'>".$error_message."</div>";
                    }
                    if(isset($success_message) && $success_message !='') {
    	                echo "<div class='success' style='padding: 2px;background:#f1f1f1;margin-bottom:0px;'>".$success_message."</div>";
                    }
                ?>
			<div class="social-container">
			</div>
			<input type="text" placeholder="Nombre" name="nomUsu"/>
			<input type="text" placeholder="Email" name="emaUsu"/>
			<input type="number" maxlength="10" placeholder="Teléfono" name="telUsu"/>
			<input type="password" placeholder="Contraseña" name="passUsu"/>
			<input type="password" placeholder="Confirmar contraseña" name="rePassUsu"/>
			<input type="submit" class="btn" value="<?php echo "Registrate" ?>" name="form1">
		</form>
	</div>
	<div class="form-container sign-in-container validate-form">
		<form autocomplete="off" method="post"  role="form">			
			<div class="social-container">
				<img class="imgL" src="img/<?php echo $logo;?>">
			</div>
			<?php
                    if(isset($error_message) && $error_message !='') {
                        echo "<div class='error' style='padding: 2px;background:#f1f1f1;margin-bottom:0px;'>".$error_message."</div>";
                    }
                    if(isset($success_message) && $success_message !='') {
    	                echo "<div class='success' style='padding: 2px;background:#f1f1f1;margin-bottom:0px;'>".$success_message."</div>";
                    }
                ?>
			<input type="text" name="emaUsu2" value=""  autocomplete="off" data-validate="Enter username or email" autocomplete="new-password" placeholder="Email" />
			<input type="password" required="true" name="passUsu2" value="" data-validate="Enter password" placeholder="Contraseña" />
			<!--<a href="olvidado.php">¿Has olvidado tú contraseña?</a>-->
			<input type="submit" class="btn btn-danger" value="<?php echo "Entrar"; ?>" name="form2">
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>¡Bienvenido de nuevo!</h1>
				<p>Para mantenerse conectado con nosotros, inicie sesión con sus datos</p>
				<button class="ghost" id="signIn">Entrar</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>¡Bienvenido!</h1>
				<p>¿Aún no tienes una cuenta?</p>
				<p>Registrate y comienza con una nueva experiencia</p>
				<button class="ghost" id="signUp">Registrarse</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/login.js"></script>

</body>
</html>