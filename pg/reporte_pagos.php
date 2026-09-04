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
                                    <li>Reporte de pagos a proveedores</li>
                                </ul>
                                <h4>Reporte de pagos a proveedores</h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA-------------------------------------------------------------------------------- -->
                    <div class="contentpanel" >
                      <!-- CONTENIDO ----------------------------------------------------------------------- -->
                      

                            <div style="widows:100%">
                            
                                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="php/excel_pagos.php" target="_blank">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                           
                                            <h4 class="panel-title">Reporte de pagos a proveedores para exportar a excel</h4>
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
												<label class="col-sm-2">Rango de fechas de pago:</label>
												<div class="col-sm-3">
													<input type="text" name="fecha_inicio"  id="fecha" class="form-control" value="" placeholder="Fecha Inicio"/>
												</div>
												<div class="col-sm-3">
													<input type="text" name="fecha_termino"  id="fecha2" class="form-control" value="" placeholder="Fecha Final"/>
												</div>	
											</div>

											<div class="form-group col-sm-12">	
												<label class="col-sm-2">Proveedor :</label>
												<div class="col-sm-9">
													<select name="id_proveedor" id="id_proveedor" class="form-control" required>
                                                		<option value="0">Todos los proveedores</option>
                                                		<?php 
                                                		echo $funciones->llenarcombo("select id_proveedor as id, nombre as valor from tblc_proveedor ORDER BY nombre");
                                                		?>
                                                    </select>
												</div>
											</div>


										  	<div class="form-group col-sm-12">	
												<label class="col-sm-2">Partida :</label>
													<div class="col-sm-6">
														<select name="id_partida" id="id_partida" class="form-control" required>
	                                                		<option value="0">Todas las partidas</option>
	                                                		<?php 
	                                                		echo $funciones->llenarcombo("select id_partida as id, nombre as valor from tblc_partida ORDER BY nombre");
	                                                		?>
	                                                    </select>
													</div>	
												</div>

											  	<div class="form-group col-sm-12">	
												<label class="col-sm-2">Concepto de pago :</label>
													<div class="col-sm-6">
														<select name="id_concepto_pago" id="id_concepto_pago" class="form-control" required>
	                                                		<option value="0">Todos los conceptos de pago</option>
	                                                		<?php 
	                                                		echo $funciones->llenarcombo("select id_concepto_pago as id, nombre as valor from tblc_concepto_pago ORDER BY nombre");
	                                                		?>
	                                                    </select>
													</div>	
												</div>

											  	<div class="form-group col-sm-12">	
													<label class="col-sm-2">Forma de pago:</label>
													<div class="col-sm-6">
														<select name="id_forma_pago" id="id_forma_pago" class="form-control" required>
	                                                		<option value="0">Todas las formas de pago</option>
	                                                		<?php
	                                                			echo $funciones->llenarcombo("select id_forma_pago as id, nombre as valor from tblc_forma_pago ORDER BY nombre");
	                                                		?>
	                                                    </select>
													</div>	
												</div>

											<div class="form-group">
												<label class="col-sm-2">Folio:</label>
												<div class="col-sm-7">
													<input type="text" name="folio"  id="folio" class="form-control"/>
												</div>
											</div>

											<div class="form-group col-sm-6">	
												<label class="col-sm-3">Usuario captura :</label>
												<div class="col-sm-7">
													<select name="captura" id="captura" class="form-control" required>
                                                		<option value="0">Todos los usuarios</option>
                                                		<?php echo $funciones->llenarcombo("select id_usuario as id, nombre as valor from tblc_usuario");?>
                                                    </select>
												</div>
											</div>

											<div class="form-group col-sm-6">	
												<label class="col-sm-3">Usuario autoriza :</label>
												<div class="col-sm-7">
													<select name="autoriza" id="autoriza" class="form-control" required>
                                                		<option value="0">Todos los usuarios</option>
                                                		<?php echo $funciones->llenarcombo("select id_usuario as id, nombre as valor from tblc_usuario WHERE autoriza_apoyo = 1");?>
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

					