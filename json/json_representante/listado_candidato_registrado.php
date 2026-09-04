<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}

	require_once("../../php/clase_variables.php");
	require_once("../../php/clase_mysql.php");
	require_once("../../php/clase_funciones.php");

	$conexion = new DB_mysql();
	$funciones = new Funciones();	
	$datos = array();
	$urlimg = "http://demosistemas.com/demos/electoral/";	
	$idproceso = $funciones->limpia($_GET['idproceso']);    
	$idele = $funciones->limpia($_GET['idele']);   
	$rcasilla = $funciones->limpia($_GET['rcasilla']);    	

	$totalRegistros = $conexion->consultadato("SELECT COUNT(idcandidato_c) FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$idproceso." and idt_eleccion_c = ".$idele." and id_casilla = ".$rcasilla);
	$consulta =  "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$idproceso." and idt_eleccion_c = ".$idele." and id_casilla = ".$rcasilla;
	$resultados = $conexion->obtenerlista($consulta);
	if ($totalRegistros == 0) {	
		$datos[] = array("totalRegistros" => $totalRegistros);				
	}else{
	  $resultados = $conexion->obtenerlista($consulta);
	  foreach($resultados as $resultado){

		if ($resultado->icono_pa != '' || $resultado->icono_pa != NULL) {
			$ruta_icono = $urlimg."archivos/partido_politico/".$resultado->icono_pa;
		}else{ $ruta_icono = "img/urna.png"; } 		
           
           $datos[] = array("id_candidato" => $resultado->idcandidato_c,
							 "id_proceso_electoral" => $resultado->idp_electoral_c,
 							 "id_tipo_eleccion" => $resultado->idt_eleccion_c,
  							 "id_partido_politico" => $resultado->idp_politico_c, 							
 							 "icono" => $ruta_icono,
 							 "nombre" => $resultado->nombre_c,
							 "principal" => $resultado->principal_c,
 							 "resultado" => $resultado->resultado,
 							 "totalRegistros" => $totalRegistros);
        }
	}
	$conexion->cerrarconexion();
	header('Content-type: application/json');
			
	echo '{"candidatosRegistrado":'.json_encode($datos).'}';
			
?> 