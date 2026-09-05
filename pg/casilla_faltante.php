<?php
    @session_start();
    $id_usuario = $_SESSION['id_usuario'];
    $id_estado = $_SESSION['id_estado'];
    $id_municipio = $_SESSION['id_municipio'];
    $autentificado = $_SESSION['autentificado'];
    $eliminar = $_SESSION['eliminar'];
    $editar = $_SESSION['editar'];
    $nombre = $_SESSION['nombre'];
    $id_sesion_sistema = $_SESSION['id_sesion_sistema'];

    require ("../php/clase_variables.php");
    require ("../php/clase_mysql.php");
    require ("../php/clase_funciones.php");
    include_once ('../php/clase_paginador.php');
    date_default_timezone_set('America/Mexico_City');
    
    $funciones = new Funciones();
    //LLAMAMOS A LA CLASE CONEXION
    $entity = Entity::createInstance();    
    	$pagina = isset($_GET['pag']) ? $funciones->limpia($_GET['pag']) : 1;
		$limite = 15;
		$cantenlaces = 7;
		$inicio = ($pagina - 1) * $limite;

		$peticion_enlace = "";
		$sentencia = "";
		if(isset($_GET['c'])){

            if(isset($_GET['q'])){
                if ($_GET['q'] != '' || $_GET['q'] != null ) {
                     $sentencia .= $entity->statement('fragment.casilla_faltante.32.1').$_GET['q'];
                     $peticion_enlace .= "&seccion=".$_GET['q'];
                }               
            }
			$idprocesoE = $entity->scopedProcessId($funciones->limpia(base64_decode($_GET['c'])));
            $peticion_enlace .= "&c=".$_GET['c'];


            $pe = $entity->row($entity->statement('casilla_faltante.40.1').$idprocesoE);
            $tipo_eleccion = $entity->row($entity->statement('casilla_faltante.41.2').$pe['id_tipo_eleccion']);
            
            switch ($tipo_eleccion['tipo']) {
              case '1': //FEDERAL
                $totalCasillas = $entity->scalar($entity->statement('casilla_faltante.45.3'));
                break;

              case '2'://ESTATAL
                $totalCasillas = $entity->scalar($entity->statement('casilla_faltante.49.4').$pe['id_estado']);
                break;

              case '3'://MUNICIPAL
                $cadena2 = $entity->statement('casilla_faltante.municipal_count_suffix').$pe['id_municipio'].
                    $entity->statement('casilla_faltante.municipal_missing_suffix').$idprocesoE.") = 0";
                $cadena = $entity->statement('casilla_faltante.municipal_list_suffix').$pe['id_municipio'].
                    $entity->statement('casilla_faltante.municipal_missing_suffix').$idprocesoE.") = 0";

                break;
            }

            $totalRegistros = $entity->scalar($cadena2);
			$cadenaResultado = $entity->objects($cadena);	

		}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Sistema administrador</title>

        <link href="../assets/css/style.default.css" rel="stylesheet">
        <!-- <link href="../fancybox/jquery.fancybox.css" rel="stylesheet" /> -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <script src="../assets/js/jquery-1.11.1.min.js"></script>
        <?php if (!empty($_ENV['GOOGLE_MAPS_API_KEY'])): ?>
            <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo rawurlencode($_ENV['GOOGLE_MAPS_API_KEY']); ?>" defer></script>
        <?php endif; ?>

        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/jquery-ui-1.10.3.min.js"></script>  
		<!-- <script src="../fancybox/jquery.fancybox.js"></script> -->
      
    </head>

    <body>
         		<!-- <div class="pageheader">
                        <div class="media">
                            <div class="pageicon pull-left">
                                <i class="fa fa-database"></i>
                            </div>
                            <div class="media-body">
                                <ul class="breadcrumb">
                                    <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                                    <li>Resultado de Casillas</li>
                                </ul>
                                <h4>Resultado de Casillas</h4>
                            </div>
                        </div>
                    </div> -->
                    
                    <!--FIN ARRIBA-------------------------------------------------------------------------------- -->
                    <div class="contentpanel">
                      <!-- CONTENIDO ----------------------------------------------------------------------- -->
                        <div class="row">
                          
                            <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                           
                                            <h4 class="panel-title">Casillas sin capturar</h4>
                                            <p></p>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                            
                                             <!--formulariooooooo busquedaaaaaaaa------------------------------------------------------------------>
                                            
												<div class="panel-group" id="accordion2">
													<div class="panel panel-primary">
														<div class="panel-heading">
															<h4 class="panel-title">
																<a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
																   <div style="color:#FFFFFF" >Buscar por Sección</div>
																</a>
															</h4>
														</div>
														<div id="collapseOne2" class="panel-collapse collapse <?php if(isset($_POST['bus'])) echo "in"; ?>">
															<div class="panel-body">
															<table width="100%" border="0">
																	<tr>
																		<td width="100%">
																			 <div class="form-group">
																					<label class="col-sm-3">Número de sección:</label>
																					<form id="form_busqueda">
																						<div class="col-sm-6">
																						  <input type="hidden" name="c" value="<?php echo base64_encode($idprocesoE);?>">
                                                                                          <input type="hidden" name="cand" value="<?php echo base64_encode($idcandidatoP);?>">

																							<input type="text" name="q" id="q" class="form-control" value="<?php if(isset($_GET['q'])) echo $_GET['q']; ?>"/>
																							
																						</div>
																						<input type="submit" class="btn btn-primary mr5" value="Buscar" />
																						<input type="button" class="btn btn-secundary mr5" onclick="window.location.href='casilla_resultados.php?c=<?php echo base64_encode($idprocesoE);?>&cand=<?php echo base64_encode($idcandidatoP);?>'" value="Cancelar">
																						
																					</form>
																			  </div>
																		</td>
																	</tr>
																</table>
															</div>
														</div>
													</div><!-- panel -->
												</div><!-- panel-group -->
								                
                                             <!--fin formulariooooooo busquedaaaaaaaa------------------------------------------------------------------>
                                           											 
											 <div id="div_buscar">
												<table id="basicTable" class="table table-striped table-bordered responsive">
													<thead class="">
														<tr>
                                                            <th>Sección</th>
                                                            <th>Casilla</th>
														</tr>
													</thead>											 
													<tbody>
													<?php 													
													 foreach($cadenaResultado as $resultado_fila){
													?>													
															<tr>
                                                                <td><?php echo $resultado_fila->seccion; ?></td>
                                                                <td><?php echo $funciones->llenarCasillatbl($resultado_fila->id_casilla); ?></td>
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
                                                                            ?><li><a href="?pag=<?php echo $enlace['numero'].$peticion_enlace ?>"><?php echo $enlace['vista']; ?></a></li><?php
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
											 </div>
																								
                                            </div><!-- form-group -->
                                        </div><!-- panel-body -->
                                    </div><!-- panel -->
                            </div><!-- col-md-12 -->
                        </div><!-- row -->    
                                
                                <!--FIN DE CONTENIDO-------------------------------------------------------->
                        
                    </div><!-- contentpanel -->
        
    </body>
</html>
