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
                                    <li>Reporte de cheques</li>
                                </ul>
                                <h4>Reporte de cheques</h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA-------------------------------------------------------------------------------- -->
                    <div class="contentpanel" >
                      <!-- CONTENIDO ----------------------------------------------------------------------- -->
                      

                            <div style="widows:100%">
                            
                                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="php/excel_cheques.php" target="_blank">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                           
                                            <h4 class="panel-title">Reporte de cheques para exportar a excel</h4>
                                            <p></p>
                                        </div>
										<div class="panel-body">

											<div class="form-group col-sm-12">	
												<label class="col-sm-2">Rango de fechas de cheques:</label>
												<div class="col-sm-3">
													<input type="text" name="fecha_inicio"  id="fecha" class="form-control" value="" placeholder="Fecha Inicio"/>
												</div>
												<div class="col-sm-3">
													<input type="text" name="fecha_termino"  id="fecha2" class="form-control" value="" placeholder="Fecha Final"/>
												</div>	
											</div>

											<div class="form-group col-sm-12">	
												<label class="col-sm-2">Bancos :</label>
												<div class="col-sm-6">
													<select name="id_banco" id="id_banco" class="form-control" required onchange="combodependiente('id_banco', 'id_cuenta_bancaria', 'combo_dependiente/cuentas.php')">
                                                		<option value="0">Todos los bancos</option>
                                                		<?php 
                                                		echo $funciones->llenarcombo($entity->statement('reporte_cheques.58.1'));
                                                		?>
                                                    </select>
												</div>
											</div>

											<div class="form-group col-sm-12">	
												<label class="col-sm-2">Cuenta bancaria :</label>
												<div class="col-sm-6">
													<select name="id_cuenta_bancaria" id="id_cuenta_bancaria" class="form-control" required>
                                                		<option value="0">Todas las cuentas bancarias</option>
                                                    </select>
												</div>
											</div>

											<div class="form-group col-sm-12">	
												<label class="col-sm-2">Tipo operación:</label>
												<div class="col-sm-9">
													<select name="tipo" id="tipo" class="form-control" required>
                                                		<option value="0">Todos los tipos</option>
                                                		<?php 
                                                		echo $funciones->getcombotipocheque(0);
                                                		?>
                                                    </select>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-2">Número de cheque:</label>
												<div class="col-sm-7">
													<input type="text" name="folio"  id="folio" class="form-control"/>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-2">Nombre del beneficiario:</label>
												<div class="col-sm-7">
													<input type="text" name="nombre"  id="nombre" class="form-control"/>
												</div>
											</div>

											<div class="form-group col-sm-6">	
												<label class="col-sm-3">Usuario captura :</label>
												<div class="col-sm-7">
													<select name="captura" id="captura" class="form-control" required>
                                                		<option value="0">Todos los usuarios</option>
                                                		<?php echo $funciones->llenarcombo($entity->statement('reporte_cheques.104.2'));?>
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

					