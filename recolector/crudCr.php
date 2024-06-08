<?php
include("../inc/config.php");
include("../inc/functions.php");
include("../inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();

$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
$email = $_SESSION['correo'];

switch ($opcion) {
    case 1:
        $statement = $pdo->prepare("SELECT idUsuario FROM usuario WHERE correo=?");
        $statement->execute(array($email));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array(); // Array para almacenar todos los resultados
        foreach ($result as $row) {
            $id = $row['idUsuario'];

            $statement = $pdo->prepare("SELECT Historial_idHistorial FROM recolector WHERE Recolector_idUsuario=?");
            $statement->execute(array($id));
            $results_recolector = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results_recolector as $row_recolector) {
                $idH = $row_recolector['Historial_idHistorial'];

                $statement = $pdo->prepare("SELECT h.cantidadAceite, h.fecha, u.nombreUsuario
                FROM historial h
                JOIN recolector r ON h.idHistorial = r.Historial_idHistorial
                JOIN ubicacion u1 ON h.Ubicacion_idUbicacion = u1.idUbicacion
                JOIN usuario u ON u1.Usuario_idUsuario = u.idUsuario
                WHERE h.idHistorial = ?");
                $statement->execute(array($idH));
                $data[] = $statement->fetch(PDO::FETCH_ASSOC); // Usar fetch para obtener solo un registro
            }
        }

        $email = "";
        break;
}

header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE); // Enviar los datos como JSON al frontend

?>