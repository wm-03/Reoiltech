<?php
include("../inc/config.php");
include("../inc/functions.php");
include("../inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();

$statement = $pdo->prepare("SELECT * FROM configuracion WHERE idConfiguracion=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
    $favicon = $row['favIcon'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ReOilTech</title>
        <link rel="icon" type="image/png" href="../img/<?php echo $favicon; ?>">
        <!-- <style>
            #floating-panel {
            position: absolute;
            top: 10px;
            left: 25%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: "Roboto", "sans-serif";
            line-height: 30px;
            padding-left: 10px;
            }

            #floating-panel {
            position: absolute;
            top: 5px;
            left: 50%;
            margin-left: -180px;
            width: 350px;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            }

            #latlng {
            width: 225px;
            }
        </style> -->

        <script>
            (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
              key: "AIzaSyAuli5N4nhEuFh1R1Www_EYUK-ar5D46FE",
              map_ids: "fc52ced33562ec84",
            });
        </script>

            <script>
                let map;
                var texto;
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

                var coords2= [
                <?php
                $statement = $pdo->prepare("SELECT * FROM ubicacion WHERE centro=1");
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach($result as $row) {?>
                    [{ lat: <?php echo $row['latitud'];?> , lng: <?php echo $row['longitud'];?> , title: "<?php echo $row['nombreLugar'];?>"}, "<?php echo $row['descripcion']; echo"<br>Este centro reclicla: <br>"; echo $row['recicla'];?>"], 
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
                    // const geocoder = new google.maps.Geocoder();
                    // const infowindow = new google.maps.InfoWindow();

                    // document.getElementById("submit").addEventListener("click", () => {
                    //     geocodeLatLng(geocoder, map, infowindow);
                    // });

                    const infoWindow = new google.maps.InfoWindow();

                    // Create the markers.
                    coords.forEach(([position, title], i) => {
                    console.log(position);
                    const marker = new google.maps.Marker({
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
                    });

                    // Create the markers.
                    coords2.forEach(([position, title], i) => {
                    const marker = new google.maps.Marker({
                        position,
                        map,
                        icon: image2,
                        title: title,
                        label:  "Centro" + `${i + 1}`,
                        optimized: false,
                    });
                    
                    // Add a click listener for each marker, and set up the info window.
                    marker.addListener("click", () => {
                        infoWindow.close();
                        infoWindow.setContent(marker.getTitle());
                        infoWindow.open(marker.getMap(), marker);
                    });
                    });
                }

                // function geocodeLatLng(geocoder, map, infowindow) {
                // const input = document.getElementById("latlng").value;
                // const latlngStr = input.split(",", 2);
                // const latlng = {
                //     lat: parseFloat(latlngStr[0]),
                //     lng: parseFloat(latlngStr[1]),
                // };

                // geocoder
                //     .geocode({ location: latlng })
                //     .then((response) => {
                //     if (response.results[0]) {
                //         map.setZoom(11);

                //         const marker = new google.maps.Marker({
                //         position: latlng,
                //         map: map,
                //         });

                //         infowindow.setContent(response.results[0].formatted_address);
                //         texto = response.results[0].formatted_address;
                //         document.getElementById("texto").value=texto;
                //         infowindow.open(map, marker);
                //     } else {
                //         window.alert("No results found");
                //     }
                //     })
                //     .catch((e) => window.alert("Geocoder failed due to: " + e));
                // }

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
        </style>
    </head>

    <body>
        <!-- <div id="floating-panel">
        <input id="latlng" type="text" value="40.714224,-73.961452" />
        <input id="submit" type="button" value="Reverse Geocode" />
        <input id="texto" type="text" value="40.714224,-73.961452" />
        </div> -->
        <div id="map"></div>
    </body>
</html>