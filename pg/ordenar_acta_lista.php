<?php 
    require "../php/inicializandoDatosExterno.php";

 $id_proceso_electoral = $funciones->limpia($_POST['id']);
 ?>
            <?php    
              $sentencia = "";

              $consulta =  $entity->statement('ordenar_acta_lista.9.1').$id_proceso_electoral.$entity->statement('fragment.ordenar_acta_lista.9.1');
            //echo $consulta;
            $resul_lista = $entity->objects($consulta);

            foreach($resul_lista as $resultado_fila){
             ?>
             <tr draggable="true" data-id="<?= $resultado_fila->id_candidato ?>-<?= $resultado_fila->id_partido_politico ?>">
                <td><?= $resultado_fila->id_candidato ?>-<?= $resultado_fila->id_partido_politico ?></td>
                <td><img height="35px" src="archivos/partido_politico/<?php echo $resultado_fila->icono ?>"/>&nbsp;&nbsp;&nbsp;<font color="<?= $resultado_fila->colo ?>"><b><?php echo $resultado_fila->partido ?></b></font></td>
                <td><?= $resultado_fila->nombre ?></td>
              </tr>
              <?php 
                  }
              ?>                             
