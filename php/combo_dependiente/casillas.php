<?php
require ("../clase_variables.php");
require ("../clase_mysql.php");
require ("../clase_funciones.php");
require "../clase_querys.php";	
$conexion = new DB_mysql(1);
$funciones = new Funciones();
$querys    = new Querys();

echo '<option value="0">-- Seleccionar Casilla --</option>';

$funciones->llenarcombo($querys->combocasillas(0,$_POST['id']));
?>