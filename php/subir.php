<?php 
	ini_set("session.cookie_lifetime","10800");
    ini_set("session.gc_maxlifetime","10800");
	@session_start();
	$id_usuario = $_SESSION['id_usuario'];
	$autentificado = $_SESSION['autentificado'];
	$nombre = $_SESSION['nombre'];
	$id_sesion_sistema = $_SESSION['id_sesion_sistema'];
	$id_proceso_electoral_sesion = (int) ($_SESSION['id_proceso_electoral'] ?? 0);
	if ($id_proceso_electoral_sesion > 0) {
		foreach (['id_proceso_electoral', 'idprocesoElect', 'proceso_electoral'] as $campoProceso) {
			if (array_key_exists($campoProceso, $_POST)) $_POST[$campoProceso] = $id_proceso_electoral_sesion;
		}
	}
	
	require ("clase_variables.php");
	require ("clase_mysql.php");
	require ("clase_funciones.php");
	include_once("clase_upload.php");
	
	$funciones = new Funciones();
	//LLAMAMOS A LA CLASE CONEXION
	$conexion = new DB_mysql(1);
	
	//llamamos a la clase upload para cargar archivos
	$upload = new upload();
	$datos = array();
	
	$ruta_cargar = "src='assets/images/cargando.gif'";
	$guardar = "Registro Guardado Satisfactoriamente";
	$editar = "Registro Modificado Satisfactoriamente";

	$datos = array();
	$datos['fecha_actual']=date("Y")."-".date("m")."-".date("d");
	$datos['hora_actual']=date("H").":".date("i").":".date("s");

	$datos['fecha_nombre']=date("d").date("m").date("Y");
	$datos['hora_nombre']=date("H").date("i").date("s");

	switch($_POST['opcion']){
		
			case 1://INSERTAR USUARIOS
				$datos['nombre'] = $funciones->limpia($_POST['nombre']);
				$datos['usuario'] = $funciones->limpia($_POST['usuario']);
				$datos['pass'] = $funciones->create_password($_POST['pass']);
				$datos['correo'] = $funciones->limpia($_POST['correo']);
				$datos['estatus'] = intval($_POST['estatus']);
				$datos['id_estado'] = intval($_POST['id_estado']);
				$datos['id_municipio'] = intval($_POST['id_municipio']);
				$datos['id_proceso_electoral'] = (int) ($_SESSION['id_proceso_electoral'] ?? 0) > 0
					? (int) $_SESSION['id_proceso_electoral']
					: max(0, intval($_POST['id_proceso_electoral'] ?? 0));

				if(isset($_POST['eliminar'])) $datos['eliminar'] = $_POST['eliminar']; else $datos['eliminar'] = 2;
				if(isset($_POST['editar'])) $datos['editar'] = $_POST['editar']; else $datos['editar'] = 2;
				
				$existente = $conexion->consultadato("SELECT COUNT(id_usuario) FROM tblc_usuario WHERE usuario like '".$datos['usuario']."'");
				if($existente != 0){
					echo '<script>parent.error("ERROR el usuario ingresado ya existe...");</script>';
					exit(0);
				}

				$consulta = "INSERT INTO tblc_usuario (nombre, usuario, pass, correo, estatus, eliminar, editar, fecha_registro, id_estado, id_municipio, id_proceso_electoral)
				VALUES ('".$datos['nombre']."', '".$datos['usuario']."', '".$datos['pass']."', '".$datos['correo']."', '".$datos['estatus']."', '".$datos['eliminar']."', '".$datos['editar']."', now(), '".$datos['id_estado']."', '".$datos['id_municipio']."', '".$datos['id_proceso_electoral']."') ";
				if($conexion->consulta($consulta) == 0){
					echo '<script>parent.error("ERROR al guardar el registro, intente de nuevo más tarde");</script>';
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se registro el usuario: '.$datos['nombre'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				// echo '<script>parent.satisfactorio("'.$guardar.'","../privilegios&id='.base64_encode($id).'");</script>';

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se ha registrado un usuario correctamente.",
					"funcion" => ["usuarios_registro"]
				]);
							
			break;
			case 2://MODIFICAR USUARIOS
				$datos['id'] = $_POST['id'];
				$datos['nombre'] = $funciones->limpia($_POST['nombre']);
				$datos['usuario'] = $funciones->limpia($_POST['usuario']);
				$datos['correo'] = $funciones->limpia($_POST['correo']);
				$datos['estatus'] = intval($_POST['estatus']);
				$datos['id_estado'] = intval($_POST['id_estado']);
				$datos['id_municipio'] = intval($_POST['id_municipio']);
				$datos['id_proceso_electoral'] = (int) ($_SESSION['id_proceso_electoral'] ?? 0) > 0
					? (int) $_SESSION['id_proceso_electoral']
					: max(0, intval($_POST['id_proceso_electoral'] ?? 0));
				$datos['pass'] = '';

				if(isset($_POST['eliminar'])) $datos['eliminar'] = $_POST['eliminar']; else $datos['eliminar'] = 2;
				if(isset($_POST['editar'])) $datos['editar'] = $_POST['editar']; else $datos['editar'] = 2;

				if($_POST['pass'] != ""){
					$datos['pass'] = ", pass = '".$funciones->create_password($_POST['pass'])."'";
				}
				
				$consulta = "UPDATE tblc_usuario SET id_estado = '".$datos['id_estado']."', id_municipio = '".$datos['id_municipio']."', id_proceso_electoral = '".$datos['id_proceso_electoral']."', nombre = '".$datos['nombre']."', usuario = '".$datos['usuario']."', correo = '".$datos['correo']."', estatus = '".$datos['estatus']."', eliminar = '".$datos['eliminar']."', editar = '".$datos['editar']."'".$datos['pass']." WHERE id_usuario = '".$datos['id']."'";
				if($conexion->consulta($consulta) == 0){
					echo '<script>parent.error("ERROR al actualizar el registro, intente de nuevo más tarde");</script>';
					exit(0);
				}
				if ((int) $datos['id'] === (int) $id_usuario) {
					$_SESSION['id_proceso_electoral'] = $datos['id_proceso_electoral'];
				}

				$msj = 'Se actualizaron los datos del usuario '.$datos['nombre'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se ha modificado un usuario correctamente.",
					"funcion" => ["usuarios_registro"]
				]);			
				
				break;

			case 3 :{ // GUARDAR PRIVILEGIOS
				$datos['id'] = $_POST['id'];
				$nombre = $conexion->fetch_array("SELECT nombre FROM tblc_usuario WHERE id_usuario = ".$datos['id']);
				
				$numero_arreglo = count($_POST['privilegios']);
				
				if($numero_arreglo != 0){
					
					$conexion->consulta("DELETE FROM tbl_usuario_permiso WHERE id_usuario = ".$datos['id']."");
					foreach($_POST['privilegios'] as $valor){

						$padre = $conexion->consultadato("SELECT id_padre FROM tblc_permiso WHERE id_permiso = ".$valor);

						if($conexion->consultadato("SELECT COUNT(id_permiso) FROM tbl_usuario_permiso WHERE id_permiso = ".$padre." AND id_usuario = ".$datos['id']) == 0 && $padre != 0){
							$conexion->consulta("INSERT INTO tbl_usuario_permiso (id_usuario, id_permiso) VALUES (".$datos['id'].", ".$padre.")");
						}

						$consulta = "INSERT INTO tbl_usuario_permiso (id_usuario, id_permiso) VALUES (".$datos['id'].", ".$valor.")";
						if($conexion->consulta($consulta) == 0){
							echo '<script>parent.error("ERROR al actualizar el registro, intente de nuevo más tarde");</script>';
							exit(0);
						}
					}

					$msj = 'Se actualizaron los permisos del usuario '.$nombre['nombre'].' con id: '.$datos['id'];
					$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
					$conexion->consulta($log_actividad);
					
					echo json_encode([
						"estatus" => 2,
						"tipo"    => "success",
						"titulo"  => "Listo!",
						"mensaje" => "Los permisos del usuario se registraron correctamente.",
					]);	
				}
				else{
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "error!",
						"mensaje" => "Seleccione al menos un permiso.",
					]);						
					exit(0);
				}
						
			}
			break;

			case 4://INSERTAR CATEGORÍA
				$datos['nombre'] = $funciones->limpia($_POST['nombre']);
				
				$existente = $conexion->consultadato("SELECT COUNT(id_categoria) FROM tblc_categoria WHERE nombre like '".$datos['nombre']."'");
				if($existente != 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "El banco ingresado ya existe.",
					]);						
					exit(0);
				}

				$consulta = "INSERT INTO tblc_categoria (nombre) VALUES ('".$datos['nombre']."') ";
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro intente de nuevo mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se registro la categoría '.$datos['nombre'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se registro la categoria correctamente.",
				]);								
			break;
			case 5://MODIFICAR CATEGORÍA
				$datos['id'] = $_POST['id'];
				$datos['nombre'] = $funciones->limpia($_POST['nombre']);
				
				$consulta = "UPDATE tblc_categoria SET nombre = '".$datos['nombre']."' WHERE id_categoria = '".$datos['id']."'";
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro intente de nuevo mas tarde.",
				]);	
				exit(0);
				}

				$msj = 'Se actualizaron la categoría '.$datos['nombre'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se registro la categoria correctamente.",
				]);	
			break;

			case 6 :  // GUARDAR MENUS
				$datos['menu'] = $_POST['menu'];
				$datos['menu2'] = $_POST['menu2'];
				$datos['archivo'] = $_POST['archivo'];
				$datos['ordenamiento'] = $_POST['ordenamiento'];
				$datos['icono'] = $_POST['icono'];
						
				$consulta = "INSERT INTO tblc_permiso (id_padre, nombre, archivo, ordenamiento, icono) 
				VALUES ('".$datos['menu2']."','".$datos['menu']."','".$datos['archivo']."','".$datos['ordenamiento']."','".$datos['icono']."')";

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
					"estatus" => 0,
					"tipo"    => "Warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro intente de nuevo mas tarde.",
				]);	
				exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se agrego el menú: '.$datos['menu'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se agrego el menu correctamente.",
					"funcion" => ["permisos_registro", "permisos_lista"],
				]);	

			break;
			case 7 : /// Modificar menu
				$datos['id'] = $_POST['id'];
				$datos['menu'] = $_POST['menu'];
				$datos['menu2'] = $_POST['menu2'];
				$datos['archivo'] = $_POST['archivo'];
				$datos['ordenamiento'] = $_POST['ordenamiento'];
				$datos['icono'] = $_POST['icono'];
						
				$consulta = "UPDATE tblc_permiso SET id_padre = '".$datos['menu2']."', nombre = '".$datos['menu']."', archivo = '".$datos['archivo']."', ordenamiento = '".$datos['ordenamiento']."', icono = '".$datos['icono']."' 
				WHERE id_permiso = ".$datos['id'];

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro intente de nuevo mas tarde.",
					]);	
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se actualizo el menú: '.$datos['menu'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizo el menu correctamente.",
					"funcion" => ["permisos_registro", "permisos_lista"],
				]);	
			break;

			case 8://MODIFICAR O INSERTAR CONFIGURACION
				$datos['fecha_inicio'] = date("Y-m-d",strtotime($funciones->limpia($_POST['fecha_inicio'])));
				$datos['fecha_termino'] = date("Y-m-d",strtotime($funciones->limpia($_POST['fecha_termino'])));
				$datos['msj_antes'] = addslashes($funciones->limpia($_POST['msj_antes']));
				$datos['msj_despues'] = addslashes($funciones->limpia($_POST['msj_despues']));

				$consulta = "REPLACE INTO tbl_configuracion (id_configuracion, fecha_inicio, fecha_termino, msj_antes, msj_despues) 
				VALUES (1,'".$datos['fecha_inicio']."','".$datos['fecha_termino']."','".$datos['msj_antes']."','".$datos['msj_despues']."')";

				if($conexion->consulta($consulta) == 0){echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "No se guardo el registro correctamente favor de intetar mas tarde.",
				]);	
				exit(0);
				}
				
				$msj = 'Se actualizaron los datos de configuración';
				$log_actividad1 = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad1);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizaron los datos correctamente.",
				]);	
			break;

			case 9: // SUBIR ETIQUETAS
				$datos['id_categoria']=(int) ($_POST['id_categoria'] ?? 0);
				$datos['etiqueta']=$funciones->limpia(trim($_POST['etiqueta'] ?? ''));

				if ($datos['id_categoria'] <= 0 || $datos['etiqueta'] === '' || strlen($datos['etiqueta']) > 100) {
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Datos incompletos",
						"mensaje" => "Selecciona una categoría e ingresa una etiqueta de hasta 100 caracteres.",
					]);
					exit(0);
				}
				
				$consulta = 'INSERT INTO tblc_etiqueta(id_categoria, etiqueta)VALUES("'.$datos['id_categoria'].'", "'.$datos['etiqueta'].'")';
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);	
					
				exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se registro la etiqueta '.$datos['etiqueta'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se registro la etiqueta correctamente.",
					"funcion" => ["etiquetas_registro", "etiquetas_lista"],
				]);	
			break;
			case 10: // MODIFICAR ETIQUETAS
				$datos['id']=(int) ($_POST['id'] ?? 0);
				$datos['id_categoria']=(int) ($_POST['id_categoria'] ?? 0);
				$datos['etiqueta']=$funciones->limpia(trim($_POST['etiqueta'] ?? ''));

				if ($datos['id'] <= 0 || $datos['id_categoria'] <= 0 || $datos['etiqueta'] === '' || strlen($datos['etiqueta']) > 100) {
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Datos incompletos",
						"mensaje" => "Selecciona una categoría e ingresa una etiqueta de hasta 100 caracteres.",
					]);
					exit(0);
				}
				
				$consulta = 'UPDATE tblc_etiqueta SET id_categoria="'.$datos['id_categoria'].'", etiqueta="'.$datos['etiqueta'].'" WHERE id_etiqueta='.$datos['id'];
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);	
				exit(0);
				}

				$msj = 'Se actualizaron la etiqueta: '.$datos['etiqueta'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "La etiqueta se actualizó correctamente.",
					"funcion" => ["etiquetas_registro", "etiquetas_lista"],
				]);	
			break;
			
			case 110: // SUBIR ESTADO
				$datos['clave']=$funciones->limpia($_POST['clave']);
				$datos['nombre']=$_POST['nombre'];
				$datos['latitud']=$_POST['latitud'];
				$datos['longitud']=$_POST['longitud'];

				if ($datos['clave'] === '' || strlen($datos['clave']) > 5) {
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Datos incompletos",
						"mensaje" => "La clave del estado es obligatoria y debe tener un máximo de 5 caracteres.",
					]);
					exit(0);
				}

				$consulta = 'INSERT INTO tblc_estado(clave, nombre, latitud, longitud) VALUES("'.$datos['clave'].'","'.$datos['nombre'].'","'.$datos['latitud'].'","'.$datos['longitud'].'")';
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guarar el registro favor de intentar mas tarde.",
				]);	
				exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se registro un estado '.$datos['nombre'].' con id: '.$id;
				$msj_log = $conexion->escapar_variable($msj);
				$consulta_log = $conexion->escapar_variable($consulta);
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.(int) $id_sesion_sistema.'", now(),"'.$msj_log.'","'.$consulta_log.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se registro un estado correctamente.",
				]);	
				break;
			
			case 111: //MODIFICAR ESTADO
				$datos['id']=(int) ($_POST['id'] ?? 0);
				$datos['clave']=$funciones->limpia($_POST['clave']);
				$datos['nombre']=$_POST['nombre'];
				$datos['latitud']=$_POST['latitud'];
				$datos['longitud']=$_POST['longitud'];

				if ($datos['id'] <= 0 || $datos['clave'] === '' || strlen($datos['clave']) > 5) {
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Datos incompletos",
						"mensaje" => "La clave del estado es obligatoria y debe tener un máximo de 5 caracteres.",
					]);
					exit(0);
				}
				
				$consulta = 'UPDATE tblc_estado SET clave="'.$datos['clave'].'", nombre="'.$datos['nombre'].'", latitud="'.$datos['latitud'].'", longitud="'.$datos['longitud'].'" WHERE id_estado='.$datos['id'];
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);	
				exit(0);
				}

				$msj = 'Se actualizaron datos del estado '.$datos['nombre'].' con id: '.$datos['id'];
				$msj_log = $conexion->escapar_variable($msj);
				$consulta_log = $conexion->escapar_variable($consulta);
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.(int) $id_sesion_sistema.'", now(),"'.$msj_log.'","'.$consulta_log.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizaron los datos del estado correctamente correctamente.",
				]);	
			break;
			
			case 112: // subir municipio
				$datos['id_estado']=(int) ($_POST['id_estado'] ?? 0);
				$datos['clave']=$funciones->limpia($_POST['clave']);
				$datos['nombre']=$conexion->escapar_variable(trim($_POST['nombre'] ?? ''));
				$datos['latitud']=$conexion->escapar_variable(trim($_POST['latitud'] ?? ''));
				$datos['longitud']=$conexion->escapar_variable(trim($_POST['longitud'] ?? ''));

				if ($datos['id_estado'] <= 0 || $datos['clave'] === '' || strlen($datos['clave']) > 5 || $datos['nombre'] === '') {
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Datos incompletos",
						"mensaje" => "La clave del municipio es obligatoria y debe tener un máximo de 5 caracteres.",
					]);
					exit(0);
				}

				$consulta = 'INSERT INTO tblc_municipio(id_estado,clave,nombre,latitud,longitud) VALUES("'.$datos['id_estado'].'", "'.$datos['clave'].'", "'.$datos['nombre'].'", "'.$datos['latitud'].'", "'.$datos['longitud'].'")';
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);	
				exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se registro un estado '.$datos['nombre'].' con id: '.$id;
				$msj_log = $conexion->escapar_variable($msj);
				$consulta_log = $conexion->escapar_variable($consulta);
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.(int) $id_sesion_sistema.'", now(),"'.$msj_log.'","'.$consulta_log.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se registro el estado correctamente correctamente.",
				]);	
			break;
			case 113: // modificar municipio
				$datos['id']=(int) ($_POST['id'] ?? 0);
				$datos['id_estado']=(int) ($_POST['id_estado'] ?? 0);
				$datos['clave']=$funciones->limpia($_POST['clave']);
				$datos['nombre']=$conexion->escapar_variable(trim($_POST['nombre'] ?? ''));
				$datos['latitud']=$conexion->escapar_variable(trim($_POST['latitud'] ?? ''));
				$datos['longitud']=$conexion->escapar_variable(trim($_POST['longitud'] ?? ''));

				if ($datos['id'] <= 0 || $datos['id_estado'] <= 0 || $datos['clave'] === '' || strlen($datos['clave']) > 5 || $datos['nombre'] === '') {
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Datos incompletos",
						"mensaje" => "La clave del municipio es obligatoria y debe tener un máximo de 5 caracteres.",
					]);
					exit(0);
				}

				$consulta = 'UPDATE tblc_municipio SET id_estado="'.$datos['id_estado'].'", clave="'.$datos['clave'].'", nombre="'.$datos['nombre'].'", latitud="'.$datos['latitud'].'", longitud="'.$datos['longitud'].'" WHERE id_municipio='.$datos['id'];
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$msj = 'Se actualizaron datos del muncipio '.$datos['nombre'].' con id: '.$datos['id'];
				$msj_log = $conexion->escapar_variable($msj);
				$consulta_log = $conexion->escapar_variable($consulta);
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.(int) $id_sesion_sistema.'", now(),"'.$msj_log.'","'.$consulta_log.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizaron los datos del municipio correctamente.",
				]);	
			break;

			case 114 :  // GUARDAR PROCESO ELECTORAL

				$datos['fecha'] = date("Y-m-d",strtotime($funciones->limpia($_POST['fecha'])));
				$datos['descripcion'] = $_POST['descripcion'];
				$datos['estatus'] = $_POST['estatus'];
				$datos['id_estado'] = $funciones->limpia($_POST['id_estado']);	
				$datos['id_municipio'] = $funciones->limpia($_POST['id_municipio']);
				$datos['eleccion'] = $funciones->limpia($_POST['eleccion']);
						
				$consulta = "INSERT INTO tblc_proceso_electoral (fecha, descripcion, estatus, id_tipo_eleccion, id_estado, id_municipio) 
				VALUES ('".$datos['fecha']."','".$datos['descripcion']."','".$datos['estatus']."','".$datos['eleccion']."','".$datos['id_estado']."','".$datos['id_municipio']."')";

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar elregistro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se agrego proceso electoral: '.$datos['fecha'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se agrego el proceso electoral correctamente.",
				]);	

			break;

			case 115 : /// Modificar PROCESO ELECTORAL

				$datos['id'] = $_POST['id'];
				$datos['fecha'] = date("Y-m-d",strtotime($funciones->limpia($_POST['fecha'])));
				$datos['descripcion'] = $_POST['descripcion'];
				$datos['estatus'] = $_POST['estatus'];
				$datos['id_estado'] = $funciones->limpia($_POST['id_estado']);	
				$datos['id_municipio'] = $funciones->limpia($_POST['id_municipio']);
				$datos['eleccion'] = $funciones->limpia($_POST['eleccion']);
						
				$consulta = "UPDATE tblc_proceso_electoral SET id_tipo_eleccion = '".$datos['eleccion']."', id_estado = '".$datos['id_estado']."', id_municipio = '".$datos['id_municipio']."', fecha = '".$datos['fecha']."', descripcion = '".$datos['descripcion']."', estatus = '".$datos['estatus']."' WHERE id_proceso_electoral = ".$datos['id'];

				if($conexion->consulta($consulta) == 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);						
				exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se actualizo proceso electoral: '.$datos['fecha'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizo el proceso electoral correctamente.",
				]);	
			break;

			case 116 :  // GUARDAR PARTIDO POLITICO

				$datos['nombre'] = $_POST['nombre'];
				$datos['color'] = $_POST['color'];
				$datos['ordenamiento'] = $_POST['ordenamiento'];
				$datos['estatus'] = $_POST['estatus'];
						
				if(isset($_FILES["icono"]["tmp_name"]) and $_FILES["icono"]["tmp_name"] != ""){						
					if($upload->load("icono") === false){
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Archivo no permitido.",
						]);	
						exit(0);
					}
					$archivo = $upload->nombre_final;
					$upload->setisimage(false);						   
					if($upload->save("../archivos/partido_politico/".$archivo) === false){					
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Error al subir el archivo.",
						]);	
						exit(0);
						}
					$datos['icono'] = $archivo;
					}
				else{ $datos['icono'] = '';	}

				$consulta = "INSERT INTO tblc_partido_politico (nombre, colo, icono, ordenamiento, estatus) 
				VALUES ('".$datos['nombre']."','".$datos['color']."','".$datos['icono']."','".$datos['ordenamiento']."','".$datos['estatus']."')";

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);	
				exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se agrego partido politico: '.$datos['nombre'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se registro el estado correctamente correctamente.",
				]);	
			break;

			case 117 : /// Modificar PARTIDO POLITICO

				$datos['id'] = $_POST['id'];
				$datos['nombre'] = $_POST['nombre'];
				$datos['color'] = $_POST['color'];
				$datos['ordenamiento'] = $_POST['ordenamiento'];
				$datos['estatus'] = $_POST['estatus'];
						
				if(isset($_FILES["icono"]["tmp_name"]) and $_FILES["icono"]["tmp_name"] != ""){					
					if($upload->load("icono") === false){
						echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Formato de archivo no permitido.",
					]);	
						exit(0);
					}
					$archivo = $upload->nombre_final;
					$upload->setisimage(false);				   
					if($upload->save("../archivos/partido_politico/".$archivo) === false){					
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Error al guardar el archivo.",
						]);	
						exit(0);
						}
					$datos['icono'] =", icono = '".$archivo."'";
					}
				else{ $datos['icono'] = ''; }

				$consulta = "UPDATE tblc_partido_politico SET estatus = '".$datos['estatus']."', nombre = '".$datos['nombre']."', colo = '".$datos['color']."', ordenamiento = '".$datos['ordenamiento']."'".$datos['icono']." WHERE id_partido_politico = ".$datos['id'];

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se actualizo partido politico: '.$datos['nombre'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!!",
					"mensaje" => "El partido politico se actualizo correctamente.",
				]);				
				break;

		
			case 118 :  // GUARDAR TIPO ELECCION

				$datos['nombre'] = $_POST['nombre'];
				$datos['estatus'] = $_POST['estatus'];
				$datos['tipo'] = $_POST['tipo'];
		
				$consulta = "INSERT INTO tblc_tipo_eleccion (nombre, estatus, tipo) 
				VALUES ('".$datos['nombre']."','".$datos['estatus']."','".$datos['tipo']."')";

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se agrego tipo de elección: '.$datos['nombre'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se agrego el tipo de eleccion correctamente.",
				]);	
			break;

			case 119 : /// Modificar TIPO ELECCION

				$datos['id'] = $_POST['id'];
				$datos['nombre'] = $_POST['nombre'];
				$datos['estatus'] = $_POST['estatus'];
				$datos['tipo'] = $_POST['tipo'];
		
				$consulta = "UPDATE tblc_tipo_eleccion SET nombre = '".$datos['nombre']."', estatus = '".$datos['estatus']."', tipo = '".$datos['tipo']."' WHERE id_tipo_eleccion = ".$datos['id'];

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se actualizo tipo de elección: '.$datos['fecha'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizo el tipo dem eleccion correctamente.",
				]);				
				break;

			case 120 :  // GUARDAR DISTRITO

				$datos['id_estado'] = $_POST['id_estado'];
				$datos['estatus'] = $_POST['estatus'];
				$datos['nombre'] = $_POST['nombre'];
				$datos['coordenadas'] = $_POST['coordenadas'];
						
				$consulta = "INSERT INTO tblc_distrito (nombre, coordenadas, id_estado, estatus) 
				VALUES ('".$datos['nombre']."','".$datos['coordenadas']."','".$datos['id_estado']."','".$datos['estatus']."')";

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se agrego el distrito: '.$datos['nombre'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se agrego el distrito correctamente.",
				]);	
			break;

			case 121 : /// Modificar DISTRITO

				$datos['id'] = $_POST['id'];
				$datos['id_estado'] = $_POST['id_estado'];
				$datos['estatus'] = $_POST['estatus'];
				$datos['nombre'] = $_POST['nombre'];
				$datos['coordenadas'] = $_POST['coordenadas'];
						
				$consulta = "UPDATE tblc_distrito SET nombre = '".$datos['nombre']."', coordenadas = '".$datos['coordenadas']."', id_estado = '".$datos['id_estado']."', estatus = '".$datos['estatus']."' WHERE id_distrito = ".$datos['id'];

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se actualizo el distrito: '.$datos['nombre'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizo el registro correctamente.",
				]);				
				break;

			case 122 :  // GUARDAR SECCION

				$datos['id_municipio'] = $_POST['id_municipio'];
				$datos['estatus'] = $_POST['estatus'];
				$datos['id_distrito'] = $_POST['id_distrito'];
				$datos['nombre'] = $_POST['nombre'];
				$datos['coordenadas'] = $_POST['coordenadas'];
						
				$existente = $conexion->consultadato("SELECT COUNT(id_seccion) FROM tblc_seccion WHERE nombre = '".$datos['nombre']."' AND id_distrito = ".$datos['id_distrito']);

				if($existente != 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "La seccion ya fue registrada",
					]);						
					exit(0);
				}

				$consulta = "INSERT INTO tblc_seccion (nombre, coordenadas, id_distrito, id_municipio, estatus) 
				VALUES ('".$datos['nombre']."','".$datos['coordenadas']."','".$datos['id_distrito']."','".$datos['id_municipio']."','".$datos['estatus']."')";

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se agrego la sección: '.$datos['nombre'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se agrego la seccion correctamente.",
				]);	
			break;

			case 123 : /// Modificar SECCION

				$datos['id'] = $_POST['id'];
				$datos['id_distrito'] = $_POST['id_distrito'];
				$datos['nombre'] = $_POST['nombre'];
				$datos['coordenadas'] = $_POST['coordenadas'];
				$datos['id_municipio'] = $_POST['id_municipio'];
				$datos['estatus'] = $_POST['estatus'];
						
				$consulta = "UPDATE tblc_seccion SET estatus = '".$datos['estatus']."', id_municipio = '".$datos['id_municipio']."', nombre = '".$datos['nombre']."', coordenadas = '".$datos['coordenadas']."', id_distrito = '".$datos['id_distrito']."' WHERE id_seccion = ".$datos['id'];

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);	
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se actualizo la sección: '.$datos['nombre'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizon la seccion correctamente.",
				]);				
				break;


			case 124 :  // GUARDAR CASILLA ELECTORAL

				$datos['id_estado'] = $_POST['id_estado'];
				$datos['id_municipio'] = $_POST['id_municipio'];
				$datos['id_seccion'] = $_POST['id_seccion'];
				$datos['seccion'] = $conexion->consultadato("SELECT nombre FROM tblc_seccion WHERE id_seccion = ".$datos['id_seccion']);
				$datos['numero'] = $_POST['numero'];
				$datos['direccion'] = $_POST['direccion'];
				$datos['txtLatitud'] = $_POST['txtLatitud'];
				$datos['txtLongitud'] = $_POST['txtLongitud'];
				$datos['tipo'] = $_POST['tipo'];
				$datos['num_contigua'] = $_POST['num_contigua'];
				$datos['nombre'] = $_POST['nombre'];
				$datos['estatus'] = $_POST['estatus'];
						
				$consulta = "INSERT INTO tblc_casilla (id_seccion, numero, nombre, tipo, id_municipio, latitud, longitud, direccion, seccion, num_contigua, estatus) 
				VALUES ('".$datos['id_seccion']."','".$datos['numero']."','".$datos['nombre']."','".$datos['tipo']."','".$datos['id_municipio']."','".$datos['txtLatitud']."','".$datos['txtLongitud']."','".$datos['direccion']."','".$datos['seccion']."','".$datos['num_contigua']."','".$datos['estatus']."')";

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se agrego la casilla: '.$datos['nombre'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				//echo '<script>parent.satisfactorio("'.$guardar.'","../casilla_electoral");</script>';
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se agrego la casilla correctamente.",
				]);	
			break;

			case 125 : /// Modificar CASILLA ELECTORAL

				$datos['id'] = $_POST['id'];
				$datos['id_estado'] = $_POST['id_estado'];
				$datos['id_municipio'] = $_POST['id_municipio'];
				$datos['id_seccion'] = $_POST['id_seccion'];
				$datos['seccion'] = $conexion->consultadato("SELECT nombre FROM tblc_seccion WHERE id_seccion = ".$datos['id_seccion']);
				$datos['numero'] = $_POST['numero'];
				$datos['direccion'] = $_POST['direccion'];
				$datos['txtLatitud'] = $_POST['txtLatitud'];
				$datos['txtLongitud'] = $_POST['txtLongitud'];
				$datos['tipo'] = $_POST['tipo'];
				$datos['num_contigua'] = $_POST['num_contigua'];
				$datos['nombre'] = $_POST['nombre'];
				$datos['estatus'] = $_POST['estatus'];

				$consulta = "UPDATE tblc_casilla SET id_seccion = '".$datos['id_seccion']."', estatus = '".$datos['estatus']."', nombre = '".$datos['nombre']."', numero = '".$datos['numero']."', tipo = '".$datos['tipo']."', id_municipio = '".$datos['id_municipio']."', latitud = '".$datos['txtLatitud']."', longitud = '".$datos['txtLongitud']."', direccion = '".$datos['direccion']."', seccion = '".$datos['seccion']."', num_contigua = '".$datos['num_contigua']."' WHERE id_casilla = ".$datos['id'];

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$id = $conexion->ultimoid();

				$msj = 'Se actualizo la casilla: '.$datos['numero'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizo la casilla correctamente.",
				]);				
				break;

			case 126://INSERTAR REPRESENTANTE DE CASILLA
				
				$datos['id_proceso_electoral'] = $funciones->limpia($_POST['id_proceso_electoral']);
				$datos['casilla'] = $funciones->limpia($_POST['casilla']);
				$datos['nombre'] = $funciones->limpia($_POST['nombre']);
				$datos['pass'] = $funciones->create_password($_POST['pass']);
				$datos['telefono'] = $funciones->limpia($_POST['telefono']);
				$datos['correo'] = $funciones->limpia($_POST['correo']);
				$datos['usuario'] = $funciones->limpia($_POST['usuario']);
				$datos['id_municipio'] = $funciones->limpia($_POST['id_municipio']);
				$datos['estatus'] = $funciones->limpia($_POST['estatus']);
				
				$existente = $conexion->consultadato("SELECT COUNT(id_representante) FROM tblc_representante WHERE usuario like '".$datos['usuario']."'");
				if($existente != 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "El usuario ingresado ya existe.",
					]);						
					exit(0);
				}

				$consulta = "INSERT INTO tblc_representante (id_proceso_electoral, nombre, telefono, correo, usuario, pass, id_casilla, id_municipio, estatus) 
				VALUES ('".$datos['id_proceso_electoral']."', '".$datos['nombre']."', '".$datos['telefono']."', '".$datos['correo']."', '".$datos['usuario']."', '".$datos['pass']."', '".$datos['casilla']."', '".$datos['id_municipio']."', '".$datos['estatus']."') ";
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}
				$id = $conexion->ultimoid();
				$msj = 'Se registro el representante de casilla: '.$datos['nombre'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Se registro el representamete correctamente.",
				]);								
			break;

			case 127://MODIFICAR REPRESENTANTE DE CASILLA
				$datos['id'] = $_POST['id'];
				$datos['id_proceso_electoral'] = $funciones->limpia($_POST['id_proceso_electoral']);
				$datos['casilla'] = $funciones->limpia($_POST['casilla']);
				$datos['nombre'] = $funciones->limpia($_POST['nombre']);
				$datos['telefono'] = $funciones->limpia($_POST['telefono']);
				$datos['correo'] = $funciones->limpia($_POST['correo']);
				$datos['usuario'] = $funciones->limpia($_POST['usuario']);
				$datos['id_municipio'] = $funciones->limpia($_POST['id_municipio']);
				$datos['estatus'] = $funciones->limpia($_POST['estatus']);

				if($_POST['pass'] != ""){
					$datos['pass'] = ", pass = '".$funciones->create_password($_POST['pass'])."'";
				}
				
				$consulta = "UPDATE tblc_representante SET estatus = '".$datos['estatus']."', id_proceso_electoral = '".$datos['id_proceso_electoral']."', id_municipio = '".$datos['id_municipio']."', nombre = '".$datos['nombre']."', telefono = '".$datos['telefono']."', correo = '".$datos['correo']."', usuario = '".$datos['usuario']."', id_casilla = '".$datos['casilla']."'".$datos['pass']." WHERE id_representante = '".$datos['id']."'";
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$msj = 'Se actualizaron los datos del representante de casilla '.$datos['nombre'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualixaron los datos correctamente.",
				]);				
				break;

			case 128://INSERTAR CANDIDATO

				$datos['proceso_electoral'] = $funciones->limpia($_POST['proceso_electoral']);
				$datos['nombre'] = $funciones->limpia($_POST['nombre']);
				$datos['principal'] = $funciones->limpia($_POST['principal']);
				$datos['ordenamiento'] = $funciones->limpia($_POST['ordenamiento']);

				$consulta = "INSERT INTO tblc_candidato (id_proceso_electoral, nombre, principal, ordenamiento) 
				VALUES ('".$datos['proceso_electoral']."', '".$datos['nombre']."', '".$datos['principal']."', '".$datos['ordenamiento']."') ";

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}
				$id = $conexion->ultimoid();

				$numero_arreglo = count($_POST['partido']);
				
				if($numero_arreglo != 0){
					
					//$conexion->consulta("DELETE FROM tblc_candidato_partido WHERE id_candidato = ".$datos['id']."");
					foreach($_POST['partido'] as $valor){

						$consulta = "INSERT INTO tblc_candidato_partido (id_candidato, id_partido_politico) VALUES (".$id.", ".$valor.")";
						$conexion->consulta($consulta);
					}

				}

				$msj = 'Se registro un candidato electoral: '.$datos['nombre'].' con id: '.$id;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se registro un candidato electoral correctamente.",
				]);								
			break;

			case 129://MODIFICAR CANDIDATO

				$datos['id'] = $_POST['id'];
				$datos['proceso_electoral'] = $funciones->limpia($_POST['proceso_electoral']);
				$datos['nombre'] = $funciones->limpia($_POST['nombre']);
				$datos['principal'] = $funciones->limpia($_POST['principal']);
				$datos['ordenamiento'] = $funciones->limpia($_POST['ordenamiento']);

				$consulta = "UPDATE tblc_candidato SET id_proceso_electoral = '".$datos['proceso_electoral']."', nombre = '".$datos['nombre']."', principal = '".$datos['principal']."', ordenamiento = '".$datos['ordenamiento']."' WHERE id_candidato = '".$datos['id']."'";
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);						
					exit(0);
				}

				$numero_arreglo = count($_POST['partido']);
				$conexion->consulta("DELETE FROM tblc_candidato_partido WHERE id_candidato = ".$datos['id']."");

				if($numero_arreglo != 0){
					
					foreach($_POST['partido'] as $valor){

						$consulta = "INSERT INTO tblc_candidato_partido (id_candidato, id_partido_politico) VALUES (".$datos['id'].", ".$valor.")";
						$conexion->consulta($consulta);
					}

				}

				$msj = 'Se actualizaron los datos del candidato electoral '.$datos['nombre'].' con id: '.$datos['id'];
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizaron los datos correctamente.",
				]);	
			break;

			case 130://MODIFICAR RESULTADO DE VOTACION
			$datos['id'] = $_POST['id'];
			foreach($_POST['resultado'] as $indice => $idresultados){    
			  		$total_votos = $_POST['votos_total'.$idresultados];
					
					$consulta = "UPDATE tbl_resultado SET resultado = '".$total_votos."' WHERE id_resultado = '".$idresultados."'";
						if($conexion->consulta($consulta) == 0){
							echo json_encode([
								"estatus" => 0,
								"tipo"    => "warning",
								"titulo"  => "Error!",
								"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
							]);								
							exit(0);
						}

				$msj = 'Se actualizaron el resultado de la votacion electoral con resultado'.$total_votos.' con id: '.$idresultados;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

			    }
				
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se actualizaron el resultado de la votacion electoral con resultado ' . $total_votos . ' con id: ' . $idresultados . '",
				]);	
			break;

			case 34: // MODIFICA ESTATUS DE ACCESO A LA APP DEL CLIENTE
				$datos['id'] = $_POST['id']; 
				$datos['idreprent'] = $_POST['idreprent'];
				$datos['accesoapp'] = $_POST['accesoapp'];	
											
				$consulta = "UPDATE tbl_representante_movil SET estatus = '".$datos['accesoapp']."' WHERE id_representante_movil = ".$datos['id'];
				
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);									
					exit(0);
					}

				
				$msj = 'Se actualizaron el estatus de la app con id'.$datos['id'].' con id: ';
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);

				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => 'Se actualizaron el estatus de la app con id ' . $datos['id'] . '',
				]);		
				break;


			case 131://SUBIR RESULTADO DE VOTACION

			$datos['idprocesoElect'] = $_POST['idprocesoElect'];
			$datos['idcasillaElect'] = $_POST['idcasillaElect'];
			$datos['votos_null'] = $_POST['votos_null'];
			$datos['no_registrados'] = $_POST['no_registrados'];
			$datos['total_votos'] = $_POST['total_votos'];

			$votos = $_POST['votos'];
			$candidato = $_POST['candidato'];

			$idcandidatoPrin = '';
			if ($conexion->consultadato("SELECT COUNT(id_representante) FROM tblc_representante WHERE id_casilla =".$datos['idcasillaElect']) != 0) {
				$datos['idrepreseElect'] = $conexion->consultadato("SELECT id_representante FROM tblc_representante WHERE id_casilla =".$datos['idcasillaElect']);
			}else{
				$datos['idrepreseElect'] = '0';
			}   

			if(isset($_FILES["acta_file"]["tmp_name"]) and $_FILES["acta_file"]["tmp_name"] != ""){						
					if($upload->load("acta_file") === false){
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Formato de archivo no permitido.",
						]);	
						exit(0);
					}
					$archivo = $upload->nombre_final;
					$upload->setisimage(false);						   
					if($upload->save("../archivos/actas_eleccion/".$archivo) === false){					
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Fallo al guardar el archivo.",
						]);	
						exit(0);
						}
					$datos['acta_file'] = $archivo;
					}
			else{ 
				$datos['acta_file'] = '';
				}
		 
		    foreach($_POST['partido'] as $indice => $idpartido){  
				
  				$total_votos = $votos[$indice];
  				$idcandidato = $candidato[$indice];

			    $consulta = "REPLACE INTO tbl_resultado(id_candidato, id_casilla, id_partido_politico, resultado, id_representante, fecha_registro, id_usuario) VALUES(".$idcandidato.", ".$datos['idcasillaElect'].", ".$idpartido.", '".$total_votos."' , '".$datos['idrepreseElect']."', NOW(), ".$id_usuario.")";

			        if($conexion->consulta($consulta) == 0){
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
						]);							
						exit(0);
			        }
			    $msj = 'Se guardaron el resultado de la votacion electoral con resultado'.$total_votos.' con id del candidato: '.$idcandidato;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);
			  
			}

			$acta = $conexion->consultadato("SELECT id_acta FROM tbl_acta WHERE id_proceso_electoral = ".$datos['idprocesoElect']." AND id_casilla = ".$datos['idcasillaElect']);

			if($conexion->numregistros() == 0){
				$consultacta = "INSERT INTO tbl_acta(id_proceso_electoral, id_casilla, archivo, id_representante, fecha_registro, votos_nulos, no_registrados, total_votos) VALUES(".$datos['idprocesoElect'].", ".$datos['idcasillaElect'].", '".$datos['acta_file']."' , '".$datos['idrepreseElect']."', NOW(), '".$datos['votos_null']."', '".$datos['no_registrados']."', '".$datos['total_votos']."')";
			}
			else{
				$consultacta = "UPDATE tbl_acta SET total_votos = '".$datos['total_votos']."', archivo = '".$datos['acta_file']."', id_representante = '".$datos['idrepreseElect']."', votos_nulos = '".$datos['votos_null']."', no_registrados = '".$datos['no_registrados']."' WHERE id_acta = ".$acta;
			}
	   		$conexion->consulta($consultacta);
		 
			   echo json_encode([
				"estatus" => 2,
				"tipo"    => "success",
				"titulo"  => "Listo!",
				"mensaje" => 'Se guardaron el resultado de la votacion electoral con resultado'.$total_votos.' con id del candidato: '.$idcandidato.'',
			]);	
		
		break;

		case 132://SUBIR REPORTE
			$datos['nombre'] = $_POST['nombre'];
			$datos['tipo_reporte'] = $_POST['tipo_reporte'];
			$datos['tipo_registro'] = $_POST['tipo_registro'];
			$datos['id_municipio'] = $_POST['id_municipio'];
			$datos['id_casilla'] = $_POST['id_casilla'];
			$datos['direccion'] = addslashes($_POST['direccion']);
			$datos['descripcion'] = addslashes($_POST['descripcion']);

			if(isset($_FILES["archivo"]["tmp_name"]) and $_FILES["archivo"]["tmp_name"] != ""){						
					if($upload->load("archivo") === false){
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Formato de archivo no permitido favor de intentar mas tarde.",
						]);		
						exit(0);
					}
					$archivo = $upload->nombre_final;
					$upload->setisimage(false);						   
					if($upload->save("../archivos/".$archivo) === false){					
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Error al guardar el archivo.",
						]);		
						exit(0);
						}
					$datos['archivo'] = $archivo;
					}
			else{ 
				$datos['archivo'] = '';
				}

			$MySQL = "INSERT INTO tbl_reporte(folio, id_municipio, nombre,tipo_reporte,descripcion,direccion,foto,tipo_registro,id_casilla, fecha_registro) 
			VALUES ('0','".$datos['id_municipio']."','".$datos['nombre']."','".$datos['tipo_reporte']."','".$datos['descripcion']."','".$datos['direccion']."','".$datos['archivo']."','".$datos['tipo_registro']."','".$datos['id_casilla']."',Now())";
			
			if($conexion->consulta($MySQL) == 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);						
				exit(0);
			}

			$id = $conexion->ultimoid();
			$folio = $id.date('dmY');

			$MySQL = "UPDATE tbl_reporte SET folio ='".$folio."' WHERE id_reporte = ".$id;
			if($conexion->consulta($MySQL) == 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);						
				exit(0);
			}

			if(isset($_POST['etiquetas'])){
				foreach($_POST['etiquetas'] as $indice => $etiqueta){
					$consulta = "INSERT INTO tbl_reporte_etiqueta(id_reporte,id_etiqueta) VALUES('".$id."','".$etiqueta."')";
					$conexion->consulta($consulta);
					}
				}
		 
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se guardo correctamente el registro.",
				]);				
		break;

		case 133://MODIFICAR REPORTE

			$datos['id'] = $_POST['id'];
			$datos['nombre'] = $_POST['nombre'];
			$datos['tipo_reporte'] = $_POST['tipo_reporte'];
			$datos['tipo_registro'] = $_POST['tipo_registro'];
			$datos['id_casilla'] = $_POST['id_casilla'];
			$datos['id_municipio'] = $_POST['id_municipio'];
			$datos['direccion'] = addslashes($_POST['direccion']);
			$datos['descripcion'] = addslashes($_POST['descripcion']);

			if(isset($_FILES["archivo"]["tmp_name"]) and $_FILES["archivo"]["tmp_name"] != ""){						
					if($upload->load("archivo") === false){
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Formato de archivo no permitido favor de intentar mas tarde.",
						]);			
						exit(0);
					}
					$archivo = $upload->nombre_final;
					$upload->setisimage(false);						   
					if($upload->save("../archivos/".$archivo) === false){					
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Fallo al guardar el archivo.",
						]);		
						exit(0);
						}
					$datos['archivo'] = ', foto = "'.$archivo.'"';
					}
			else{ 
				$datos['archivo'] = '';
				}

			$MySQL = "UPDATE tbl_reporte SET id_municipio = '".$datos['id_municipio']."', id_casilla = '".$datos['id_casilla']."', nombre = '".$datos['nombre']."',tipo_reporte = '".$datos['tipo_reporte']."',descripcion = '".$datos['descripcion']."',direccion = '".$datos['direccion']."', tipo_registro = '".$datos['tipo_registro']."'".$datos['archivo']." WHERE id_reporte = ".$datos['id'];
			
			if($conexion->consulta($MySQL) == 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);						
				exit(0);
			}

			$conexion->consulta("DELETE FROM tbl_reporte_etiqueta WHERE id_reporte = ".$datos['id']);
			if(isset($_POST['etiquetas'])){
				foreach($_POST['etiquetas'] as $indice => $etiqueta){
					$consulta = "INSERT INTO tbl_reporte_etiqueta(id_reporte,id_etiqueta) VALUES('".$datos['id']."','".$etiqueta."')";
					$conexion->consulta($consulta);
					}
				}
		 
				echo json_encode([
					"estatus" => 2,
					"tipo"    => "success",
					"titulo"  => "Listo!",
					"mensaje" => "Se registro correctamente.",
				]);			
		break;

		case 134://SUBIR ESTATUS CASILLA

			$datos['id_proceso_electoral'] = $_POST['id_proceso_electoral'];
			$datos['id_casilla'] = $_POST['id_casilla'];
			$datos['fecha_hora'] = date('Y/m/d')." ".$_POST['hora'].":00";
			$datos['tipo'] = $_POST['tipo'];
			$datos['observaciones'] = addslashes($_POST['observaciones']);

			$existente = $conexion->consultadato("SELECT COUNT(id_estatus_casilla) FROM tbl_estatus_casilla WHERE id_casilla = ".$datos['id_casilla']." AND tipo = ".$datos['tipo']." AND id_proceso_electoral = '".$datos['id_proceso_electoral']."'");

				if($existente != 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "El registro ya existe.",
					]);						
					exit(0);
				}

			$MySQL = "INSERT INTO tbl_estatus_casilla(id_proceso_electoral, id_casilla, fecha_hora,tipo,observaciones, id_usuario, fecha_registro) 
			VALUES ('".$datos['id_proceso_electoral']."','".$datos['id_casilla']."','".$datos['fecha_hora']."','".$datos['tipo']."','".$datos['observaciones']."','".$id_usuario."',Now())";
			
			if($conexion->consulta($MySQL) == 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);					
				exit(0);
			}

			$id = $conexion->ultimoid();
		 
			echo json_encode([
				"estatus" => 2,
				"tipo"    => "success",
				"titulo"  => "Listo!",
				"mensaje" => "El registro se guardo correctamente.",
			]);		
		break;

		case 135://MODIFICAR REPORTE

			$datos['id'] = $_POST['id'];
			$datos['id_proceso_electoral'] = $_POST['id_proceso_electoral'];
			$datos['id_casilla'] = $_POST['id_casilla'];
			$datos['fecha_hora'] = date('Y/m/d')." ".$_POST['hora'].":00";
			$datos['tipo'] = $_POST['tipo'];
			$datos['observaciones'] = addslashes($_POST['observaciones']);

			$MySQL = "UPDATE tbl_estatus_casilla SET id_proceso_electoral = '".$datos['id_proceso_electoral']."', id_casilla = '".$datos['id_casilla']."', fecha_hora = '".$datos['fecha_hora']."',tipo = '".$datos['tipo']."',observaciones = '".$datos['observaciones']."' WHERE id_estatus_casilla = ".$datos['id'];
			
			if($conexion->consulta($MySQL) == 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);				
				exit(0);
			}
		 
			echo json_encode([
				"estatus" => 2,
				"tipo"    => "success",
				"titulo"  => "Listo!",
				"mensaje" => "El registro se guardo correctamente.",
			]);			
		break;

		case 136://INSERTAR LISTA NOMINAL

			$datos['id_proceso_electoral'] = $funciones->limpia($_POST['id_proceso_electoral']);
			$datos['casilla'] = $funciones->limpia($_POST['casilla']);
			$datos['nombre'] = $funciones->limpia($_POST['nombre']);
			$datos['clave_elector'] = $funciones->limpia($_POST['clave_elector']);
			$datos['folio'] = $funciones->limpia($_POST['folio']);
			
			$existente = $conexion->consultadato("SELECT COUNT(id_lista_nominal) FROM tbl_lista_nominal WHERE clave_elector = '".$datos['clave_elector']."'");
			if($existente != 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "El registro ingresado ya existe.",
				]);						
				exit(0);
			}

			$consulta = "INSERT INTO tbl_lista_nominal (folio, id_proceso_electoral, nombre, id_casilla, clave_elector, fecha_registro) 
			VALUES ('".$datos['folio']."', '".$datos['id_proceso_electoral']."', '".$datos['nombre']."', '".$datos['casilla']."', '".$datos['clave_elector']."', NOW()) ";
			if($conexion->consulta($consulta) == 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);						
				exit(0);
			}
			$id = $conexion->ultimoid();

			// $folio = $id.'-'.date('mY');

			// $sql2 = "UPDATE tbl_lista_nominal SET folio ='".$folio."' WHERE id_lista_nominal = ".$id;
			// $conexion->consulta($sql2);

			$msj = 'Se registro la lista nominal: '.$datos['nombre'].' con id: '.$id;
			$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
			$conexion->consulta($log_actividad);

			echo json_encode([
				"estatus" => 2,
				"tipo"    => "success",
				"titulo"  => "Listo!",
				"mensaje" => "Se agrego la lista correctamente.",
			]);								
		break;

		case 137://MODIFICAR LISTA NOMINAL

			$datos['id'] = $_POST['id'];
			$datos['id_proceso_electoral'] = $funciones->limpia($_POST['id_proceso_electoral']);
			$datos['casilla'] = $funciones->limpia($_POST['casilla']);
			$datos['nombre'] = $funciones->limpia($_POST['nombre']);
			$datos['clave_elector'] = $funciones->limpia($_POST['clave_elector']);
			$datos['folio'] = $funciones->limpia($_POST['folio']);
			
			$consulta = "UPDATE tbl_lista_nominal SET folio = '".$datos['folio']."', nombre = '".$datos['nombre']."', id_proceso_electoral = '".$datos['id_proceso_electoral']."', id_casilla = '".$datos['casilla']."', clave_elector = '".$datos['clave_elector']."' WHERE id_lista_nominal = '".$datos['id']."'";
			if($conexion->consulta($consulta) == 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
				]);						
				exit(0);
			}

			$msj = 'Se actualizaron la lista nominal '.$datos['nombre'].' con id: '.$datos['id'];
			$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
			$conexion->consulta($log_actividad);

			echo json_encode([
				"estatus" => 2,
				"tipo"    => "success",
				"titulo"  => "Listo!",
				"mensaje" => "El registro se guardo correctamente.",
			]);				
			break;


		case 138://INSERTAR LISTA NOMINAL

			$datos['id_proceso_electoral'] = $funciones->limpia($_POST['id_proceso_electoral']);
			$datos['casilla'] = $funciones->limpia($_POST['casilla']);
			// $datos['nombre'] = $funciones->limpia($_POST['nombre']);
			// $datos['clave_elector'] = $funciones->limpia($_POST['clave_elector']);
			// $datos['folio'] = $funciones->limpia($_POST['folio']);
			
			$existente = $conexion->consultadato("SELECT COUNT(id_lista_nominal) FROM tbl_lista_nominal WHERE clave_elector = '".$datos['clave_elector']."'");
			if($existente != 0){
				echo json_encode([
					"estatus" => 0,
					"tipo"    => "warning",
					"titulo"  => "Error!",
					"mensaje" => "El registro ingresado ya existe.",
				]);						
				exit(0);
			}

			$nombre = count($_POST['nombre']);

			foreach($_POST['nombre'] as $idx => $valor){
				$datos['nombre'] = $valor;
				$datos['clave_elector'] = $_POST['clave_elector'][$idx];
				$datos['folio'] = $idx + 1;
				$consulta = "INSERT INTO tbl_lista_nominal (folio, id_proceso_electoral, nombre, id_casilla, clave_elector, fecha_registro) 
				VALUES ('".$datos['folio']."', '".$datos['id_proceso_electoral']."', '".$datos['nombre']."', '".$datos['casilla']."', '".$datos['clave_elector']."', NOW()) ";
				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);							
					exit(0);
				}
			}

			

			$msj = 'Se registro la lista nominal: '.$datos['nombre'].' con id: '.$id;
			$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
			$conexion->consulta($log_actividad);

			echo json_encode([
				"estatus" => 2,
				"tipo"    => "success",
				"titulo"  => "Listo!",
				"mensaje" => "Se registro la lista nominal correctamenete",
			]);								
		break;

		case 139://MODIFICAR LISTA NOMINAL

			// $datos['id'] = $_POST['id'];
			// $datos['id_proceso_electoral'] = $funciones->limpia($_POST['id_proceso_electoral']);
			// $datos['casilla'] = $funciones->limpia($_POST['casilla']);
			// $datos['nombre'] = $funciones->limpia($_POST['nombre']);
			// $datos['clave_elector'] = $funciones->limpia($_POST['clave_elector']);
			// $datos['folio'] = $funciones->limpia($_POST['folio']);
			
			// echo '<script languaje="javascript"> parent.document.getElementById("cargando").innerHTML= "<center><img  '.$ruta_cargar.'/></center>"; </script>';	

			// $consulta = "UPDATE tbl_lista_nominal SET folio = '".$datos['folio']."', nombre = '".$datos['nombre']."', id_proceso_electoral = '".$datos['id_proceso_electoral']."', id_casilla = '".$datos['casilla']."', clave_elector = '".$datos['clave_elector']."' WHERE id_lista_nominal = '".$datos['id']."'";
			// if($conexion->consulta($consulta) == 0){
			// 	echo '<script>parent.error("ERROR al actualizar el registro, intente de nuevo más tarde");</script>';
			// 	exit(0);
			// }

			// $msj = 'Se actualizaron la lista nominal '.$datos['nombre'].' con id: '.$datos['id'];
			// $log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
			// $conexion->consulta($log_actividad);

			// echo '<script>parent.satisfactorio("'.$editar.'","../lista_nominal&id='.base64_encode($datos['id']).'");</script>';
		break;

		case 140://SUBIR RESULTADO DE VOTACION

			$datos['idprocesoElect'] = $_POST['idprocesoElect'];
			$datos['votos_null'] = $_POST['votos_null'];
			$datos['no_registrados'] = $_POST['no_registrados'];
			$datos['total_votos'] = $_POST['total_votos'];

			$votos = $_POST['votos'];
			$candidato = $_POST['candidato'];

			$datos['id_estado'] = $_POST['id_estado'];
				$datos['id_municipio'] = $_POST['id_municipio'];
				$datos['seccion'] = $_POST['seccion'];
				$datos['numero'] = 0;
				$datos['direccion'] = '';
				$datos['txtLatitud'] = '';
				$datos['txtLongitud'] = '';
				$datos['tipo'] = $_POST['tipo'];
				$datos['num_contigua'] = 0;
				$datos['nombre'] = $_POST['nombre'];


			$existente = $conexion->consultadato("SELECT COUNT(id_casilla) FROM tblc_casilla WHERE nombre like '".$datos['nombre']."' AND seccion = '".$datos['seccion']."' AND id_municipio = '".$datos['id_municipio']."' AND tipo = '".$datos['tipo']."'");
				if($existente != 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Casilla Existente.",
					]);							
					exit(0);
				}
						
				$consulta = "INSERT INTO tblc_casilla (numero, nombre, tipo, id_municipio, latitud, longitud, direccion, seccion, num_contigua) 
				VALUES ('".$datos['numero']."','".$datos['nombre']."','".$datos['tipo']."','".$datos['id_municipio']."','".$datos['txtLatitud']."','".$datos['txtLongitud']."','".$datos['direccion']."','".$datos['seccion']."','".$datos['num_contigua']."')";

				if($conexion->consulta($consulta) == 0){
					echo json_encode([
						"estatus" => 0,
						"tipo"    => "warning",
						"titulo"  => "Error!",
						"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
					]);							
					exit(0);
				}

			$datos['idcasillaElect'] = $conexion->ultimoid();

			$idcandidatoPrin = '';
			if ($conexion->consultadato("SELECT COUNT(id_representante) FROM tblc_representante WHERE id_casilla =".$datos['idcasillaElect']) != 0) {
				$datos['idrepreseElect'] = $conexion->consultadato("SELECT id_representante FROM tblc_representante WHERE id_casilla =".$datos['idcasillaElect']);
			}else{
				$datos['idrepreseElect'] = '0';
			}   

			if(isset($_FILES["acta_file"]["tmp_name"]) and $_FILES["acta_file"]["tmp_name"] != ""){						
					if($upload->load("acta_file") === false){
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Formato no permitido.",
						]);							
						exit(0);
					}
					$archivo = $upload->nombre_final;
					$upload->setisimage(false);						   
					if($upload->save("../archivos/actas_eleccion/".$archivo) === false){					
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
						]);							
						exit(0);
						}
					$datos['acta_file'] = $archivo;
					}
			else{ 
				$datos['acta_file'] = '';
				}
		 
		    foreach($_POST['partido'] as $indice => $idpartido){  
				
  				$total_votos = $votos[$indice];
  				$idcandidato = $candidato[$indice];

			    $consulta = "REPLACE INTO tbl_resultado(id_candidato, id_casilla, id_partido_politico, resultado, id_representante, fecha_registro, id_usuario) VALUES(".$idcandidato.", ".$datos['idcasillaElect'].", ".$idpartido.", '".$total_votos."' , '".$datos['idrepreseElect']."', NOW(), ".$id_usuario.")";

			        if($conexion->consulta($consulta) == 0){
						echo json_encode([
							"estatus" => 0,
							"tipo"    => "warning",
							"titulo"  => "Error!",
							"mensaje" => "Error al guardar el registro favor de intentar mas tarde.",
						]);							
						exit(0);
			        }
			    $msj = 'Se guardaron el resultado de la votacion electoral con resultado'.$total_votos.' con id del candidato: '.$idcandidato;
				$log_actividad = 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("'.$id_sesion_sistema.'", now(),"'.$msj.'","'.$consulta.'")';
				$conexion->consulta($log_actividad);
			  
			}

			$consultacta = "INSERT INTO tbl_acta(id_proceso_electoral, id_casilla, archivo, id_representante, fecha_registro, votos_nulos, no_registrados, total_votos) VALUES(".$datos['idprocesoElect'].", ".$datos['idcasillaElect'].", '".$datos['acta_file']."' , '".$datos['idrepreseElect']."', NOW(), '".$datos['votos_null']."', '".$datos['no_registrados']."', '".$datos['total_votos']."')";

	   		$conexion->consulta($consultacta);
		 
			echo json_encode([
				"estatus" => 2,
				"tipo"    => "success",
				"titulo"  => "Listo!",
				"mensaje" => "Se registro el acta correctamenete",
			]);
		
		break;


		}

?>






	
