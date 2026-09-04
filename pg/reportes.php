<script>
	window.onload = function(){
		reporte_listado();
	}
</script>
<div class="pageheader">
	<div class="media">
		<div class="pageicon pull-left">
			<i class="fa fa-database"></i>
		</div>
		<div class="media-body">
			<div class="row prep-page-heading-row">
				<div class="col-sm-8">
					<ul class="breadcrumb">
						<li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
						<li>Incidencias</li>
					</ul>
					<h4>Incidencias</h4>
				</div>

				<div class="col-sm-4 text-right prep-page-actions"><input type="button" class="btn btn-success mr5" value="+ Registrar Reporte" onclick="reporte_registro()"/></div>
			</div>

		</div>
	</div>
</div>
<div class="contentpanel prep-reports-module">
	<div class="row">
		<div class="col-md-12">
			<div class="panel-group" id="accordion2">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
								<div style="color:#FFFFFF">Formulario de búsqueda</div>
							</a>
						</h4>
					</div>
					<div id="collapseOne2" class="panel-collapse collapse <?php if (!empty($_POST['bus'])) echo "in"; ?>">
						<div class="panel-body">
							<form id="form_busqueda" class="prep-search-form">
								<div class="row">
												<?php
												if ($id_estado == 0) {
												?>
													<div class="form-group col-sm-4">
														<div style="margin: 2px 5px;">
															<label>Estado</label>
															<div>
																<select class="select2-container" name="estado_busqueda" id="estado_busqueda" onchange="combodependiente('estado_busqueda', 'municipio_busqueda', 'combo_dependiente/municipios2.php')" required style="width: 98%">
																	<option value="0">Todos los Estados</option>
																	<?php
																	if (!empty($_POST['estado_busqueda'])) echo $funciones->llenarcombomodifica("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre", $_GET['estado_busqueda']);
																	else echo $funciones->llenarcombo("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre");
																	?>
																</select>
															</div>
														</div>
													</div>
												<?php
												} else{
													echo '<input type="hidden" name="estado_busqueda" id="estado_busqueda" value="'.$id_estado.'" />';
												}

												if ($id_municipio == 0) {
												?>
													<div class="form-group col-sm-4">
														<div style="margin: 2px 5px;"><label>Municipio:</label>
															<div>
																<select class="select2-container"name="municipio_busqueda" id="municipio_busqueda" required style="width: 98%" onchange="combodependiente('municipio_busqueda', 'casilla_busqueda', 'combo_dependiente/casillas.php')">
																	<option value="0">Todos los Municipios</option>
																	<?php
																	if ($id_estado != 0) echo $funciones->llenarcombo("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = " . $id_estado . " ORDER BY nombre");
																	?>
																</select>
															</div>
														</div>
													</div>
												<?php
												} else{
													echo '<input type="hidden" name="municipio_busqueda" id="municipio_busqueda" value="'.$id_municipio.'" />';
												}
												?>
												<div class="form-group col-sm-4">
													<div style="margin: 2px 5px;"><label>Tipo reporte:</label>
														<div>
															<select class="select2-container" name="servicio_busqueda" id="servicio_busqueda" required style="width: 98%">
																<option value="0">Todas</option>
																<?php
																echo $funciones->getcombotiposervicio(0);
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group col-sm-2">
													<div style="margin: 2px 5px;"><label>Hora inicio:</label>
														<div>
															<input type="text" name="hora_busqueda" id="hora_inicio" class="form-control" value="" />
														</div>
													</div>
												</div>
												<div class="form-group col-sm-2">
													<div style="margin: 2px 5px;"><label>Hora Fin:</label>
														<div>
															<input type="text" name="hora2_busqueda" id="hora_final" class="form-control" value="" />
														</div>
													</div>
												</div>
												<div class="form-group col-sm-4">
													<div style="margin: 2px 5px;"><label>Tipo registro:</label>
														<div>
															<select class="select2-container" name="tipo_busqueda" id="tipo_busqueda" required style="width: 98%">
																<option value="0">Todas</option>
																<?php
																echo $funciones->getcombotiporegistro(0);
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group col-sm-4">
													<div style="margin: 2px 5px;"><label>Etiqueta:</label>
														<div>
															<select class="select2-container" name="etiqueta" id="etiqueta" required style="width: 98%">
																<option value="0">Todas las etiquetas</option>
																<?php
																if (!empty($_POST['etiqueta'])) echo $funciones->llenarcombomodifica("SELECT id_etiqueta as id, etiqueta as valor FROM tblc_etiqueta ORDER BY etiqueta");
																else echo $funciones->llenarcombo("SELECT id_etiqueta as id, etiqueta as valor FROM tblc_etiqueta ORDER BY etiqueta");
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group col-sm-4">
													<div style="margin: 2px 5px;"><label class="col-sm-4">Folio:</label>
														<div>
															<input type="text" name="folio_busqueda" id="folio_busqueda" class="form-control" value="<?php if (!empty($_GET['folio_busqueda'])) echo $_GET['folio_busqueda']; ?>" />
														</div>
													</div>
												</div>
												<div class="form-group col-sm-4">
													<div style="margin: 2px 5px;"><label>Casilla:</label>
														<div>
															<select class="select2-container" name="casilla_busqueda" id="casilla_busqueda" required style="width: 98%">
																<option value="0">Todas Casilla</option>
																<?php
																if ($id_municipio != 0) echo $funciones->llenarcombomodificaCasilla("SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0 AND c.id_municipio = " . $id_municipio . "  ORDER BY c.seccion ASC, c.tipo ASC, c.nombre ASC",0);
																?>
															</select>
														</div>
													</div>
												</div>
								</div>
								<div class="prep-form-actions">
												<input type="hidden" name="pagina" id="pagina" value="1" />
												<input type="button" class="btn btn-primary mr5" value="Buscar" onclick="reporte_listado()"/>
												<input type="button" class="btn btn-danger mr5"  value="Cancelar" onclick="location.href='reportes'" >
								</div>
							</form>
						</div>
					</div>
				</div><!-- panel -->
			</div><!-- panel-group -->
			<!--fin formulariooooooo busquedaaaaaaaa---------------------------------------------------------------- -->
		</div>
	</div>

	<div id="contenido"></div>	
</div>






