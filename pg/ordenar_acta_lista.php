<?php 
    require "../php/inicializandoDatosExterno.php";

 $id_proceso_electoral = $funciones->limpia($_POST['id']);
 ?>
            <?php    
              $sentencia = "";

              $consulta =  "SELECT c.*, cp.ordenamiento, p.id_partido_politico, p.nombre as partido, p.icono, p.colo 
            FROM tblc_candidato_partido AS cp 
            INNER JOIN tblc_candidato AS c ON c.id_candidato = cp.id_candidato 
            INNER JOIN tblc_partido_politico AS p ON p.id_partido_politico = cp.id_partido_politico 
            WHERE c.id_proceso_electoral = ".$id_proceso_electoral." ORDER BY cp.ordenamiento ASC";
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
