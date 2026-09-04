<?php
require ("../clase_variables.php");
require ("../clase_mysql.php");
require ("../clase_funciones.php");
require "../clase_querys.php";	
$conexion = new DB_mysql(1);
$querys    = new Querys();
$funciones = new Funciones();

echo $funciones->llenarcombo($querys->combodistritos($_POST['id']));
?>