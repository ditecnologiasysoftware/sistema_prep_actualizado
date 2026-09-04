<?php
@session_start();

require ("clase_variables.php");
require ("clase_mysql.php");
require ("clase_funciones.php");
	
	$funciones = new Funciones();
	$conexion = new DB_mysql();
	
echo $funciones->create_password('admin');
  ?>