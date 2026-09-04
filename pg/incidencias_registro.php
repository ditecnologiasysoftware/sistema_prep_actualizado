<?php
require "../php/inicializandoDatosExterno.php";

$pagina = !empty($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;
if ($id_municipio != 0) {
	$query .= " and c.id_municipio = " . $id_municipio . "";
	$query_pe .= " and id_municipio = " . $id_municipio . "";
} elseif ($id_estado != 0) {
	$query .= " and m.id_estado = " . $id_estado . "";
	$query_pe .= " and id_estado = " . $id_estado . "";
}
?>
<!--FIN ARRIBA-------------------------------------------------------------------------------- -->
<div class="contentpanel">
	<!-- CONTENIDO ----------------------------------------------------------------------- -->
	<div class="row">
		<div style="widows:100%">
			<form id="enviar_formulario" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"></h4>
						<p></p>
					</div>
					<?php
					if (!empty($_POST['id'])) {
						$id = $funciones->limpia($_POST['id']);
						$row = $entity->row("SELECT r.*, m.id_estado FROM tbl_reporte as r INNER JOIN tblc_municipio as m ON r.id_municipio = m.id_municipio WHERE r.id_reporte = " . $id);
					}
					?>
					<div class="panel-body">
						<div class=" col-md-4">
							<div class="form-group" style="margin: 2px 5px;">
								<label>¿Quién reporta? :</label>
								<input type="text" name="nombre" id="nombre" class="form-control" required value="<?php if (!empty($_POST['id'])) echo $row['nombre']; ?>" />
							</div>
						</div>
						<?php if ($id_estado == 0) { ?>
							<div class="col-md-4">
								<div class="form-group" style="margin: 2px 5px;">
									<label>Estado</label>
									<div>
										<select class="select2-container form-control" name="id_estado" id="id_estado" required onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios.php')" style="width: 98%">
											<option value=""> - Seleccionar Estado - </option>
											<?php
											if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre", $row['id_estado']);
											else echo $funciones->llenarcombo("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre");
											?>
										</select>
									</div>
								</div>
							</div>
						<?php } else { ?>
							<input type="hidden" name="id_estado" id="id_estado" value="<?= $id_estado; ?>" />
						<?php } ?>

						<?php
						if ($id_municipio == 0) {
						?>
							<div class="col-md-4">
								<div class="form-group" style="margin: 2px 5px;">
									<label>Municipio:</label>
									<div>
										<select class="select2-container form-control" name="id_municipio" id="id_municipio" required>
											<?php
											if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = " . $row['id_estado'] . " ORDER BY nombre", $row['id_municipio']);
											?>
										</select>
									</div>
								</div>
							</div>
						<?php } else { ?>
							<input type="hidden" name="id_municipio" id="id_municipio" value="<?= $id_municipio; ?>" />
						<?php } ?>

						<div class="col-md-4">
							<div class="form-group" style="margin: 2px 5px;">
								<label>Casilla:</label>
								<div>
									<select class="select2-container form-control" name="id_casilla" id="id_casilla" required>
										<?php
										if (!empty($_POST['id']))
											echo $funciones->llenarcombomodificaCasilla("SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0" . $query . "  ORDER BY c.seccion ASC, c.tipo ASC, c.nombre ASC", 0);
										else
											echo $funciones->llenarcombomodificaCasilla("SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0" . $query . "  ORDER BY c.seccion ASC, c.tipo ASC, c.nombre ASC", $row['id_casilla']);

										?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group" style="margin: 2px 5px;">
								<label>Medio :</label>
								<div>
									<select class="select2-container form-control" name="tipo_registro" id="tipo_registro" required style="width: 98%">
										<?php
										if (!empty($_POST['id'])) echo $funciones->getcombotiporegistro($row['tipo_registro']);
										else echo $funciones->getcombotiporegistro(1);
										?>
									</select>
								</div>
							</div>
						</div>
						<div style="clear: both;"></div>
						<div class="col-md-6">
							<div class="form-group" style="margin: 2px 5px;">
								<label>Etiquetas:</label>
								<select class="select2-container form-control" name="etiquetas[]" id="etiquetas" style="width:100%" required>
									<option value=""> - Seleccionar Estado - </option>
									<?php
									$sqletiquetas = "SELECT id_etiqueta as id, etiqueta as valor FROM tblc_etiqueta ORDER BY etiqueta";
									if (!empty($_POST['id'])) {
										$etiquetas = array();
										$etq = $entity->objects("SELECT id_etiqueta FROM tbl_reporte_etiqueta WHERE id_reporte = " . $id);
										foreach ($etq as $value) {
											array_push($etiquetas, $value->id_etiqueta);
										}
										echo $funciones->llenarcombomodificaarreglo($sqletiquetas, $etiquetas);
									} else {
										echo $funciones->llenarcombo($sqletiquetas);
									}
									?>
								</select>
							</div>
						</div>
						<div style="clear: both;"></div>
						<div class=" col-md-12">
							<div class="form-group" style="margin: 2px 5px;">
								<label>Descripción :</label>
								<textarea name="descripcion" required class="form-control"><?php if (!empty($_POST['id'])) echo $row['descripcion']; ?></textarea>
							</div>
						</div>
						<div style="clear: both;"></div>
						<div class="form-group" style="margin: 2px 5px;">
							<label class="col-sm-2">Archivo anexo :</label>
							<div class="col-sm-6">
								<input type="file" id="archivo" name="archivo">
								<?php
								if (!empty($_POST['id'])) {
									if ($row['foto'] != "")
										echo '<img src="archivos/' . $row['foto'] . '" width="180px">';
								}
								?>
							</div>
						</div>
					</div><!-- panel-body -->
					<div class="panel-footer">
						<button class="btn btn-primary mr5"><?php if (!empty($_POST['id'])) echo "Editar";
															else echo "Guardar"; ?></button>
						<?php
						$redi = "window.location.href='incidencia'";
						if (!empty($_POST['id'])) echo '<button class="btn btn-danger mr5" onclick="' . $redi . '">Cancelar</button>';
						?>
					</div><!-- panel-footer -->
				</div><!-- panel-default -->
				<input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "132";
																		else echo "133"; ?>" />
				<input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />
				<input type="hidden" name="direccion" id="direccion" value="<?php if (isset($_POST['id'])) echo $row['direccion'];
																			else echo ''; ?>" />
				<input type="hidden" name="tipo_reporte" id="tipo_reporte" value="<?php if (isset($_POST['id'])) echo $row['tipo_reporte'];
																					else echo 1; ?>" />
			</form>
			<div id="cargando"></div>
		</div><!-- col-md-6 -->
		<!--FIN DE CONTENIDO-------------------------------------------------------->
	</div><!-- contentpanel -->
</div><!-- tab-content -->