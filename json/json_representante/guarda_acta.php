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

	$datos2['fecha_actual']=date("Y")."-".date("m")."-".date("d");
	$datos2['hora_actual']=date("H").":".date("i").":".date("s");

	$datos2['fecha_nombre']=date("d").date("m").date("Y");
	$datos2['hora_nombre']=date("H").date("i").date("s");

	$idprocesoEleccion = $funciones->limpia($_GET['idprocesoEleccion']);
	$idcasi = $funciones->limpia($_GET['idcasi']);
	$idrepresenta = $funciones->limpia($_GET['idrepre']);
	$foto_acta = $funciones->limpia($_GET['foto_acta']);
	$votosNulos = $funciones->limpia($_GET['votosNulos']);

	$idCandidato = $conexion->consultadato("SELECT id_candidato FROM tblc_candidato WHERE id_proceso_electoral =".$idprocesoEleccion." and principal = 1");

    $consulta = "INSERT INTO tbl_acta(id_candidato, id_casilla, archivo, id_representante, fecha_registro, votos_nulos) VALUES(".$idCandidato.", ".$idcasi.", '".$foto_acta."' , '".$idrepresenta."', NOW(), ".$votosNulos.")";
	
	if($conexion->consulta($consulta) == 0){
		 $datos[] = array("dato" => '0');
	}else{
		$datos[] = array("dato" => '1'); 
	}

	$conexion->cerrarconexion();
	//Se declara que esta es una aplicacion que genera un JSON
	header('Content-type: application/json');
			
	echo '{"actaElecciones":'.json_encode($datos).'}';


?>