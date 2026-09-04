<?php
	require_once("../../php/clase_variables.php");
	require_once("../../php/clase_funciones.php");
	require_once("../../php/clase_upload.php");
	
	$new_image_name = "app".date('dmY')."_".date('his').".jpg";
	if (move_uploaded_file($_FILES["foto"]["tmp_name"], "../../archivos/actas_eleccion/".$new_image_name))
		echo $new_image_name;
	else
		echo 0;
		
?>
