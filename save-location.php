<?php

    include("inc/config.php");
    include("inc/functions.php");
    include("inc/CSRF_Protect.php");
    $csrf = new CSRF_Protect();

    // comprobar si tenemos los parametros en la URL
    if (isset($_GET["lat"]) && isset($_GET["lng"]) && isset($_GET["cod"])) {
        // asignar w1 y w2 a dos variables
        $latv = $_GET["lat"];
        $lngv = $_GET["lng"];   
        $cod = $_GET["cod"];
    }

    $statement = $pdo->prepare("SELECT idUsuario FROM usuario WHERE correo=?");
    $statement->execute(array($_SESSION['correo']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row) {
        $id = $row['idUsuario'];
    }

    $statement = $pdo->prepare("SELECT * FROM ubicacion WHERE Usuario_idUsuario=?");
    $statement->execute(array($id));
    $total = $statement->rowCount();
    if($total==0) {
        $statement = $pdo->prepare("INSERT INTO ubicacion (
            Usuario_idUsuario,
            latitud,
            longitud,
            codigo_postal
        ) VALUES (?,?,?,?)");
        $statement->execute(array(
            $id,
            $latv,
            $lngv,
            $cod
        ));

        $statement = $pdo->prepare("SELECT * FROM insignias");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $idI = $row['idInsignias'];  
                    
            $statement2 = $pdo2->prepare("INSERT INTO insignias_has_usuario (
            Insignias_idInsignias,
            Usuario_idUsuario,
            puntosUsuario,
            fin
            ) VALUES (?,?,?,?)");
            $statement2->execute(array(
                $idI,
                $id,
                0,
                0
            ));
        }
        
    } else {
        $statement2 = $pdo->prepare("SELECT * FROM ubicacion");
        $statement2->execute();
        $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
        foreach($result2 as $row) {
            $lat = $row['latitud'];
            $lng = $row['longitud'];

            if($lat!=$latv && $lng!=$lngv) {
            } else {
                $statement = $pdo->prepare("INSERT INTO ubicacion (
                    Usuario_idUsuario,
                    latitud,
                    longitud,
                    codigo_postal
                ) VALUES (?,?,?,?)");
                $statement->execute(array(
                    $id,
                    $latv,
                    $lngv,
                    $cod
                ));

                $statement = $pdo->prepare("SELECT * FROM insignias");
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($results as $row) {
                    $idI = $row['idInsignias'];  
                    
                    $statement2 = $pdo2->prepare("INSERT INTO Insignias_has_usuario (
                        Insignias_idInsignias,
                        Usuario_idUsuario,
                        puntosUsuario,
                        fin
                    ) VALUES (?,?,?,?)");
                    $statement2->execute(array(
                        $idI,
                        $id,
                        0,
                        0
                    ));
                }
            }
        }
    }
    header("location: ".BASE_URL."dashboard.php");
?>