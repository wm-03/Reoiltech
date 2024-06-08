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
$total=0;
$statement = $pdo->prepare("SELECT cantidadAceite FROM historial");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $totallitros = $row['cantidadAceite'];
    $total += $totallitros;
}
$email=$_SESSION['correo'];
$statement = $pdo->prepare("SELECT * FROM usuario WHERE correo=?");
$statement->execute(array($email));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $nombre = $row['nombreUsuario'];
    $rol = $row['rol'];
    $imgUsu = $row['fotoUsuario'];
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

    <style>
        .containeres {
            width: 35rem;
            height: 23rem;
            overflow: hidden;
            position: relative;
            margin: 50px auto;
        }

        .barcontaineres {
            background-color: #181818;
            position: relative;
            transform: translateY(-50%);
            top: 50%;
            margin-left: 6rem;
            width: 50px;
            height: 320px;
            float: left;
        }

        .bar {
            background-color: #9BC9C7;
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 80%;
            box-sizing: border-box;
            animation: grow 1.5s ease-out forwards;
            transform-origin: bottom;
        }

        @keyframes grow {
            from {
                transform: scaleY(0);
            }
        }

        .user {
            margin-bottom: 20px;
        }
    </style>

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
                <a href="">
                    <span >
                        .
                    </span>
                </a>
            </div>
        </aside>
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <h1>Dashboard</h1>
            <!-- Analyses -->
            <div class="analyse">
                <div class="sales">
                    <div class="status">
                        <div class="info">
                            <h3>Total de recolecciones</h3>
                            <h1><?php echo "2"; ?></h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>+ 81%</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="visits">
                    <div class="status">
                        <div class="info">
                            <p style="margin-left: 10px; font-size: 0.87rem;">Litros recolectados</p>
                            <h1><?php echo "10";?></h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="40" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>+ 234%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Analyses -->

            <!-- New Users Section -->
            <div class="new-users">
                <h2>Estadísticas</h2>
                <div class="user-list">
                    
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM usuario WHERE estado=1");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                    foreach ($result as $row) {
                
                    }
                    ?>
                <div class="user">
                    <div class="containeres1">
                        <h3>Litros por semana</h3>
                        <div class="barcontaineres">
                            <div class="bar" style="height:10%"></div>
                        </div>
                        <h3>Eficiencia</h3>
                        <div class="barcontaineres">
                            <div class="bar" style="height:70%"></div>
                        </div>
                        <h3>Horas por semana</h3>
                        <div class="barcontaineres">
                            <div class="bar" style="height:30%"></div>
                        </div>
                    </div>
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