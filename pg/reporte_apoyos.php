                <!--  ARRIBA----------------------------------------------------------------------------------- -->
				  
				<?php				
				$pagina = isset($_GET['pag']) ? $funciones->limpia($_GET['pag']) : 1;
				$limite = 10;
				$cantenlaces = 7;
				$inicio = ($pagina - 1) * $limite;
				
				?>
                    
                   <div class="pageheader">
                        <div class="media">
                            <div class="pageicon pull-left">
                                <i class="fa fa-database"></i>
                            </div>
                            <div class="media-body">
                                <ul class="breadcrumb">
                                    <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                                    <li>Reporte de Apoyos</li>
                                </ul>
                                <h4>Reporte de Apoyos</h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA-------------------------------------------------------------------------------- -->
                    <div class="contentpanel" >
                      <!-- CONTENIDO ----------------------------------------------------------------------- -->
                      

                            <div style="widows:100%">
                            
                                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="php/excel_apoyos.php" target="_blank">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                           
                                            <h4 class="panel-title">Reporte de apoyos para exportar a excel</h4>
                                            <p></p>
                                        </div>
										<div class="panel-body">

											<div class="form-group">
												<label class="col-sm-2">Estatus:</label>
												<div class="col-sm-7">
													<select name="estatus" class="form-control">
                                                		<option value="0">Todos</option>
                                                		<?php
                                                		echo $funciones->getComboordenpago(0);
                                                		?>
                                                    </select>
												</div>
											</div>

											<div class="form-group col-sm-12">	
												<label class="col-sm-2">Rango de fechas :</label>
												<div class="col-sm-3">
													<input type="text" name="fecha_inicio"  id="fecha" class="form-control" value="" placeholder="Fecha Inicio"/>
												</div>
												<div class="col-sm-3">
													<input type="text" name="fecha_termino"  id="fecha2" class="form-control" value="" placeholder="Fecha Final"/>
												</div>	
											</div>

											<div class="form-group">
												<label class="col-sm-2">Nombre del beneficiario:</label>
												<div class="col-sm-7">
													<input type="text" name="nombre"  id="nombre" class="form-control"/>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-2">Folio de apoyo:</label>
												<div class="col-sm-7">
													<input type="text" name="folio"  id="folio" class="form-control"/>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-2">Sexo:</label>
												<div class="col-sm-3">
													<select name="estatus" class="form-control">
                                                		<option value="0">Todos</option>
                                                		<?php
                                                		echo $funciones->getcombosexo(0);
                                                		?>
                                                    </select>
												</div>
											</div>

											<div class="form-group col-sm-6">	
												<label class="col-sm-3">Estado :</label>
												<div class="col-sm-7">
													<select name="estado" id="estado" class="form-control" onchange="combodependiente('estado','municipio','combo_dependiente/municipios2.php')" required>
                                                		<option value="0">Todos los estados</option>
                                                		<?php echo $funciones->llenarcombo($entity->statement('reporte_apoyos.95.1'));?>
                                                    </select>
												</div>
											</div>

											<div class="form-group col-sm-6">	
												<label class="col-sm-4">Municipio :</label>
												<div class="col-sm-7">
													<select name="municipio" id="municipio" class="form-control" required>
														<option value="0">Todos los municipios</option>
                                                    </select>
												</div>	
											</div>

											<div class="form-group col-sm-6">	
												<label class="col-sm-3">Usuario captura :</label>
												<div class="col-sm-7">
													<select name="captura" id="captura" class="form-control" required>
                                                		<option value="0">Todos los usuarios</option>
                                                		<?php echo $funciones->llenarcombo($entity->statement('reporte_apoyos.114.2'));?>
                                                    </select>
												</div>
											</div>

											<div class="form-group col-sm-6">	
												<label class="col-sm-3">Usuario autoriza :</label>
												<div class="col-sm-7">
													<select name="autoriza" id="autoriza" class="form-control" required>
                                                		<option value="0">Todos los usuarios</option>
                                                		<?php echo $funciones->llenarcombo($entity->statement('reporte_apoyos.124.3'));?>
                                                    </select>
												</div>
											</div>			
													
								  </div><!-- panel-body -->
									<div class="panel-footer">
										<button class="btn btn-danger mr5">Exportar a Excel</button>
									</div><!-- panel-footer -->
								</div><!-- panel-default -->

                                </form>
                                <div id="cargando"></div>
                                
                            <!--FIN DE CONTENIDO-------------------------------------------------------->
                        
                   	</div><!-- contentpanel -->
                                        
                </div><!-- tab-pane -->

					