<?php
include("../inc/config.php");
include("../inc/functions.php");
include("../inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
?>

<?php
$statement = $pdo->prepare("SELECT * FROM configuracion WHERE idConfiguracion=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $logo = $row['logo'];
}

$email=$_SESSION['correo'];
$statement = $pdo->prepare("SELECT * FROM usuario WHERE correo=?");
$statement->execute(array($email));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $id = $row['idUsuario'];
    $nombre = $row['nombreUsuario'];
    $telefono = $row['telefono'];
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
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="../admin/style.css">
    <link rel="icon" type="image/png" href="../img/<?php echo $favicon; ?>">
    <title>Recolector-Dashboard</title>
</head>

<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="../img/<?php echo $logo; ?>">
                </div>
                <div class="close" id="close-btn">
                    <span>
                    Cerrar
                    </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="index.php" class="active">
                    <span >
                        Dashboard
                    </span>
                </a>
                <a href="perfil.php">
                    <span >
                        Perfil
                    </span>
                </a>
                <a href="rutas.php">
                    <span >
                        Ver rutas
                    </span>
                </a>
                <a href="historial.php">
                    <span >
                        Historial
                    </span>
                </a>
                <a href="logout.php">
                    <span >
                        Cerrar sesion
                    </span>
                </a>
            </div>
        </aside>
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <h1>Perfil</h1>
            <!-- New Users Section -->
            <div class="new-users">
                <div class="user-list">
                <form action="" method="post" enctype="multipart/form-data">
                    <img style="width: 130px;" src="../img/usuarios/<?php echo $imgUsu;?>" alt=""><br>
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
                </div>
            </div>
            <!-- End of New Users Section -->
        </main>
        <!-- End of Main Content -->

        <!-- Right Section -->
        <div class="right-section">
            <div class="nav">
                <button id="menu-btn">
                    <span class="material-icons-sharp">
                        Menu
                    </span>
                </button>
                <h2>Tema: </h2>
                <div class="dark-mode">
                    <span class="material-icons-sharp active">
                        .
                    </span>
                    <span class="material-icons-sharp">
                        .
                    </span>
                </div>

                <div class="profile">
                    <div class="info">
                        <p>Binvenido, <b><?php echo $nombre?></b></p>
                    </div>
                    <!-- <div class="profile-photo">                        
                        <img src="../img/usuarios/<?php echo $imgUsu;?>">
                    </div> -->
                </div>

            </div>
            <!-- End of Nav -->

            <div class="user-profile">
                <div class="logo">
                    <center>
                        <img src="../img/usuarios/<?php echo $imgUsu; ?>">
                    </center>
                    <h2><?php echo $nombre;?></h2>
                    <p>Recolector de ReOilTech</p>
                </div>
            </div>
        </div>
    </div>
    <script src="../admin/orders.js"></script>
    <script src="../admin/js/index.js"></script>
    <script>
        var skillPers = document.querySelectorAll(".skill-per");

        skillPers.forEach(function(skillPer) {
        var per = parseFloat(skillPer.getAttribute("per"));
        skillPer.style.width = per + "%";
        
        var animatedValue = 0; 
        var startTime = null;
        
        function animate(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = timestamp - startTime;
            var stepPercentage = progress / 1000; // Dividing by duration in milliseconds (1000ms = 1s)
            
            if (stepPercentage < 1) {
            animatedValue = per * stepPercentage;
            skillPer.setAttribute("per", Math.floor(animatedValue) + "%");
            requestAnimationFrame(animate);
            } else {
            animatedValue = per;
            skillPer.setAttribute("per", Math.floor(animatedValue) + "%");
            }
        }
        
        requestAnimationFrame(animate);
        });

    </script>
</body>

</html>