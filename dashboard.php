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
    $nombre = $row['nombreUsuario'];
    $rol = $row['rol'];
    $imgUsu = $row['fotoUsuario'];
    $id = $row['idUsuario'];
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
    <script>var reCaptchaV3SiteKey = "6LelmdcaAAAAAD8Q48a-SqiHMsdWFObXOhv9NnS1";</script>
    <script src="../www.google.com/recaptcha/api5c65.js?lang=es-419&amp;render=6LelmdcaAAAAAD8Q48a-SqiHMsdWFObXOhv9NnS1"></script>
    <script defer="defer" src="js/recorder.js"></script>
    <script defer="defer" src="js/main.js"></script>
    <script defer="defer" src="js/main2.js"></script>

    <style>
        .skill-main {
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            .skill-wrrap {
                display: flex;
                flex-direction: column;
                gap: 10px;
                .skill-name {
                color: black;
                font-size: 18px;
                font-weight: 500;
                }

                .skill-bar {
                height: 20px;
                background-color: #3A8334;
                border-radius: 8px;
                }

                .skill-per {
                height: 20px;
                background: #4BD8BF;
                border-radius: 8px;
                width: 0;
                transition: 1s linear;
                position: relative;
                &:before {
                    content: attr(per);
                    position: absolute;
                    padding: 4px 6px;
                    background-color: #2C9552;
                    color: #fff;
                    font-size: 11px;
                    border-radius: 4px;
                    top: -35px;
                    right: 0;
                    transform: translateX(50%);
                }

                &:after {
                    content: "";
                    position: absolute;
                    width: 10px;
                    height: 10px;
                    background-color: #2C9552;
                    top: -20px;
                    right: 0;
                    transform: translateX(50%) rotate(45deg);
                    border-radius: 2px;
                }
                }
            }
        }
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
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WHGZM66" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

<section id="home" class="leaves"></section>

<div class="navTrashContainer">
    <img loading="lazy" class="clickMenu icnMenu" src="img/menu.svg" alt="menu">
    <img loading="lazy" class="clickMenu icnClose" src="img/close.svg" alt="menu">
    <nav class="navTrash">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="configuracion.php">Configuración</a></li>
            <li><a href="dashboard.php#obj">Medallas</a></li>
            <li><a href="depositar.php">Depositar/Historial</a></li>
            <li><a href="noticias.php">Noticias</a></li>
            <li><a href="informacion/index.php">Mapa</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </nav>
</div>

<div style="text-aling: center;" class="panel intro introTrash"> 
    <div style="width: 230px; margin-top: 15%" class="logo">
        <img loading="lazy" src="img/usuarios/<?php echo $imgUsu?>" alt="">
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    </div>
    <h3 style="text-align: center; margin-top: 1%">¡¡Bienvenido de nuevo <?php echo $nombre;?> !!</h3>
</div>
<div class="scrollIcn">
    <img loading="lazy" src="img/btn_scrolldown.svg" alt="ScollDown">
</div>
<section class="container panelBanner">
    <div class="secondaryBanner" id="obj">
        <div class="bannerContainer">
            <div class="txtSecondary textureBanner">
                <div class="borderPaper"></div>
                    <div class="cashiBanner">
                        <br><br>
                        <div class="text-left bannerPromo">
                            <?php
                            $statement = $pdo->prepare(
                                "SELECT nombreInsignia, descripcion, icono, meta, puntosUsuario
                                FROM insignias INNER JOIN insignias_has_usuario ON Insignias_idInsignias = idInsignias
                                WHERE Usuario_idUsuario = ?");
                            $statement->execute(array($id));
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach($result as $row) {
                                $meta=$row['meta'];
                                $puntos=$row['puntosUsuario'];
                                $puntos=($puntos*100)/$meta;
                                ?>
                                <h3 style="text-align: center;"><?php echo $row['nombreInsignia'];?></h3>
                                <p style="text-align: center;"><?php echo $row['descripcion'];?></p>
                                <img style="text-align: center;" src="img/<?php echo $row['icono'];?>" alt="">
                                <div class="skill-main">
                                    <div class="skill-wrrap">
                                        <div class="skill-name">Revisa tú progreso...</div><br><br><br>
                                        <div class="skill-bar">
                                        <div class="skill-per" per="<?php echo $puntos;?>"></div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>

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