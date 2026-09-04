<?php
require "../php/inicializandoDatosExterno.php";

if ($_POST['id'] > 0) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row("SELECT (SELECT id_estado FROM tblc_municipio WHERE id_municipio = dis.id_municipio) as id_estado, dis.* FROM tblc_casilla as dis WHERE dis.id_casilla = " . $id . " ");
}
?>
    <form id="enviar_formulario" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php">
        <div class="panel panel-default">
            <div class="panel-heading">

                <h4 class="panel-title">Formulario de Registro</h4>
                <p></p>
            </div>
            <div class="panel-body">

                <?php if ($id_estado == 0) { ?>
                    <div class="form-group">
                        <label class="col-sm-3">Estado :</label>
                        <div class="col-sm-9">
                            <select name="id_estado" id="id_estado" onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios2.php')" class="form-control" required>
                                <option value="0"> Seleccionar Estado </option>
                                <?php
                                if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($querys->comboestados(), $row['id_estado']);
                                else echo $funciones->llenarcombo($querys->comboestados());
                                ?>
                            </select>
                        </div>
                    </div>
                <?php } else {
                    echo '<input type="hidden" name="id_estado" id="id_estado" value="'.$id_estado.'" />';
                }

                if ($id_municipio == 0) { ?>
                    <div class="form-group">
                        <label class="col-sm-3">Municipio :</label>
                        <div class="col-sm-9">
                            <select name="id_municipio" id="id_municipio" class="form-control" onchange="combodependiente('id_municipio', 'seccion', 'combo_dependiente/secciones.php')" required>
                                <option value="0"> Seleccionar Municipio </option>
                                <?php
                                if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($querys->combomunicipios($row['id_estado']), $row['id_municipio']);
                                else if ($id_municipio == 0) echo $funciones->llenarcombo($querys->combomunicipios($id_estado));
                                ?>
                            </select>
                        </div>
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="id_municipio" id="id_municipio" value="<?= $id_municipio; ?>" />
                    <?php
                    }
                ?>

                <div class="form-group">
                    <label class="col-sm-3">Sección :</label>
                    <div class="col-sm-9">
                        <select name="seccion" id="seccion" class="form-control" required>
                            <option value="0">-- Ninguna Sección --</option>
                            <?php
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($querys->combosecciones($row['id_municipio']), $row['id_seccion']);
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Nombre :</label>
                    <div class="col-sm-9">
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['nombre']; ?>" placeholder="Nombre" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Tipo :</label>
                    <div class="col-sm-9">
                        <select name="tipo" id="tipo" class="form-control" required>
                            <?= $funciones->getcomboTipoEleccion($row['tipo']); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Estatus :</label>
                    <div class="col-sm-9">
                        <select name="estatus" id="estatus" class="form-control" required>
                            <?php
                                if (!empty($_POST['id'])) echo $funciones->getcombotipoactivo($row['estatus']);
                                else echo $funciones->getcombotipoactivo(1);
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Dirección :</label>
                    <div class="col-sm-9">
                        <input type="text" name="direccion" id="direccion" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['direccion']; ?>" />
                    </div>
                </div>
                <div id="google_canvas" style="width:100%; height:165px;"></div>
                <input type="hidden" name="num_contigua" value="<?php if (!empty($_POST['id'])) echo $row['num_contigua'];
                                                                else echo 0; ?>" />
                <input type="hidden" name="numero" id="numero" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['numero']; ?>" />
                
                <input type="hidden" name="numero" id="numero" value="0" />
                <input type="text" name="txtLatitud" id="txtLatitud" value="<?php if (!empty($_POST['id'])) echo $row['latitud']; ?>" />
                <input type="text" name="txtLongitud" id="txtLongitud" value="<?php if (!empty($_POST['id'])) echo $row['longitud']; ?>" />
            </div><!-- panel-body -->

            <div class="panel-footer">
                <input type="submit" class="btn btn-primary mr5" id="btn_guardar" value="Guardar">
                <button class="btn btn-danger mr5" onclick="casilla_electoral_registro()">Cancelar</button>
            </div>
        </div>
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "124";
                                                                else echo "125"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />
    </form>
    <div id="cargando"></div>


<script type="text/javascript">
    window.setTimeout(function() {
        <?php if (isset($_GET['id'])) {  ?>
            drawmapcoords(<?php echo $row['latitud'] . ',' . $row['longitud']; ?>);
        <?php } else { ?>
            drawmapcoords(21.8852562, -102.2915677);
        <?php }  ?>
    }, 700);
    /*************MAPA MARCADOR JS****************/

    function municipiomapa() {
        buscar_mapa('id_estado', 'id_municipio', '')
        combodependiente('id_municipio', 'seccion', 'combo_dependiente/secciones.php');
    }
    //google.maps.event.addDomListener(window,'load',drawMap);
    var marcador;
    var opcionesMapa = {
        draggableCursor: "crosshair",
        zoom: 11,
        zoomControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var mapa;

    function marcador_map(coordenadas) {
        mapa = new google.maps.Map(document.getElementById('google_canvas'), opcionesMapa);
        marcador = new google.maps.Marker({
            map: mapa,
            draggable: false,
            position: coordenadas,
            visible: true
        });
        mapa.setCenter(coordenadas);

        google.maps.event.addListener(mapa, 'click', function(event) {
            marcador_map(event.latLng);
            $('#txtLatitud').val(event.latLng.lat());
            $('#txtLongitud').val(event.latLng.lng());
        });
        // Aplicamos las restricciones
        //  mapa._restricter = new TRestricter(mapa);
        //map._restricter.zoomLevels(14, 17);
        //(DireccionSur,<),(DireccionNorte,Direccion >)
        //  mapa._restricter.restrict(new google.maps.LatLng(16.693914241546522,-93.24517250061035),new google.maps.LatLng(16.816208207908115,-93.01437377929687));           
    }

    function drawMap() {
        navigator.geolocation.getCurrentPosition(function(posicion) {
            // var geolocalizacion = new google.maps.LatLng(posicion.coords.latitude, posicion.coords.longitude);
            var geolocalizacion = new google.maps.LatLng(21.8852562, -102.2915677);
            marcador_map(geolocalizacion);
            //calcRoute(geolocalizacion,mapa);  
            $('#txtLatitud').val(posicion.coords.latitude);
            $('#txtLongitud').val(posicion.coords.longitude);
        });
    }


    function drawmapcoords(latitud, longitud) {
        var geolocalizacion = new google.maps.LatLng(latitud, longitud);
        marcador_map(geolocalizacion);
        //calcRoute(geolocalizacion,mapa);
    }

    function buscar_mapa(estado, municipio, colonia) {
        var valor_estado = $('#' + estado + ' option:selected').text();
        var valor_municipio = $('#' + municipio + ' option:selected').text();
        // var valor_colonia   = $('#'+colonia+' option:selected').text();
        // var env   = $('#'+colonia+' option:selected').val();
        var address = valor_municipio + ', ' + valor_estado;

        // if(valor_municipio != "")
        // address = valor_municipio + ', ' +address;

        var geoCoder = new google.maps.Geocoder(address)
        var request = {
            address: address
        };
        geoCoder.geocode(request, function(result, status) {
            var latlng = new google.maps.LatLng(result[0].geometry.location.lat(), result[0].geometry.location.lng());
            //var marker = new google.maps.Marker({position:latlng,map:map,title:'title'});
            $('#txtLatitud').val(result[0].geometry.location.lat());
            $('#txtLongitud').val(result[0].geometry.location.lng());
            marcador_map(latlng);
        })
    }
    /*************MAPA MARCADOR JS****************/
</script>