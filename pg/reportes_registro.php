<?php
require "../php/inicializandoDatosExterno.php";
if (!empty($_POST['id'])) {
	$id = $funciones->limpia($_POST['id']);
	$row = $entity->row($entity->statement('reportes_registro.5.1') . $id);
}
?>

	<div class="row">
		<div style="widows:100%">
			<form id="enviar_formulario" class="form-horizontal" method="post" action="php/subir.php">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"></h4>
						<p>Formulario de registro</p>
					</div>
					
					<div class="panel-body">

						<div class=" col-md-6">
							<div class="form-group" style="margin: 2px 5px;">
								<label>¿Quién reporta? :</label>
								<input type="text" name="nombre" id="nombre" class="form-control" required value="<?php if (isset($id)) echo $row['nombre']; ?>" />
							</div>
						</div>

						<?php
						if ($id_estado == 0) { ?>
		                    <div class="col-md-3">
		                    	<div class="form-group" style="margin: 2px 5px;">
			                        <label>Estado :</label>
			                        <div>
			                            <select name="id_estado" id="id_estado" onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios2.php')" style="width: 98%" required>
			                                <option value="0"> Seleccionar Estado </option>
			                                <?php
			                                if (isset($id)) echo $funciones->llenarcombomodifica($querys->comboestados(), $row['id_estado']);
			                                else echo $funciones->llenarcombo($querys->comboestados());
			                                ?>
			                            </select>
			                        </div>
			                    </div>
		                    </div>
		                <?php }
		                else {
		                    echo '<input type="hidden" name="id_estado" id="id_estado" value="'.$id_estado.'" />';
		                }

		                if ($id_municipio == 0) { ?>
		                    <div class="col-md-3">
		                    	<div class="form-group" style="margin: 2px 5px;">
			                        <label>Municipio :</label>
			                        <div>
			                            <select name="id_municipio" id="id_municipio" style="width: 98%" onchange="combodependiente('id_municipio', 'id_casilla', 'combo_dependiente/casillas.php')" required>
			                                <option value="0"> Seleccionar Municipio </option>
			                                <?php
			                                if (isset($id)) echo $funciones->llenarcombomodifica($querys->combomunicipios($row['id_estado']), $row['id_municipio']);
			                                else if ($id_estado != 0) echo $funciones->llenarcombo($querys->combomunicipios($id_estado));
			                                ?>
			                            </select>
			                        </div>
			                    </div>
		                    </div>

		                <?php 
		                    }
		                    else {
		                ?>
		                    <input type="hidden" name="id_municipio" id="id_municipio" value="<?= $id_municipio; ?>" />
		                <?php 
		                    }
		                ?>

						<div class=" col-md-3">
							<div class="form-group" style="margin: 2px 5px;">
								<label>Casilla:</label>
								<div>
									<select name="id_casilla" id="id_casilla" required style="width: 98%">
										<option value=""> - Seleccionar Casilla - </option>
										<?php
										if (!empty($id))
											echo $funciones->llenarcombomodifica($querys->combocasillas($row['id_municipio']), $row['id_casilla']);
										if ($id_municipio != 0)
											echo $funciones->llenarcombo($querys->combocasillas($id_municipio));
										?>
									</select>
								</div>
							</div>
						</div>

						<div class=" col-md-3">
							<div class="form-group" style="margin: 2px 5px;">
								<label>Medio :</label>
								<div><select name="tipo_registro" id="tipo_registro" required style="width: 98%">
										<?php
										if (!empty($id)) echo $funciones->getcombotiporegistro($row['tipo_registro']);
										else echo $funciones->getcombotiporegistro(1);
										?>
									</select></div>
							</div>
						</div>

						<div style="clear: both;"></div>

						<div class="col-md-12">
							<div class="form-group" style="margin: 2px 5px;">
								<label>Etiquetas:</label>
								<select name="etiquetas[]" id="etiquetas" multiple style="width:100%" required>
									<?php
									$sqletiquetas = $entity->statement('reportes_registro.109.2');
									if (!empty($id)) {
										$etiquetas = array();
										$etq = $entity->objects($entity->statement('reportes_registro.112.3') . $id);
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
								<textarea name="descripcion" required class="form-control"><?php if (!empty($id)) echo $row['descripcion']; ?></textarea>
							</div>
						</div>

						<div style="clear: both;"></div>

						<div class="form-group" style="margin: 2px 5px;">
							<label class="col-sm-2">Archivo anexo :</label>
							<div class="col-sm-6">
								<input type="file" id="archivo" name="archivo">
								<?php
								if (!empty($id)) {
									if ($row['foto'] != "")
										echo '<img src="archivos/' . $row['foto'] . '" width="180px">';
								}
								?>
							</div>
						</div>

					</div><!-- panel-body -->
					<div class="panel-footer">
						<button class="btn btn-primary mr5"><?php if (!empty($id)) echo "Editar";
															else echo "Guardar"; ?></button>
						<input type="button" class="btn btn-danger mr5" value="Cancelar" onclick="reporte_listado()"/>
					</div><!-- panel-footer -->
				</div><!-- panel-default -->

				<input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($id)) echo "132";
																		else echo "133"; ?>" />
				<input type="hidden" name="id" id="id" value="<?php if (isset($id)) echo $id; ?>" />
				<input type="hidden" name="direccion" id="direccion" value="<?php if (isset($id)) echo $row['direccion'];
																			else echo ''; ?>" />
				<input type="hidden" name="tipo_reporte" id="tipo_reporte" value="<?php if (isset($id)) echo $row['tipo_reporte'];
																					else echo 1; ?>" />
			</form>
			<div id="cargando"></div>
		</div><!-- col-md-6 -->
