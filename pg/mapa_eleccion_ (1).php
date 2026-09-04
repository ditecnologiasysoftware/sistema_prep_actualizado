            <?php               
                $pagina = isset($_GET['pag']) ? $funciones->limpia($_GET['pag']) : 1;
                $limite = 10;
                $cantenlaces = 7;
                $inicio = ($pagina - 1) * $limite;                
             ?>     
               
                   <div class="pageheader">
                        <div class="media">
                            <div class="pageicon pull-left">
                                <i class="fa fa-home"></i>
                            </div>
                            <div class="media-body">
                                <ul class="breadcrumb">
                                    <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                                    <li>Mapa Elección</li>
                                </ul>
                                <h4>Mapa Elección</h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA-------------------------------------------------------------------------------- -->
                    <div class="contentpanel">
                      <!-- CONTENIDO ----------------------------------------------------------------------- -->
                       <div class="row">
                            <div class="col-md-12">
                            <div class="form-group">
                                    <div class="panel-group" id="accordion2">
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                                                                   <div style="color:#FFFFFF" >Seleccione el proceso electoral</div>
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseOne2" class="panel-collapse collapse <?php if(isset($_POST['bus'])) echo "in"; ?>">
                                                            <div class="panel-body">
                                                            <table width="100%" border="0">
                                                                    <tr>
                                                                        <td width="100%">
                                                                             <div class="form-group">
                                                                                    <form id="form_busqueda">
                                                                                    <div class="form-group">
                                                                                            <div class="col-sm-5">

                                                                                            <select name="c" id="c" class="form-control">
                                                                                                <?php 
                                                                                                    echo $funciones->llenarcombomodifica("SELECT id_proceso_electoral as id, CONCAT('Fecha del Proceso Electoral: ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1 ORDER BY fecha DESC", $_GET['c'] );
                                                                                                ?>
                                                                                            </select>
                                                                                            </div>
                                                                                            <div class="col-sm-5">
                                                                                             <select name="t" id="t" class="form-control">
                                                                                             <option value="0">-- Todo los Tipo de Elección --</option>
                                                                                                <?php 
                                                                                                    echo $funciones->llenarcombomodifica("SELECT id_tipo_eleccion as id, nombre as valor FROM tblc_tipo_eleccion ORDER BY nombre DESC", $_GET['t'] );
                                                                                                ?>
                                                                                            </select>

                                                                                            </div>
                                                                                        </div>
                                                                                        <input type="submit" class="btn btn-primary mr5" value="Buscar" />
                                                                                        <input type="button" class="btn btn-secundary mr5" onclick="window.location.href='mapa_eleccion'" value="Cancelar">
                                                                                    </form>
                                                                              </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div><!-- panel -->
                                                </div><!-- panel-group -->
                            </div>
                            <div id="google-map2" style="width: 100%; height: 405px; position:relative;"></div>                            


                            </div><!-- row -->
                                        
                        </div><!-- contentpanel -->
  <?php 
  $eleccion = '';
  $query = "";
  $cerca = '';
  $centro = '';

    if ($id_estado == 0 && $id_municipio == 0) { 
        $cerca = '4';
        $centro = '21.8852562, -102.2915677';
    }else if ($id_estado != 0 && $id_municipio == 0) {
          $query .= " and estado_c = ".$id_estado."";
          $coordena = $entity->scalar("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =".$id_estado);
        $cerca = '8';
        $centro = $coordena;
    }else if ($id_estado != 0 && $id_municipio != 0) {
        $query .= " and municipio_c = ".$id_municipio." and estado_c = ".$id_estado."";
        $cerca = '12';
         $coordena = $entity->scalar("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =".$id_municipio);
        $centro = $coordena;
    }
     if(isset($_GET['c'])){
      $idpelectoral = $funciones->limpia($_GET['c']);
        if ($_GET['t'] != '0') {
          $idteleccion = $funciones->limpia($_GET['t']);
          $eleccion .= " and idt_eleccion_c = ".$idteleccion."";
        }              
      }else{
              $idpelectoral = $entity->scalar("SELECT idp_electoral_c FROM vw_resultado_elecciones WHERE idp_electoral_c = (SELECT MAX(idp_electoral_c) FROM vw_resultado_elecciones) LIMIT 1");
      }            
    
    ?>
        <script type="text/javascript" language="javascript">

        var locations = [<?php

            $cadena = "";
            $img = "";
            $tipo="";
            foreach ($cadenaResultado as $value){
                    $idCasilla =    $value->id_casilla;
                    $numCasilla = $value->numero;
                    $municipio  = $entity->scalar("SELECT nombre FROM tblc_municipio WHERE id_municipio =".$value->id_municipio);
                    $latitud = $value->latitud;
                    $longitud   = $value->longitud;
                    $direccion  = $value->direccion;
                    $seccion  = $value->seccion;
                     if(isset($_GET['t'])){
                        if ($_GET['t'] != '0') {
                          $tipo_eleccion = $value->idt_eleccion_c;
                        }else{  $tipo_eleccion = '0'; }  
                      }else{ $tipo_eleccion = '0'; }

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
                    $ganador = $entity->row("SELECT vw_resultado_elecciones.* FROM vw_resultado_elecciones WHERE id_casilla = ".$value->id_casilla.$eleccion." ORDER BY resultado DESC LIMIT 1");
                    $votos_ganador = $ganador['resultado'];
                     if(isset($_GET['c'])){ if ($_GET['t'] != '0') { $img = $ganador['icono_pa']; }else{ $img = 'marker.png'; } }else{ $img = 'marker.png'; }
                    
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

          
                    
                    //****************************************************************************
                    //*****************************************************************************
                
                 $cadena .= "[".$latitud.", ".$longitud.", '".$idCasilla."', '".$numCasilla."', '".$municipio."' ,'".$direccion."', '".$seccion."', '".$tipo."', '".$img."', '".$nombre_ganador."', '".$color_ganador."', '".$tipo_ganador."', '".$votos_ganador."', '".$tipo_eleccion."'],
                ";
            }       
                  echo trim($cadena, ",");
                ?>];
                var map = new google.maps.Map(document.getElementById('google-map2'),{
                  zoom: <?= $cerca ?>,
                  center: new google.maps.LatLng(<?= $centro ?>),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                //map.controls[google.maps.ControlPosition.TOP_RIGHT].push(new FullScreenControl(map));
                var infowindow = new google.maps.InfoWindow();
                var marker, i;
                var bounds = new google.maps.LatLngBounds();
                for (i = 0; i < locations.length; i++){
               // var image = new google.maps.MarkerImage("../archivos/market.png",
                var image = new google.maps.MarkerImage("archivos/partido_politico/"+locations[i][8], 
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
                  verResultados2(locations[i][2],locations[i][13]);
                  
            if(locations[i][13] != 0){                                       
                   var contenidoDetalle = '<h3 style="color:'+locations[i][10]+'"><img src="archivos/partido_politico/'+locations[i][8]+'"> '+locations[i][9]+'</h3>'+
                                        '<b>CASILLA NÚMERO: <font style="color:#4272A2">'+locations[i][3]+'</font></b>&nbsp;&nbsp;&nbsp;'+
                                        '<b>TIPO: <font style="color:#4272A2">'+locations[i][11]+'</font></b>&nbsp;&nbsp;&nbsp;'+
                                        '<b>TOTAL DE VOTOS: <font style="color:#4272A2">'+locations[i][12]+'</font></b><br>'+
                                         '<b>DIRECCIÓN: <font style="color:#4272A2">'+locations[i][5]+'</font></b><br>';
            }else{
              var contenidoDetalle ='';
            }
                   var contenido = '<div id="content">'+
                                      '<div id="siteNotice">'+
                                      '</div>'+contenidoDetalle+
                                          '<div id="tbl_resultado'+locations[i][2]+'">'+                                         
                                          '</div>'+
                                        '</div>';
                      infowindow.setContent(contenido);
                      infowindow.open(map, marker);
                    }
                  })(marker, i));
                }

    
        </script>  


            <script type="text/javascript" language="javascript">

                var map = new google.maps.Map(document.getElementById('google-map'),{
                  zoom: 13,
                  center: new google.maps.LatLng(<?= $centro ?>),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var marker, i;
            var infowindow = new google.maps.InfoWindow();
            var infowindow1 = new google.maps.InfoWindow();
            
            $(document).on('ready', function(){
                mostrarCapa(1, '<?php echo $idpelectoral ?>', '<?php echo $eleccion ?>', '<?php echo base64_encode($query) ?>');
              });

            function verResultados2(id,idtie){
                var enlace = "pg/tbl_resultados_eleccion.php";
                  $.ajax({
                      type: "POST",
                      url: enlace,
                      data: "&idcasilla=" + id + "&idtie=" + idtie,
                      beforeSend: function() {
                          $("#tbl_resultado"+id).html('<center><img src="assets/css/assets/images/ajax-loader.gif" ><br>Enviando su solicitud, espere por favor...</center>');
                      },
                      success: function(datos) {
                          $("#tbl_resultado"+id).html(datos);
                      },
                      error: function(result) {
                        $("#tbl_resultado"+id).html("");
                             alert("Hay problemas intente de nuevo más tarde");
                      }
                 });
                  return false;
              }
          </script>