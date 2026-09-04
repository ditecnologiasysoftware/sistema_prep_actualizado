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
	require_once("../php/clase_variables.php");
	require_once("../php/clase_mysql.php");
	require_once("../php/clase_funciones.php");

	$conexion = new DB_mysql();
	$funciones = new Funciones();

	$info = $conexion->fetch_array("SELECT * FROM tbl_configuracion LIMIT 1");
	
	if($conexion->numregistros() == 0){
		$datos[] = array("estatus" => "0", "mensaje" => "Aplicación fuera de servicio");
		}
	else{
		if($info['fecha_inicio'] > date('Y-m-d'))			
			$datos[] = array("estatus" => "0", "mensaje" => $info['msj_antes']);
		else if($info['fecha_termino'] <= date('Y-m-d'))
			$datos[] = array("estatus" => "0", "mensaje" => $info['msj_despues']);
		else
			$datos[] = array("estatus" => "1", "mensaje" => "");

		}
	$conexion->cerrarconexion();
	echo '{"validacion":'.json_encode($datos).'}';
?>