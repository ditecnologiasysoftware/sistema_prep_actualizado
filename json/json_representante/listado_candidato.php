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
	
	$totalRegistros = $conexion->consultadato("SELECT COUNT(id_candidato) FROM tblc_candidato WHERE id_proceso_electoral =".$idproceso." and id_tipo_eleccion =".$idele);	
	$consulta =  "SELECT * FROM tblc_candidato WHERE id_proceso_electoral = ".$idproceso." and id_tipo_eleccion = ".$idele." ORDER BY nombre ASC" ;


if ($totalRegistros == 0) {	
	$datos[] = array("totalRegistros" => $totalRegistros);				
}else{
	  $resultados = $conexion->obtenerlista($consulta);
	  foreach($resultados as $resultado){

	$tipo_eleccion = $conexion->consultadato("SELECT nombre FROM tblc_tipo_eleccion WHERE id_tipo_eleccion =".$resultado->id_tipo_eleccion);
	$iconoPartido = $conexion->fetch_array("SELECT * FROM tblc_partido_politico WHERE id_partido_politico =".$resultado->id_partido_politico);
		if ($iconoPartido['icono'] != '' || $iconoPartido['icono'] != NULL) {
			$ruta_icono = $urlimg."archivos/partido_politico/".$iconoPartido['icono'];
		}else{ $ruta_icono = "img/urna.png"; } 		
           
           $datos[] = array("id_candidato" => $resultado->id_candidato,
							 "id_proceso_electoral" => $resultado->id_proceso_electoral,
 							 "id_tipo_eleccion" => $resultado->id_tipo_eleccion,
  							 "id_partido_politico" => $resultado->id_partido_politico, 							
 							 "icono" => $ruta_icono,
 							 "nombre" => $resultado->nombre,
							 "principal" => $resultado->principal,
 							 "totalRegistros" => $totalRegistros);
        }

	$conexion->cerrarconexion();
	//Se declara que esta es una aplicacion que genera un JSON
	
			

}
header('Content-type: application/json');
	echo '{"candidatos":'.json_encode($datos).'}';
			
?> 
