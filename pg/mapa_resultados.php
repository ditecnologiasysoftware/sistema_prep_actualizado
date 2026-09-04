<?php
    @session_start();
    $id_usuario = $_SESSION['id_usuario'];
    $id_estado = $_SESSION['id_estado'];
    $id_municipio = $_SESSION['id_municipio'];
    $autentificado = $_SESSION['autentificado'];
    $eliminar = $_SESSION['eliminar'];
    $editar = $_SESSION['editar'];
    $nombre = $_SESSION['nombre'];
    $id_sesion_sistema = $_SESSION['id_sesion_sistema'];
    $autoriza_apoyo = $_SESSION['autoriza_apoyo'];
    $autoriza_ordenpago = $_SESSION['autoriza_ordenpago'];

    require ("../php/clase_variables.php");
    require ("../php/clase_mysql.php");
    require ("../php/clase_funciones.php");
    include_once ('../php/clase_paginador.php');
    date_default_timezone_set('America/Mexico_City');
    
    $funciones = new Funciones();
    //LLAMAMOS A LA CLASE CONEXION
    $entity = Entity::createInstance();    
 
   
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Sistema administrador</title>

        <link href="../assets/css/style.default.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <script src="../assets/js/jquery-1.11.1.min.js"></script>
        <?php if (!empty($_ENV['GOOGLE_MAPS_API_KEY'])): ?>
            <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo rawurlencode($_ENV['GOOGLE_MAPS_API_KEY']); ?>" defer></script>
        <?php endif; ?>

        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/jquery-ui-1.10.3.min.js"></script>        
    </head>

    <body>
         <!--  ARRIBA----- -->
        <div class="pageheader" style="height: 47px;">
            <div class="media">
                <div class="media-body">
                    <h4 style="color: #2256AE">MAPA DE RESULTADOS</h4>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->                    
        <!--FIN ARRIBA----- -->
        <div class="contentpanel">
            <!-- CONTENIDO ---- -->
             <div class="row">                            
                <div id="google-map" style="width: 100%; height: 405px; position:relative;"></div>                             
            </div><!-- row -->                                    
          <!--FIN DE CONTENIDO------>                        
        </div><!-- contentpanel -->
        <?php 
            if(isset($_GET['c'])){
                  $idcandidatoP = $funciones->limpia(base64_decode($_GET['cand']));
                  $idpelectoral = $funciones->limpia(base64_decode($_GET['c']));
                  $tipoelec = $funciones->limpia(base64_decode($_GET['tipoelec']));
                  $tipmap = $funciones->limpia($_GET['tipmap']);
                  $latLon = $funciones->limpia($_GET['latLon']);

                  $cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$idpelectoral." and idt_eleccion_c =".$tipoelec." GROUP BY id_casilla";
                  $cadenaResultado = $entity->objects($cadena);
             }
        ?>
        <script type="text/javascript" language="javascript">

        var locations = [<?php

            $cadena = "";
            $img = "";
            $tipo="";
            foreach ($cadenaResultado as $value){
                    $idCasilla =    $value->id_casilla;
                    $numCasilla = $funciones->llenarCasillatbl($value->id_casilla);
                    $municipio  = $entity->scalar("SELECT nombre FROM tblc_municipio WHERE id_municipio =".$value->id_municipio);
                    $latitud = $value->latitud;
                    $longitud   = $value->longitud;
                    $direccion  = $value->direccion;
                    $seccion  = $value->seccion;
                    $tipo_eleccion  = $value->idt_eleccion_c;

                    switch ($value->tipo) {
                        case 1:
                            $tipo="Basica";
                        break;
                        case 2:
                            $tipo="Contigua";
                        break;
                        case 3:
                            $tipo="Extraordinaria";
                        break;               
                    }
                    $ganador = $entity->row("SELECT vw_resultado_elecciones.* FROM vw_resultado_elecciones WHERE id_casilla = ".$value->id_casilla." and idt_eleccion_c =".$value->idt_eleccion_c." ORDER BY resultado DESC LIMIT 1");
                    $votos_ganador = $ganador['resultado'];
                     if($votos_ganador != 0){ $img = $ganador['icono_pa']; }else{ $img = 'marker.png'; }
                    
                    $nombre_ganador = $ganador['nombre_c'];
                    $color_ganador = $ganador['color_pa'];
                     switch ($ganador['tipo']) {
                        case 1:
                            $tipo_ganador="Basica";
                        break;
                        case 2:
                            $tipo_ganador="Contigua";
                        break;
                        case 3:
                            $tipo_ganador="Extraordinaria";
                        break;               
                    }

                       $infoPOP = $funciones->ganadoresEleccionResultado($idCasilla,$tipoelec);
                    
                    //****************************************************************************
                    //*****************************************************************************
                
                 $cadena .= "[".$latitud.", ".$longitud.", '".$idCasilla."', '".$numCasilla."', '".$municipio."' ,'".$direccion."', '".$seccion."', '".$tipo."', '".$img."', '".$nombre_ganador."', '".$color_ganador."', '".$tipo_ganador."', '".$votos_ganador."', '".$tipo_eleccion."', '".$infoPOP."'],
                ";
            }       
                  echo trim($cadena, ",");
                ?>];
                <?php 
                if ($tipmap == 1) { $cerca = '9'; }else{ $cerca = '13'; }                

                ?>
                var map = new google.maps.Map(document.getElementById('google-map'),{
                  zoom: <?= $cerca ?>,
                  center: new google.maps.LatLng(<?= $_GET['latLon'] ?>),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                //map.controls[google.maps.ControlPosition.TOP_RIGHT].push(new FullScreenControl(map));
                var infowindow = new google.maps.InfoWindow();
                var marker, i;
                var bounds = new google.maps.LatLngBounds();
                for (i = 0; i < locations.length; i++){
               // var image = new google.maps.MarkerImage("../archivos/market.png",
                var image = new google.maps.MarkerImage("../archivos/partido_politico/"+locations[i][8], 
                        new google.maps.Size(30, 30),
                        new google.maps.Point(0,0),
                        new google.maps.Point(1, 40));
                  marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                    icon: image,
                    map: map
                  });
                  google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                    // verResultados(locations[i][2],locations[i][13]);
                   var contenido = '<div id="content">'+
                                      '<div id="siteNotice">'+
                                      '</div>'+
                                      '<h3 style="color:'+locations[i][10]+'"><img src="../archivos/partido_politico/'+locations[i][8]+'"> '+locations[i][9]+'</h3>'+
                                        '<b>INFO CASILLA: <font style="color:#4272A2">'+locations[i][3]+'</font></b>&nbsp;&nbsp;&nbsp;'+
                                        // '<b>TIPO: <font style="color:#4272A2">'+locations[i][11]+'</font></b>&nbsp;&nbsp;&nbsp;'+
                                        '<b>TOTAL DE VOTOS: <font style="color:#4272A2">'+locations[i][12]+'</font></b><br>'+
                                         '<b>DIRECCIÓN: <font style="color:#4272A2">'+locations[i][5]+'</font></b><br>'+
                                          '<div id="tbl_resultado'+locations[i][2]+'">'+locations[i][14]+                                      
                                          '</div>'+
                                        '</div>';
                      infowindow.setContent(contenido);
                      infowindow.open(map, marker);
                    }
                  })(marker, i));
                }

    // function verResultados(id,tipoe){
    //   var enlace = "tbl_resultados.php";
    //     $.ajax({
    //         type: "POST",
    //         url: enlace,
    //         data: "&idcasilla=" + id + "&tipoe=" + tipoe,
    //         beforeSend: function() {
    //             $("#tbl_resultado"+id).html('<center><img src="assets/css/assets/images/ajax-loader.gif" ><br>Enviando su solicitud, espere por favor...</center>');
    //         },
    //         success: function(datos) {
    //             $("#tbl_resultado"+id).html(datos);
    //         },
    //         error: function(result) {
    //           $("#tbl_resultado"+id).html("");
    //                alert("Hay problemas intente de nuevo más tarde");
    //         }
    //    });
    //     return false;
    // }
        </script>
    </body>
</html>
