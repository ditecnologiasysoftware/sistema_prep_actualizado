<?php
	 @session_start();
	 $id_usuario = $_SESSION['id_usuario'];
	 $id_estado = $_SESSION['id_estado'];
	 $id_municipio = $_SESSION['id_municipio'];
	 $autentificado = $_SESSION['autentificado'];
	 $eliminar = $_SESSION['eliminar'];
	 $editar = $_SESSION['editar'];
	 $nombre = $_SESSION['nombre'];
	 $id_sesion_sistema = $_SESSION['id_sesion_sistema'];
	//  $autoriza_apoyo = $_SESSION['autoriza_apoyo'];
	//  $autoriza_ordenpago = $_SESSION['autoriza_ordenpago'];
	 
    $id_proceso_electoral = $_SESSION['id_proceso_electoral'];
 
	 require_once __DIR__ . "/clase_variables.php";
	 require_once __DIR__ . "/clase_paginador.php";
	 require_once __DIR__ . "/clase_mysql.php";
	 require_once __DIR__ . "/Entity.php";
	 require_once __DIR__ . "/clase_funciones.php";
	 require_once __DIR__ . "/clase_querys.php";	
	 date_default_timezone_set('America/Mexico_City');
	 
	 $funciones = new Funciones();
	 //LLAMAMOS A LA CLASE CONEXION
	 $entity = Entity::createInstance();
	 $conexion = $entity; // Compatibilidad temporal fuera de pg.
	$querys    = new Querys();    

	if($autentificado == md5("sistemaadministradordenuncias")){

		$modulo = isset($_GET['modulo']) ? $funciones->limpia($_GET['modulo']) : 'inicio';			
	}
	else
	{
		echo'<script languaje="javascript">
				var msg = alert("Acceso Denegado");
				location.href="index.php";	
			</script>';
		exit(0);
	}
?>
