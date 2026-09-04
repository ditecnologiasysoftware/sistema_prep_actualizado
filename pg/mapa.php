<?php
    require ("../php/clase_variables.php");
    require ("../php/clase_mysql.php");
    require ("../php/clase_funciones.php");
    date_default_timezone_set('America/Mexico_City');
    
    $funciones = new Funciones();
    //LLAMAMOS A LA CLASE CONEXION
    $entity = Entity::createInstance();

    $id = $funciones->limpia($_GET['id']);
    $latlon = $entity->row("SELECT latitud, longitud FROM tbl_reporte WHERE id_reporte = ".$id);
?>
<html lang="es">
    <head>
        <script src="assets/js/jquery.min.js"></script>
        <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    </head>
    <body onload="marcador_map();" style="margin:0px;">
    <div id="google_canvas" style="width:100%;position:relative; height:100%; margin:0px; padding:0px;"></div>

    <script>
    var mapa;
    
    function marcador_map(){

        var coordenadas = new google.maps.LatLng(<?php echo $latlon['latitud'] ?>, <?php echo $latlon['longitud'] ?>);
        var marcador;
        var opcionesMapa = {
            //draggableCursor:"crosshair",
            //disableDefaultUI: true,
            zoom: 16,
            zoomControl: false,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
            }

        mapa = new google.maps.Map(document.getElementById('google_canvas'),opcionesMapa);

        marcador = new google.maps.Marker({
            map: mapa,
            draggable: false,
            position:coordenadas,
            animation: google.maps.Animation.DROP,
            disableAutoPan: true,
            visible: true

            });

        mapa.setCenter(marcador.position);

        }
                      
    </script> 
    </body>
</html>