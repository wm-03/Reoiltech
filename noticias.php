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
    $vistas = $row['visitas'];
    $favicon = $row['favIcon'];
    $fondo = $row['fondoPag'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sección de Noticias</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="css/styles2.css">
        <link rel="stylesheet" href="css/dots/three-dots.min.css" />
        <link rel="icon" type="image/png" href="img/<?php echo $favicon; ?>">


        <script defer="defer" src='cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js' integrity='sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==' crossorigin='anonymous'></script>
        <script defer="defer" src="cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js" integrity="sha512-7rusk8kGPFynZWu26OKbTeI+QPoYchtxsmPeBqkHIEXJxeun4yJ4ISYe7C6sz9wdxeE1Gk3VxsIWgCZTc+vX3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script defer="defer" src="cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script defer="defer" src='cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js' integrity='sha512-f8mwTB+Bs8a5c46DEm7HQLcJuHMBaH/UFlcgyetMqqkvTcYg4g5VXsYR71b3qC82lZytjNYvBj2pf0VekA9/FQ==' crossorigin='anonymous'></script>
        <script defer="defer" src='cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js' integrity='sha512-A64Nik4Ql7/W/PJk2RNOmVyC/Chobn5TY08CiKEX50Sdw+33WTOpPJ/63bfWPl0hxiRv1trPs5prKO8CpA7VNQ==' crossorigin='anonymous'></script>
        <script defer="defer" src='cdnjs.cloudflare.com/ajax/libs/Swiper/9.2.1/swiper-bundle.js' integrity='sha512-hgzDTjAr3KToNCNN8ffTBol8aWBv6Oga3XbgPR8Qow3RzH7pFLYHDjDRU2+AD5gRjtdw5GvW+Kjwi5BNt66ufg==' crossorigin='anonymous'></script>
        <script defer="defer" src="js/main.js"></script>
        <script defer="defer" src="js/main2.js"></script>


        <style>
            .gallery {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-around;
              
            }
            .gallery-item.vertical {
                flex: 0 0 calc(33.33% - 10px);
                overflow: hidden;
                text-align: center;
                position: relative;
            }

            .gallery-item.horizontal {
                flex: 0 0 calc(33.33% - 10px); 
               
                overflow: hidden;
                text-align: center;
                position: relative;
            }
            .gallery-item.double-vertical {
                flex: 0 0 calc(33.33% - 10px);
                
                overflow: hidden;
                text-align: center;
                position: relative;
            }

            .gallery-item.double-horizontal {
                flex: 0 0 calc(33.33% - 10px); 
                margin-top: 10px;
                overflow: hidden;
                text-align: center;
                position: relative;
            }

            .gallery-item.vertical img {
                width: 100%;
                max-height: 350px;
            }
            .gallery-item.double-vertical img {
                width: 100%;
                max-height: 515px;
            }

            
            .gallery-item.horizontal img {
                width: 100%; 
                max-height: 170px;
            }

            .gallery-item.double-horizontal img {
                width: 100%; 
                max-height: 255px; 
            }
           /*AJUSTA LA IMAGEN DE FONDO*/
            body {
                background-image: url('img/<?php echo $fondo;?>');
                background-size: cover;
                background-attachment: fixed;
                text-align: center;
                padding: 0px;
            }

            
            .image-container:hover .overlay {
                display: block;
            }

            .image-container {
                position: relative;
            }

            
            .overlay {
                position: absolute;
                top: 50%; 
                left: 50%;
                transform: translate(-50%, -50%);
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                max-width: 440px; 
                max-height: 230px;
                display: none;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding-top: 1px;
                color: white;
            }


           
            .overlay h2 {
                font-size: 18px;
                color: white;
                text-align: center;
            }

           
            .overlay button {
                background-color: lightsteelblue;
                color: black;
                padding: 5px 10px;
                border: none;
                cursor: pointer;
            }
            @media screen and (max-width: 550px) {
                .gallery-item.vertical,
                .gallery-item.horizontal,
                .gallery-item.double-vertical,
                .gallery-item.double-horizontal {
                    flex: 0 0 calc(50% - 10px);
                    max-width: 100%; 
               
                }
            }
            .notificationContainer {
                position: relative;
                display: inline-block;
                margin-left: 10px; /* Ajusta según sea necesario */
            }

            .notificationIcon {
                width: 50px; /* Ajusta según sea necesario */
                cursor: pointer;
            }

            .notificationDropdown {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                min-width: 30rem;
                box-shadow: 0 8px 16px rgba(125, 121, 123, 0.8);
                z-index: 999;
                overflow-y: auto; /* Agrega un scrollbar vertical si es necesario */
                max-height: 200px; /* Define una altura máxima para el contenedor y agrega un scrollbar si se excede */
            }

            .notificationDropdown p {
                padding: 12px;
                margin: 0;
                border-bottom: 1px solid #ddd;
            }

            .notificationContainer:hover .notificationDropdown {
                display: block;
            }

            </style>
        </head>

        <body>
        <section id="home" class="leaves"></section>

        <div class="navTrashContainer">
            <img loading="lazy" class="clickMenu icnMenu" src="img/menu.svg" alt="menu">
            <img loading="lazy" class="clickMenu icnClose" src="img/close.svg" alt="menu">
            <nav class="navTrash">
                <div class="notificationContainer">
                    <img loading="lazy" class="notificationIcon" src="https://play-lh.googleusercontent.com/ttddO56u2ISI0hdeC-jWi-y3VNTeytT9eDSZq_JB7W51ZtOzkImywh1vhzZP0Loftw" alt="notification">
                </div>
                <ul>
                    <?php if(isset($_SESSION['correo'])) { ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                    <?php
                    } else {?>
                        <li><a href="login.php">Login/Registrarse</a></li> 
                    <?php
                    }?>
                    <li><a href="index.php#obj">Objetivo</a></li>
                    <li><a href="noticias.php">Noticias</a></li>
                    <li><a href="index.php#centrosdeacopio">Centros de acopio</a></li>
                    <!-- <li><a href="">FAQ’s</a></li> -->
                    <span>Compartir:</span>
                    <span class="d-block">
                        <li class="d-inline">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=https://reoiltech.com/" target="_blank"><img alt="facebook" src="img/facebook.svg"></a>
                        </li>
                        <li class="d-inline">
                            <a href="https://twitter.com/intent/tweet?text=https://reoiltech.com/" target="_blank"><img alt="twitter" src="img/twitter.svg"></a>
                        </li>
                    </span>
                </ul>
            </nav>
        </div>

        <center>
            <br><br>
            <h1 style="font-size: 50px;">Noticias</h1>
        </center>
        <div class="gallery">

            <?php
            $i=0;
            $statement = $pdo->prepare("SELECT * FROM noticia WHERE tipo=1");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach($result as $row) {
                $i++;
                ?>
                <div class="gallery-item double-vertical">
                    <div class="image-container">
                    <img src="img/<?php echo $row['foto'];?>" alt="Noticia <?php echo $i;?>">
                        <div class="overlay">
                            <h2><?php echo $row['titulo'];?></h2>
                            <font face="Calibri (Cuerpo)">
                                <?php echo $row['contenido'];?>
                            </font>
                                <a href="<?php echo $row['enlace'];?>" target="_blank">
                                <br><br>
                                <button>Ver noticia</button>
                            </a>
                        </div>
                    </div>
                </div>

            <?php
            }
            ?>
        </div>
        </section>
    </body>
</html>