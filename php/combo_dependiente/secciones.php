<?php
require ("../clase_variables.php");
require ("../clase_mysql.php");
require "../clase_querys.php";	
$conexion = new DB_mysql(1);
$querys    = new Querys();

echo '<option value="0">-- Ninguna Sección --</option>
	';
$resultados = $conexion->obtenerlista($querys->combosecciones($_POST['id']));
foreach($resultados as $resultado){
	echo '<option value="'.$resultado->id.'">Sección - '.$resultado->valor.'</option>
	';
	}
?>