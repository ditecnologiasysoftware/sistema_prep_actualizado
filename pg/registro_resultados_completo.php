<?php 
    $query = "";
    $query_pe = "";

    if ($id_municipio != 0) {
         $query .= $entity->statement('fragment.registro_resultados_completo.6.1').$id_municipio."";
       $query_pe .= $entity->statement('fragment.registro_resultados_completo.7.2').$id_municipio."";
      }elseif ($id_estado != 0) {
         $query .= $entity->statement('fragment.registro_resultados_completo.9.3').$id_estado."";
       $query_pe .= $entity->statement('fragment.registro_resultados_completo.10.4').$id_estado."";
      }
 ?>
                  <!--  ARRIBA----------------------------------------------------------------------------------- -->

                   <div class="pageheader">
                        <div class="media">
                            <div class="pageicon pull-left">
                                <i class="fa fa-database"></i>
                            </div>
                            <div class="media-body">
                                <ul class="breadcrumb">
                                    <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                                    <li>Registro de Resultados</li>
                                </ul>
                                <h4>Registro de Resultados</h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA-------------------------------------------------------------------------------- -->
                    <div class="contentpanel prep-results-entry">
                      <!-- CONTENIDO ----------------------------------------------------------------------- -->
					   <div class="row">
                            <div class="col-md-12">
                              <div class="panel panel-default">
                                <div class="panel-heading">
                                  <h4 class="panel-title">Captura del acta de escrutinio</h4>
                                  <p>Seleccione la casilla y registre los votos obtenidos.</p>
                                </div>
                                <div class="panel-body">

                                <form class="form-horizontal prep-results-form" id="enviar_formulario" method="post" enctype="multipart/form-data" action="php/subir.php">
                                  <div class="row prep-results-filters">

                                    <?php if ($id_proceso_electoral == "0") { ?> 
                                    <div class="form-group col-sm-3">
                                        <label>Proceso Electoral :</label>
                                            <select name="idprocesoElect" id="idprocesoElect" style="width:95%;" required onchange="lista_candidatos_completo(this)">
                                                <option value=""> - Seleccionar Proceso Electoral - </option>
                                                <?php
                                                echo $funciones->llenarcombo($querys->comboprocesoelectoral());
                                                ?>
                                            </select>
                                    </div> 

                                    <?php }else{ ?>                                                   
                                         <input type="hidden" name="idprocesoElect" id="idprocesoElect" value="<?php echo $id_proceso_electoral; ?>"/>
                                    <?php } ?>  

                                    <?php if ($id_estado == 0 && $id_municipio == 0) { ?>
                                                <div class="form-group col-sm-2">
                                                    <label>Estado :</label>
                                                        <select name="id_estado" id="id_estado" onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios2.php')" style="width:90%;" required>
                                                            <?php 
                                                            if(isset($_GET['id'])) echo $funciones->llenarcombomodifica($entity->statement('registro_resultados_completo.65.2'), $row['id_estado']);
                                                            else echo $funciones->llenarcombo($entity->statement('registro_resultados_completo.66.3'));
                                                            ?>
                                                        </select>
                                                </div>

                                                <div class="form-group col-sm-2">
                                                    <label>Municipio :</label>
                                                        <select name="id_municipio" id="id_municipio" style="width:90%;" required onchange="combodependiente('id_municipio', 'seccion', 'combo_dependiente/secciones.php')">
                                                            <?php 
                                                            if(isset($_GET['id'])) echo $funciones->llenarcombomodifica($entity->statement('registro_resultados_completo.75.4').$row['id_estado'].$entity->statement('fragment.registro_resultados_completo.75.6'), $row['id_municipio']);
                                                            ?>
                                                        </select>
                                                </div> 
                                            <?php }else if ($id_estado != 0 && $id_municipio == 0) { ?> 
                                            <div class="form-group col-sm-2">
                                                <label>Municipio :</label>
                                                    <select name="id_municipio" id="id_municipio" style="width:90%;" required onchange="combodependiente('id_municipio', 'seccion', 'combo_dependiente/secciones.php')">
                                                        <?php 
                                                        if(isset($_GET['id'])) echo $funciones->llenarcombomodifica($entity->statement('registro_resultados_completo.84.5').$id_estado.$entity->statement('fragment.registro_resultados_completo.84.7'), $row['id_municipio']);
                                                        else echo $funciones->llenarcombo($entity->statement('registro_resultados_completo.85.6').$id_estado.$entity->statement('fragment.registro_resultados_completo.85.8'));
                                                        ?>
                                                    </select>
                                            </div> 
                                                <input type="hidden" name="id_estado" id="id_estado" value="<?= $id_estado;?>" />

                                            <?php }else{ ?>                                                   
                                                 <input type="hidden" name="id_municipio" id="id_municipio" value="<?= $id_municipio;?>" />
                                                 <input type="hidden" name="id_estado" id="id_estado" value="<?= $id_estado;?>" />
                                            <?php } ?>                                                                       

                                             <div class="form-group col-sm-2">
                                                <label>Sección :</label>
                                                    <input type="text" name="seccion" id="seccion" class="form-control" value="" placeholder="Sección" required style="width:90%;" />
                                            </div>

                                            <div class="form-group col-sm-2">
                                                <label>Nombre de casilla :</label>
                                                    <input type="text" name="nombre" id="nombre" class="form-control" value="" placeholder="Ejemplo: B1, C1, E1" required style="width:90%;"/>
                                            </div>

                                             <div class="form-group col-sm-2">
                                                <label>Tipo :</label>
                                                    <select name="tipo" id="tipo" required style="width:90%;">
                                                        <?php 
                                                             echo $funciones->getcomboTipoEleccion($row['tipo']);
                                                        ?>
                                                    </select>
                                            </div>

                                            <input type="hidden" name="opcion" id="opcion" value="140"/>

                                  </div>

                                    <div id="listapartidos" class="table-responsive prep-results-table">

                                        <table id="basicTable" class="table table-striped table-bordered responsive">
                                              <thead class="">
                                                  <tr>
                                                      <th style="width:45%">Partido</th>
                                                      <th style="width:30%">Candidato</th>
                                                      <th style="width:15%">Total Votos</th>
                                                  </tr>
                                              </thead>                                             
                                              <tbody>                                                                                                        
                                              <?php    
                                                $sentencia = "";

                                                $consulta =  $entity->statement('registro_resultados_completo.133.7').$id_proceso_electoral.$entity->statement('fragment.registro_resultados_completo.133.9');
                                              //echo $consulta;
                                              $resul_lista = $entity->objects($consulta);

                                              if ($entity->numregistros() != 0) {

                                              foreach($resul_lista as $resultado_fila){
                                               ?>
                                                      <tr>
                                                          <td><img height="35px" src="archivos/partido_politico/<?php echo $resultado_fila->icono ?>"/>&nbsp;&nbsp;&nbsp;<font color="<?= $resultado_fila->colo ?>"><b><?php echo $resultado_fila->partido ?></b></font></td>
                                                          <td>
                                                              <?php echo $resultado_fila->nombre ?>
                                                          </td>
                                                          <td style="text-align: right;">
                                                              <input type="hidden" name="partido[]" id="partido" value="<?php echo $resultado_fila->id_partido_politico; ?>">
                                                              <input type="hidden" name="candidato[]" id="candidato" value="<?php echo $resultado_fila->id_candidato; ?>">
                                                              <input style="width: 120px;" type="number" name="votos[]" id="votos" class="form-control" value="" placeholder="Votos">
                                                          </td>                                                  
                                                      </tr>
                                                      <?php 
                                                          }
                                                      ?>
                                                      <tr >
                                                          <td colspan="2"><font color="#2B4E7D"><b>NO REGISTRADOS : </b></font></td>
                                                          <td style="text-align: right;"><input style="width: 120px;" type="number" name="no_registrados" id="no_registrados" class="form-control" value="<?php echo $acta['no_registrados'] ?>" placeholder="No registrados"></td>
                                                      </tr>
                                                      <tr >
                                                          <td colspan="2"><font color="#2B4E7D"><b>VOTOS NULOS : </b></font></td>
                                                          <td style="text-align: right;"><input style="width: 120px;" type="number" name="votos_null" id="votos_null" class="form-control" value="<?php echo $acta['votos_nulos'] ?>" placeholder="Votos nulos"></td>
                                                      </tr>
                                                      <tr >
                                                          <td colspan="2"><font color="#2B4E7D"><b>TOTAL DE VOTOS : </b></font></td>
                                                          <td style="text-align: right;"><input style="width: 120px;" type="number" name="total_votos" id="total_votos" class="form-control" value="<?php echo $acta['total_votos'] ?>" placeholder="total de votos"></td>
                                                      </tr>
                                                      <tr >
                                                          <td colspan="2"><font color="#2B4E7D"><b>ACTA : </b></font></td>
                                                          <td style="text-align: right;">
                                                            <input type="file" name="acta_file" id="acta_file">
                                                            <?php 
                                                            if($acta['votos_nulos'] != "")
                                                              echo '<a href="archivos/actas_eleccion/'.$acta['archivo'].'" target="_blank">Ver archivo</a>';
                                                              ?>
                                                          </td>
                                                      </tr>                                                            
                                                   </tbody>
                                                </table>
                                            <div class="form-group text-right">  
                                                <button class="btn btn-primary mr5">Guardar Resultados</button>
                                                <?php 
                                                $redi = "window.location.href='registro_resultados_completo'";                                           
                                                echo '<button class="btn btn-danger mr5" onclick="'.$redi.'">Cancelar</button>';

                                            echo '</div>';  
                                                }
                                         ?>
                                        
                                    </div>
                                               
                                </form>
                                <div id="cargando"></div>
                                </div>
                              </div>

                            </div>
                          
                        </div><!-- row -->  
                                
                                <!--FIN DE CONTENIDO-------------------------------------------------------->
                        
                    </div><!-- contentpanel -->

                    <script>
                        window.onload = function() {
                            enviar_formulario();
                        }
                    </script>
