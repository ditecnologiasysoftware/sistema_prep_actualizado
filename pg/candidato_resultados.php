<?php
    require "../php/inicializandoDatosExterno.php";

    $idcandidatoP = $funciones->limpia($_POST['cand']);
    $idprocesoE = $entity->scopedProcessId($funciones->limpia($_POST['c']));

    $cadena = $entity->statement('candidato_resultados.7.1').$idprocesoE.$entity->statement('fragment.candidato_resultados.7.1');
    //echo $cadena;
    $cadenaResultado = $entity->objects($cadena);    

    $candidato = ' Resultado del candidato - <b>'.$entity->scalar($entity->statement('candidato_resultados.15.2').$idcandidatoP).'</b>';  

?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 id="titulo" class="modal-title"><?php echo $candidato ?></h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">

                    <div class="col-md-12 form-group">
                        
                        <table id="basicTable" class="table table-striped table-bordered responsive">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Candidato</th>
                                    <th>Partidos</th>
                                    <th>Proceso Electoral</th>
                                    <th>Votos</th>
                                </tr>
                            </thead>                                             
                            <tbody>
                            <?php
                                $conteo = 0;                                            
                             foreach($cadenaResultado as $resultado_fila){
                                $conteo++;
                                $color = '';

                                if($idcandidatoP == $resultado_fila->id_candidato)
                                    $color = ' style="background-color:green !important; color:#fff !important;"';

                                $tipoPartidos = "";
                                    $partidos = $entity->objects($entity->statement('candidato_resultados.54.3').$resultado_fila->id_candidato);
                                    foreach ($partidos as $value) {
                                        $tipoPartidos .= $value->nombre.', ';
                                    }
                                    $tipoPartidos = trim($tipoPartidos, ', ');
                            ?>                                                  
                                    <tr<?php echo $color ?>>
                                        <td<?php echo $color ?>><?php echo $conteo; ?></td>
                                        <td<?php echo $color ?>><?php echo $resultado_fila->nombre; ?></td>
                                        <td<?php echo $color ?>><?php echo $tipoPartidos; ?></td>
                                        <td<?php echo $color ?>><?php echo $resultado_fila->descripcion." - ".$resultado_fila->fecha; ?></td>
                                        <td<?php echo $color ?> align="center"><?php echo '<b>'.number_format($resultado_fila->suma, 0, '.', ',').'</b> Votos.'; ?></td>                                                            
                                    </tr>
                                    <?php 
                                        }
                                    ?>
                           </tbody>

                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
