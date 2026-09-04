<?php
require "../php/inicializandoDatosExterno.php";

$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$peticion_enlace = "";
$sentencia = "";
$inner = "";

if (!empty($_POST['casilla_busqueda'])) {
	$sentencia .= " AND r.id_casilla = '" . $funciones->limpia($_POST['casilla_busqueda']) . "'";
	$peticion_enlace .= "&casilla_busqueda=" . $_POST['casilla_busqueda'];
}

if ($id_municipio != 0) {
	$sentencia .= " AND r.id_municipio = '" . $id_municipio . "'";
	$peticion_enlace .= "&municipio_busqueda=" . $_POST['municipio_busqueda'];
} else if ($id_estado != 0) {
	$sentencia .= " AND m.id_estado = '" . $id_estado . "'";
}

if (isset($_POST['municipio_busqueda']) && $_POST['municipio_busqueda'] != 0) {
	$sentencia .= " AND r.id_municipio = '" . $funciones->limpia($_POST['municipio_busqueda']) . "'";
	$peticion_enlace .= "&municipio_busqueda=" . $_POST['municipio_busqueda'];
}

if (isset($_POST['estado_busqueda']) && $_POST['estado_busqueda'] != 0) {
	$sentencia .= " AND m.id_estado = '" . $funciones->limpia($_POST['estado_busqueda']) . "'";
	$peticion_enlace .= "&estado_busqueda=" . $_POST['estado_busqueda'];
}

if (!empty($_POST['hora_busqueda']) && $_POST['hora2_busqueda'] != "") {
	$sentencia .= " AND date_format(r.fecha_registro, '%H:%i') >= '" . $_POST['hora_busqueda'] . "'";
	$sentencia .= " AND date_format(r.fecha_registro, '%H:%i') <= '" . $_POST['hora2_busqueda'] . "'";
	$peticion_enlace .= "&hora_busqueda=" . $_POST['hora_busqueda'];
	$peticion_enlace .= "&hora2_busqueda=" . $_POST['hora2_busqueda'];
}

if (isset($_POST['folio_busqueda']) && $_POST['folio_busqueda'] != "") {
	$sentencia .= " AND r.folio LIKE '%" . $funciones->limpia($_POST['folio_busqueda']) . "%'";
	$peticion_enlace .= "&folio_busqueda=" . $_POST['folio_busqueda'];
}

if (isset($_POST['servicio_busqueda']) && $_POST['servicio_busqueda'] != 0) {
	$sentencia .= " AND r.tipo_reporte = '" . $funciones->limpia($_POST['servicio_busqueda']) . "'";
	$peticion_enlace .= "&servicio_busqueda=" . $_POST['servicio_busqueda'];
}

if (isset($_POST['tipo_busqueda']) && $_POST['tipo_busqueda'] != 0) {
	$sentencia .= " AND r.tipo_registro = '" . $funciones->limpia($_POST['tipo_busqueda']) . "'";
	$peticion_enlace .= "&tipo_busqueda=" . $_POST['tipo_busqueda'];
}

if (isset($_POST['etiqueta']) && $_POST['etiqueta'] != 0) {
	$sentencia .= " AND re.id_etiqueta = '" . $funciones->limpia($_POST['etiqueta']) . "'";
	$peticion_enlace .= "&etiqueta=" . $_POST['etiqueta'];
	$inner = " INNER JOIN tbl_reporte_etiqueta AS re ON r.id_reporte = re.id_reporte";
}

$cadena = "SELECT r.*, m.nombre as municipio, e.nombre as estado, date_format(r.fecha_registro, '%H:%i') as hora, date_format(r.fecha_registro, '%Y-%m-%d') as fecha2 
FROM tbl_reporte AS r 
INNER JOIN tblc_municipio AS m ON r.id_municipio = m.id_municipio 
INNER JOIN tblc_estado AS e ON m.id_estado = e.id_estado 
" . $inner . "
WHERE r.id_reporte != 0" . $sentencia . " ORDER BY r.fecha_registro DESC LIMIT " . $inicio . "," . $limite . "";
//echo $cadena;
$cadena2 = "SELECT COUNT(r.id_reporte) FROM tbl_reporte AS r 
INNER JOIN tblc_municipio AS m ON r.id_municipio = m.id_municipio 
INNER JOIN tblc_estado AS e ON m.id_estado = e.id_estado 
" . $inner . " WHERE r.id_reporte != 0" . $sentencia;

$totalCirculares = $entity->scalar($cadena2);
$resul_lista = $entity->objects($cadena);

?>
<div id="div_buscar" class="panel panel-default prep-list-panel">
	<div class="panel-heading">
		<h4 class="panel-title">Listado de incidencias</h4>
		<p><?php echo $totalCirculares; ?> registros encontrados</p>
	</div>
	<div class="panel-body">
	<div class="table-responsive">
	<table id="basicTable" class="table table-striped table-bordered responsive">
		<thead class="">
			<tr>
				<th>Folio</th>
				<th>Nombre</th>
				<th>Registro</th>
				<th>Casilla</th>
				<th>Descripción</th>
				<th>Etiquetas</th>
				<th>Foto</th>
				<th>Map</th>
				<?php if ($editar == 1) { ?>
					<th>Edit</th>
				<?php } ?>
				<?php if ($eliminar == 1) { ?>
					<th>Elim</th>
				<?php } ?>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($resul_lista as $resultado_fila) {
			?>

				<tr>
					<td>
						<?php echo $resultado_fila->folio ?>
						<br>
						<?php echo $funciones->cambiarFormatoFechaform($resultado_fila->fecha2) . " " . $resultado_fila->hora . " hrs." ?>
					</td>
					<td><?php echo $resultado_fila->nombre ?></td>
					<td><?php echo $funciones->tipo_registro($resultado_fila->tipo_registro) ?></td>
					<td>
						<?php echo $funciones->llenarCasillatbl($resultado_fila->id_casilla) ?>
					</td>
					<td><textarea rows="3"><?php echo $resultado_fila->descripcion ?></textarea></td>
					<td>
						<ul style="margin-left: 0px; padding-left: 5px; font-size: 10px;"><?php
						$etiquetas = $entity->objects("SELECT e.etiqueta FROM tbl_reporte_etiqueta AS er INNER JOIN tblc_etiqueta AS e ON er.id_etiqueta = e.id_etiqueta WHERE er.id_reporte = " . $resultado_fila->id_reporte);
						foreach ($etiquetas as $value) {
							echo '<li>' . $value->etiqueta . '</li>';
						}
						?></ul>
					</td>
					<td align="center"><?php if ($resultado_fila->foto != "") { ?><a href="archivos/<?php echo $resultado_fila->foto ?>" target="_blank"><span class="glyphicon glyphicon-camera"></span></a><?php } ?></td>
					<td align="center"><?php if ($resultado_fila->latitud != 0) { ?><a href="pg/mapa.php?id=<?php echo $resultado_fila->id_reporte ?>" target="_blank"><span class="glyphicon glyphicon-map-marker"></span></a><?php } ?></td>
					<?php if ($editar == 1) { ?>
						<td align="center"><a class="btn btn-success" href="javascript:reporte_registro(<?php echo $resultado_fila->id_reporte ?>)"><span class="fa fa-pen"></span></a></td>
					<?php } ?>

					<?php if ($eliminar == 1) { ?>
						<td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?php echo $resultado_fila->id_reporte ?>,4)"><span class="fa fa-trash"></span></a></td>
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
							?><li><a href="javascript:reporte_listado(<?php echo $enlace['numero'] ?>)"><?php echo $enlace['vista']; ?></a></li><?php
									} else {
										?><li class="active"><span><?php echo $enlace['vista']; ?></span></li><?php
									}
								}
							}
							?>
						</ul>
					</div>
				</td>
				<td colspan="4">
					<a class="btn btn-danger mr5" target="_blank" href="php/excel_reportes.php?valor=1<?php echo $peticion_enlace ?>">Exportar Excel</a>
				</td>
			</tr>
		</tfoot>
	</table>
	</div>
	</div>
</div>
