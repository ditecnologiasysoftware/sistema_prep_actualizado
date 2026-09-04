<?php
@session_start();
	$id_usuario = $_SESSION['id_usuario'];
	$autentificado = $_SESSION['autentificado'];
	$nombre = $_SESSION['nombre'];
	$id_sesion_sistema = $_SESSION['id_sesion_sistema'];

require ("clase_variables.php");
require ("clase_mysql.php");
require ("clase_funciones.php");

$funciones = new Funciones();
//LLAMAMOS A LA CLASE CONEXION
$conexion = new DB_mysql(1);

$datos = array();
$datos['id'] = $_POST['id'];

switch($_POST['opcion']){
	case 1:

		$nombre = $conexion->consultadato("SELECT nombre FROM tblc_usuario WHERE id_usuario = ".$datos['id']);
		$descripcion_log = "Se elimino el usuario: ".$nombre;
		$consulta = "DELETE FROM tblc_usuario WHERE id_usuario = ".$datos['id'];
		
		if($conexion->consulta($consulta) == 0){
			echo 'ERROR al Eliminar registro';
			exit(0);
			}

	break;

	case 2:

		$nombre = $conexion->consultadato("SELECT nombre FROM tblc_categoria WHERE id_categoria = ".$datos['id']);
		$descripcion_log = "Se elimino la categoría: ".$nombre;
		$consulta = "DELETE FROM tblc_categoria WHERE id_categoria = ".$datos['id'];
		
		if($conexion->consulta($consulta) == 0){
			echo 'ERROR al Eliminar registro';
			exit(0);
			}

	break;

	case 3:

		$nombre = $conexion->consultadato("SELECT etiqueta FROM tblc_etiqueta WHERE id_etiqueta = ".$datos['id']);
		$descripcion_log = "Se elimino la etiqueta: ".$nombre;
		$consulta = "DELETE FROM tblc_etiqueta WHERE id_etiqueta = ".$datos['id'];
		
		if($conexion->consulta($consulta) == 0){
			echo 'ERROR al Eliminar registro';
			exit(0);
			}

	break;

	case 4:

		$nombre = $conexion->consultadato("SELECT folio FROM tbl_reporte WHERE id_reporte = ".$datos['id']);
		$descripcion_log = "Se elimino el reporte con folio: ".$nombre;
		$consulta = "DELETE FROM tbl_reporte WHERE id_reporte = ".$datos['id'];
		
		if($conexion->consulta($consulta) == 0){
			echo 'ERROR al Eliminar registro';
			exit(0);
			}

	break;

	case 11:

		$permiso = $conexion->consultadato("SELECT nombre FROM tblc_permiso WHERE id_permiso = ".$datos['id']);

		$descripcion_log = "Se elimino el permiso: ".$permiso;
		$consulta = "UPDATE tblc_permiso SET fecha_eliminado = NOW() WHERE id_permiso = ".$datos['id'];
		
		if($conexion->consulta($consulta) == 0){
			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;

	case 106: // ELIMINAR ESTADO
		$nombre_estado=$conexion->consultadato("select nombre from tblc_estado where id_estado=".$datos['id']);
		$descripcion_log = "Se elimino estado con nombre ".$nombre_estado.", id: ".$datos['id'];
		$consulta = "UPDATE tblc_estado SET fecha_eliminado = NOW() WHERE id_estado = ".$datos['id'];
		
		if($conexion->consulta($consulta) == 0){
			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	
	case 107: // ELIMINAR MUNICIPIO
		$nombre_municipio=$conexion->consultadato("select nombre from tblc_municipio where id_municipio=".$datos['id']);
		$descripcion_log = "Se elimino municipio con nombre ".$nombre_municipio.", id: ".$datos['id'];
		$consulta = "UPDATE tblc_municipio SET fecha_eliminado = NOW() WHERE id_municipio = ".$datos['id'];
		
		if($conexion->consulta($consulta) == 0){
			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;

		case 34: ////////////////////////////////ELIMINAR PROCESO ELECTORAL
		
		if($conexion->consulta("UPDATE tblc_proceso_electoral SET fecha_eliminado = NOW() WHERE `id_proceso_electoral` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	case 35: ////////////////////////////////ELIMINAR TIPO ELECCION
		
		if($conexion->consulta("UPDATE tblc_tipo_eleccion SET fecha_eliminado = NOW() WHERE `id_tipo_eleccion` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	case 36: ////////////////////////////////ELIMINAR DISTRITO
		
		if($conexion->consulta("UPDATE tblc_distrito SET fecha_eliminado = NOW() WHERE `id_distrito` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	case 37: ////////////////////////////////ELIMINAR CASILLA
		
		if($conexion->consulta("UPDATE tblc_casilla SET fecha_eliminado = NOW() WHERE `id_casilla` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	case 38: ////////////////////////////////ELIMINAR SECCION
		
		if($conexion->consulta("UPDATE tblc_seccion SET fecha_eliminado = NOW() WHERE `id_seccion` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	case 39: ////////////////////////////////ELIMINAR SECCION
		
		if($conexion->consulta("UPDATE tblc_partido_politico SET fecha_eliminado = NOW() WHERE `id_partido_politico` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	case 40: ////////////////////////////////ELIMINAR REPRESENTANTE ELCETORAL
		
		if($conexion->consulta("UPDATE tblc_representante SET fecha_eliminado = NOW() WHERE `id_representante` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	case 41: ////////////////////////////////ELIMINAR CANDIDATO ELCETORAL
		
		if($conexion->consulta("UPDATE tblc_candidato SET fecha_eliminado = NOW() WHERE `id_candidato` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	case 42: ////////////////////////////////ELIMINAR RESULTADO
		
		if($conexion->consulta("DELETE FROM tbl_resultado WHERE `id_resultado` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;

	case 43: ////////////////////////////////ELIMINAR ESTATUS CASILLA
		
		if($conexion->consulta("DELETE FROM tbl_estatus_casilla WHERE `id_estatus_casilla` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;

	case 44: ////////////////////////////////ELIMINAR LISTA NOMINAL
		
		if($conexion->consulta("DELETE FROM tbl_lista_nominal WHERE `id_lista_nominal` = ".$datos['id']) == 0){

			echo 'ERROR al Eliminar registro';
			exit(0);
			}
	break;
	
}
$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$descripcion_log.'","'.$consulta.'")';
$conexion->consulta($log_actividad);

echo 'Actualizacion realizada satisfactoriamente';
?>