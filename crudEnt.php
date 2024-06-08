<?php
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$cantidad = (isset($_POST['cantidad'])) ? $_POST['cantidad'] : '';

$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
$email=$_SESSION['correo'];


switch($opcion){
    case 1:
        
        $statement = $pdo->prepare("SELECT * FROM usuario WHERE correo=?");
        $statement->execute(array($email));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
            $id = $row['idUsuario'];
        }

        $fecha= date("Y-m-d H:i:s");
        $statement = $pdo->prepare("SELECT * FROM ubicacion WHERE Usuario_idUsuario=?");
        $statement->execute(array($id));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
            $idUbi = $row['idUbicacion'];
        }
        // saving into the database
        $statement = $pdo->prepare("INSERT INTO historial (
                                        Ubicacion_idUbicacion,
                                        cantidadAceite,
                                        fecha
                                    ) VALUES (?,?,?)");
        $statement->execute(array(
                                        $idUbi,
                                        $cantidad,
                                        $fecha
                                    ));

        $statement = $pdo->prepare("SELECT * FROM insignias_has_usuario WHERE Usuario_idUsuario=? AND fin=0");
        $statement->execute(array($id));
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $puntos = $row['puntosUsuario'];
            
            // Obtener la meta correspondiente a la insignia
            $statementMeta = $pdo->prepare("SELECT * FROM insignias WHERE idInsignia = ?");
            $statementMeta->execute(array($row['Insignias_idInsignia']));
            $metaResult = $statementMeta->fetch(PDO::FETCH_ASSOC);
            $meta = $metaResult['meta'];
            
            $puntos += $_POST['cantidad'];

            if ($puntos >= $meta) {
                $statementUpdate = $pdo->prepare("UPDATE insignias_has_usuario SET fin=? WHERE Usuario_idUsuario=? AND fin=0 AND Insignias_idInsignia=?");
                $statementUpdate->execute(array("1", $id, $row['Insignias_idInsignia']));
            } else {
                $statementUpdate = $pdo->prepare("UPDATE insignias_has_usuario SET puntosUsuario=? WHERE fin=0 AND Usuario_idUsuario=? AND Insignias_idInsignia=?");
                $statementUpdate->execute(array($puntos, $id, $row['Insignias_idInsignia']));
            }
        }

        $email="";
        break;    
    
    case 2:   

        $statement = $pdo->prepare("SELECT idUsuario FROM usuario WHERE correo=?");
        $statement->execute(array($email));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
            $id = $row['idUsuario'];
        }

        $statement = $pdo->prepare("SELECT idUbicacion FROM ubicacion WHERE Usuario_idUsuario=?");
        $statement->execute(array($id));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
            $idUbi = $row['idUbicacion'];
        }

        $statement = $pdo->prepare("SELECT * FROM historial WHERE Ubicacion_idUbicacion=?");
        $statement->execute(array($idUbi));
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        $email="";
        break;
}

print json_encode($data, JSON_UNESCAPED_UNICODE);//envio el array final el formato json a AJAX