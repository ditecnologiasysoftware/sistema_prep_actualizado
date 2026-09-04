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

 $objPHPExcel = PHPExcel_IOFactory::load('../eleccion2015.xlsx');
 $objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

 foreach ($objHoja as $iIndice=>$objCelda) {

	$conteo++;
 	
 	$idproceso_electoral = trim($objCelda['A']);
 	$idcandidato_excel = trim($objCelda['C']);
 	$idcasilla_excel = trim($objCelda['D']);
 	$resultado_excel = trim($objCelda['E']);
 	$idusuaro_excel = trim($objCelda['F']);
 	$nulo_excel = trim($objCelda['G']);
 	$idrepresentante = 0;
	$idcandidatoPrin ='';
	$id_sesion_sistema = 0;
 			$registroExiste = $conexion->consultadato("SELECT COUNT(r.id_resultado) FROM tbl_resultado as r JOIN tblc_candidato as c ON(r.id_candidato = c.id_candidato) WHERE c.id_proceso_electoral =".$idproceso_electoral." and r.id_candidato = ".$idcandidato_excel." and r.id_casilla = ".$idcasilla_excel." and r.id_representante =".$idrepresentante);
	  			
	  			if ($registroExiste == 0) {
	  				$idcandidatoPrin .= $conexion->consultadato("SELECT id_candidato FROM tblc_candidato WHERE principal = 1 and id_candidato =".$idcandidato_excel);

				    $consulta = "INSERT INTO tbl_resultado(id_candidato, id_casilla, resultado, id_representante, fecha_registro, id_usuario) VALUES(".$idcandidato_excel.", ".$idcasilla_excel.", '".$resultado_excel."' , '".$idrepresentante."', NOW(), ".$idusuaro_excel.")";
		            //echo $consulta;
		            //exit(0);

			        if($conexion->consulta($consulta) == 0){
			            echo '<span style="color:red;">ERROR al actualizar el registro </span><br>'.$consulta;
						exit(0);
			        }

				    echo 'Se guardo el resultado de la fila '.$conteo;

	  			}

			   // $consultacta = "INSERT INTO tbl_acta(id_candidato, id_casilla, id_representante, fecha_registro, votos_nulos) VALUES(".$idcandidatoPrin.", ".$idcasilla_excel.", '".$idrepresentante."', NOW(), '".$datos['votos_null']."')";
	   		// 	$conexion->consulta($consultacta);


		}
echo '<script>alert("Actualizacion realizada satisfactoriamente")</script>';
exit(0);	
?>