<?php
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$statement = $pdo->prepare("SELECT * FROM configuracion WHERE idConfiguracion=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $favicon = $row['favIcon'];
}
?>
<?php
if ( (!isset($_REQUEST['email'])) || (isset($_REQUEST['token'])) )
{
    $var = 1;

    // check if the token is correct and match with database.
    $statement = $pdo->prepare("SELECT * FROM usuario WHERE correo=?");
    $statement->execute(array($_REQUEST['email']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                           
    foreach ($result as $row) {
        if($_REQUEST['token'] != $row['token']) {
            header('location: '.BASE_URL);
            exit;
        }
    }

    // everything is correct. now activate the user removing token value from database.
    if($var != 0)
    {
        $statement = $pdo->prepare("UPDATE usuario SET token=?, estado=? WHERE correo=?");
        $statement->execute(array('',1,$_GET['email']));

        $success_message = '<p style="color:green;">Su correo electrónico se ha verificado correctamente. Ahora puede iniciar sesión en nuestro sitio web.</p><p><a href="'.BASE_URL.'login.php" style="color:#167ac6;font-weight:bold;">Haga clic aquí para ingresar</a></p>';     
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro completo</title>
    <link rel="icon" type="image/png" href="img/<?php echo $favicon; ?>">
    <style>
        /* Estilos para la página */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        .page-banner {
            background-color: #444;
            color: #fff;
            text-align: center;
            padding: 50px 0;
        }

        .inner {
            max-width: 960px;
            margin: 0 auto;
        }

        .inner h1 {
            margin: 0;
            font-size: 36px;
        }

        .page {
            padding: 30px 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .user-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-content p {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
        }

        .user-content a {
            color: #167ac6;
            font-weight: bold;
            text-decoration: none;
        }

        .user-content a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="page-banner">
        <div class="inner">
            <h1>Registro completo</h1>
        </div>
    </div>

    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="user-content">
                        <?php
                        if (isset($error_message)) {
                            echo $error_message;
                        }
                        if (isset($success_message)) {
                            echo $success_message;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
