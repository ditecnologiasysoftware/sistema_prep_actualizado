<?php
session_start();

/*	require ("clase_variables.php");
	require ("clase_mysql.php");
	$conexion = new DB_mysql(1);
	
	$conexion->consulta("UPDATE tbl_ususario SET conectado = 1 WHERE id_ususario = ".$_SESSION['tipo_usuario']);*/
// descoloco todas la variables de la sesión
 session_unset();
// Destruyo la sesión
 session_destroy();
//Y me voy al inicio
  ?>
	<script languaje="javascript">
		        function mensage() {
			location.href='../index.php';				 
			}
		mensage();
</script>