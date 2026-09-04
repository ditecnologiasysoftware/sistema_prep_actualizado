<?php
require "../php/inicializandoDatosExterno.php";

$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;
$row = $entity->row("SELECT * FROM tbl_configuracion WHERE id_configuracion = 1");
$num = $entity->numregistros();
?>
<!--FIN ARRIBA-------------------------------------------------------------------------------- -->
<div class="contentpanel">
	<!-- CONTENIDO ----------------------------------------------------------------------- -->
	<div class="row">
		<form id="enviar_formulario" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Configurar fechas y mensajes de acceso a la App Movil</h4>
					<p></p>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-sm-3">Fecha de inicio :</label>
						<div class="col-sm-4">
							<input type="text" name="fecha_inicio" id="fecha" class="form-control" required value="<?php if ($num != 0) echo date("d-m-Y", strtotime($row['fecha_inicio'])) ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Mensaje antes de la fecha de publicación:</label>
						<div class="col-sm-9">
							<textarea name="msj_antes" rows="4" class="form-control"><?php if ($num != 0) echo $row['msj_antes'] ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Fecha termino :</label>
						<div class="col-sm-4">
							<input type="text" name="fecha_termino" id="fecha2" class="form-control" required value="<?php if ($num != 0) echo date("d-m-Y", strtotime($row['fecha_termino'])) ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Mensaje despues de la fecha de publicación:</label>
						<div class="col-sm-9">
							<textarea name="msj_despues" rows="4" class="form-control"><?php if ($num != 0) echo $row['msj_despues'] ?></textarea>
						</div>
					</div>
				</div><!-- panel-body -->
				<div class="panel-footer">
					<button class="btn btn-primary mr5">Actualizar</button>
				</div><!-- panel-footer -->
			</div><!-- panel-default -->
			<input type="hidden" name="opcion" id="opcion" value="8" />
		</form>
		<div id="cargando"></div>
	</div><!-- col-md-6 -->
	<!-- -->
</div>