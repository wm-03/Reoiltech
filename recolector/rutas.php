<?php
include("../inc/config.php");
include("../inc/functions.php");
include("../inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ReOilTech</title>
        <link href='//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'/>

        <script>
            (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
              key: "AIzaSyAuli5N4nhEuFh1R1Www_EYUK-ar5D46FE",
              map_ids: "fc52ced33562ec84",
            });
        </script>

            <script>

                let map;
                var pos;
                var markers = [];
                var coords=[
                <?php
                $statement = $pdo->prepare("SELECT * FROM ubicacion WHERE centro=0");
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach($result as $row) {?>
                    [{ lat: <?php echo $row['latitud'];?> , lng: <?php echo $row['longitud'];?>}, "<?php echo $row['descripcion'];?>"], 
                <?php
                }
                ?>
                ];

                async function initMap() {
                    //@ts-ignore
                    const { Map } = await google.maps.importLibrary("maps");
        
                    map = new Map(document.getElementById("map"), {
                    center: { lat: 20.247825, lng: -99.189054 },
                    zoom: 16,
                    mapId: "fc52ced33562ec84",
                    });                          

                    const image ="../img/casa.png";
                    const image2 ="../img/centro.png";

                    const infoWindow = new google.maps.InfoWindow();

                    // Create the markers.
                    coords.forEach(([position, title], i) => {
                    var marker = new google.maps.Marker({
                        position,
                        map,
                        icon: image,
                        title: `${i + 1}. ${title}`,
                        label: "Usuario " +`${i + 1}`,
                        optimized: false,
                    });
                    
                    // Add a click listener for each marker, and set up the info window.
                    marker.addListener("click", () => {
                        infoWindow.close();
                        infoWindow.setContent(marker.getTitle());
                        infoWindow.open(marker.getMap(), marker);
                    });
                    markers.push(marker);
                    });

                    // Note: This example requires that you consent to location sharing when
                    // prompted by your browser. If you see the error "The Geolocation service
                    // failed.", it means you probably did not give permission for the browser to
                    // locate you.

                    const locationButton = document.createElement("button");

                    locationButton.textContent = "Obtener ubicación actual";
                    locationButton.classList.add("custom-map-control-button");
                    map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
                    locationButton.addEventListener("click", () => {
                        // Try HTML5 geolocation.
                        if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                            pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };

                            infoWindow.setPosition(pos);
                            infoWindow.setContent("Ubicación encontrada.");
                            infoWindow.open(map);
                            map.setCenter(pos);
                            },
                            () => {
                            handleLocationError(true, infoWindow, map.getCenter());
                            },
                        );
                        } else {
                        // Browser doesn't support Geolocation
                        handleLocationError(false, infoWindow, map.getCenter());
                        }
                    });
                }

                function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                    infoWindow.setPosition(pos);
                    infoWindow.setContent(
                        browserHasGeolocation
                        ? "Error: El servicio de Geolocalización falló."
                        : "Error: Tu navegador no soporta geolocalización.",
                    );
                    infoWindow.open(map);
                }

                function crearRuta1() {
                    var btn;

                    var fin = { 
                    <?php
                    $statement = $pdo->prepare("SELECT DISTINCT U.idUbicacion, U.Usuario_idUsuario, U.latitud, U.longitud
                    FROM ubicacion U
                    INNER JOIN historial H ON U.idUbicacion = H.Ubicacion_idUbicacion
                    WHERE SUBSTRING(U.codigo_postal, 1, 3) IN (
                        SELECT DISTINCT SUBSTRING(codigo_postal, 1, 3)
                        FROM historial
                        WHERE estado = 'pendiente'
                    )
                    AND H.estado = 'pendiente';
                    ");
                    $statement->execute();
                    $total = $statement->rowCount();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach($result as $row) {?>
                        lat: <?php echo $row['latitud'];?>,
                        lng: <?php echo $row['longitud'];?>,
                    <?php
                    }
                    ?>
                    };
                    if (<?php echo $total;?>>=1) {

                        if (pos==null) {
                            window.alert("Primero debes obtener tu ubicación actual");
                        } else {

                            if (<?php echo $total;?> >1) {
                            
                                var objConfigDr ={
                                map: map,
                                suppressMarkers: false
                                }

                                var objConfigDs ={
                                    origin: pos,
                                    destination: fin,
                                    waypoints: [
                                        <?php
                                        $statement = $pdo->prepare("SELECT DISTINCT U.idUbicacion, U.Usuario_idUsuario, U.latitud, U.longitud
                                        FROM ubicacion U
                                        INNER JOIN historial H ON U.idUbicacion = H.Ubicacion_idUbicacion
                                        WHERE SUBSTRING(U.codigo_postal, 1, 3) IN (
                                            SELECT DISTINCT SUBSTRING(codigo_postal, 1, 3)
                                            FROM historial
                                            WHERE estado = 'pendiente'
                                        )
                                        AND H.estado = 'pendiente';
                                        ");
                                        $statement->execute();
                                        $total = $statement->rowCount();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($result as $row) {?>
                                        {
                                        location: "<?php echo $row['latitud'];?>"+", "+"<?php echo $row['longitud'];?>",
                                        stopover: true
                                        },
                                        
                                        <?php
                                        }
                                        ?>
                                    ],
                                    travelMode: google.maps.TravelMode.DRIVING
                                }

                                var ds = new google.maps.DirectionsService();
                                var dr = new google.maps.DirectionsRenderer(
                                    objConfigDr
                                );

                                ds.route(objConfigDs, fnRutear);

                                function fnRutear(resultados, status) {
                                    if (status=='OK'){
                                        dr.setDirections(resultados);
                                    } else {
                                        alert ('ERROR' +status);
                                    }
                                }
                            } else {

                                var objConfigDr ={
                                map: map,
                                suppressMarkers: false
                                }

                                var objConfigDs ={
                                    origin: pos,
                                    destination: fin,
                                    travelMode: google.maps.TravelMode.DRIVING
                                }

                                var ds = new google.maps.DirectionsService();
                                var dr = new google.maps.DirectionsRenderer(
                                    objConfigDr
                                );

                                ds.route(objConfigDs, fnRutear);

                                function fnRutear(resultados, status) {
                                    if (status=='OK'){
                                        dr.setDirections(resultados);
                                    } else {
                                        alert ('ERROR' +status);
                                    }
                                }
                            }
                            crearBtn();
                            eliminarMarcadores();
                        }
                    }
                    else {
                        window.alert("No hay destinos con ordenes pendientes");
                    }
                }

                function crearRuta2() {
                    var fin = { 
                    <?php
                    $statement = $pdo->prepare("SELECT DISTINCT idUbicacion, Usuario_idUsuario, latitud, longitud
                    FROM ubicacion
                    WHERE idUbicacion IN (
                        SELECT ubicacion_idUbicacion
                        FROM historial
                        WHERE estado = 'pendiente'
                    )");
                    $statement->execute();
                    $total = $statement->rowCount();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach($result as $row) {?>
                        lat: <?php echo $row['latitud'];?>,
                        lng: <?php echo $row['longitud'];?>,
                    <?php
                    }
                    ?>
                    };

                    if (<?php echo $total;?>>=1) {

                        if (pos==null) {
                            window.alert("Primero debes obtener tu ubicación actual");
                        } else {

                            if (<?php echo $total;?> >1) {
                            
                                var objConfigDr ={
                                map: map,
                                suppressMarkers: false
                                }

                                var objConfigDs ={
                                    origin: pos,
                                    destination: fin,
                                    waypoints: [
                                        <?php
                                        $statement = $pdo->prepare("SELECT DISTINCT idUbicacion, Usuario_idUsuario, longitud, latitud
                                        FROM ubicacion
                                        WHERE idUbicacion IN (
                                            SELECT ubicacion_idUbicacion
                                            FROM historial
                                            WHERE estado = 'pendiente'
                                        )");
                                        $statement->execute();
                                        $total = $statement->rowCount();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($result as $row) {?>
                                        // waypoints: [
                                        // {
                                        {
                                        location: "<?php echo $row['latitud'];?>"+", "+"<?php echo $row['longitud'];?>",
                                        stopover: true
                                        },
                                        // }],
                                        <?php
                                        }
                                        ?>
                                    ],
                                    travelMode: google.maps.TravelMode.DRIVING
                                }

                                var ds = new google.maps.DirectionsService();
                                var dr = new google.maps.DirectionsRenderer(
                                    objConfigDr
                                );

                                ds.route(objConfigDs, fnRutear);

                                function fnRutear(resultados, status) {
                                    if (status=='OK'){
                                        dr.setDirections(resultados);
                                    } else {
                                        alert ('ERROR' +status);
                                    }
                                }
                            } else {

                                var objConfigDr ={
                                map: map,
                                suppressMarkers: false
                                }

                                var objConfigDs ={
                                    origin: pos,
                                    destination: fin,
                                    travelMode: google.maps.TravelMode.DRIVING
                                }

                                var ds = new google.maps.DirectionsService();
                                var dr = new google.maps.DirectionsRenderer(
                                    objConfigDr
                                );

                                ds.route(objConfigDs, fnRutear);

                                function fnRutear(resultados, status) {
                                    if (status=='OK'){
                                        dr.setDirections(resultados);
                                    } else {
                                        alert ('ERROR' +status);
                                    }
                                }
                            }
                            crearBtn2();
                            eliminarMarcadores();
                        }
                    }
                    else {
                        window.alert("No hay destinos con ordenes pendientes");
                    }
                }

                function crearBtn() {
                    <?php
                        $statement3 = $pdo->prepare("SELECT DISTINCT U.idUbicacion, U.Usuario_idUsuario, U.latitud, U.longitud
                        FROM ubicacion U
                        INNER JOIN historial H ON U.idUbicacion = H.Ubicacion_idUbicacion
                        WHERE SUBSTRING(U.codigo_postal, 1, 3) IN (
                            SELECT DISTINCT SUBSTRING(codigo_postal, 1, 3)
                            FROM historial
                            WHERE estado = 'pendiente'
                        )
                        AND H.estado = 'pendiente';
                        ");
                        $statement3->execute();
                        $t = $statement3->rowCount();
                        $result3 = $statement3->fetchAll(PDO::FETCH_ASSOC);
                        foreach($result3 as $row) {?>
                        var boton = document.createElement("button");
                        boton.innerHTML = "Recolectado en el lugar " + <?php echo $t;?>;
                        boton.setAttribute("id", <?php echo $row['idUbicacion'];?>);
                        boton.addEventListener("click", function() {
                            // Llamar a la función para insertar en la tabla recolector
                            insertarEnRecolector(<?php echo $row['idUbicacion'];?>);
                        });
                        var div = document.getElementById("lista"); 
                        
                        div.appendChild(boton);
                    <?php
                    }
                    ?>
                }

                function crearBtn2() {
                    <?php
                        $statement3 = $pdo->prepare("SELECT DISTINCT idUbicacion, Usuario_idUsuario, latitud, longitud
                        FROM ubicacion
                        WHERE idUbicacion IN (
                            SELECT ubicacion_idUbicacion
                            FROM historial
                            WHERE estado = 'pendiente'
                        )");
                        $statement3->execute();
                        $t = $statement3->rowCount();
                        $result3 = $statement3->fetchAll(PDO::FETCH_ASSOC);
                        foreach($result3 as $row) {?>
                        var boton = document.createElement("button");
                        boton.innerHTML = "Recolectado en el lugar " + <?php echo $t;?>;
                        boton.setAttribute("id", <?php echo $row['idUbicacion'];?>);
                        boton.addEventListener("click", function() {
                            // Llamar a la función para insertar en la tabla recolector
                            insertarEnRecolector(<?php echo $row['idUbicacion'];?>);
                        });
                        var div = document.getElementById("lista"); 
                        div.appendChild(boton);
                    <?php
                    }
                    ?>
                }

                function insertarEnRecolector(idUbicacion) {
                    var i=document.getElementById(idUbicacion);
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "insertar.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // Éxito, se insertó en la tabla recolector
                                window.alert("Insertado en la tabla recolector"+idUbicacion);
                                i.parentNode.removeChild(i);
                            } else {
                                // Error al insertar
                                window.alert("Error al insertar en la tabla recolector"+idUbicacion);
                            }
                        }
                    };
                    xhr.send("idUbicacion=" + idUbicacion);
                }

                function eliminarMarcadores() {
                    markers.forEach(function(marker) {
                        marker.setMap(null);
                    });
                    // Vacía el array de marcadores después de eliminarlos
                    markers = [];
                }
                
                initMap();
            </script>

        <style>

            #map {
                height: 100%;
            }
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            .contact-container * {
                box-sizing: content-box;
            }    
            .contact-container {
                position: fixed;
                right: 0;
                z-index: 99999999;
                bottom: 280px;
                transform: translateX(100%);
                transition: transform 0.4s ease-in-out;
            }
            .contact-container.visible {
                transform: translateX(-10px);
            }
            .contact {    
                background-color: #fff;
                border-radius: 16px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2), 0 16px 20px rgba(0,0,0,0.2);
                border: 5px solid #337AB7;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                font-family: Verdana, sans-serif;  
                position: relative;
                height: 110px;    
                width: 350px;
                max-width: calc(100% - 10px);
            }
            .contact span.close-btn {
                width: 20px;
                height: 20px;
                border: 0;
                cursor: pointer;
                position: absolute;
                top: 5px;
                right: 5px;
            }
            .contact span.close-btn svg {
                stroke: #337AB7;
                width: 20px;
                height: 20px;
            }
            .contact span.close-btn:focus {
                outline: none;
            }
            .contact div {
                background-color: #337AB7;
                border-radius: 0 0 10px 10px;
                color: #fff;
                font-size: 13px;
                font-weight: bold;
                text-transform: uppercase;
                line-height: 18px;
                padding: 2px 0 6px;
                position: absolute;
                top: 0;
                left: 50%;
                margin: 0;
                transform: translateX(-50%);
                text-align: center;
                width: 280px;
            }
            .contact ul {
                display: flex;
                list-style-type: none;
                padding: 0;
                margin: 0;
            }
            .contact ul li {
                margin: 24px 6px 0 6px;
            }
            .contact ul li a {
                border: 4px solid #BFE2FF;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 48px;
                font-size: 24px;
                color: #337AB7;
                width: 48px;
                text-decoration: none;
                transition: all 0.2s ease-in-out;
            }
            .contact ul li a:hover {
                border-color: #337AB7;
                color: #000;
                box-shadow: 0 9px 12px -9px #337AB7;
            }
            .contact-btn {
                border-radius: 30px;
                background-color: #337AB7;
                border: 2px solid #337AB7;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2), 0 16px 20px rgba(0,0,0,0.2);
                color: #fff;
                cursor: pointer;
                padding: 12px 20px;
                position: fixed;
                bottom: 190px;
                right: 20px;
                z-index: 99999999;
                font-size: 15px;
                font-weight: bold;
                line-height: 20px;
                font-family: Verdana, sans-serif;      
                text-transform: uppercase;
                transition: all 0.4s ease-in-out;
            }
            .contact-btn:hover {
                background-color: #ffffff;
                color: #337AB7;
            }
            .contact-btn:focus {
                outline: none;
            }
            @media screen and (max-width: 480px) {
                .contact-container.visible {
                    transform: translateX(0px);
                }
                .contact-btn {
                    right: 10px;
                }
            }

            /* recolectar css */

            .contact-container2 * {
                box-sizing: content-box;
            }    
            .contact-container2 {
                position: fixed;
                right: 0;
                z-index: 99999999;
                bottom: 280px;
                transform: translateX(100%);
                transition: transform 0.4s ease-in-out;
            }
            .contact-container2.visible {
                transform: translateX(-10px);
            }
            .contact2 {    
                background-color: #fff;
                border-radius: 16px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2), 0 16px 20px rgba(0,0,0,0.2);
                border: 5px solid #337AB7;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                font-family: Verdana, sans-serif;  
                position: relative;
                height: 110px;    
                width: 350px;
                max-width: calc(100% - 10px);
            }
            .contact2 span.close-btn2 {
                width: 20px;
                height: 20px;
                border: 0;
                cursor: pointer;
                position: absolute;
                top: 5px;
                right: 5px;
            }
            .contact2 span.close-btn2 svg {
                stroke: #337AB7;
                width: 20px;
                height: 20px;
            }
            .contact2 span.close-btn2:focus {
                outline: none;
            }
            .contact2 div {
                background-color: #337AB7;
                border-radius: 0 0 10px 10px;
                color: #fff;
                font-size: 13px;
                font-weight: bold;
                text-transform: uppercase;
                line-height: 18px;
                padding: 2px 0 6px;
                position: absolute;
                top: 0;
                left: 50%;
                margin: 0;
                transform: translateX(-50%);
                text-align: center;
                width: 280px;
            }
            .contact2 ul {
                display: flex;
                list-style-type: none;
                padding: 0;
                margin: 0;
            }
            .contact2 ul li {
                margin: 24px 6px 0 6px;
            }
            .contact2 ul li a {
                border: 4px solid #BFE2FF;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 48px;
                font-size: 24px;
                color: #337AB7;
                width: 48px;
                text-decoration: none;
                transition: all 0.2s ease-in-out;
            }
            .contact2 ul li a:hover {
                border-color: #337AB7;
                color: #000;
                box-shadow: 0 9px 12px -9px #337AB7;
            }
            .contact-btn2 {
                border-radius: 30px;
                background-color: #337AB7;
                border: 2px solid #337AB7;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2), 0 16px 20px rgba(0,0,0,0.2);
                color: #fff;
                cursor: pointer;
                padding: 12px 20px;
                position: fixed;
                bottom: 252px;
                right: 20px;
                z-index: 99999999;
                font-size: 15px;
                font-weight: bold;
                line-height: 20px;
                font-family: Verdana, sans-serif;      
                text-transform: uppercase;
                transition: all 0.4s ease-in-out;
            }
            .contact-btn2:hover {
                background-color: #ffffff;
                color: #337AB7;
            }
            .contact-btn2:focus {
                outline: none;
            }
            @media screen and (max-width: 480px) {
                .contact-container2.visible {
                    transform: translateX(0px);
                }
                .contact-btn2 {
                    right: 10px;
                }
            }
        </style>
    </head>

    <body>

         <div class="contact-container2">
            <div class="contact2">
                <div>RECOLECTAR</div>
                <span class="close-btn2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>
                <ul>
                    <li id="lista">
                    </li>
                </ul>
            </div>
        </div>
        <button class="contact-btn2">RECOLECTAR</button>


        <div class="contact-container">
            <div class="contact">
                <div>CREAR RUTA</div>
                <span class="close-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>
                <ul>
                    <li>
                        <i class="fa fa-phone"></i>
                        <button onclick="crearRuta2();">Crear ruta con cualquier marcador</button>
                    </li>
                    <li>
                        <i class="fa fa-phone"></i>
                        <button onclick="crearRuta1();">Crear ruta con marcadores cercanos</button>
                    </li>
                </ul>
            </div>
        </div>
        <button class="contact-btn">CREAR RUTAS</button>
        <div id="map"></div>
        <script>
            const contact_btn = document.querySelector('.contact-btn');
            const close_btn = document.querySelector('.close-btn');
            const contact_container = document.querySelector('.contact-container');
            contact_btn.addEventListener('click', () => {
                contact_container.classList.toggle('visible')
            });
            close_btn.addEventListener('click', () => {
                contact_container.classList.remove('visible')
            });
        </script>

        <script>
            const contact_btn2 = document.querySelector('.contact-btn2');
            const close_btn2 = document.querySelector('.close-btn2');
            const contact_container2 = document.querySelector('.contact-container2');
            contact_btn2.addEventListener('click', () => {
                contact_container2.classList.toggle('visible')
            });
            close_btn2.addEventListener('click', () => {
                contact_container2.classList.remove('visible')
            });
        </script>
    </body>
</html>