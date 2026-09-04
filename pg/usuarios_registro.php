<?php
require "../php/inicializandoDatosExterno.php";

if (!empty($_POST['id'])) {
	$id = $funciones->limpia($_POST['id']);
	$row = $entity->row("SELECT * FROM tblc_usuario WHERE id_usuario = " . $id);
}

?>

<!-- Tab panes -->
<div>
	<div class="tab-pane <?php if (!isset($_GET['act']))
		echo 'active'; ?>" id="home">

		<div class="row">

			<div style="widows:100%">
				<form id="enviar_formulario" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php">
					<div class="panel panel-default">
						<div class="panel-heading">

							<h4 class="panel-title"></h4>
							<p></p>
						</div>
						<div class="panel-body">

							<div class=" col-md-6">
								<div class="form-group" style="margin: 5px;">
									<label>Nombre :</label>
									<div>
										<input type="text" name="nombre" id="nombre" class="form-control" required
											value="<?php if (!empty($_POST['id']))
												echo $row['nombre']; ?>" />
									</div>
								</div>
							</div>

							<div class=" col-md-6">
								<div class="form-group" style="margin: 5px;">
									<label>Correo :</label>
									<div>
										<input type="email" name="correo" id="correo" class="form-control" value="<?php if (!empty($_POST['id']))
											echo $row['correo']; ?>" />
									</div>
								</div>
							</div>

							<div class=" col-md-6">
								<div class="form-group" style="margin: 5px;">
									<label>Usuario :</label>
									<div>
										<input type="text" name="usuario" id="usuario" class="form-control" required
											value="<?php if (!empty($_POST['id']))
												echo $row['usuario']; ?>" />
									</div>
								</div>
							</div>

							<div class=" col-md-6">
								<div class="form-group" style="margin: 5px;">
									<label>Contraseña :</label>
									<div>
										<input type="password" name="pass" id="pass" class="form-control" <?php if (!isset($_POST['id']))
											echo ' required'; ?> value="" />
									</div>
								</div>
							</div>

							<?php if ($id_estado == 0) { ?>
								<div class=" col-md-6">
									<div class="form-group" style="margin: 5px;">
										<label>Estado</label>
										<div>
											<select class="select2 form-control" name="id_estado" id="id_estado"
												onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios2.php')"
												class="form-control" required>
												<option value="0">Todos los Estados</option>
												<?php
												if (!empty($_POST['id']))
													echo $funciones->llenarcombomodifica("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre", $row['id_estado']);
												else
													echo $funciones->llenarcombo("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre");
												?>
											</select>
										</div>
									</div>
								</div>
							<?php } else { ?>
								<input type="hidden" name="id_estado" id="id_estado" value="<?= $id_estado; ?>" />
							<?php }
							if ($id_municipio == 0) { ?>
								<div class=" col-md-6">
									<div class="form-group" style="margin: 5px;">
										<label>Municipio:</label>
										<div>
											<select class="select2 form-control" name="id_municipio" id="id_municipio" class="form-control" required>
												<option value="0">Todos los Municipio</option>
												<?php
												if (!empty($_POST['id']))
													echo $funciones->llenarcombomodifica("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = " . $row['id_estado'] . " ORDER BY nombre", $row['id_municipio']);
												?>
											</select>
										</div>
									</div>
								</div>
							<?php } else { ?>
								<input type="hidden" name="id_municipio" id="id_municipio" value="<?= $id_municipio; ?>" />
							<?php } ?>

							<div class=" col-md-6">
								<div class="form-group" style="margin: 5px;">
									<label>¿Usuario activo? :</label>
									<div>
										<select class="select2 form-control" name="estatus" id="estatus" style="width:100%">
											<?php
											if (!empty($_POST['id']))
												echo $funciones->getComboVisible($row['estatus']);
											else
												echo $funciones->getComboVisible(1);
											?>
										</select>
									</div>
								</div>
							</div>

							<div class=" col-md-6">
								<div class="form-group col-md-6" style="margin: 5px;">
									<label>Acciones :</label>
									<div>
										<input type="checkbox" name="eliminar" id="eliminar" value="1" <?php if (isset($row['eliminar']) && $row['eliminar'] == 1)
											echo 'checked'; ?> />
										<label style="margin-top:0px; width:80%; float:none;" for="eliminar"> Eliminar
											registros </label><br>
										<input type="checkbox" name="editar" id="editar" value="1" <?php if (isset($row['editar']) && $row['editar'] == 1)
											echo 'checked'; ?> /> <label
											style="margin-top:0px; width:80%; float:none;" for="modificar"> Modificar
											registros </label>
									</div>
								</div>
							</div>

						</div><!-- panel-body -->
						<div class="panel-footer">
							<button class="btn btn-primary mr5"><?php if (!empty($_POST['id']))
								echo "Editar";
							else
								echo "Guardar"; ?></button>
							<?php
							$redi = "window.location.href='usuarios'";
							if (isset($_POST['id']))
								echo '<button class="btn btn-danger mr5" onclick="' . $redi . '">Cancelar</button>';
							?>
						</div><!-- panel-footer -->
					</div><!-- panel-default -->

					<input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id']))
						echo "1";
					else
						echo "2"; ?>" />
					<input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id']))
						echo $id; ?>" />
				</form>
				<div id="cargando"></div>
			</div><!-- col-md-6 -->
			<!--FIN DE CONTENIDO-------------------------------------------------------->
		</div><!-- contentpanel -->
	</div><!-- tab-pane -->
	<div class="tab-pane <?php if (!empty($_POST['act']))
		echo "active"; ?>" id="profile">
	</div>
</div>