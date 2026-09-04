<?php
@session_start();
$id_sesion_sistema = $_SESSION['id_sesion_sistema'];

  //  error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', '0');
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 0);
    set_time_limit(0);

header("Content-Type: text/html;charset=utf-8");
require ("../php/clase_funciones.php");
require ("../php/clase_variables.php");
require ("../php/clase_mysql.php");
require_once ('../excelphp/PHPExcel/IOFactory.php');

$entity = Entity::createInstance();
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

 			$registroExiste = $entity->scalar("SELECT COUNT(r.id_resultado) FROM tbl_resultado as r JOIN tblc_candidato as c ON(r.id_candidato = c.id_candidato) WHERE c.id_proceso_electoral =".$idproceso_electoral." and r.id_candidato = ".$idcandidato_excel." and r.id_casilla = ".$idcasilla_excel." and r.id_representante =".$idrepresentante);
	  			
	  			if ($registroExiste == 0) {
	  				$idcandidatoPrin .= $entity->scalar("SELECT id_candidato FROM tblc_candidato WHERE principal = 1 and id_candidato =".$idcandidato_excel);

				    $consulta = "INSERT INTO tbl_resultado(id_candidato, id_casilla, resultado, id_representante, fecha_registro, id_usuario) VALUES(".$idcandidato_excel.", ".$idcasilla_excel.", '".$resultado_excel."' , '".$idrepresentante."', NOW(), ".$idusuaro_excel.")";

				        if($entity->execute($consulta) == 0){
				            echo '<script>parent.error("ERROR al actualizar el registro, intente de nuevo más tarde");</script>';
							exit(0);
				        }
				    $msj = 'Se guardaron el resultado de la votacion electoral con resultado'.$resultado_excel.' con id del candidato: '.$idcandidato_excel;
					$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
					$entity->execute($log_actividad);

	  			}else{
			  	 	echo '<script>parent.error("Estos datos ya han sido registrados");</script>';
					exit(0);
			   }

			   // $consultacta = "INSERT INTO tbl_acta(id_candidato, id_casilla, id_representante, fecha_registro, votos_nulos) VALUES(".$idcandidatoPrin.", ".$idcasilla_excel.", '".$idrepresentante."', NOW(), '".$datos['votos_null']."')";
	   		// 	$entity->execute($consultacta);

		$id = $entity->ultimoid();	

}
echo '<script>alert("Actualizacion realizada satisfactoriamente")</script>';
exit(0);	
?>
