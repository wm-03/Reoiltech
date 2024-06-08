<?php
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
?>

<?php
$statement = $pdo->prepare("SELECT * FROM configuracion WHERE idConfiguracion=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $favicon = $row['favIcon'];
    $fondo = $row['fondoPag'];
}

$email=$_SESSION['correo'];
$statement = $pdo->prepare("SELECT * FROM usuario WHERE correo=?");
$statement->execute(array($email));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $id = $row['idUsuario'];
    $nombre = $row['nombreUsuario'];
    $correo = $row['correo'];
    $telefono = $row['telefono'];
    $rol = $row['rol'];
    $imgUsu = $row['fotoUsuario'];
}
?>

<?php
if(isset($_POST['form1'])) {

    $valid = 1;
    
    $path = $_FILES['imgusua']["name"];
    $path_tmp = $_FILES['imgusua']['tmp_name'];

    if($path != '') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' ) {
            $valid = 0;
            $error_message .= 'Debe tener un archivo png, jpg o jpeg<br>';
        }
    }

    if($valid == 1) {

        if($path != '') {
		    $final_name = 'usuario-'.$id.'.'.$ext;
            move_uploaded_file( $path_tmp, 'img/usuarios/'.$final_name );
            
		    $statement = $pdo->prepare("UPDATE usuario SET fotoUsuario=? WHERE idUsuario=?");
		    $statement->execute(array($final_name, $id));
            
            header('location: configuracion.php');
        }        
    }
    
}
if(isset($_POST['form3'])) {
    if(isset($_POST['telefonou'])) {
            
        $statement = $pdo->prepare("UPDATE usuario SET telefono=? WHERE idUsuario=?");
        $statement->execute(array($_POST['telefonou'], $id));
            
        header('location: configuracion.php');      
    }
}
if(isset($_POST['form4'])) {
    $valid = 0;
    $statement = $pdo->prepare("SELECT * FROM mensaje WHERE Usuario_idUsuario=?");
    $statement->execute(array($id));
    $total = $statement->rowCount();
    if ($total == 0) {
        $valid = 1;
    }

    if($valid == 1){
        if(isset($_POST['crol'])) {
            $txt = "Solicitud de cambio de rol a: ".$nombre.". Con id: ".$id;
            
            $statement = $pdo->prepare("INSERT INTO mensaje (contenido, Usuario_idUsuario, rol) VALUES (?, ?, ?)");
            $statement->execute(array($txt, $id, $_POST['crol']));
            
            header('location: configuracion.php');
        }    
    }
}
?>


<!doctype html>
<html lang="es-MX">

<head>
    <!-- begin metas -->
    <meta charset="utf-8">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- end metas -->
    <title>ReOilTech</title>
    
    <!-- begin css -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles2.css">
    <link rel="stylesheet" href="css/dots/three-dots.min.css" />
    <link rel="icon" type="image/png" href="img/<?php echo $favicon; ?>">

    <!-- end css -->

    <!-- begin scripts -->
    <script defer="defer" src='cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js' integrity='sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==' crossorigin='anonymous'></script>
    <script defer="defer" src="cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js" integrity="sha512-7rusk8kGPFynZWu26OKbTeI+QPoYchtxsmPeBqkHIEXJxeun4yJ4ISYe7C6sz9wdxeE1Gk3VxsIWgCZTc+vX3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script defer="defer" src="cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script defer="defer" src='cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js' integrity='sha512-f8mwTB+Bs8a5c46DEm7HQLcJuHMBaH/UFlcgyetMqqkvTcYg4g5VXsYR71b3qC82lZytjNYvBj2pf0VekA9/FQ==' crossorigin='anonymous'></script>
    <script defer="defer" src='cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js' integrity='sha512-A64Nik4Ql7/W/PJk2RNOmVyC/Chobn5TY08CiKEX50Sdw+33WTOpPJ/63bfWPl0hxiRv1trPs5prKO8CpA7VNQ==' crossorigin='anonymous'></script>
    <script defer="defer" src='cdnjs.cloudflare.com/ajax/libs/Swiper/9.2.1/swiper-bundle.js' integrity='sha512-hgzDTjAr3KToNCNN8ffTBol8aWBv6Oga3XbgPR8Qow3RzH7pFLYHDjDRU2+AD5gRjtdw5GvW+Kjwi5BNt66ufg==' crossorigin='anonymous'></script>
    <script defer="defer" src="js/main.js"></script>
    <script defer="defer" src="js/main2.js"></script>
	<script src="js/main.js" defer></script>

    <style>
        
        body {
                background-image: url('img/<?php echo $fondo;?>');
                background-size: cover;
                background-attachment: fixed;
                text-align: center;
                padding: 0px;
            }
    </style>

</head>
<body class="blocked">

<section id="home" class="leaves"></section>

<div class="navTrashContainer">
    <img loading="lazy" class="clickMenu icnMenu" src="img/menu.svg" alt="menu">
    <img loading="lazy" class="clickMenu icnClose" src="img/close.svg" alt="menu">
    <nav class="navTrash">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="dashboard.php#obj">Medallas</a></li>
            <li><a href="depositar.php">Depositar/Historial</a></li>
            <li><a href="noticias.php">Noticias</a></li>
            <li><a href="informacion/index.php">Mapa</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </nav>
</div>

<section class="container panelBanner" id="obj">
    <center>
        <form action="" method="post" enctype="multipart/form-data">
            <img style="width: 130px;" src="img/usuarios/<?php echo $imgUsu;?>" alt=""><br>
            <label for="usu"></label>Imagen de usuario actual</label>
            <input type="hidden" name="img1" value="<?php echo $imgUsu; ?>"><br><br>
            <label for="usu"></label>Modifíca tú imagen de usuario</label><br><br>
            <input type="file" name="imgusua"><br>
            <input type="submit" value="<?php echo "Actualizar"; ?>" name="form1">
        </form><br>
        <form action="" method="post">
            <label for="usu"></label>Número atual</label><br>
            <input type="text" name="usu" value="<?php echo $telefono; ?>" disabled><br><br>
            <label for="usu"></label>Modifíca tú número de teléfono</label><br>
            <input type="text" name="telefonou" style="background: cyan;"><br><br>
            <input type="submit" value="<?php echo "Actualizar"; ?>" name="form3">
        </form><br>
        <form action="" method="post">
            <label for="crol">Solicitar cambio de rol</label><br>
            <label for="crol"><b>*Nota: solo puedes solicitar un cambio de rol*</b></label><br><br>
            <select name="crol" id="crol">
                <option value="" selected>Selecciona una opción</option>
                <option value="rec">Recolector</option>
                <option value="adm">Admin</option>
            </select>
            <input type="submit" value="Solicitar cambio de rol" name="form4">
        </form>

    </center>
</section>

</body>
</html>