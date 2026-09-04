<?php

require ("clase_variables.php");
require ("clase_mysql.php");
require ("clase_funciones.php");

$funciones = new Funciones();
//LLAMAMOS A LA CLASE CONEXION
$conexion = new DB_mysql(1);

$nombre= $funciones->limpia($_POST['nombre']);
$apellido_p= $funciones->limpia($_POST['apellido_p']);
$apellido_m= $funciones->limpia($_POST['apellido_m']);

$cadena = "SELECT a.id_apoyo, a.folio, a.monto, a.estatus, a.observaciones, b.apellido_m, b.apellido_p, b.nombre, b.clave_elector, date_format(a.fecha, '%H:%i') as hora, date_format(a.fecha, '%Y-%m-%d') as fecha2 
			FROM tbl_apoyo AS a 
			INNER JOIN tblc_beneficiario AS b ON a.id_beneficiario = b.id_beneficiario 
			WHERE b.nombre LIKE '".$nombre."' AND b.apellido_p LIKE '".$apellido_p."' AND b.apellido_m LIKE '".$apellido_m."'";
			
$resul_lista = $conexion->obtenerlista($cadena);
if($conexion->numregistros() != 0){
?>
<center>
	<table width="90%">
		<thead>
			<tr>
				<th>Folio</th>
				<th>Fecha</th>
				<th>Clave elector</th>
				<th>Monto</th>
				<th>Estatus</th>
				<th>Observaciones</th>
			</tr>
		</thead>

		<tbody>
			<?php			
			foreach($resul_lista as $resultado_fila){
			?>
			<tr>
				<td ><?php echo $resultado_fila->folio ?></td>
				<td ><?php echo $funciones->fecha2($resultado_fila->fecha2)." ".$resultado_fila->hora ?> hrs.</td>
				<td ><?php echo $resultado_fila->clave_elector ?></td>
				<td >$ <?php echo $resultado_fila->monto ?></td>
				<td >
					<?php 
					echo $funciones->tipo_estatus_ordenpago($resultado_fila->estatus);
					?>
				</td>
				<td ><?php echo $resultado_fila->observaciones ?></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</center>
<?php
}
else{
	echo '<center> No se encontraron resultados del beneficiario </center>';
}
?>