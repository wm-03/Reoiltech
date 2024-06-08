<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ReOilTech</title>

        <script>
            (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
              key: "AIzaSyAuli5N4nhEuFh1R1Www_EYUK-ar5D46FE",
            });
        </script>
        
        <script>
            async function iniciarMapa() {
                const { Map } = await google.maps.importLibrary("maps");
                
                var mapa = new Map(document.getElementById("map"), 
                {
                    zoom: 16,
                    center: { lat: 20.247825, lng: -99.189054 },
                });

                const marcador = new google.maps.Marker({
                position: { lat: 20.247825, lng: -99.189054 },
                map: mapa,
                draggable: true
                });

                marcador.addListener("dragend",function(event){
                    var lat=document.getElementById("latitud").value = this.getPosition().lat();
                    var lng=document.getElementById("longitud").value = this.getPosition().lng();
                    var cod=document.getElementById("codigo").value;
                    if (cod.length!=5) {
                        window.alert("El código postal es inválido");
                    } else{
                    if (window.confirm("¿La ubicación seleccionada es correcta?")) {
                        window.location.href = "save-location.php" + "?lat=" + lat + "&lng=" + lng + "&cod=" + cod;
                    } else {
                        deleteMarkers();
                    }
                    }
                })
            }

            iniciarMapa();
        </script>

        <style>
            #map {
                height: 100%;
            }
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
                text-align: center;
            }
            #floating-panel {
            position: relative;
            top: 1rem;
            z-index: 5;
            background-color: cyan;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: "Roboto", "sans-serif";
            line-height: 30px;
            }
            .cod{
                width: 16rem;
            }
        </style>
    </head>

    <body>
        <div id="floating-panel">
            <p>1- Ingresa tu código postal.</p>
            <p>2- Arrastra el globo rojo a tu ubicación.</p>
            <p>3- Selecciona "sí", si la ubicación es la correcta.</p>
            <p>Si no, mueve nuevamente el globo rojo hasta que sea correcto.</p>
            <label for="codigo">Escribe el código postal de la ubicación</label><br>
            <input class="cod" type="number" placeholder="Ingresa el código postal de la ubicación" name="codigo" id="codigo"/>
            <input type="hidden" id="latitud">
            <input type="hidden" id="longitud">
        </div>
        <div id="map"></div>
    </body>
</html>