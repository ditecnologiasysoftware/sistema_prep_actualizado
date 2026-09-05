<?php
  require ("../php/clase_variables.php");
  require ("../php/clase_mysql.php");
  require ("../php/clase_funciones.php");
  
  $funciones = new Funciones();
  //LLAMAMOS A LA CLASE CONEXION
  $entity = Entity::createInstance();
 ?>
<br><br><br>
     <form  id="form_menu" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php" target="mandar_formulario">
              <table id="basicTable" class="table table-striped table-bordered responsive">
                    <thead class="">
                        <tr>
                            <th style="width:80%">Partido</th>
                            <th style="width:20%">Total Votos</th>
                        </tr>
                    </thead>                                             
                    <tbody>                                                                                                        
                    <?php    
                     $sentencia = "";
                    $idproceso = $funciones->limpia($_POST['p']); 
                    $casilla = $funciones->limpia($_POST['c']);

                    $acta = $entity->row($entity->statement('lista_candidatos2.25.1').$idproceso.$entity->statement('fragment.lista_candidatos2.25.1').$casilla);

                  	$consulta =  $entity->statement('lista_candidatos2.27.2').$idproceso.$entity->statement('fragment.lista_candidatos2.27.2');
                    //echo $consulta;
                    $resul_lista = $entity->objects($consulta);

                    if ($entity->numregistros() != 0) {

                    foreach($resul_lista as $resultado_fila){
                      $resultados = $entity->scalar($entity->statement('lista_candidatos2.38.3').$casilla.$entity->statement('fragment.lista_candidatos2.34.3').$resultado_fila->id_candidato);
                     ?>
                            <tr>
                                <td style="font-size: 11px;"><img height="25px" src="electoral/archivos/partido_politico/<?php echo $resultado_fila->icono ?>"/>&nbsp;&nbsp;&nbsp;<font color="<?= $resultado_fila->colo ?>"><b><?php echo $resultado_fila->partido ?></b></font></td>

                                <td style="text-align: right; width:20%">
                                    <input type="hidden" name="partido[]" id="partido" value="<?php echo $resultado_fila->id_partido_politico; ?>">
                                    <input type="hidden" name="candidato[]" id="candidato" value="<?php echo $resultado_fila->id_candidato; ?>">
                                    <input style="width: 120px; margin:0px;" type="number" name="votos[]" id="votos" class="form-control" value="<?php echo $resultados ?>" placeholder="Votos">
                                </td>                                                  
                            </tr>
                            <?php 
                                }
                            ?>
                            <tr >
                                <td><font color="#2B4E7D"><b>NO REGISTRADOS : </b></font></td>
                                <td style="text-align: right;"><input style="width: 120px;" type="number" name="no_registrados" id="no_registrados" class="form-control" value="<?php echo $acta['no_registrados'] ?>" placeholder="No registrados"></td>
                            </tr>
                            <tr >
                                <td><font color="#2B4E7D"><b>VOTOS NULOS : </b></font></td>
                                <td style="text-align: right;"><input style="width: 120px;" type="number" name="votos_null" id="votos_null" class="form-control" value="<?php echo $acta['votos_nulos'] ?>" placeholder="Votos nulos"></td>
                            </tr>
                            <td><font color="#2B4E7D"><b>TOTAL DE VOTOS : </b></font></td>
                                <td style="text-align: right;"><input style="width: 120px;" type="number" name="total_votos" id="total_votos" class="form-control" value="<?php echo $acta['total_votos'] ?>" placeholder="total de votos"></td>
                            </tr>
                            <tr >
                                <td><font color="#2B4E7D"><b>ACTA : </b></font></td>
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
                        <input type="submit" value="Guardar" class="btn" style="background: #2071db; border-color: #2071db;width: 90%;">
                    <?php 
                    $redi = "window.location.href='registro_resultados'";                                           
                    if(isset($_POST['p'])) echo '<button class="btn btn-danger mr5" onclick="'.$redi.'">Cancelar</button>';  
                    }
                  else{
                    echo '<center><h1>No se encontraron resultados</h1></center>';
                  }
               ?>                                   
                  </div>   
             
        <input type="hidden" name="opcion" id="opcion" value="131"/>
        <input type="hidden" name="idprocesoElect" id="idprocesoElect" value="<?php if(isset($_POST['p'])) echo $_POST['p']; ?>"/>
        <input type="hidden" name="idcasillaElect" id="idcasillaElect" value="<?php if(isset($_POST['c'])) echo $_POST['c']; ?>"/>

      </form>