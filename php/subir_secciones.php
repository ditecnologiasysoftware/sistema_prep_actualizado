<?php
  //  error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', '0');
    ini_set('memory_limit', '1024M');
    ini_set('max_execution_time', 0);
    set_time_limit(0);

header("Content-Type: text/html;charset=utf-8");
require ("clase_funciones.php");
require ("clase_variables.php");
require ("clase_mysql.php");
require_once ('../excelphp/PHPExcel/IOFactory.php');

$conexion = new DB_mysql(1);
$funciones = new Funciones();
	//LLAMAMOS A LA CLASE CONEXION
$fecha_actual=date("Y")."-".date("m")."-".date("d");
$hora_actual=date("H").":".date("i").":".date("s");

$inicio = 45;
$termino = 56;
$distrito = 15;

for ($i=$inicio; $i <= $termino; $i++) { 	
	
	$duplicado = $conexion->consultaregistro("SELECT COUNT(*) FROM tblc_seccion WHERE nombre = '".$i."' AND id_distrito = ".$distrito);

	if($duplicado == 0){
		$consulta = "INSERT INTO tblc_seccion (nombre, id_distrito) 
					VALUES ('".$i."', '".$distrito."') ";
		if($conexion->consulta($consulta) == 0){
			echo 'ERROR al guardar el registro '.$i;
			exit(0);
			}
		echo '<span style="color:green;">DISTRITO '.$i.' REGISTRADO</span><br>';
		}
	else{
		echo 'DISTRITO NO '.$i.' REGISTRADO POR DUPLICADO<br>';
		}
	}
echo '<script>alert("Actualizacion realizada satisfactoriamente")</script>';
exit(0);	
?>