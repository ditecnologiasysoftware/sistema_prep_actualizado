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
    $autoriza_apoyo = $_SESSION['autoriza_apoyo'];
    $autoriza_ordenpago = $_SESSION['autoriza_ordenpago'];

    require ("../php/clase_variables.php");
    require ("../php/clase_mysql.php");
    require ("../php/clase_funciones.php");
    include_once ('../php/clase_paginador.php');
    date_default_timezone_set('America/Mexico_City');
    
    $funciones = new Funciones();
    //LLAMAMOS A LA CLASE CONEXION
    $entity = Entity::createInstance();    
    	$pagina = isset($_GET['pag']) ? $funciones->limpia($_GET['pag']) : 1;
		$limite = 10;
		$cantenlaces = 7;
		$inicio = ($pagina - 1) * $limite;

		$peticion_enlace = "";
		$sentencia = "";
		if(isset($_GET['c'])){

            if(isset($_GET['q'])){
                if ($_GET['q'] != '' || $_GET['q'] != null ) {
                     $fecha = date("Y-m-d",strtotime($_GET['q']));
                     $sentencia .= " AND fecha_acceso LIKE '%".$fecha."%'";
                     $peticion_enlace .= "&fecha_acceso LIKE '%".$fecha."%'";
                }               
            }
			$idRepresentante = $funciones->limpia(base64_decode($_GET['c']));

			$cadena = "SELECT tbl_representante_movil.estatus as estatus_disp, tbl_representante_movil.*, tblc_representante.* FROM tblc_representante INNER JOIN tbl_representante_movil WHERE tbl_representante_movil.id_representante = tblc_representante.id_representante  and tbl_representante_movil.id_representante =".$idRepresentante.$sentencia." ORDER BY tbl_representante_movil.id_representante_movil DESC LIMIT ".$inicio.','.$limite;

			$cadena2 = "SELECT COUNT(tblc_representante.id_representante) FROM tblc_representante INNER JOIN tbl_representante_movil WHERE tbl_representante_movil.id_representante = tblc_representante.id_representante and tbl_representante_movil.id_representante =".$idRepresentante.$sentencia;	
			$totalRegistros = $entity->scalar($cadena2);
			$cadenaResultado = $entity->objects($cadena);	
			
            $representante = ' del representante - <b>'.$entity->scalar("SELECT nombre FROM tblc_representante WHERE id_representante =".$idRepresentante).'</b>';													

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
        <link href="../assets/css/morris.css" rel="stylesheet">
        <link href="../assets/css/select2.css" rel="stylesheet" />

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->

       
        <script src="../assets/js/jquery-1.11.1.min.js"></script>
        <script src="../assets/js/jquery-migrate-1.2.1.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/modernizr.min.js"></script>
        <script src="../assets/js/pace.min.js"></script>
        <script src="../assets/js/retina.min.js"></script>
        <script src="../assets/js/jquery.cookies.js"></script>
        <script src="../assets/js/jquery-ui-1.10.3.min.js"></script>

     <!--  COMENTE POR QUE FALLA EL FANCYBOX  <script src="assets/js/flot/jquery.flot.min.js"></script>
        <script src="assets/js/flot/jquery.flot.resize.min.js"></script>
        <script src="assets/js/flot/jquery.flot.spline.min.js"></script> -->
        <script src="../assets/js/jquery.sparkline.min.js"></script>
        <script src="../assets/js/morris.min.js"></script>
        <script src="../assets/js/raphael-2.1.0.min.js"></script>
        <script src="../assets/js/bootstrap-wizard.min.js"></script>
        <script src="../assets/js/select2.min.js"></script>
        <script src="../assets/js/jquery.validate.min.js"></script>
        <script src="../assets/js/funciones.js"></script>

        <!--<script src="common/editor.js"></script>-->
        <script src="../editor/ckeditor.js"></script>

        
        <script src="../assets/js/custom.js"></script>
        <!-- COMENTE POR QUE FALLA EL FANCYBOX  <script src="assets/js/dashboard.js"></script> -->
        
        <script src="../assets/js/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>

      
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
                                           
                                            <h4 class="panel-title">Movil <?php echo $representante; ?></h4>
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
																   <div style="color:#FFFFFF" >Buscar por número de casilla</div>
																</a>
															</h4>
														</div>
														<div id="collapseOne2" class="panel-collapse collapse <?php if(isset($_POST['bus'])) echo "in"; ?>">
															<div class="panel-body">
															<table width="100%" border="0">
																	<tr>
																		<td width="100%">
																			 <div class="form-group">
																					<label class="col-sm-3">Fecha de Acceso:</label>
																					<form id="form_busqueda">
																						<div class="col-sm-6">
                                                                                          <input type="hidden" name="c" value="<?php echo base64_encode($idRepresentante);?>">

                                                                                          <input type="text" name="fecha" id="fecha" class="form-control" required value="<?php if(isset($_GET['q'])) echo date("d-m-Y",strtotime($row['q'])) ?>" />

																							
																						</div>
																						<input type="submit" class="btn btn-primary mr5" value="Buscar" />
																						<input type="button" class="btn btn-secundary mr5" onclick="window.location.href='bitacora_representante.php?c=<?php echo base64_encode($idRepresentante);?>'" value="Cancelar">
																						
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
															<th>Sistema Operativo</th>
                                                            <th>Versión</th>
                                                            <th>Modelo</th>
                                                            <th>Ultimo Acceso</th>
                                                            <th>Número de Acceso</th>
                                                            <th>Estatus Movil</th>
														</tr>
													</thead>											 
													<tbody>
													<?php 													
													 foreach($cadenaResultado as $resultado_fila){	
													?>													
            										  <tr>
                                                            <td>
                                                                <?php echo $resultado_fila->so; ?>
                                                            </td>
                                                           
                                                            <td align="center">
                                                                <?php echo $resultado_fila->version; ?>
                                                            </td>
                                                            <td align="center">
                                                                <?php echo $resultado_fila->modelo; ?>
                                                            </td>
                                                            <td align="center">
                                                                <?php echo $funciones->fecha4(substr($resultado_fila->fecha_acceso, 0, 10))." ".substr($resultado_fila->fecha_acceso, 11, 5)." hrs."; ?>                                              
                                                            </td>
                                                            <td align="center">
                                                                <?php echo $resultado_fila->num_accesos; ?>
                                                            </td>
                                                            <td align="center">                                                    
                                                             <select  class="form-control" id="accesoapp" name="accesoapp" onchange="cambioestatusappModal(this,<?php echo $resultado_fila->id_representante_movil ?>,34,<?php echo $resultado_fila->id_representante ?>);">
                                                            <?php echo $funciones->getComboVisible2($resultado_fila->estatus_disp); ?>
                                                             </select>
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
        <script type="text/javascript">
            jQuery("select, #select-multi").select2();
            
            jQuery("#select-basic, #select-multi").select2();
            jQuery('#select-search-hide').select2({
                minimumResultsForSearch: -1
            });
            
            function format(item) {
                return '<i class="fa ' + ((item.element[0].getAttribute('rel') === undefined)?"":item.element[0].getAttribute('rel') ) + ' mr10"></i>' + item.text;
            }
            
            // This will empty first option in select to enable placeholder
            //jQuery('select option:first-child').text('');
            
            jQuery("#icono").select2({
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function(m) { return m; }
            });
            
            jQuery("#menu2").select2({
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function(m) { return m; }
            });
            
            $.mask.definitions['H']='[01]';
            $.mask.definitions['h']='[0123456789]';
            $.mask.definitions['N']='[012345]';
            $.mask.definitions['n']='[0123456789]';
            $("#hora_inicio").mask("Hh:Nn");
            $("#hora_final").mask("Hh:Nn");
            $('#fecha').datepicker({dateFormat: 'dd-m-yy', changeMonth: true, changeYear: true, yearRange: '-100:+0'});
            $('#fecha2').datepicker({dateFormat: 'dd-m-yy', changeMonth: true, changeYear: true, yearRange: '-100:+0'});
        </script>
    </body>
</html>
