<?php
require "../php/inicializandoDatosExterno.php";

$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$peticion_enlace = "";
$sentencia = "";

if (isset($_POST['nombre_busqueda'])) {
	$sentencia .= $entity->statement('fragment.usuarios_lista.13.1') . $_POST['nombre_busqueda'] . "%'";
	$peticion_enlace .= "&nombre_busqueda=" . $_POST['nombre_busqueda'];
}

$cadena = $entity->statement('usuarios_lista.17.1') . $sentencia . $entity->statement('fragment.usuarios_lista.17.2') . $inicio . "," . $limite . "";
$cadena2 = $entity->statement('usuarios_lista.18.2') . $sentencia;

$totalCirculares = $entity->scalar($cadena2);
$resul_lista = $entity->objects($cadena);
?>
								
<div class="tab-pane <?php if (isset($_POST['act'])) echo "active";  ?>" id="profile">
	<div style="widows:100%">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"></h4>
				<p></p>
			</div>
			<div class="panel-body">
				<div class="form-group">

					<div class="panel-group" id="accordion2">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
										<div style="color:#FFFFFF">Buscar</div>
									</a>
								</h4>
							</div>
							<div id="collapseOne2" class="panel-collapse collapse <?php if (isset($_POST['bus'])) echo "in"; ?>">
								<div class="panel-body">
									<table width="100%" border="0">
										<tr>
											<td width="100%">
												<div class="form-group">
													<label class="col-sm-3">Buscar :</label>
													<form id="form_busqueda">
														<div class="form-group">
															<label class="col-sm-4">Nombre :</label>
															<div class="col-sm-7">
																<input type="text" name="nombre_busqueda" id="nombre_busqueda" class="form-control" value="<?php if (!empty($_POST['nombre_busqueda'])) echo $_POST['nombre_busqueda']; ?>" />
															</div>
														</div>

														<input type="hidden" name="act" id="act" value="1" />

														<input type="submit" class="btn btn-primary mr5" value="Buscar" />
														<input type="button" class="btn btn-secundary mr5" onclick="window.location.href='usuarios&act=1'" value="Cancelar">
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
					<div id="div_buscar">
						<table id="basicTable" class="table table-striped table-bordered responsive">
							<thead class="">
								<tr>
									<th>Usuarios</th>
									<th>Correo</th>
									<th>Activo</th>
									<th>Privilegios</th>
									<th>Bitácora</th>
									<?php if ($editar == 1) { ?>
										<th>Editar</th>
									<?php } ?>
									<?php if ($eliminar == 1) { ?>
										<th>Eliminar</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($resul_lista as $resultado_fila) {
								?>

									<tr>
										<td><?php echo $resultado_fila->nombre ?></td>
										<td><?php echo $resultado_fila->correo ?></td>
										<td><?php echo $funciones->activo($resultado_fila->estatus) ?></td>
										<td align="center"><?php echo '<a class="btn btn-info" onclick="privilegios(' . $resultado_fila->id_usuario . ')"><span class="fa fa-user"></span></a>' ?></td>
										<td align="center"><?php echo '<a class="btn btn-warning" href="bitacora&id=' . base64_encode($resultado_fila->id_usuario) . '"><span class="fa fa-file"></span></a>'; ?></td>
										<?php if ($editar == 1) { ?>
											<td align="center"><a class="btn btn-success" onclick="usuarios_registro(<?= $resultado_fila->id_usuario ?>)"><span class="fa fa-pen"></span></a></td>
										<?php } ?>
										<?php if ($eliminar == 1) { ?>
											<td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?php echo $resultado_fila->id_usuario ?>,1)"><span class="fa fa-trash"></span></a></td>
										<?php } ?>
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

												if ($datos) {

													echo 'Pagina: ' . $pagina . ' de ' . $pag->getCantidadPaginas() . '<br />';
													foreach ($datos as $enlace) {
														if ($enlace['active'] == false) {
												?><li><a href="?pag=<?php echo $enlace['numero'] . $peticion_enlace ?>&act=1"><?php echo $enlace['vista']; ?></a></li><?php
																																															} else {
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
	</div><!-- col-md-6 -->
</div><!-- row -->