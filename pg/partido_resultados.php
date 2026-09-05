<?php
    require "../php/inicializandoDatosExterno.php";

    $cand = $funciones->limpia($_POST['cand']);
    $idprocesoE = $entity->scopedProcessId($funciones->limpia($_POST['c']));
	    
    $cadena = $entity->statement('partido_resultados.7.1').$idprocesoE.$entity->statement('fragment.partido_resultados.7.1');
	//echo $cadena;
    $cadenaResultado = $entity->objects($cadena);	

    $candidato = ' Resultados por Partidos y alianzas';  

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
                                    <th>Partido</th>
                                    <th>Candidato</th>
                                    <th>Proceso Electoral</th>
                                    <th>Votos</th>
                                </tr>
                            </thead>                                             
                            <tbody>
                            <?php
                            $conteo = 0;            
                             foreach($cadenaResultado as $resultado_fila){
                                $color = '';
                                $conteo ++;
                                if($cand == $resultado_fila->id_candidato)
                                    $color = ' style="background-color:green !important; color:#fff !important;"';
                            ?>                                                  
                                    <tr<?php echo $color ?>>
                                        <td<?php echo $color ?>><?php echo $conteo; ?></td>
                                        <td<?php echo $color ?>><img width="25px" height="25px" src="archivos/partido_politico/<?php echo $resultado_fila->icono ?>"> <?php echo $resultado_fila->partido; ?></td>
                                        <td<?php echo $color ?>><?php echo $resultado_fila->nombre; ?></td>
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
