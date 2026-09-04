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
	
	$data = json_decode(file_get_contents("php://input"), true);

	if ($data && isset($data["orden"])) {
	    $orden = $data["orden"];

	    foreach ($orden as $posicion => $id) {
	        // Aquí actualizas la columna "orden" en la tabla
	        /*$stmt = $conn->prepare("UPDATE usuarios SET orden = ? WHERE id = ?");
	        $stmt->bind_param("ii", $posicion, $id);
	        $stmt->execute();*/

	        $datos = explode("-", $id);

	        $consulta = "UPDATE tblc_candidato_partido SET ordenamiento=".$posicion." WHERE id_candidato=".$datos[0]." AND id_partido_politico =".$datos[1];
			$conexion->consulta($consulta);

	    }

	    echo "Orden actualizado";
	} else {
	    echo "Error al recibir datos";
	}
?>






	
