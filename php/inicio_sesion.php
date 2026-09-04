<?php
ini_set("session.cookie_lifetime","10800");
ini_set("session.gc_maxlifetime","10800");
@session_start();

require ("clase_variables.php");
require ("clase_mysql.php");
require_once ("Entity.php");
require ("clase_funciones.php");
// include '../captcha/securimage.php';

	// $captcha = new securimage();
	$funciones = new Funciones();
	$entity = Entity::createInstance();
	$conexion = $entity;

	
	$ip = $funciones->getRealIP();
	$navegador = $funciones->getBrowser();
	$so = $funciones->getOs();
	$fecha_actual = date("Y")."-".date("m")."-".date("d");
	$hora_actual = date("H").":".date("i").":".date("s");

	/*
		require "../recaptchagoogle/recaptchalib.php";
		$secret = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';
		$resp = null;
		$error = null;
	$reCaptcha = new ReCaptcha($secret);

	if ($_POST["g-recaptcha-response"]) {
		$resp = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
		);
	}
	

	if($resp == null){
		echo '<script>parent.alert("ERROR. No Corresponde el código de verificación");</script>';
		exit(0);
	}*/
	
	if(isset($_POST["usuario"]) and isset($_POST["pass"])){
		
		$lat = $funciones->limpia($_POST["lat"]);			
		$lon = $funciones->limpia($_POST["lon"]);			
		$usuario = $funciones->limpia($_POST["usuario"]);			
		$password = $_POST["pass"];
		$password = $funciones->create_password($password);
		
		$dato = $conexion->fetch_array("select * from tblc_usuario where usuario like '".$usuario."'");
							
		$resultados = $conexion->numregistros();
	
		if($resultados == 0 || $dato['pass'] != $password){ 
			echo ' 
				<script languaje="javascript">
						parent.alert("Usuario o contraseña incorrectos");
				</script> 
			';
			exit(0);
			}
		else if($dato['estatus'] != 1){  
		echo '
			<script languaje="javascript">
					parent.alert("Acceso Denegado!!");
			</script> 
			';
		exit(0);
			}
		else{
			$_SESSION['autentificado']= md5('sistemaadministradordenuncias');
			$_SESSION['id_usuario']= $dato['id_usuario'];
			$_SESSION['id_estado']= $dato['id_estado'];
			$_SESSION['id_municipio']= $dato['id_municipio'];
			$_SESSION['nombre']= $dato['nombre'];
			$_SESSION['eliminar']= $dato['eliminar'];
			$_SESSION['editar']= $dato['editar'];
			$_SESSION['id_proceso_electoral']= $dato['id_proceso_electoral'];
			
			$consulta = "INSERT INTO tbl_sesion(id_usuario,fecha_acceso,ip,navegador,so) VALUES('".$dato['id_usuario']."',now(),'".$ip."','".$navegador."','".$so."')";
			if($conexion->consulta($consulta) != 0){
				$_SESSION['id_sesion_sistema']= $conexion->ultimoid();
			}
			else $_SESSION['id_sesion_sistema'] = 0;
			
			echo '
				<script languaje="javascript">
					top.location.href="../inicio.php";
				</script> 
				';
				
			}
			exit(0);	
						
		}
		else{
			echo '<script languaje="javascript">
						top.location.href="../index.php";
				</script> 
						';
					exit(0);	
			}
  ?>
