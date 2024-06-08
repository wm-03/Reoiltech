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
?>

<?php
$statement = $pdo->prepare("SELECT * FROM configuracion WHERE idConfiguracion=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $vistas = $row['visitas'];
    $favicon = $row['favIcon'];
    $fondo = $row['fondoPag'];
    $fondo1 = $row['fondo1'];
    $fondo2 = $row['fondo2'];
    $fondo3 = $row['fondo3'];
    $fondo4 = $row['fondo4'];
}
$vistas++;
$statement = $pdo->prepare("UPDATE configuracion SET visitas=? WHERE idConfiguracion=1");
$statement->execute(array($vistas));  
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
    <style>
            body 
            {
                background-image: url('img/<?php echo $fondo;?>');
                background-size: cover;
                background-attachment: fixed;
                text-align: center;
                padding: 0px;
            }
            
            .panel.intro.introTrash {
                background-color: black;
                background-image: url('img/gifs/fondo.gif');
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center;
                background-attachment: fixed;
                text-align: center;
                padding: 10rem;
            }
            .navTrash ul li a{
                    font-size: 1rem;
            }
            
            .imgB{
                height:25rem ;
            }
            
            @media (min-width: 768px) {
                
            }
            
            @media (min-width: 576px) {
                .container.panelBanner {
                    padding: 1rem;
                }
                .imgB{
                    height: 30rem;
                }
                .navTrash ul li a{
                    font-size: 2rem;
                }
                .navTrash{
                    width: 400px;
                }
                .clickMenu.icnMenu{
                    margin-top: 20px;
                }
                .clickMenu.icnClose{
                    margin-top: 20px;
                }
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
            <?php if(isset($_SESSION['correo'])) { ?>
                <br><br><br><li><a href="dashboard.php">Dashboard</a></li><br>
            <?php
            } else {?>
                <li><a href="login.php">Login/Registrarse</a></li><br>
            <?php
            }?>
            <li><a href="index.php#obj">Objetivo</a></li><br>
            <li><a href="noticias.php">Noticias</a></li><br>
            <li><a href="index.php#centrosdeacopio">Centros de acopio</a></li><br>
        </ul>
    </nav>
</div>

<div class="panel intro introTrash">

    <div class="logo">
        <!-- <a href="index.php"><img loading="lazy" src="img/home.png" alt=""></a> -->
        <!-- <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br> -->
    </div>
</div>
<div class="scrollIcn">
    <img loading="lazy" src="img/btn_scrolldown.svg" alt="ScollDown">
</div>
<div class="traductorIcn">
    <a><img src="img/icon.png"></a>
    <br><br><br>
</div>
<section class="container panelBanner">
    <div class="secondaryBanner" id="obj">
        <div class="bannerContainer">
            <div class="txtSecondary textureBanner">
                <div class="borderPaper"></div>
                <div class="cashiBanner">
                    <div>
                        <img class="imgB" loading="lazy" src="img/img1.jpg" alt="Cashi">
                    </div>
                    <div class="text-left bannerPromo" style="text-align: justify;">
                        <p class="parr">
                            Proyecto desarrollado por estudiantes de <span class="primary GreenhighlightL">ITSOEH®</span>.
                            Con la finalidad de desarrollar motivar y facilitar a los usuarios la participación activa en la recolección 
                            y reciclaje responsable de <span class="primary GreenhighlightL">aceite usado</span>. Esta aplicación busca no solo <span class="primary GreenhighlightL">educar</span> sobre los beneficios 
                            ambientales del reciclaje de aceite, sino también proporcionar herramientas<span class="primary GreenhighlightL"> para lograr los objetivos</span>.
                        </p>
                        <a href="" target="_blank">Conoce más</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- START SABIAS QUE -->
<section class="intro panele" id="knowledgeSection">
    <div class="scroll1"></div>
    <div class="container-panel-bg"></div>
    <div class="noBlur">
        <section class="knowledge" id="dato">
            
            <div class="knowledgeContainer">
                <div class="circleKnowledge">
                    <img alt="circulo" loading="lazy" src="img/circle.png">
                </div>
                <div class="knowledgeTxt">
                    <div class="sabias" style="color: black;">
                        ¿Sabías que?
                    </div>
                    <div class="txtKnow" style="color: black;">
                        Una sola gota de aceite de cocina puede contaminar
                    </div>
                    <div>
                        <span class="highlight bigTxtKnow">hasta 25 litros de agua</span>
                    </div>
                    <div class="txtKnow Greenhighlight">
                        lo que equivale a aproximadamente a <span class="Greenhighlight">cinco galones.</span>
                    </div>
                </div>
                <div class="knowledgeImg">
                    <img loading="lazy" class="sembrar" src="img/neutraliza.svg" alt="Envase Nestlé®">
                </div>
            </div>
        </section>
    </div>
</section>

<section class="trashlater mt-3 container">
    <div id="centrosdeacopio"></div>
    <section class="centrosAcopio p-0">
        <div class="container">
            <div class="row">
                <div class="col-12 h-100">
                    <h2>Centros de acopio</h2>
                    <div class="ctaCentro">
                        <p><strong class="knowTxt">¿Sabías que?</strong></p>
                        <p class="knowTxtNormal"><strong>Cerca de tí</strong> hay un centro de</p>
                        <p class="knowTxtRE"><strong>RECICLAJE</strong></p>
                        <a href="informacion/index.php" target="_blank">Buscar</a>
                    </div>

                    <img loading="lazy" class="mapCenter" src="img/mapa.png" alt="Mapa de la república Mexicana">
                    <!-- <section class="text-center mt-1 recaptcha">
                        Este sitio está protegido por reCAPTCHA y se aplica la
                        <a href="https://policies.google.com/privacy" target="_blank">Política de privacidad</a> y los
                        <a href="https://policies.google.com/terms" target="_blank">Términos de servicio</a> de Google.
                    </section> -->
                </div>
            </div>
        </div>
    </section>
</section>
<!--End Result -->
  
    <!-- <footer>
        <a href="informacion/terminos-y-condiciones.html">Términos y Condiciones</a> / <a href="informacion/aviso-de-contenido.html">Aviso de Contenido.</a>
    </footer> -->
</body>
</html>