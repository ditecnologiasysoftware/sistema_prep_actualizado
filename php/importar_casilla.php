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
$conteo = 0;
$fecha_actual=date("Y")."-".date("m")."-".date("d");
$hora_actual=date("H").":".date("i").":".date("s");

 $objPHPExcel = PHPExcel_IOFactory::load('../casillas.xlsx');
 $objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

 foreach ($objHoja as $iIndice=>$objCelda) {

	$conteo++;
 	$seccion = trim($objCelda['D']);
 	$casilla = trim($objCelda['E']);
 	$direccion = trim($objCelda['F']);
 	$municipio = 1;

 	$tipo = substr($casilla, 0, 1);
 	switch ($tipo) {
 		case 'B':
 			$tipocasilla = 1;
 		break;
 		case 'C':
 			$tipocasilla = 2;
 		break;
 		case 'E':
 			$tipocasilla = 3;
 		break;
 	}

	$registroExiste = $conexion->consultadato("SELECT COUNT(id_casilla) FROM tblc_casilla WHERE id_municipio = 1 and seccion = '".$seccion."' and nombre LIKE '".$casilla."'");
		
	if ($registroExiste == 0) {

		$registroExiste = $conexion->consultadato("SELECT COUNT(s.id_seccion) FROM tblc_seccion as s INNER JOIN tblc_distrito AS d ON s.id_distrito = d.id_distrito WHERE d.id_municipio = 1 and s.nombre LIKE '".$seccion."'");

		if ($registroExiste != 0) {
		    $consulta = "INSERT INTO tblc_casilla(id_municipio, nombre, tipo, seccion, direccion) VALUES(".$municipio.", '".$casilla."', '".$tipocasilla."' , '".$seccion."', '".$direccion."')";
		    //echo $conteo.".- ".$consulta."<br><br>";
		    //exit(0);

		    if($conexion->consulta($consulta) == 0){
		        echo $conteo.'.- <span style="color:red;">ERROR al actualizar el registro </span><br>'.$consulta;
				exit(0);
		    	}

		    echo $conteo.'.- Se guardo la casilla '.$casilla." de la sección ".$seccion."<br>";
			}
		else{
			echo $conteo.'.- <span style="color:red;">La seccion '.$seccion.' no existe </span><br>';
				exit(0);
		}


		}
		else{
			echo $conteo.".- La casilla ".$casilla." Ya existe <br>";
		}

	}
echo '<script>alert("Actualizacion realizada satisfactoriamente")</script>';
exit(0);	
?>