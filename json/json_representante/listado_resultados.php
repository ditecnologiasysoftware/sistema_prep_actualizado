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
	$idRep= $funciones->limpia($_GET['idrep']);
 	$totalRegistos = $conexion->numregistros();
	$consulta = "SELECT c.* FROM tbl_resultado as r JOIN tblc_candidato as c ON(r.id_candidato = c.id_candidato) WHERE c.principal = 1 and r.id_representante = ".$idRep;
	$resultados = $conexion->obtenerlista($consulta);
	$totalRegistos = $conexion->numregistros();
	 if ($totalRegistos === 0) {
	 		$datos[] = array("totalRegistos" => $totalRegistos);
	 }else{
		foreach($resultados as $resultado){
			$procesoFecha = $conexion->consultadato("SELECT fecha FROM tblc_proceso_electoral WHERE id_proceso_electoral =".$resultado->id_proceso_electoral);
			$eleccionTipo = $conexion->consultadato("SELECT nombre FROM tblc_tipo_eleccion WHERE id_tipo_eleccion =".$resultado->id_tipo_eleccion);
			$datos[] = array("idprocesoE" => $resultado->id_proceso_electoral,
							 "idtipoE" => $resultado->id_tipo_eleccion,			 			
							 "idpartido" => $resultado->id_partido_politico,
							 "candidato" => $resultado->nombre,
							 "procesoEletoral" => 'Proceso electoral: <b>'.$funciones->fecha2($procesoFecha).'</b>',	
							 "tipoEleccion" => $eleccionTipo,	
							 "totalRegistos" => $totalRegistos);
		}
	}
	$conexion->cerrarconexion();
	header('Content-type: application/json');			
	echo '{"listaResultado":'.json_encode($datos).'}';


			
?> 