<?php
require "../php/inicializandoDatosExterno.php";

if (isset($_POST['id']))
	$id = $funciones->limpia($_POST['id']);

$obtener_permisos_usu = $entity->objects($entity->statement('privilegios_modulo.7.1') . $id);
$obtener_menualto = $entity->objects($entity->statement('privilegios_modulo.8.2'));
?>

		<div class="panel panel-default">
			<form id="enviar_formulario_privilegios" class="form-horizontal" method="post" action="php/subir.php">

			<div class="panel-heading">

				<h4 class="panel-title">Privilegios</h4>
				<p></p>
			</div>
			<div class="panel-body">
				<table width="100%" border="0">
					<?php
					foreach ($obtener_menualto as $menu_alto) {

						$obtener_subchicos = $entity->objects($entity->statement('privilegios_modulo.24.3') . $menu_alto->id_permiso . $entity->statement('fragment.privilegios_modulo.24.1'));

						$num_arreglo_1 = count($obtener_subchicos);

						if ($num_arreglo_1 != 0) {
							echo '<tr>
										<td colspan="8" style="background-color: #f9f9f9"><div style="color:#06F"><strong>' . $menu_alto->nombre . '</strong></div> </td>
									</tr>';

							echo '<tr>';

							$conta = 0;

							foreach ($obtener_subchicos as $menu_chico) {
								$conta++;

								$checar = "";

								foreach ($obtener_permisos_usu as $permisos) {

									if ($menu_chico->id_permiso == $permisos->id_permiso)
										$checar = 'checked="checked"';
								}


								echo '		<td width="12.5%">' . $menu_chico->nombre . '</td>
											<td width="12.5%"><input type="checkbox" name="privilegios[]" value="' . $menu_chico->id_permiso . '" ' . $checar . '><br/><br/></td>';

								if ($conta == 4) {
									echo '</tr><tr>';
									$conta = 0;
								}
							}

							echo '</tr>';
						} else {

							$checar = "";

							foreach ($obtener_permisos_usu as $permisos) {

								if ($menu_alto->id_permiso == $permisos->id_permiso)
									$checar = 'checked="checked"';
							}

							echo '<tr>
										<td width="12.5%" style="background-color: #f9f9f9"><div style="color:#06F" ><strong>' . $menu_alto->nombre . '</strong></div></td>
										<td width="12.5%" style="background-color: #f9f9f9"><div><input type="checkbox" name="privilegios[]" value="' . $menu_alto->id_permiso . '" ' . $checar . '></div></td>
										<td width="12.5%" style="background-color: #f9f9f9">&nbsp;</td>
										<td width="12.5%" style="background-color: #f9f9f9">&nbsp;</td>
										<td width="12.5%" style="background-color: #f9f9f9">&nbsp;</td>
										<td width="12.5%" style="background-color: #f9f9f9">&nbsp;</td>
										<td width="12.5%" style="background-color: #f9f9f9">&nbsp;</td>
										<td width="12.5%" style="background-color: #f9f9f9">&nbsp;</td>
									</tr>';
						}
					}
					?>

					</table>

					<br />
					<div style="clear:both;"></div>

			</div><!-- panel-body -->

			<div class="panel-footer">
				<input type="submit" class="btn btn-primary mr5" value="Guardar" id="btn_guardar_privilegios">
				<button class="btn btn-danger mr5" onclick="usuarios_lista()">Cancelar</button>

				<input type="hidden" name="opcion" id="opcion" value="3" />
				<input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />
			</div><!-- panel-footer -->

			</form>
		</div><!-- panel-default -->
