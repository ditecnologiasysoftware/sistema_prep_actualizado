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

	$plataforma = $funciones->limpia($_GET['plataforma']);
	$uuid = $funciones->limpia($_GET['uuid']);
	$version = $funciones->limpia($_GET['version']);
	$modelo = $funciones->limpia($_GET['nombre_movil']);
	$push = $_GET['push'];
	
	$clientee = $conexion->fetch_array("SELECT app.* FROM tbl_representante_movil AS app INNER JOIN tblc_representante AS u ON app.id_representante = u.id_representante WHERE app.uuid = '".$uuid."'");
	if ($conexion->numregistros() == 0) {
		$datos[] = array("estatus" => "2");
	}else{
	if ($clientee['estatus'] == 3) {
		$datos[] = array("estatus" => "3");
	}
	else if($clientee['estatus'] == 2){
		$datos[] = array("estatus" => "2");
	}else if($clientee['estatus'] == 1){		
		$id_representantere = $conexion->consultadato("SELECT app.id_representante FROM tbl_representante_movil AS app INNER JOIN tblc_representante AS u ON app.id_representante = u.id_representante WHERE app.uuid = '".$uuid."' AND app.estatus = 1");	

		$query_srcUsersExist = "SELECT dis.estatus as disestatus, cl.*, dis.* FROM tblc_representante as cl INNER JOIN tbl_representante_movil as dis WHERE cl.id_representante = '".$id_representantere."' AND dis.id_representante = cl.id_representante AND dis.uuid = '".$uuid."'";	
		$consulta = $conexion->fetch_array($query_srcUsersExist);
		$resultados = $conexion->numregistros();
			
			$sentencia = "UPDATE tbl_representante_movil SET num_accesos = (num_accesos + 1), fecha_acceso = '".date('Y-m-d h:i:s')."' WHERE uuid = ".$uuid;
			$conexion->consulta($sentencia);
			if ($consulta['id_casilla'] != 0) {
				//$numCasilla = $conexion->fetch_array("SELECT * FROM tblc_casilla WHERE id_casilla = ".$consulta['id_casilla']);
				//$datCasilla = 'Casilla Num. <b> '.$numCasilla['numero'].'</b> <br> Tipo de casilla: '.$funciones->getcomboTipoEleccionText($numCasilla['tipo']);
				 $datCasilla = $funciones->llenarCasillatbl($consulta['id_casilla']);

			}else{ $datCasilla = 'Usted tiene acceso a todas las casillas'; }
			$datos[] = array("mensaje" => "Validacion Correcta",
						 "estatus" => "1",
						 "idrepresentateLog" => $consulta['id_representante'],
						 "nombreLog" => $consulta['nombre'],
						 "telefonoLog" => $consulta['telefono'],
						 "correoLog" => $consulta['correo'],
						 "usuarioLog" => $consulta['usuario'],
						 "idCasillaLog" => $consulta['id_casilla'],
						 "numCasillaLog" => $datCasilla,
						 "validacion" => '1');
	}
}
	$conexion->cerrarconexion();
	echo '{"validacione":'.json_encode($datos).'}';

				//Se declara que esta es una aplicacion que genera un JSON
			header('Content-type: application/json');
			//Se abre el acceso a las conexiones que requieran de esta aplicacion
			header("Access-Control-Allow-Origin: *");
?>
