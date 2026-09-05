<?php 
    require "../php/inicializandoDatosExterno.php";

 $id_proceso_electoral = $funciones->limpia($_POST['id']);
 ?>


                                        <div class="table-responsive prep-results-table">
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

                                                $consulta =  $entity->statement('registro_resultados_completo_lista.21.1').$id_proceso_electoral.$entity->statement('fragment.registro_resultados_completo_lista.21.1');
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
