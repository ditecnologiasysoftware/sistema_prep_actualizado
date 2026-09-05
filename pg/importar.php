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

 			$registroExiste = $entity->scalar($entity->statement('importar.40.1').$idproceso_electoral.$entity->statement('fragment.importar.40.1').$idcandidato_excel.$entity->statement('fragment.importar.40.2').$idcasilla_excel.$entity->statement('fragment.importar.40.3').$idrepresentante);
	  			
	  			if ($registroExiste == 0) {
	  				$idcandidatoPrin .= $entity->scalar($entity->statement('importar.43.2').$idcandidato_excel);

				    $consulta = $entity->statement('importar.45.3').$idcandidato_excel.", ".$idcasilla_excel.", '".$resultado_excel."' , '".$idrepresentante."', NOW(), ".$idusuaro_excel.")";

				        if($entity->execute($consulta) == 0){
				            echo '<script>parent.error("ERROR al actualizar el registro, intente de nuevo más tarde");</script>';
							exit(0);
				        }
				    $msj = 'Se guardaron el resultado de la votacion electoral con resultado'.$resultado_excel.' con id del candidato: '.$idcandidato_excel;
					$log_actividad = $entity->statement('importar.52.4').$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
					$entity->execute($log_actividad);

	  			}else{
			  	 	echo '<script>parent.error("Estos datos ya han sido registrados");</script>';
					exit(0);
			   }

		$id = $entity->ultimoid();	

}
echo '<script>alert("Actualizacion realizada satisfactoriamente")</script>';
exit(0);	
?>
