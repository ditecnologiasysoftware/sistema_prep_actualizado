<?php
require ("../clase_variables.php");
require ("../clase_mysql.php");
require "../clase_querys.php";	
$conexion = new DB_mysql(1);
$querys    = new Querys();

echo '<option value="0">Todos los municipios</option>
	';
$resultados = $conexion->obtenerlista($querys->combomunicipios($_POST['id']));
foreach($resultados as $resultado){
	echo '<option value="'.$resultado->id.'">'.$resultado->valor.'</option>
	';
	}
?>