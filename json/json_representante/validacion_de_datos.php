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
	
	$usuarioEnviado = $funciones->limpia($_GET['usuario']);
	$passwordEnviado = $_GET['password'];
	$passwordEnviado = $funciones->create_password($passwordEnviado);	
	
	$plataforma = $funciones->limpia($_GET['plataforma']);
	$uuid = $funciones->limpia($_GET['uuid']);
	$version = $funciones->limpia($_GET['version']);
	$modelo = $funciones->limpia($_GET['nombre_movil']);
	
	$lat = $funciones->limpia($_GET['lat']);
	$lon = $funciones->limpia($_GET['lon']);
	$push = $_GET['push'];

	
	//Usando "!==" comprobamos si $texto no devuelve false, si tiene algo devolverÃ¡ TRUE, en caso contrario devolverÃ¡ False 
	if(isset($usuarioEnviado) and isset($passwordEnviado)){		
			
		$consulta = $conexion->fetch_array("SELECT * FROM tblc_representante WHERE usuario='".$usuarioEnviado."' and estatus = 1");		
		$resultados = $conexion->numregistros();
		
		// verifica que el usuario y password concuerden correctamente
		if($resultados == 0){
			$datos[] = array("mensaje" => "Acceso denegado!!!",
	                     "validacion" => "0");
			}
		/*else if($consulta['pass'] != $passwordEnviado){
			$datos[] = array("mensaje" => "Contraseña Incorrecta!!! ",
	                     "validacion" => "0");
			}*/
		else{				
			//esta informacion se envia solo si la validacion es correcta 			
		$conexion->obtenerlista("SELECT id_representante_movil FROM tbl_representante_movil WHERE uuid = '".$uuid."' AND id_representante = ".$consulta['id_representante']);
		if($conexion->numregistros() == 0){

				$sentencia = "INSERT INTO tbl_representante_movil(id_representante, uuid, so, version, modelo, num_accesos, fecha_acceso, estatus) VALUES('".$consulta['id_representante']."','".$uuid."','".$plataforma."','".$version."','".$modelo."','1','".date('Y-m-d h:i:s')."','1')";
				$conexion->consulta($sentencia);

			if ($consulta['id_casilla'] != 0) {
				$numCasilla = $conexion->fetch_array("SELECT * FROM tblc_casilla WHERE id_casilla = ".$consulta['id_casilla']);
				$datCasilla = 'Casilla Num. <b> '.$numCasilla['numero'].'</b> <br> Tipo de casilla: '.$funciones->getcomboTipoEleccionText($numCasilla['tipo']);
			}else{ $datCasilla = 'Usted tiene acceso a todas las casillas'; }

				$datos[] = array("mensaje" => "Validacion Correcta",
						 "validacion" => "1",
						 "idrepresentateLog" => $consulta['id_representante'],
						 "nombreLog" => $consulta['nombre'],
						 "telefonoLog" => $consulta['telefono'],
						 "correoLog" => $consulta['correo'],
						 "usuarioLog" => $consulta['usuario'],
						 "idCasillaLog" => $consulta['id_casilla'],
						 "numCasillaLog" => $datCasilla);

		}else{
				$estatus = $conexion->consultadato("SELECT app.estatus FROM tbl_representante_movil AS app WHERE app.uuid = '".$uuid."'");
				if($estatus == 3){
					$datos[] = array("mensaje" => "Dispositivo desactivado!!!",
	                "validacion" => "3");
				}else{
					$sentencia = "UPDATE tbl_representante_movil SET num_accesos = (num_accesos + 1), fecha_acceso = '".date('Y-m-d h:i:s')."', estatus = '1' WHERE uuid = '".$uuid."' and id_representante = ".$consulta['id_representante'];
					$conexion->consulta($sentencia);

				if ($consulta['id_casilla'] != 0) {
				//$numCasilla = $conexion->fetch_array("SELECT * FROM tblc_casilla WHERE id_casilla = ".$consulta['id_casilla']);
				//$datCasilla = 'Casilla Num. <b> '.$numCasilla['numero'].'</b> <br> Tipo de casilla: '.$funciones->getcomboTipoEleccionText($numCasilla['tipo']);
				$datCasilla = $funciones->llenarCasillatbl($consulta['id_casilla']);
				}else{ $datCasilla = 'Usted tiene acceso a todas las casillas'; }

					$datos[] = array("mensaje" => "Validacion Correcta",
						 "validacion" => "1",
						 "idrepresentateLog" => $consulta['id_representante'],
						 "nombreLog" => $consulta['nombre'],
						 "telefonoLog" => $consulta['telefono'],
						 "correoLog" => $consulta['correo'],
						 "usuarioLog" => $consulta['usuario'],
						 "idCasillaLog" => $consulta['id_casilla'],
						 "numCasillaLog" => $datCasilla);

					}

				}
		
		}

	}

	$conexion->cerrarconexion();
	echo '{"logeo":'.json_encode($datos).'}';

	//Se declara que esta es una aplicacion que genera un JSON
	header('Content-type: application/json');
	//Se abre el acceso a las conexiones que requieran de esta aplicacion
	header("Access-Control-Allow-Origin: *");
	exit(0);
?>
