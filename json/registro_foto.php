<?php

	include_once("clase_upload.php");

	$upload = new upload();

	if($upload->load("foto") === false){
		echo 0;
		exit(0);
		}

	$archivo = $upload->nombre_final;
	$upload->setisimage(true);			
	//$upload->resize(250, 'width'); //funcion para redimencionar la imagen

	if($upload->width >= 1001){
		$upload->resize(1000, 'width');
		}

	$upload->setquality(85); //funcion para especificar la calidad de la imagen

	if($upload->save("../archivos/".$archivo) === false){
		echo 0;
		exit(0);
		}
	else{
		echo $archivo;
		}
?>