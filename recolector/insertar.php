<?php
include("../inc/config.php");
include("../inc/functions.php");
include("../inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();

$id = $_POST['idUbicacion'];

$email=$_SESSION['correo'];
$statement = $pdo->prepare("SELECT * FROM usuario WHERE correo=?");
$statement->execute(array($email));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $idUsu = $row['idUsuario'];
}

$statement = $pdo->prepare("SELECT idHistorial FROM historial WHERE Ubicacion_idUbicacion=? AND estado='pendiente'");
$statement->execute(array($id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $idH = $row['idHistorial'];
    $statement2 = $pdo->prepare("INSERT INTO recolector (Recolector_idUsuario,Historial_idHistorial) VALUES (?,?)");
    $statement2->execute(array($idUsu, $idH));

    $statement3 = $pdo->prepare("UPDATE historial SET estado=? WHERE Ubicacion_idUbicacion=? AND estado='pendiente'");
    $statement3->execute(array("finalizado", $id));
}
?>