                <!--  ARRIBA----------------------------------------------------------------------------------- -->
				  
				<?php				
				$pagina = isset($_GET['pag']) ? $funciones->limpia($_GET['pag']) : 1;
				$limite = 10;
				$cantenlaces = 7;
				$inicio = ($pagina - 1) * $limite;
				
				$id = $funciones->limpia(base64_decode($_GET['id'])); 
				$nombre = $entity->scalar("SELECT nombre FROM tblc_usuario WHERE id_usuario = ".$id);

				$ultimo_acceso = $entity->row("SELECT date_format(fecha_acceso, '%H:%i') as hora, date_format(fecha_acceso, '%d-%m-%Y') as fecha FROM tbl_sesion WHERE id_usuario = ".$id);
				?>
                    
                   <div class="pageheader">
                        <div class="media">
                            <div class="pageicon pull-left">
                                <i class="fa fa-database"></i>
                            </div>
                            <div class="media-body">
                                <ul class="breadcrumb">
                                    <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                                    <li>Usuarios</li>
                                </ul>
                                <h4>Bitácora del usuario: <?php echo $nombre ?> - Último acceso: <?php echo $ultimo_acceso['fecha'] ?> <?php echo $ultimo_acceso['hora'] ?> hrs.</h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA-------------------------------------------------------------------------------- -->
                    <div class="contentpanel" >
                      <!-- CONTENIDO ----------------------------------------------------------------------- -->
                      
		        		<div class="row">

                                    <div class="panel panel-default">

                                        <div class="panel-heading">
                                          
                                            <h4 class="panel-title"> Listado de acciones</h4>
                                            <p></p>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                
												<div class="panel-group" id="accordion2">
													<div class="panel panel-primary">
														<div class="panel-heading">
															<h4 class="panel-title">
																<a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
																   <div style="color:#FFFFFF" >Buscar</div>
																</a>
															</h4>
														</div>
														<div id="collapseOne2" class="panel-collapse collapse <?php if(isset($_POST['bus'])) echo "in"; ?>">
															<div class="panel-body">
															<table width="100%" border="0">
																	<tr>
																		<td width="100%">
																			 <div class="form-group">
																					<form id="form_busqueda">
																						<div class="form-group">
																							<label class="col-sm-4">Mes :</label>
																							<div class="col-sm-8">
																								<select name="mes_busqueda" class="form-control">
																			                    	<?php $funciones->getcombomes($mes_busqueda) ?>
																			                    </select>
																							</div>
																						</div>

																						<div class="form-group">
																							<label class="col-sm-4">Año :</label>
																							<div class="col-sm-8">
																								<select name="anio_busqueda" class="form-control">
																			                    	<?php $funciones->llenarcombomodifica("SELECT DISTINCT YEAR(fecha_acceso) as id, YEAR(fecha_acceso) as valor FROM tbl_sesion WHERE id_usuario=".$id,$anio_busqueda) ?>
																			                    </select>
																							</div>
																						</div>
																						
																						<input type="hidden" name="id"  id="id" value="<?php echo base64_encode($id) ?>"/>
																							
																						<input type="submit" class="btn btn-primary mr5" value="Buscar" />
																						<input type="button" class="btn btn-secundary mr5" onclick="window.location.href='bitacora?id=<?php echo base64_encode($id) ?>'" value="Cancelar">
																					</form>
																			  </div>
																		</td>
																	</tr>
																</table>
															</div>
														</div>
													</div><!-- panel -->
												</div><!-- panel-group -->
								                
                                             <!--fin formulariooooooo busquedaaaaaaaa---------------------------------------------------------------- -->
                                            
												<table id="basicTable" class="table table-striped table-bordered responsive">
													<thead class="">
														<tr>
															<th>Fecha</th>
															<th>SO</th>
															<th>Navegador</th>
															<th>IP</th>
															<th>Descripción</th>
														</tr>
													</thead>
											 
													<tbody>
													
													
													<?php
															$pagina = isset($_GET['pag']) ? $funciones->limpia($_GET['pag']) : 1;
															$limite = 10;
															$cantenlaces = 7;
															$inicio = ($pagina - 1) * $limite;

															$peticion_enlace = "&id=".base64_encode($id);
															$sentencia = "";

															if(isset($_GET['mes_busqueda'])){
																$mes_busqueda = $funciones->limpia($_GET['mes_busqueda']);
															    $sentencia .= " AND MONTH(log.fecha) = '".$mes_busqueda."'";
															    $peticion_enlace .= "&mes_busqueda=".$mes_busqueda;
															}
															else{
																$mes_busqueda = date('m');
															    $sentencia .= " AND MONTH(log.fecha) = '".$mes_busqueda."'";
															    $peticion_enlace .= "&mes_busqueda=".$mes_busqueda;
															}	

															if(isset($_GET['anio_busqueda'])){
																$anio_busqueda = $funciones->limpia($_GET['anio_busqueda']);
															    $sentencia .= " AND YEAR(log.fecha) = '".$anio_busqueda."'";
															    $peticion_enlace .= "&anio_busqueda=".$anio_busqueda;
															}
															else{
																$anio_busqueda = date('Y');
															    $sentencia .= " AND YEAR(log.fecha) = '".$anio_busqueda."'";
															    $peticion_enlace .= "&anio_busqueda=".$anio_busqueda;
															}

															$cadena = "SELECT ses.so, ses.navegador, ses.ip, log.descripcion, log.fecha, date_format(log.fecha, '%H:%i') as hora, date_format(log.fecha, '%Y-%m-%d') as fecha2 FROM tbl_log AS log INNER JOIN tbl_sesion AS ses ON log.id_sesion = ses.id_sesion WHERE ses.id_usuario = ".$id.$sentencia." LIMIT ".$inicio.",".$limite."";
															$cadena2 = "SELECT COUNT(log.id_log) FROM tbl_log AS log INNER JOIN tbl_sesion AS ses ON log.id_sesion = ses.id_sesion WHERE ses.id_usuario = ".$id.$sentencia;	
															
															$totalCirculares = $entity->scalar($cadena2);
															$resul_lista = $entity->objects($cadena);
															
															foreach($resul_lista as $resultado_fila){
															?>
													
															<tr>
																<td ><?php echo $funciones->fecha2($resultado_fila->fecha2)." ".$resultado_fila->hora ?></td>
																<td ><?php echo $resultado_fila->so ?></td>
																<td ><?php echo $resultado_fila->navegador ?></td>
																<td ><?php echo $resultado_fila->ip ?></td>
																<td ><textarea style="width:100%;"><?php echo $resultado_fila->descripcion ?></textarea></td>
															</tr>
															<?php 
																}
															?>
												   </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td colspan="7">
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
                                                                    $datos = $pag->paginar($pagina, $totalCirculares);
                                                                    
                                                                    if($datos) {
                                    
                                                                        echo 'Pagina: ' .$pagina. ' de ' . $pag->getCantidadPaginas() . '<br />';
                                                                        foreach ($datos as $enlace) {
                                                                            if($enlace['active'] == false){	
                                                                            ?><li><a href="?pag=<?php echo $enlace['numero'].$peticion_enlace ?>&act=1"><?php echo $enlace['vista']; ?></a></li><?php
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
												
                                        </div><!-- panel-body -->
                                    </div><!-- panel -->
                        </div><!-- row -->    
                                       
                    </div><!-- tab-pane -->
                                                     
                    
                </div><!-- tab-content -->
                                
                                
					