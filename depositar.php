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
    <!-- <script defer="defer" src="../cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js" integrity="sha512-7rusk8kGPFynZWu26OKbTeI+QPoYchtxsmPeBqkHIEXJxeun4yJ4ISYe7C6sz9wdxeE1Gk3VxsIWgCZTc+vX3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <!-- <script defer="defer" src="../cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="admin/assets/bootstrap/css/bootstrap.min.css">
    <!-- CSS personalizado --> 
    <link rel="stylesheet" href="admin/assets/main.css">  
      
      
    <!--datables CSS básico-->
    <link rel="stylesheet" type="text/css" href="admin/assets/datatables/datatables.min.css"/>
    <!--datables estilo bootstrap 4 CSS-->  
    <link rel="stylesheet"  type="text/css" href="admin/assets/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css">    
      
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="blocked">

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

    <section>
        <center>
        <br><br><h1>Entregas</h1><br>
        </center>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">            
                <button id="btnNuevo" type="button" class="btn btn-info" data-toggle="modal"><i class="material-icons">library_add</i></button>    
                </div>    
            </div>    
        </div>    
        <br>  

        <div class="container caja">
            <div class="row">
                <div class="col-lg-12">
                <div class="table-responsive">        
                    <table id="tablaUsuarios" class="table table-striped table-bordered table-condensed" style="width:100%" >
                        <thead class="text-center">
                            <tr>
                                <th>id</th>
                                <th>Cantidad</th>                                
                                <th>Fecha</th>    
                                <th>Estado de la entrega</th>                                                            
                            </tr>
                        </thead>
                        <tbody>                           
                        </tbody>        
                    </table>               
                </div>
                </div>
            </div>  
        </div>   

        <!--Modal para CRUD-->
        <div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form id="formUsuarios" method="post">    
                    <div class="modal-body">
                        <div class="col-lg-6">
                        <div class="form-group">
                        <label for="" class="col-form-label"><b>*Escríbe la cantidad en mili litros*</b></label>
                        <input type="number" class="form-control" id="cantidad">
                        </div> 
                        </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </div>
                </form>    
                </div>
            </div>
        </div>
    </section>
    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="admin/assets/jquery/jquery-3.3.1.min.js"></script>
    <script src="admin/assets/popper/popper.min.js"></script>
    <script src="admin/assets/bootstrap/js/bootstrap.min.js"></script>
      
    <!-- datatables JS -->
    <script type="text/javascript" src="admin/assets/datatables/datatables.min.js"></script>    
     
    <script type="text/javascript" src="mainEnt.js"></script>  
</body>
</html>