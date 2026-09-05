<?php   
  $estadoget = 0;
  $municipioget = 0;
  $tipoEleccion = 0;
  $procesoElectoral = 0;     
   if(isset($_GET['e'])){ $estadoget = $_GET['e']; }
   if(isset($_GET['m'])){ $municipioget = $_GET['m']; }
   if(isset($_GET['t'])){ $tipoEleccion = $_GET['t']; }
   if(isset($_GET['c'])){ $procesoElectoral = $entity->scopedProcessId($_GET['c']); }

    if ($id_estado == 0 && $id_municipio == 0) { 
      $cerca = '4';
      $centro = '21.8852562, -102.2915677';
    }else if ($id_estado != 0 && $id_municipio == 0) {
      $coordena = $entity->scalar($entity->statement('mapa_seccion_2.15.1').$id_estado);
      $cerca = '8';
      $centro = $coordena;
    }else if ($id_estado != 0 && $id_municipio != 0) {
      $cerca = '12';
      $coordena = $entity->scalar($entity->statement('mapa_seccion_2.20.2').$id_municipio);
      $centro = $coordena;
    }
    if(isset($_GET['e'])){ 
      if ($_GET['e'] != 0) {
        $coordena = $entity->scalar($entity->statement('mapa_seccion_2.25.3').$_GET['e']);
        $cerca = '8';
        $centro = $coordena;
      }
    }
    if(isset($_GET['m'])){
      if($_GET['m'] != 0){
        $cerca = '12';
        $coordena = $entity->scalar($entity->statement('mapa_seccion_2.33.4').$_GET['m']);
        $centro = $coordena;
      }
    }
   $parametro = "?e=".$estadoget."&m=".$municipioget."&t=".$tipoEleccion."&p=".$procesoElectoral."";
             
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
                                                                                            <div class="col-sm-6">

                                                                                            <select name="c" id="c" class="form-control">
                                                                                                <option value="0">-- Todo Proceso de Elección --</option>

                                                                                                <?php 
                                                                                                    echo $funciones->llenarcombomodifica($querys->comboprocesoelectoral(), $_GET['c'] ?? $id_proceso_electoral);
                                                                                                ?>
                                                                                            </select>
                                                                                            </div>
                                                                                            <div class="col-sm-6">
                                                                                             <select name="t" id="t" class="form-control">
                                                                                             <option value="0">-- Todo Tipo de Elección --</option>
                                                                                                <?php 
                                                                                                    echo $funciones->llenarcombomodifica($entity->statement('mapa_seccion_2.93.6'), $_GET['t'] );
                                                                                                ?>
                                                                                            </select>

                                                                                            </div>
                                                                                         <?php
                                                                                        if($id_estado == 0 && $id_municipio == 0){
                                                                                        ?>
                                                                                            <div class="col-sm-6">
                                                                                              <select name="e" id="e" onchange="combodependiente('e', 'm', 'combo_dependiente/municipios.php')" class="form-control" required>
                                                                                                                        <option value="0">-- Todos los Estados --</option>
                                                                                                                        <?php 
                                                                                                                        if(isset($_GET['e'])) echo $funciones->llenarcombomodifica($entity->statement('mapa_seccion_2.105.7'), $_GET['e']);
                                                                                                                        else echo $funciones->llenarcombo($entity->statement('mapa_seccion_2.106.8'));
                                                                                                                        ?>
                                                                                                                        </select>
                                                                                            </div>

                                                                                            <div class="col-sm-6">
                                                                                              <select name="m" id="m" class="form-control" >
                                                                                                                          <option value="0">-- Todos los Municipios --</option>
                                                                                                                          <?php 
                                                                                                                        if(isset($_GET['m']) && $_GET['e'] != 0) echo $funciones->llenarcombomodifica($entity->statement('mapa_seccion_2.115.9').$_GET['e'].$entity->statement('fragment.mapa_seccion_2.115.1'), $_GET['m']);
                                                                                                                        ?>
                                                                                                                        </select>
                                                                                            </div>
                                                                                        <?php
                                                                                        }
                                                                                        else if($id_estado != 0 && $id_municipio == 0){
                                                                                          ?>
                                                                                            <div class="col-sm-6">
                                                                                              <select name="m" id="m" class="form-control" >
                                                                                                  <?php 
                                                                                                    if(isset($_GET['m'])) echo $funciones->llenarcombomodifica($entity->statement('mapa_seccion_2.126.10').$id_estado.$entity->statement('fragment.mapa_seccion_2.126.2'), $_GET['m']);
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
                            <div id="google-map" style="width: 100%; height: 405px; position:relative;"></div>                            
                            <h4><strong><?php echo $total; ?></strong> Casillas Encontradas</h4>

                            </div><!-- row -->
                                        
                        </div><!-- contentpanel -->

<script type="text/javascript" language="javascript">

inicializar(<?= $centro ?>,<?= $cerca ?>);
mapaSecciones(<?php echo "'".$parametro."'"; ?>);
    function verResultados2(id,idtie){
      var enlace = "pg/tbl_resultados_eleccion_seccion.php";
        $.ajax({
            type: "POST",
            url: enlace,
            data: "&seccion=" + id + "&idtie=" + idtie,
            beforeSend: function() {
                $("#tbl_resultado"+id).html('<center><img src="../assets/images/loaders/loader1.gif" ><br>Enviando su solicitud, espere por favor...</center>');
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
