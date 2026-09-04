<?php
$eleccion = '';
$query = "";
$cerca = '';
$centro = '';

if ($id_estado == 0 && $id_municipio == 0) {
  $cerca = '5';
  $centro = '21.8852562, -102.2915677';
  $nConsulta = 1;
  if (isset($_GET['e'])) {
    if ($_GET['e'] != 0) {
      $nConsulta = 2;
    } else {
      $nConsulta = 1;
    }
  }
  if (isset($_GET['m'])) {
    if ($_GET['m'] != 0) {
      $nConsulta = 3;
    } else {
      $nConsulta = 1;
    }
  }
} else if ($id_estado != 0 && $id_municipio == 0) {
  $query .= " and estado_c = " . $id_estado . "";
  $coordena = $entity->scalar("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =" . $id_estado);
  $cerca = '8';
  $centro = $coordena;
  if (isset($_GET['m'])) {
    if ($_GET['m'] != 0) {
      $nConsulta = 3;
    } else {
      $nConsulta = 2;
    }
  } else {
    $nConsulta = 2;
  }
} else if ($id_estado != 0 && $id_municipio != 0) {
  $query .= " and municipio_c = " . $id_municipio . " and estado_c = " . $id_estado . "";
  $cerca = '13';
  $coordena = $entity->scalar("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =" . $id_municipio);
  $centro = $coordena;
  $nConsulta = 3;
}


if (isset($_GET['e'])) {
  if ($_GET['e'] != 0) {
    $query .= " and estado_c = " . $_GET['e'] . "";
    $coordena = $entity->scalar("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =" . $_GET['e']);
    $cerca = '8';
    $centro = $coordena;
  }
}
if (isset($_GET['m'])) {
  if ($_GET['m'] != 0) {
    $query .= " and municipio_c = " . $_GET['m'] . "";
    $cerca = '13';
    $coordena = $entity->scalar("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =" . $_GET['m']);
    $centro = $coordena;
  }
}

if (isset($_GET['c'])) {
  $idpelectoral = $funciones->limpia($_GET['c']);
  if ($_GET['t'] != '0') {
    $idteleccion = $funciones->limpia($_GET['t']);
    $eleccion .= " and idt_eleccion_c = " . $idteleccion . "";
  }
} else {
  $idpelectoral = $entity->scalar("SELECT MAX(id_proceso_electoral) FROM tblc_proceso_electoral WHERE estatus = 1 LIMIT 1");
}
$cadenaa = "";
$img = "";
$tipo = "";

switch ($nConsulta) {
  case 1:
    $cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = " . $idpelectoral . $query . " GROUP BY estado_c";
    $cadenaResultado = $entity->objects($cadena);
    $total = $entity->numregistros();
    # ADMINISTRADOR                                                          
    foreach ($cadenaResultado as $value) {
      $datEstado = $entity->row("SELECT * FROM tblc_estado WHERE id_estado =" . $value->estado_c);

      $idEstado = $datEstado['id_estado'];
      $estado  = $datEstado['nombre'];
      $latitud = $datEstado['latitud'];
      $longitud   = $datEstado['longitud'];
      $infoPOPe = $funciones->ganadoresTipoEleccionEdo($idpelectoral, $value->estado_c);
      $img = 'marker.png';
      $cadenaa .= "[" . $latitud . ", " . $longitud . ", '" . $idEstado . "', '" . $estado . "', '0', '0', '0', '0', '" . $img . "', '" . $infoPOPe . "'],
                    ";
    }

    break;
  case 2:
    $cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = " . $idpelectoral . $query . " GROUP BY municipio_c";
    $cadenaResultado = $entity->objects($cadena);
    $total = $entity->numregistros();
    # ESTADO ASIGNADO
    foreach ($cadenaResultado as $value) {
      $datMunicipio = $entity->row("SELECT * FROM tblc_municipio WHERE id_municipio =" . $value->municipio_c);
      $idMunicipio = $datMunicipio['id_municipio'];
      $municipio  = $datMunicipio['nombre'];
      $latitud = $datMunicipio['latitud'];
      $longitud   = $datMunicipio['longitud'];
      $infoPOP = $funciones->ganadoresTipoEleccion($idpelectoral, $value->municipio_c);
      $img = 'marker.png';

      $cadenaa .= "[" . $latitud . ", " . $longitud . ", '" . $idMunicipio . "', '" . $municipio . "', '0', '0', '0', '0', '" . $img . "', '" . $infoPOP . "'],
                    ";
    }
    break;
  case 3:
    $cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = " . $idpelectoral . $eleccion . $query . " GROUP BY seccion";
    $cadenaResultado = $entity->objects($cadena);
    $total = $entity->numregistros();
    # MUNICIPIO ASIGNADO
    foreach ($cadenaResultado as $value) {
      $idCasilla =    $value->id_casilla;
      $numCasilla = $value->seccion;
      $municipio  = $entity->scalar("SELECT nombre FROM tblc_municipio WHERE id_municipio =" . $value->id_municipio);
      $latitud = $value->latitud;
      $longitud   = $value->longitud;
      $distrito  = $entity->scalar("SELECT d.nombre FROM tblc_distrito as d JOIN tblc_seccion as s ON(d.id_distrito = s.id_distrito) WHERE s.nombre =" . $value->seccion);
      $seccion  = $value->seccion;
      if (isset($_GET['t'])) {
        if ($_GET['t'] != '0') {
          $tipo_eleccion = $value->idt_eleccion_c;
        } else {
          $tipo_eleccion = '0';
        }
      } else {
        $tipo_eleccion = '0';
      }

      switch ($value->tipo) {
        case 1:
          $tipo = "Basica";
          break;
        case 2:
          $tipo = "Contigua";
          break;
        case 3:
          $tipo = "Extraordinaria";
          break;
      }
      $ganador = $entity->row("SELECT vw_resultado_elecciones.*, SUM(resultado) AS resultado_total FROM vw_resultado_elecciones WHERE seccion = " . $value->seccion . $eleccion . " GROUP BY idcandidato_c ORDER BY resultado_total DESC LIMIT 1");

      $votos_ganador = $ganador['resultado_total'];
      if (isset($_GET['c'])) {
        if ($_GET['t'] != '0') {
          $img = $ganador['icono_pa'];
        } else {
          $img = 'marker.png';
        }
      } else {
        $img = 'marker.png';
      }

      $nombre_ganador = $ganador['nombre_c'];
      $color_ganador = $ganador['color_pa'];
      switch ($ganador['tipo']) {
        case 1:
          $tipo_ganador = "Basica";
          break;
        case 2:
          $tipo_ganador = "Contigua";
          break;
        case 3:
          $tipo_ganador = "Extraordinaria";
          break;
      }

      $infoPOP = $funciones->ganadoresSeccion($seccion, $tipo_eleccion);
      //*****************************************************************************

      $cadenaa .= "[" . $latitud . ", " . $longitud . ", '" . $idCasilla . "', '" . $numCasilla . "', '" . $municipio . "' ,'" . $distrito . "', '" . $seccion . "', '" . $tipo . "', '" . $img . "', '" . $nombre_ganador . "', '" . $color_ganador . "', '" . $tipo_ganador . "', '" . $votos_ganador . "', '" . $tipo_eleccion . "','" . $infoPOP . "'],
                    ";
    }
    break;
}

?>

<div class="pageheader">
  <div class="media">
    <div class="pageicon pull-left">
      <i class="fa fa-home"></i>
    </div>
    <div class="media-body">
      <ul class="breadcrumb">
        <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
        <li>Mapa de Secciones</li>
      </ul>
      <h4>Mapa de Secciones</h4>
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
                  <div style="color:#FFFFFF">Seleccione el proceso electoral</div>
                </a>
              </h4>
            </div>
            <div id="collapseOne2" class="panel-collapse collapse <?php if (isset($_POST['bus'])) echo "in"; ?>">
              <div class="panel-body">
                <table width="100%" border="0">
                  <tr>
                    <td width="100%">
                      <div class="form-group">
                        <form id="form_busqueda">
                          <div class="form-group">
                            <div class="col-sm-6">

                              <select name="c" id="c" class="form-control">
                                <?php
                                echo $funciones->llenarcombomodifica("SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1 ORDER BY fecha DESC", $_GET['c']);
                                ?>
                              </select>
                            </div>
                            <div class="col-sm-6">
                              <select name="t" id="t" class="form-control">
                                <option value="0">-- Todo Tipo de Elección --</option>
                                <?php
                                echo $funciones->llenarcombomodifica("SELECT id_tipo_eleccion as id, nombre as valor FROM tblc_tipo_eleccion ORDER BY nombre DESC", $_GET['t']);
                                ?>
                              </select>

                            </div>
                            <?php
                            if ($id_estado == 0 && $id_municipio == 0) {
                            ?>
                              <div class="col-sm-6">
                                <select name="e" id="e" onchange="combodependiente('e', 'm', 'combo_dependiente/municipios.php')" class="form-control" required>
                                  <option value="0">-- Todos los Estados --</option>
                                  <?php
                                  if (isset($_GET['e'])) echo $funciones->llenarcombomodifica("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre", $_GET['e']);
                                  else echo $funciones->llenarcombo("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre");
                                  ?>
                                </select>
                              </div>

                              <div class="col-sm-6">
                                <select name="m" id="m" class="form-control">
                                  <option value="0">-- Todos los Municipios --</option>
                                  <?php
                                  if (isset($_GET['m']) && $_GET['e'] != 0) echo $funciones->llenarcombomodifica("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = " . $_GET['e'] . " ORDER BY nombre", $_GET['m']);
                                  ?>
                                </select>
                              </div>
                            <?php
                            } else if ($id_estado != 0 && $id_municipio == 0) {
                            ?>
                              <div class="col-sm-6">
                                <select name="m" id="m" class="form-control">
                                  <?php
                                  if (isset($_GET['m'])) echo $funciones->llenarcombomodifica("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = " . $id_estado . " ORDER BY nombre", $_GET['m']);
                                  ?>
                                </select>
                              </div>
                            <?php
                            }
                            ?>
                          </div>
                          <div class="col-sm-6">
                            <input type="submit" class="btn btn-primary mr5" value="Buscar" />
                            <input type="button" class="btn btn-secundary mr5" onclick="window.location.href='mapa_seccion'" value="Cancelar">
                          </div>
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
      <div style="float:left;"><strong><?php echo $total; ?></strong> REGISTROS ENCONTRADOS </h4>
      </div><?php if ($_GET['e'] != 0) { ?><div id="verestado" style="float:left; "><a href="mapa_seccion"><b>&nbsp;&nbsp;IR A ESTADOS</b></a></div><?php }
        if ($_GET['m'] != 0) { ?><div id="vermun" style="float:left; "><a href="mapa_seccion?e=<?= $_GET['e'] ?>">&nbsp;&nbsp; | &nbsp;&nbsp; <b>IR A MUNICIPIOS</b></a></div>
        <div id="vermun" style="float:left; "><a href="#">&nbsp;&nbsp; | &nbsp;&nbsp; <font color="#009327" style="font-weight: bold;"><?= $funciones->pasarMayusculas($entity->scalar("SELECT nombre FROM tblc_municipio WHERE id_municipio=" . $_GET['m'])); ?></font></a></div><?php } ?>
      <div id="google-map2" style="width: 100%; height: 435px; position:relative;"></div>
    </div><!-- row -->

  </div><!-- contentpanel -->

  <script type="text/javascript" language="javascript">
    var locations = [<?php echo trim($cadenaa, ","); ?>];
    var map = new google.maps.Map(document.getElementById('google-map2'), {
      zoom: <?= $cerca ?>,
      center: new google.maps.LatLng(<?= $centro ?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    //map.controls[google.maps.ControlPosition.TOP_RIGHT].push(new FullScreenControl(map));
    var infowindow = new google.maps.InfoWindow();
    var marker, i;
    var bounds = new google.maps.LatLngBounds();
    for (i = 0; i < locations.length; i++) {
      // var image = new google.maps.MarkerImage("../archivos/market.png",
      var image = new google.maps.MarkerImage("archivos/partido_politico/" + locations[i][8],
        new google.maps.Size(30, 30),
        new google.maps.Point(0, 0),
        new google.maps.Point(1, 40));
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][0], locations[i][1]),
        icon: image,
        map: map
      });
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          <?php if ($nConsulta == 3) { ?>
            if (locations[i][13] != 0) {
              var contenidoDetalle = '<h3 style="color:' + locations[i][10] + '"><img src="archivos/partido_politico/' + locations[i][8] + '"> ' + locations[i][9] + '</h3>' +
                '<b><font style="color:#262626" size="4">TOTAL DE VOTOS: </font><font style="color:#4272A2" size="4">' + locations[i][12] + '</font></b>&nbsp;';
            } else {
              var contenidoDetalle = '';
            }
            var contenido = '<div id="content">' +
              '<div id="siteNotice">' +
              '</div>' + contenidoDetalle +
              '<b><font style="color:#262626" size="4">SECCIÓN: </font><font style="color:#4272A2" size="4">' + locations[i][3] + '</font></b>&nbsp;' +
              '<b> - <font style="color:#4272A2" size="4">' + locations[i][5] + '</font></b><br>' +
              '<div id="tbl_resultado' + locations[i][6] + '">' + locations[i][14] +
              '</div>' +
              '</div>';
            infowindow.setContent(contenido);
          <?php } else { ?>
            infowindow.setContent(locations[i][9]);
          <?php } ?>
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  </script>