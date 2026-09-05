<?php
    require "../php/inicializandoDatosExterno.php";
    
	$pagina = isset($_POST['pagina']) ? $funciones->limpia($_POST['pagina']) : 1;
	$limite = 15;
	$cantenlaces = 7;
	$inicio = ($pagina - 1) * $limite;

	$sentencia = "";

        if(isset($_POST['seccion']) && $_POST['seccion'] != ""){
            $seccion = $funciones->limpia($_POST['seccion']);
            $sentencia .= $entity->statement('fragment.casilla_resultados_lista.13.1').$seccion;          
        }

		$idcandidatoP = $funciones->limpia($_POST['cand']);
        $idprocesoE = $funciones->limpia($_POST['c']);

		$cadena = $entity->statement('casilla_resultados_lista.19.1').$idprocesoE.$sentencia.$entity->statement('fragment.casilla_resultados_lista.19.2').$inicio.",".$limite;
		$cadena2 = $entity->statement('casilla_resultados_lista.20.2').$idprocesoE.$sentencia.$entity->statement('fragment.casilla_resultados_lista.20.3');	

        $total = $entity->scalar($cadena2);
        $totalRegistros = $entity->numregistros();

		$cadenaResultado = $entity->objects($cadena);	
?>
    <table id="basicTable" class="table table-striped table-bordered responsive">
        <thead class="">
            <tr>
                <th>Casilla</th>
                <th>Proceso Electoral</th>
                <th>Votos</th>
                <th>Total</th>
                <th>Porcentaje</th>
                <th>Actas</th>
            </tr>
        </thead>                                             
        <tbody>
        <?php                                                   
         foreach($cadenaResultado as $resultado_fila){
            $votos = $entity->scalar($entity->statement('casilla_resultados_lista.41.3').$idcandidatoP.$entity->statement('fragment.casilla_resultados_lista.41.4').$resultado_fila->id_casilla);
            $votos_nulos = $entity->scalar($entity->statement('casilla_resultados_lista.42.4').$idprocesoE.$entity->statement('fragment.casilla_resultados_lista.42.5').$resultado_fila->id_casilla);
            $no_registrados = $entity->scalar($entity->statement('casilla_resultados_lista.43.5').$idprocesoE.$entity->statement('fragment.casilla_resultados_lista.43.6').$resultado_fila->id_casilla);
            $total_votos = $entity->scalar($entity->statement('casilla_resultados_lista.44.6').$idprocesoE.$entity->statement('fragment.casilla_resultados_lista.44.7').$resultado_fila->id_casilla);

            $votos_total = $resultado_fila->suma + $votos_nulos + $no_registrados;
        ?>                                                  
                <tr>
                    <td><?php echo $funciones->llenarCasillatbl($resultado_fila->id_casilla); ?></td>
                    <td><?php echo 'Proceso Electoral: '.$resultado_fila->fecha_p; ?></td>
                    <td align="center"><?php echo '<b>'.$votos.'</b> Votos.'; ?></td>
                    <td align="center">
                        <?php echo 'Calculados:<b>'.$votos_total.'</b> '; ?>
                        <br>
                        <?php echo 'Acta:<b>'.$total_votos.'</b>'; ?>
                    </td>
                    <td align="center">
                    <strong>
                        <?php 
                        $porcentaje = $votos*100/$votos_total;
                        if($porcentaje > 50)
                            echo '<span style="color:green;">'.number_format($porcentaje, 1, '.', ',').'</span>';
                        else
                            echo '<span style="color:red;">'.number_format($porcentaje, 1, '.', ',').'</span>';
                        ?> %
                    </strong>
                    </td>
                    <td align="center">
                        <?php
                        $archivo = $entity->scalar($entity->statement('casilla_resultados_lista.70.7').$resultado_fila->id_casilla.$entity->statement('fragment.casilla_resultados_lista.70.8').$idprocesoE);
                        if ($archivo != null || $archivo != '') {
                        ?>
                        <a onclick="window.open('../archivos/actas_eleccion/<?php echo $archivo; ?>','Comprobante', 'width=600,height=900');" title="Ver Acta"><span class="glyphicon glyphicon-file"></span></a>
                        <?php } ?>
                    </td>
                
                </tr>
                <?php 
                    }
                ?>
       </tbody>
        <tfoot>
            <tr>
                <td colspan="6">
                    <div>
                      <ul class="pagination pagination-sm">
                        <?php
                            $pag = new Paginador();
                            // Configuramos la cantidad de registros por pagina, por defecto son 10.
                            // Debe de estar coordinado con la cantidad de registros traídos con la consulta MySQL.
                            $pag->setCantidadRegistros($limite);                                
                            // Configurar la cantidad de enlaces en la barra de navegación (por defecto son 10).
                            $pag->setCantidadEnlaces($cantenlaces);
                            //$pag->setMarcador('', '');
                            // Y mandamos a paginar desde la pagina actual y le pasamos tambien el total
                            // de registros de la consulta mysql.
                            $datos = $pag->paginar($pagina, $totalRegistros);
                            
                            if($datos) {

                                echo 'Pagina: ' .$pagina. ' de ' . $pag->getCantidadPaginas() . '<br />';
                                foreach ($datos as $enlace) {
                                    if($enlace['active'] == false){ 
                                    ?><li><a href="javascript:casilla_resultados_lista(<?php echo $enlace['numero'] ?>)"><?php echo $enlace['vista']; ?></a></li><?php
                                        }
                                    else{
                                    ?><li class="active"><span><?php echo $enlace['vista']; ?></span></li><?php
                                        }
                                    }
                                }

                        ?>
                      </ul>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>