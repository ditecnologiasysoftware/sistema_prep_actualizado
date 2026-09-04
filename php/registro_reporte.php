<?php
	require_once("../php/clase_variables.php");
	require_once("../php/clase_mysql.php");
	require_once("../php/clase_funciones.php");

	$conexion = new DB_mysql(1);
	$funciones = new Funciones(1);

		$img = $funciones->limpia($_POST['img']);
		$so = $funciones->limpia($_POST['so']);
		$version = $funciones->limpia($_POST['version']);
		$modelo = $funciones->limpia($_POST['modelo']);
		$uuid = $funciones->limpia($_POST['uuid']);


		$latitud = $funciones->limpia($_POST['latitud']);
		$longitud = $funciones->limpia($_POST['longitud']);
		$tipo_foto = $funciones->limpia($_POST['tipo_foto']);
		$direccion = addslashes($funciones->limpia($_POST['direccion']));
		$descripcion = addslashes($funciones->limpia($_POST['descripcion']));
		$municipio = $funciones->limpia($_POST['municipio']);
		$tipo = $funciones->limpia($_POST['tipo']);

		$MySQL = "INSERT INTO tbl_reporte(folio, id_municipio,tipo_reporte,descripcion,direccion,foto,latitud,longitud,tipo_registro,uuid, so, version, modelo, fecha_registro) 
		VALUES ('','".$municipio."','".$tipo."','".$descripcion."','".$direccion."','".$img."','".$latitud."','".$longitud."','".$tipo_foto."','".$uuid."','".$so."','".$version."','".$modelo."',Now())";
		
		if($conexion->consulta($MySQL) == 0){
			echo "Error al registrar su solicitud, intente de nuevo más tarde";
			exit(0);
		}

		$id = $conexion->ultimoid();
		$folio = $id.date('dmY');

		$MySQL = "UPDATE tbl_reporte SET folio ='".$folio."' WHERE id_reporte = ".$id;
		if($conexion->consulta($MySQL) == 0){
			echo "Error al registrar su solicitud, intente de nuevo más tarde";
			exit(0);
		}

		if(isset($_POST['etiquetas'])){
			foreach($_POST['etiquetas'] as $indice => $etiqueta){
				$consulta = "INSERT INTO tbl_reporte_etiqueta(id_reporte,id_etiqueta) VALUES('".$id."','".$etiqueta."')";
				$conexion->consulta($consulta);
				}
			}

	echo "Gracias por registrar su reporte";
	//echo "El Folio de su denuncia es: ".$NumFolio.",\n Gracias por registrar su reporte.";

?>