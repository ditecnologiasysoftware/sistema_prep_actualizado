<?php
class Funciones {
	public $db;
	/* identificador de conexión y consulta */
	private  $Consulta_ID = 0; 
	/* número de error y texto error */
	var $Errno = 0;
	var $Error = "";	 
	var $msjError = "ERROR al realizar la consulta";
	/* Ejecuta un consulta */
	function __construct($opbd = 1){ //funcion constructor de la clase, (inicializamos todo por default)		
		// Establece conexión a la BD
		$this->db=new DB_mysql($opbd);
		if($this->db->Error){
			header("Location: error/1");
			exit;
			}		
		}
	
	/* Muestra las opciones de una lista desplegable del formulario */	
	public function llenarcombo($sql) {
/*		if($this->consulta($sql,$conexion) == 0){
			echo $this->msjError;
			exit(0);
			}*/
		// mostrarmos los registros
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){
			echo '
			<option value="'.$resultado->id.'" name="'.$resultado->valor.'">'.$this->cdetectUtf8($resultado->valor).'</option>';
			}
		}
	
	public function llenarcombomodifica($sql,$id) {
		// mostrarmos los registros
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){			
			if($id == $resultado->id) echo '<option value="'.$resultado->id.'" selected="selected">'.$this->cdetectUtf8($resultado->valor).'</option>';
			else echo '<option value="'.$resultado->id.'">'.$this->cdetectUtf8($resultado->valor).'</option>';		
		}
	}

	
	public function llenarcombomodificaCasilla($sql,$id) {
		// mostrarmos los registros
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){			
			if($id == $resultado->id_casilla) echo '<option value="'.$resultado->id_casilla.'" selected="selected">Sección '.$resultado->seccion.' - '.$resultado->nombre.'</option>';
			else echo '<option value="'.$resultado->id_casilla.'">Sección '.$resultado->seccion.' - '.$resultado->nombre.'</option>';		
		}
	}

	public function llenarCasillatbl($id) {
		// mostrarmos los registros
		$sql = "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla = ".$id;
		$resultados = $this->db->fetch_array($sql);
		if($this->db->numregistros() != 0)
			return '<font>Sección '.$resultados['seccion'].' - '.$resultados['nombre'].'</font>';
	}

	public function llenarCasillatbl2($id) {
		// mostrarmos los registros
		$sql = "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla = ".$id;
		$resultados = $this->db->fetch_array($sql);
		if($this->db->numregistros() != 0)
			return 'Sección '.$resultados['seccion'].' - '.$resultados['nombre'];
	}

	public function llenarcombomodifica2($sql,$id) {
		// mostrarmos los registros
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){	
			if($id != $rows[0]) echo '<option value="'.$resultado->id.'">'.$this->cdetectUtf8($resultado->valor).'</option>';
		}
	}
	
	public function llenarcombomodificaarreglo2($sql,$arregloid) {
		// mostrarmos los registros
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){	
			if(!in_array($resultado->id, $arregloid)) echo '<option value="'.$resultado->id.'">'.$resultado->valor.'</option>';		
		}
	}	
	
	public function llenarcombomodificaarreglo($sql,$arregloid) {
		// mostrarmos los registros
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){	
			if(in_array($resultado->id, $arregloid)) echo '<option value="'.$resultado->id.'" selected="selected">'.$this->cdetectUtf8($resultado->valor).'</option>';
			else echo '<option value="'.$resultado->id.'">'.$this->cdetectUtf8($resultado->valor).'</option>';		
		}
	}

	public function llenarcombomodificaicono($sql,$id) {
		// mostrarmos los registros
		
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){	
			if(trim($id) == $resultado->valor) echo '<option value="'.$resultado->valor.'" rel="'.$resultado->valor.'" selected="selected">'.$resultado->valor.'</option>';
			else echo '<option value="'.$resultado->valor.'" rel="'.$resultado->valor.'">'.$resultado->valor.'</option>';		
		}
	}

	public function llenarcombomodificaiconoMostrarMenu($sql,$id) {
		// mostrarmos los registros
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){	
			if($id == $resultado->id) echo '<option value="'.$resultado->id.'" rel="'.$resultado->nombre_icono.'" selected="selected">'.$resultado->valor.'</option>';
			else echo '<option value="'.$resultado->id.'" rel="'.$resultado->nombre_icono.'">'.$resultado->valor.'</option>';		
		}
	}

	/* Muestra opciones con input tipo radio */
	
	public function llenaradio($sql,$nombre) {
		$x = 1;
		// mostrarmos los registros
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){	
			if($x == 1){
				echo '<input type="radio" name="'.$nombre.'" id="'.$nombre.$resultado->id.'" value="'.$nombre.$resultado->id.'" checked="checked" />'.$this->cdetectUtf8($resultado->valor).'&nbsp;&nbsp;';
				$x++;
			}
			else
				echo '<input type="radio" name="'.$nombre.'" id="'.$nombre.$resultado->id.'" value="'.$nombre.$resultado->id.'" />'.$this->cdetectUtf8($resultado->valor).'&nbsp;&nbsp;';
				
		}
	}
	
	public function llenaradiomodifica($sql,$id,$nombre,$conexion) {
		// mostrarmos los registros
		$resultados = $this->db->obtenerlista($sql);
		foreach($resultados as $resultado){			
			if($id == $resultado->id) echo '<input type="radio" name="'.$nombre.'" id="'.$nombre.$resultado->id.'" value="'.$nombre.$resultado->id.'" checked="checked" />'.$this->cdetectUtf8($resultado->valor).'&nbsp;&nbsp;';
			else echo '<input type="radio" name="'.$nombre.'" id="'.$nombre.$resultado->id.'" value="'.$nombre.$resultado->id.'" />'.$this->cdetectUtf8($resultado->valor).'&nbsp;&nbsp;';		
		}
	}

	//convierte la fecha a formato año / mes / dia
	public function cambiarFormatoFecha($fecha){
		if(strstr($fecha,"-")){
			list($anio,$mes,$dia)=explode("-",$fecha);
		}
		else{
			list($anio,$mes,$dia)=explode("/",$fecha);
		}
    	return $dia."<strong>.</strong>".$mes."<strong>.</strong>".$anio; 
	}
	//convierte la fecha a formato año - mes - dia
	public function cambiarFormatoFechabase($fecha){
		if(strstr($fecha,"-")){
			list($dia,$mes,$anio)=explode("-",$fecha);
		}
		else{
			list($dia,$mes,$anio)=explode("/",$fecha);
		}
    	return $anio."-".$mes."-".$dia; 
	}

	//convierte la fecha a formato dia / mes / año
	public function cambiarFormatoFechaform($fecha){
		if(strstr($fecha,"-")){
			list($anio,$mes,$dia)=explode("-",$fecha);
		}
		else{
			list($anio,$mes,$dia)=explode("/",$fecha);
		}
    	return $dia."/".$mes."/".$anio; 
	}

	//convierte color exadecimal a RGB
	public function rgbColor($fondo)	{
		$red = (int) hexdec(substr($fondo, 0, 2));
		$green = (int) hexdec(substr($fondo, 2, 2));
		$blue = (int) hexdec(substr($fondo, 4, 2));
		return array($red, $green, $blue);
	}

	//limpia cadena para evitar inyeccion SQL
	public function limpia($var){
		$var = strip_tags($var);
		$malo = array("\\",";","+","\'","'","$","%","!",",","(",")",'"',"*","{","}","xor","XOR","FROM","from","WHERE","where","ORDER","order","GROUP","group","by","BY","UPDATE","update","DELETE","delete",".php",".asp",".aspx",".html",".xml",".js",".css",".exe",".tar",".rar",".ocx"); // Aqui poner caracteres no permitidos
		$i=0;
		$o=count($malo);
		$o= $o-1;
		while($i<=$o){
			$var = str_replace($malo[$i],"",$var);
			$i++;
		}		

		
		
		return $var;
	}
	
	public function sanear_string($string)
	{
	
		$string = trim($string);
	
		$string = str_replace(
			array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
			$string
		);
	
		$string = str_replace(
			array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
			array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
			$string
		);
	
		$string = str_replace(
			array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
			array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
			$string
		);
	
		$string = str_replace(
			array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
			array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
			$string
		);
	
		$string = str_replace(
			array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
			array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
			$string
		);
	
		$string = str_replace(
			array('ñ', 'Ñ', 'ç', 'Ç'),
			array('n', 'N', 'c', 'C',),
			$string
		);
	
		//Esta parte se encarga de eliminar cualquier caracter extraño
		$string = str_replace(
			array("\\", "¨", "º", "~",
				 "#", "@", "|", "!", "\"",
				 "·", "$", "%", "&", "/",
				 "(", ")", "?", "'", "¡",
				 "¿", "[", "^", "`", "]",
				 "+", "}", "{", "¨", "´",
				 ">", "< ", ";", ",", ":",
				 ".", "'", '"','“','”'),
			'',
			$string
		);
	
	
		return $string;
	}

	public function comillas_formulario($string)
	{
	
		$string = trim($string);
	
		$string = str_replace(
			array('"', "'"),
			array(htmlentities('"'), htmlentities("'")),
			$string
		);
	
		return $string;
	}

	public function mes_nombre($mes){
		
		 switch($mes)
              {         
               case 1:
                  $mes='Enero';
                  break;     
               case 2:
                  $mes='Febrero';
                  break;     
               case 3:
                  $mes='Marzo';
                  break;
               case 4:
                  $mes='Abrril';
                  break;
               case 5:
                  $mes='Mayo';
                  break;
               case 6:
                  $mes='Junio';
                  break;
               case 7:
                  $mes='Julio';
                  break;
               case 8:
                  $mes='Agosto';
                  break;
               case 9:
                  $mes='Septiembre';
                  break;
               case 10:
                  $mes='Octubre';
                  break;
               case 11:
                  $mes='Noviembre';
                  break;
               case 12:
                  $mes='Diciembre';
                  break;
              }
			return $mes;
		}

	public function fecha(){
		$fecha = getdate();
		$dia = $fecha["mday"];
		$mes = $fecha["mon"];

           switch($mes)
              {         
               case 1:
                  $mes='Enero';
                  break;     
               case 2:
                  $mes='Febrero';
                  break;     
               case 3:
                  $mes='Marzo';
                  break;
               case 4:
                  $mes='Abril';
                  break;
               case 5:
                  $mes='Mayo';
                  break;
               case 6:
                  $mes='Junio';
                  break;
               case 7:
                  $mes='Julio';
                  break;
               case 8:
                  $mes='Agosto';
                  break;
               case 9:
                  $mes='Septiembre';
                  break;
               case 10:
                  $mes='Octubre';
                  break;
               case 11:
                  $mes='Noviembre';
                  break;
               case 12:
                  $mes='Diciembre';
                  break;
              }
           
		$año = $fecha["year"];
		echo "$dia de $mes del $año";		
		}

	public function mes($fecha){
		
		if(strstr($fecha,"-")){
			list($anio,$mes,$dia)=explode("-",$fecha);
		}
		else{
			list($anio,$mes,$dia)=explode("/",$fecha);
		}

           switch($mes)
              {         
               case 1:
                  $mes='Enero';
                  break;     
               case 2:
                  $mes='Febrero';
                  break;     
               case 3:
                  $mes='Marzo';
                  break;
               case 4:
                  $mes='Abril';
                  break;
               case 5:
                  $mes='Mayo';
                  break;
               case 6:
                  $mes='Junio';
                  break;
               case 7:
                  $mes='Julio';
                  break;
               case 8:
                  $mes='Agosto';
                  break;
               case 9:
                  $mes='Septiembre';
                  break;
               case 10:
                  $mes='Octubre';
                  break;
               case 11:
                  $mes='Noviembre';
                  break;
               case 12:
                  $mes='Diciembre';
                  break;
              }
           
		return $mes;		
		}


	public function fecha2($fecha){
		
		if(strstr($fecha,"-")){
			list($anio,$mes,$dia)=explode("-",$fecha);
		}
		else{
			list($anio,$mes,$dia)=explode("/",$fecha);
		}

           switch($mes)
              {         
               case 1:
                  $mes='Enero';
                  break;     
               case 2:
                  $mes='Febrero';
                  break;     
               case 3:
                  $mes='Marzo';
                  break;
               case 4:
                  $mes='Abril';
                  break;
               case 5:
                  $mes='Mayo';
                  break;
               case 6:
                  $mes='Junio';
                  break;
               case 7:
                  $mes='Julio';
                  break;
               case 8:
                  $mes='Agosto';
                  break;
               case 9:
                  $mes='Septiembre';
                  break;
               case 10:
                  $mes='Octubre';
                  break;
               case 11:
                  $mes='Noviembre';
                  break;
               case 12:
                  $mes='Diciembre';
                  break;
              }
           
		return "$dia de $mes $anio";		
		}
	
	public function fecha3($fecha){
		
		if(strstr($fecha,"-")){
			list($anio,$mes,$dia)=explode("-",$fecha);
		}
		else{
			list($anio,$mes,$dia)=explode("/",$fecha);
		}
		$i = strtotime($fecha);
		$dia1 = date("w",mktime(0, 0, 0, $mes, $dia, $anio));
           switch($mes)
              {         
               case 1:
                  $mes='Enero';
                  break;     
               case 2:
                  $mes='Febrero';
                  break;     
               case 3:
                  $mes='Marzo';
                  break;
               case 4:
                  $mes='Abril';
                  break;
               case 5:
                  $mes='Mayo';
                  break;
               case 6:
                  $mes='Junio';
                  break;
               case 7:
                  $mes='Julio';
                  break;
               case 8:
                  $mes='Agosto';
                  break;
               case 9:
                  $mes='Septiembre';
                  break;
               case 10:
                  $mes='Octubre';
                  break;
               case 11:
                  $mes='Noviembre';
                  break;
               case 12:
                  $mes='Diciembre';
                  break;
              }
			  
           switch($dia1)
              {
				  case 0:
                  $dia1='Domingo';
                  break;        
               case 1:
                  $dia1='Lunes';
                  break;     
               case 2:
                  $dia1='Martes';
                  break;     
               case 3:
                  $dia1='Miércoles';
                  break;
               case 4:
                  $dia1='Jueves';
                  break;
               case 5:
                  $dia1='Viernes';
                  break;
               case 6:
                  $dia1='Sabado';
                  break;
              }
           
		return "$dia1, $dia de $mes $anio";		
		}

	public function fecha4($fecha){
		
		if(strstr($fecha,"-")){
			list($anio,$mes,$dia)=explode("-",$fecha);
		}
		else{
			list($anio,$mes,$dia)=explode("/",$fecha);
		}
           
		return "$dia/$mes/$anio";		
		}

	function ordenaFechaHora($fechaHora){
		$aFecha_Hora    = explode(' ', $fechaHora);
		$fechaOrdenada  = $this->fecha4($aFecha_Hora[0]);
		$hora           = $aFecha_Hora[1];

		return $fechaOrdenada.' '.$hora;
	}
		
	public function activo($id){
		switch($id){
			case 1: $publicado = "SI"; break;
			case 2: $publicado = '<div style="color:#C00">NO</div>'; break;
			default: $publicado = "nada";
			}
		return $publicado;
	}
	
	public function tipo_sexo($id){
		switch($id){
			case 1: $publicado = "Hombre"; break;
			case 2: $publicado = 'Mujer'; break;
			}
		return $publicado;
	}

	public function tipo_reporte($id){
		switch($id){
			case 1: $publicado = "Denuncia"; break;
			case 2: $publicado = 'Observaciones'; break;
			}
		return $publicado;
	}

	public function tipo_estatuscasilla($id){
		switch($id){
			case 1: $publicado = "Apertura de casilla"; break;
			case 2: $publicado = 'Cierre de casilla'; break;
			case 3: $publicado = 'Casilla no aperturada'; break;
			case 4: $publicado = 'Asistencia'; break;
			//case 5: $publicado = 'Salida del RC'; break;
			}
		return $publicado;
	}

	public function tipo_registro($id){
		switch($id){
			case 1: $publicado = "Teléfonico"; break;
			case 2: $publicado = 'WhastApp'; break;
			case 3: $publicado = 'Personal'; break;
			case 4: $publicado = 'App'; break;
			case 5: $publicado = 'Redes Sociales'; break;
			}
		return $publicado;
	}

	public function getcomboestatuscasilla($value){
		$array_visible=array(1=>"Apertura de casilla", 2=>"Cierre de casilla", 3=>"Casilla no aperturada", 4=>"Asistencia");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}

	public function getcombotiporegistro($value){
		$array_visible=array(1=>"Teléfonico", 2=>"Whatsapp", 3=>"Personal", 4=>"App", 5=>"Redes Sociales");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}

	public function getcomboTipoEleccion($value){
		$array_visible=array(1=>"Basica", 2=>"Contigua", 3=>"Extraordinaria", 4=>"Especial");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}
	public function getcomboTipoEleccionText($value){
		$array_visible=array(1=>"Basica", 2=>"Contigua", 3=>"Extraordinaria", 4=>"Especial");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) return "<b>".$visible."</b>";
		}
	}

	public function getcombotiposervicio($value){
		$array_visible=array(1=>"Denuncia", 2=>"Observaciones");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}

	public function getcombotipoactivo($value){
		$array_visible=array(1=>"Activo", 2=>"Inactivo");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}

	public function getcombosexo($value){
		$array_visible=array(1=>"Hombre", 2=>"Mujer");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}

	public function getcombomes($value){
		$array_visible=array('01'=>"ENERO", '02'=>"FEBRERO", '03'=>"MARZO", '04'=>"ABRIL", '05'=>"MAYO", '06'=>"JUNIO", '07'=>"JULIO", '08'=>"AGOSTO", '09'=>"SEPTIEMBRE", '10'=>"OCTUBRE", '11'=>"NOVIEMBRE", '12'=>"DICIEMBRE");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}
	public function getComboVisibleTipoEleccion($value)
	{
		$array_visible=array(1=>"Federal", 2=>"Estatal", 3=>"Municipal");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}
	public function getComboVisible($value)
	{
		$array_visible=array(1=>"SI", 2=>"NO");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}
	public function getComboVisible2($value)
	{
		$array_visible=array(1=>"Activo", 2=>"Inactivo", 3=>"Desactivado");
		foreach($array_visible as $t => $visible)
		{
			if($value==$t) echo "<option value='".$t."' selected='selected'>".$visible."</option>";
			else echo "<option value='".$t."'>".$visible."</option>";
		}
	}
	public function getMonthDays($Month, $Year){ 
	   //Si la extensión que mencioné está instalada, usamos esa. 
	   if( is_callable("cal_days_in_month")) 
	   { 
		  return cal_days_in_month(CAL_GREGORIAN, $Month, $Year); 
	   } 
	   else 
	   { 
		  //Lo hacemos a mi manera. 
		  return date("t",mktime(0,0,0,$Month,1,$Year)); 
	   } 
	}
	public	function pasarMayusculas($cadena) { 
		$cadena = strtoupper($cadena); 
		$cadena = str_replace("á", "Á", $cadena); 
		$cadena = str_replace("é", "É", $cadena); 
		$cadena = str_replace("í", "Í", $cadena); 
		$cadena = str_replace("ó", "Ó", $cadena); 
		$cadena = str_replace("ú", "Ú", $cadena); 
	return ($cadena); 
	}  
	///////////////////////////////////////////////////////////ELECCCION POR SECCION///////////////////////////////////////////////////

	public function ganadoresTipoEleccion($idpelectoral, $municipio){
 		$cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$idpelectoral." and municipio_c = ".$municipio." GROUP BY idt_eleccion_c";
        $cadenaResultado = $this->db->obtenerlista($cadena);
        $municipionom = $this->db->fetch_array("SELECT * FROM tblc_municipio WHERE id_municipio =".$municipio);
         $result='<center>';
			$result .='<h4><font>'.$this->pasarMayusculas($municipionom['nombre']).'</font></h4>';
         	$result .='<table style="width: 100%;">';
		      $result .=  ' <thead>';
		          $result .=  ' <tr>';
				 	$result .=  ' <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Contendiente Ganador</b></th>';
		           $result .=  '  <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Votos</b></th>';
		         $result .=  '  </tr>';
		        $result .=  ' </thead>';
		       $result .=  '  <tbody>';
			foreach ($cadenaResultado as $value) {			
				$ganador =  $this->db->fetch_array("SELECT vw_resultado_elecciones.idp_politico_c, vw_resultado_elecciones.nombre_c, vw_resultado_elecciones.nombre_te,  SUM(resultado) AS resultado_total FROM vw_resultado_elecciones WHERE municipio_c = ".$municipio." and idt_eleccion_c = ".$value->idt_eleccion_c." GROUP BY idcandidato_c ORDER BY resultado_total DESC LIMIT 1");

 				$tipo_eleccion = $ganador['nombre_te'];                
 				$votos_ganador = $ganador['resultado_total'];                        
                $nombre_ganador = $ganador['nombre_c'];
                $partido =  $this->db->fetch_array("SELECT * FROM tblc_partido_politico WHERE id_partido_politico =".$ganador['idp_politico_c']);
			 		 $result.= ' <tr> ';
					 $result .=  '	<td><img style="width: 25px; height: 25px;" src="archivos/partido_politico/'.$partido['icono'].'"><font color="#3769AA"><b> '.$partido['nombre'].'</b>&nbsp; - &nbsp;'.$tipo_eleccion.'&nbsp;&nbsp;</font></td>';
					 $result .=  '	<td align="center"><b>'.$votos_ganador.'</b></td>';
		             $result .= ' </tr> ';                       
			}	
			$result.='</tbody>';
		    $result .=  ' </table><br>';
			$result .=  '  <a href="mapa_seccion?e='.$municipionom['id_estado'].'&m='.$municipio.'"><b>[ VER CASILLAS ]</b></a>';
			$result .=  '  </center>';

		 return $result;
	}
	
	public function ganadoresTipoEleccionEdo($idpelectoral, $edo){
 		$cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$idpelectoral." and estado_c = ".$edo." and tipo_te IN(1,2) GROUP BY idt_eleccion_c";
        $cadenaResultado = $this->db->obtenerlista($cadena);
        $cadenaCuenta = $this->db->numregistros();
        $estadonom = $this->db->consultadato("SELECT nombre FROM tblc_estado WHERE id_estado =".$edo);
  			$result='<center>';
			$result .='<h4><font>'.$this->pasarMayusculas($estadonom).'</font></h4>';
			if ($cadenaCuenta != 0) {			
	         	$result .='<table style="width: 100%;">';
	   		      $result .=  ' <thead>';
			          $result .=  ' <tr>';
					 	$result .=  ' <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Contendiente Ganador</b></th>';
			           $result .=  '  <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Votos</b></th>';
			         $result .=  '  </tr>';
			        $result .=  ' </thead>';
			       $result .=  '  <tbody>';
				foreach ($cadenaResultado as $value) {			
					$ganador =  $this->db->fetch_array("SELECT vw_resultado_elecciones.idp_politico_c, vw_resultado_elecciones.nombre_c, vw_resultado_elecciones.nombre_te,  SUM(resultado) AS resultado_total FROM vw_resultado_elecciones WHERE estado_c = ".$edo." and idt_eleccion_c = ".$value->idt_eleccion_c." GROUP BY idcandidato_c ORDER BY resultado_total DESC LIMIT 1");
	 				$tipo_eleccion = $ganador['nombre_te'];                
	 				$votos_ganador = $ganador['resultado_total'];                        
	                $nombre_ganador = $ganador['nombre_c'];
	                $partido =  $this->db->fetch_array("SELECT * FROM tblc_partido_politico WHERE id_partido_politico =".$ganador['idp_politico_c']);
				 		 $result.= ' <tr> ';
						 $result .=  '	<td><img style="width: 25px; height: 25px;" src="archivos/partido_politico/'.$partido['icono'].'"><font color="#3769AA"><b> '.$partido['nombre'].'</b>&nbsp; - &nbsp;'.$tipo_eleccion.'&nbsp;&nbsp;</font></td>';
						 $result .=  '	<td align="center"><b>'.$votos_ganador.'</b></td>';
			             $result .= ' </tr> ';                         
				}	
				$result.='</tbody>';
			    $result .=  ' </table><br>';
		    }else{
				$result .='<font color="#373737">No se encontraron elecciones federales y estatales</font><br><br>';
		     }
			$result .=  '  <a href="mapa_seccion?e='.$edo.'"><b>[ VER MUNICIPIOS ]</b></a>';
			$result .=  '  </center>';

		 return $result;
	}

	public function ganadoresSeccion($seccion, $tipoe){
		$query = "";
	if ($tipoe != '0') {
		$query .= " and idt_eleccion_c =".$tipoe."";
	} 		
  			$result='<br><center>';
         	$result .='<table style="width: 100%;">';
   		      $result .=  ' <thead>';
		          $result .=  ' <tr>';
				 	$result .=  ' <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Candidatos</b></th>';
		           $result .=  '  <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Total Votos</b></th>';
		         $result .=  '  </tr>';
		        $result .=  ' </thead>';
		       $result .=  '  <tbody>';
				$resultado = "SELECT vw_resultado_elecciones.*, SUM(resultado) AS resultado_total FROM vw_resultado_elecciones WHERE seccion = ".$seccion.$query." GROUP BY idcandidato_c ORDER BY idt_eleccion_c, resultado_total DESC";
		         
		                $resultadoss = $this->db->obtenerlista($resultado);
		                foreach($resultadoss as $resultadoo){                  
		                $result.='<tr>';
		                    $result.='<td><img style="width: 25px; height: 25px;" src="archivos/partido_politico/'.$resultadoo->icono_pa.'"><font color="'.$resultadoo->color_p.'"> '.$resultadoo->nombre_c.' - <b>'.$resultadoo->nombre_te.'</b></font></td>';
		                    $result.='<td align="center"><b>'.$resultadoo->resultado_total.'</b></td>';
		                $result.='</tr>';  
		                  } 
			$result.='</tbody>';
		    $result .=  ' </table><br>';
			$result .=  '  </center>';

		 return $result;
	}

	///////////////////////////////////////////////////////////TERMINA ELECCCION POR SECCION///////////////////////////////////////////////////
	///////////////////////////////////////////////////////////ELECCCION POR CASILLA///////////////////////////////////////////////////

		public function ganadoresTipoEleccion2($idpelectoral, $municipio){
 		$cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$idpelectoral." and municipio_c = ".$municipio." GROUP BY idt_eleccion_c";
        $cadenaResultado = $this->db->obtenerlista($cadena);
        $municipionom = $this->db->fetch_array("SELECT * FROM tblc_municipio WHERE id_municipio =".$municipio);
         $result='<center>';
			$result .='<h4><font>'.$this->pasarMayusculas($municipionom['nombre']).'</font></h4>';
         	$result .='<table style="width: 100%;">';
		      $result .=  ' <thead>';
		          $result .=  ' <tr>';
				 	$result .=  ' <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Contendiente Ganador</b></th>';
		           $result .=  '  <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Votos</b></th>';
		         $result .=  '  </tr>';
		        $result .=  ' </thead>';
		       $result .=  '  <tbody>';
			foreach ($cadenaResultado as $value) {			
				$ganador =  $this->db->fetch_array("SELECT vw_resultado_elecciones.idp_politico_c, vw_resultado_elecciones.nombre_c, vw_resultado_elecciones.nombre_te,  SUM(resultado) AS resultado_total FROM vw_resultado_elecciones WHERE municipio_c = ".$municipio." and idt_eleccion_c = ".$value->idt_eleccion_c." GROUP BY idcandidato_c ORDER BY resultado_total DESC LIMIT 1");

 				$tipo_eleccion = $ganador['nombre_te'];                
 				$votos_ganador = $ganador['resultado_total'];                        
                $nombre_ganador = $ganador['nombre_c'];
                $partido =  $this->db->fetch_array("SELECT * FROM tblc_partido_politico WHERE id_partido_politico =".$ganador['idp_politico_c']);
			 		 $result.= ' <tr> ';
					 $result .=  '	<td><img style="width: 25px; height: 25px;" src="archivos/partido_politico/'.$partido['icono'].'"><font color="#3769AA"><b> '.$partido['nombre'].'</b>&nbsp; - &nbsp;'.$tipo_eleccion.'&nbsp;&nbsp;</font></td>';
					 $result .=  '	<td align="center"><b>'.$votos_ganador.'</b></td>';
		             $result .= ' </tr> ';                       
			}	
			$result.='</tbody>';
		    $result .=  ' </table><br>';
			$result .=  '  <a href="mapa_eleccion?e='.$municipionom['id_estado'].'&m='.$municipio.'"><b>[ VER CASILLAS ]</b></a>';
			$result .=  '  </center>';

		 return $result;
	}
	
	public function ganadoresTipoEleccionEdo2($idpelectoral, $edo){
 		$cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$idpelectoral." and estado_c = ".$edo." and tipo_te IN(1,2) GROUP BY idt_eleccion_c";
        $cadenaResultado = $this->db->obtenerlista($cadena);
         $cadenaCuenta = $this->db->numregistros();
        $estadonom = $this->db->consultadato("SELECT nombre FROM tblc_estado WHERE id_estado =".$edo);
  			$result.='<center>';
			$result .='<h4><font>'.$this->pasarMayusculas($estadonom).'</font></h4>';
  			if ($cadenaCuenta != 0) {
         	$result .='<table style="width: 100%;">';
   		      $result .=  ' <thead>';
		          $result .=  ' <tr>';
				 	$result .=  ' <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Contendiente Ganador</b></th>';
		           $result .=  '  <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Votos</b></th>';
		         $result .=  '  </tr>';
		        $result .=  ' </thead>';
		       $result .=  '  <tbody>';
			foreach ($cadenaResultado as $value) {			
				$ganador =  $this->db->fetch_array("SELECT vw_resultado_elecciones.idp_politico_c, vw_resultado_elecciones.nombre_c, vw_resultado_elecciones.nombre_te,  SUM(resultado) AS resultado_total FROM vw_resultado_elecciones WHERE estado_c = ".$edo." and idt_eleccion_c = ".$value->idt_eleccion_c." GROUP BY idcandidato_c ORDER BY resultado_total DESC LIMIT 1");
 				$tipo_eleccion = $ganador['nombre_te'];                
 				$votos_ganador = $ganador['resultado_total'];                        
                $nombre_ganador = $ganador['nombre_c'];

                $partido =  $this->db->fetch_array("SELECT * FROM tblc_partido_politico WHERE id_partido_politico =".$ganador['idp_politico_c']);
			 		 $result.= ' <tr> ';
					 $result .=  '	<td><img style="width: 25px; height: 25px;" src="archivos/partido_politico/'.$partido['icono'].'"><font color="#3769AA"><b> '.$partido['nombre'].'</b>&nbsp; - &nbsp;'.$tipo_eleccion.'&nbsp;&nbsp;</font></td>';
					 $result .=  '	<td align="center"><b>'.$votos_ganador.'</b></td>';
		             $result .= ' </tr> ';                         
			}	
			$result.='</tbody>';
		    $result .=  ' </table><br>';
		}else{
			$result .='<font color="#373737">No se encontraron elecciones federales y estatales</font><br><br>';
		}
			$result .=  '  <a href="mapa_eleccion?e='.$edo.'"><b>[ VER MUNICIPIOS ]</b></a>';		
			$result .=  '  </center>';

		 return $result;
	}
	public function ganadoresEleccion($idcasilla, $tipoe){
		$query = "";
	if ($tipoe != '0') {
		$query .= " and idt_eleccion_c =".$tipoe."";
	} 		
  			$result='<br><center>';
         	$result .='<table style="width: 100%;">';
   		      $result .=  ' <thead>';
		          $result .=  ' <tr>';
				 	$result .=  ' <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Candidatos</b></th>';
		           $result .=  '  <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Total Votos</b></th>';
		         $result .=  '  </tr>';
		        $result .=  ' </thead>';
		       $result .=  '  <tbody>';
				 	  $resultado = "SELECT * FROM vw_resultado_elecciones WHERE id_casilla = ".$idcasilla.$query." ORDER BY idt_eleccion_c, resultado DESC" ;
		                $resultadoss = $this->db->obtenerlista($resultado);
		                foreach($resultadoss as $resultadoo){                  
		                $result.='<tr>';
		                    $result.='<td><img style="width: 25px; height: 25px;" src="archivos/partido_politico/'.$resultadoo->icono_pa.'"><font color="'.$resultadoo->color_p.'"> '.$resultadoo->nombre_c.' - <b>'.$resultadoo->nombre_te.'</b></font></td>';
		                    $result.='<td align="center"><b>'.$resultadoo->resultado.'</b></td>';
		                $result.='</tr>';  
		                  } 
			$result.='</tbody>';
		    $result .=  ' </table><br>';
			$result .=  '  </center>';

		 return $result;
	}
	public function ganadoresEleccionResultado($idcasilla, $tipoe){		
  			$result='<br><center>';
         	$result .='<table style="width: 100%;">';
   		      $result .=  ' <thead>';
		          $result .=  ' <tr>';
				 	$result .=  ' <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Candidatos</b></th>';
		           $result .=  '  <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Total Votos</b></th>';
		         $result .=  '  </tr>';
		        $result .=  ' </thead>';
		       $result .=  '  <tbody>';
				 	   $resultado = "SELECT * FROM vw_resultado_elecciones WHERE id_casilla = ".$idcasilla." and idt_eleccion_c = ".$tipoe." ORDER BY resultado DESC" ;
		                $resultadoss = $this->db->obtenerlista($resultado);
		                foreach($resultadoss as $resultadoo){                  
		                $result.='<tr>';
		                    $result.='<td><img style="width: 25px; height: 25px;" src="../archivos/partido_politico/'.$resultadoo->icono_pa.'"><font color="'.$resultadoo->color_p.'"> '.$resultadoo->nombre_c.' - <b>'.$resultadoo->nombre_te.'</b></font></td>';
		                    $result.='<td align="center"><b>'.$resultadoo->resultado.'</b></td>';
		                $result.='</tr>';  
		                  } 
			$result.='</tbody>';
		    $result .=  ' </table><br>';
			$result .=  '  </center>';

		 return $result;
	}
 ///////////////////////////////////////////////////////////TERMINA ELECCCION POR CASILLA///////////////////////////////////////////////////

	
	function create_password($password){
		
		$salt = '123%45678"$9%%9&/((&87654321';
		$password_array = str_split($password, 4);
		$hash = sha1($password_array[0].$password_array[3].$salt.$password_array[2].$password_array[1]);
		$md5 = md5($hash);
		
		return $md5;
	}

	function queBrowserIE() {
	
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';  
		
		if (strpos($user_agent, 'MSIE') !== false) {  
		   $browser = true; 
		} else {  
		   $browser = false;  
		}
		
		return $browser;

	}
	
	function html2txt($document){
		$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
					   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
					   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
					   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
		);
		$text = preg_replace($search, '', $document);
		return $text;
		}
	
	function getRealIP() {
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip=$this->limpia($_SERVER['HTTP_CLIENT_IP']);
			}
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip=$this->limpia($_SERVER['HTTP_X_FORWARDED_FOR']);
			}
		else {
			$ip=$this->limpia($_SERVER['REMOTE_ADDR']);
		}
		
		return $ip;
	}

	function download_file($archivo, $downloadfilename = null) {
		/*
		$downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . $downloadfilename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($archivo));

		ob_clean();
		flush();
		readfile($archivo);
		exit;*/

		if (!file_exists($archivo))
        {
            return FALSE;
        }

        $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
        // Grab the file extension
        $x = explode('.', $downloadfilename);
        $extension = end($x);

        // Set a default mime if we can't find it
        if ( ! isset($mimes[$extension]))
        {
            $mime = 'application/octet-stream';
        }
        else
        {
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
        }

        // Generate the server headers
        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE)
        {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$downloadfilename.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: ".filesize($archivo));
        }
        else
        {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$downloadfilename.'"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: ".filesize($archivo));
        }

        $this->readfile_chunked($archivo);
        die;
	}

	function readfile_chunked($file, $retbytes=TRUE)
    {
       $chunksize = 1 * (1024 * 1024);
       $buffer = '';
       $cnt =0;

       $handle = fopen($file, 'r');
       if ($handle === FALSE)
       {
           return FALSE;
       }

       while (!feof($handle))
       {
           $buffer = fread($handle, $chunksize);
           echo $buffer;
           ob_flush();
           flush();

           if ($retbytes)
           {
               $cnt += strlen($buffer);
           }
       }

       $status = fclose($handle);

       if ($retbytes AND $status)
       {
           return $cnt;
       }

       return $status;
    }

	function div_extracto ($contenido, $cantidadPalabras) {
		$contenido = explode(' ', $contenido);
		$contenido = array_slice($contenido, 0, $cantidadPalabras);
		$contenido = implode(' ', $contenido);
		return $contenido;
	}
	
	function getBrowser() { 
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 
		$navegadores = array(
			'Opera' => 'Opera',
			'Mozilla Firefox'=> '(Firebird)|(Firefox)',
			'Google Chrome'=>'Chrome',
			'Galeon' => 'Galeon',
			'Mozilla'=>'Gecko',
			'MyIE'=>'MyIE',
			'Lynx' => 'Lynx',
			'Google Chrome'=>'Chrome',
			'Konqueror'=>'Konqueror',
			'Internet Explorer 7' => '(MSIE 7\.[0-9]+)',
			'Internet Explorer 6' => '(MSIE 6\.[0-9]+)',
			'Internet Explorer 5' => '(MSIE 5\.[0-9]+)',
			'Internet Explorer 4' => '(MSIE 4\.[0-9]+)',
			'Internet Explorer' => 'MSIE',
			'Flock'             => 'Flock',
		    'Shiira'            => 'Shiira',
		    'Chimera'           => 'Chimera',
		    'Phoenix'           => 'Phoenix',
		    'Camino'            => 'Camino',
		    'Netscape'          => 'Netscape',
		    'OmniWeb'           => 'OmniWeb',
		    'Safari'            => 'Safari',
		    'icab'              => 'iCab',
		    'Links'             => 'Links',
		    'hotjava'           => 'HotJava',
		    'amaya'             => 'Amaya',
		    'IBrowse'           => 'IBrowse'
			);
			
		foreach($navegadores as $navegador=>$pattern){
			if(strpos($user_agent, $pattern) !== false) return $this->limpia($navegador);
			}
			
		}
	
	function getOs() {
		$user_agent= strtolower($_SERVER['HTTP_USER_AGENT']);

		$plataformas = array(
		  	'/windows nt 6.3/i'     =>  'Windows 8.1',
			'/windows nt 6.2/i'     =>  'Windows 8',
			'/windows nt 6.1/i'     =>  'Windows 7',
			'/windows nt 6.0/i'     =>  'Windows Vista',
			'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     =>  'Windows XP',
			'/windows xp/i'         =>  'Windows XP',
			'/windows nt 5.0/i'     =>  'Windows 2000',
			'/windows nt 6.3/i'     =>  'Windows 8.1',
			'/windows me/i'         =>  'Windows ME',
			'/win98/i'              =>  'Windows 98',
			'/win95/i'              =>  'Windows 95',
			'/win16/i'              =>  'Windows 3.11',
			'/macintosh|mac os x/i' =>  'Mac OS X',
			'/mac_powerpc/i'        =>  'Mac OS 9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile WebOS'
	   );
	
		foreach ($plataformas as $regex => $plataforma) { 

		    if (preg_match($regex, $user_agent)) {
		        return $this->limpia($plataforma);
		    }
		}   

	   return 'Sistema Operativo Desconocido';
	}

	function getmultimedia_documento($var) {
		/*
			1.- Multimedia
			2.- Archivos documentos
		*/
		$result = 1;

		$formatos = array(
		  'jpg' => '2',
		  'jpeg' => '2',
		  'gif' => '2',
		  'png' => '2',
		  'JPG' => '2',
		  'JPEG' => '2',
		  'GIF' => '2',
		  'PNG' => '2',

		  'mp3' => '4',
		  'midi' => '4',
		  'wma' => '4',
		  'ogg' => '4',
		  'wav' => '4',
		  'aac' => '4',
		  'MP3' => '4',
		  'MIDI' => '4',
		  'WMA' => '4',
		  'OGG' => '4',
		  'WAV' => '4',
		  'AAC' => '4',

		  'mp4' => '3',
		  'mov' => '3',
		  'flv' => '3',
		  'mkv' => '3',
		  'wmv' => '3',
		  'avi' => '3',
		  'mpg' => '3',
		  'MP4' => '3',
		  'MOV' => '3',
		  'FLV' => '3',
		  'MKV' => '3',
		  'WMV' => '3',
		  'AVI' => '3',
		  'MPG' => '3',

		  'doc' => '1',
		  'docx' => '1',
		  'xls' => '1',
		  'xlsx' => '1',
		  'ppt' => '1',
		  'pptx' => '1',
		  'txt' => '1',
		  'rar' => '1',
		  'zip' => '1',
		  'DOC' => '1',
		  'DOCX' => '1',
		  'XLS' => '1',
		  'XLSX' => '1',
		  'PPT' => '1',
		  'PPTX' => '1',
		  'TXT' => '1',
		  'RAR' => '1',
		  'ZIP' => '1'
	   );
	   foreach($formatos as $formato=>$valor){
		  if (eregi($formato, $var))
			 $result = $valor;
	   }
	   return $result ;
	}
	
	function cdetectUtf8($str){ 
		if( mb_detect_encoding($str,"UTF-8, ISO-8859-1")!="UTF-8" ){ 
		
			return  utf8_encode($str); 
			} 
		else{ 
			return $str; 
			} 
	
		}

	function getporcentaje($numero,$total){
		$porcentaje = ($numero * 100) / $total;
	    return $porcentaje;
	}

	function FileSizeConvert($bytes)
	{
	    $bytes = floatval($bytes);
	        $arBytes = array(
	            0 => array(
	                "UNIT" => "TB",
	                "VALUE" => pow(1024, 4)
	            ),
	            1 => array(
	                "UNIT" => "GB",
	                "VALUE" => pow(1024, 3)
	            ),
	            2 => array(
	                "UNIT" => "MB",
	                "VALUE" => pow(1024, 2)
	            ),
	            3 => array(
	                "UNIT" => "KB",
	                "VALUE" => 1024
	            ),
	            4 => array(
	                "UNIT" => "B",
	                "VALUE" => 1
	            ),
	        );

	    foreach($arBytes as $arItem)
	    {
	        if($bytes >= $arItem["VALUE"])
	        {
	            $result = $bytes / $arItem["VALUE"];
	            $result = str_replace(",", "." , strval(round($result, 2)))." ".$arItem["UNIT"];
	            break;
	        }
	    }
	    return $result;
	}
	
	function filesarray(&$file_post) {

	    $file_ary = array();
	    $file_count = count($file_post['name']);
	    $file_keys = array_keys($file_post);

	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }

	    return $file_ary;
	}


	public function materialIconsArray(){
		// PARA AGREGAR MAS ICONOS CONSULTAR LA DOCUMENTACIÓN: https://fonts.google.com/icons?selected=Material+Icons:settings:
		$data = array (
			1 => '360',
			2 => '3d_rotation',
			3 => '4k',
			4 => '5g',
			5 => '6_ft_apart',
			6 => 'access_alarm',
			7 => 'access_alarms',
			8 => 'access_time',
			9 => 'accessibility',
			10 => 'accessibility_new',
			11 => 'accessible',
			12 => 'accessible_forward',
			13 => 'account_balance',
			14 => 'account_balance_wallet',
			15 => 'account_box',
			16 => 'account_circle',
			17 => 'account_tree',
			18 => 'ad_units',
			19 => 'adb',
			20 => 'add',
			21 => 'add_a_photo',
			22 => 'add_alarm',
			23 => 'add_alert',
			24 => 'add_box',
			25 => 'add_business',
			26 => 'add_call',
			27 => 'add_chart',
			28 => 'add_circle',
			29 => 'add_circle_outline',
			30 => 'add_comment',
			31 => 'add_ic_call',
			32 => 'add_link',
			33 => 'add_location',
			34 => 'add_location_alt',
			35 => 'add_moderator',
			36 => 'add_photo_alternate',
			37 => 'add_reaction',
			38 => 'add_road',
			39 => 'add_shopping_cart',
			40 => 'add_task',
			41 => 'add_to_drive',
			42 => 'add_to_home_screen',
			43 => 'add_to_photos',
			44 => 'add_to_queue',
			45 => 'addchart',
			46 => 'adjust',
			47 => 'admin_panel_settings',
			48 => 'ads_click',
			49 => 'agriculture',
			50 => 'air',
			51 => 'airline_seat_flat',
			52 => 'airline_seat_flat_angled',
			53 => 'airline_seat_individual_suite',
			54 => 'airline_seat_legroom_extra',
			55 => 'airline_seat_legroom_normal',
			56 => 'airline_seat_legroom_reduced',
			57 => 'airline_seat_recline_extra',
			58 => 'airline_seat_recline_normal',
			59 => 'airplane_ticket',
			60 => 'airplanemode_active',
			61 => 'airplanemode_inactive',
			62 => 'airplay',
			63 => 'airport_shuttle',
			64 => 'alarm',
			65 => 'alarm_add',
			66 => 'alarm_off',
			67 => 'alarm_on',
			68 => 'album',
			69 => 'align_horizontal_center',
			70 => 'align_horizontal_left',
			71 => 'align_horizontal_right',
			72 => 'align_vertical_bottom',
			73 => 'align_vertical_center',
			74 => 'align_vertical_top',
			75 => 'all_inbox',
			76 => 'all_inclusive',
			77 => 'all_out',
			78 => 'alt_route',
			79 => 'alternate_email',
			80 => 'analytics',
			81 => 'anchor',
			82 => 'android',
			83 => 'animation',
			84 => 'announcement',
			85 => 'aod',
			86 => 'apartment',
			87 => 'api',
			88 => 'app_blocking',
			89 => 'app_registration',
			90 => 'app_settings_alt',
			91 => 'approval',
			92 => 'apps',
			93 => 'architecture',
			94 => 'archive',
			95 => 'area_chart',
			96 => 'arrow_back',
			97 => 'arrow_back_ios',
			98 => 'arrow_back_ios_new',
			99 => 'arrow_circle_down',
			100 => 'arrow_circle_up',
			101 => 'arrow_downward',
			102 => 'arrow_drop_down',
			103 => 'arrow_drop_down_circle',
			104 => 'arrow_drop_up',
			105 => 'arrow_forward',
			106 => 'arrow_forward_ios',
			107 => 'arrow_left',
			108 => 'arrow_right',
			109 => 'arrow_right_alt',
			110 => 'arrow_upward',
			111 => 'art_track',
			112 => 'article',
			113 => 'aspect_ratio',
			114 => 'assessment',
			115 => 'assignment',
			116 => 'assignment_ind',
			117 => 'assignment_late',
			118 => 'assignment_return',
			119 => 'assignment_returned',
			120 => 'assignment_turned_in',
			121 => 'assistant',
			122 => 'assistant_direction',
			123 => 'assistant_navigation',
			124 => 'assured_workload',
			125 => 'atm',
			126 => 'attach_email',
			127 => 'attach_file',
			128 => 'attach_money',
			129 => 'attachment',
			130 => 'attractions',
			131 => 'attribution',
			132 => 'audio_file',
			133 => 'audiotrack',
			134 => 'auto_awesome',
			135 => 'auto_awesome_mosaic',
			136 => 'auto_awesome_motion',
			137 => 'auto_delete',
			138 => 'auto_fix_high',
			139 => 'auto_fix_normal',
			140 => 'auto_fix_off',
			141 => 'auto_graph',
			142 => 'auto_mode',
			143 => 'auto_stories',
			144 => 'autofps_select',
			145 => 'autorenew',
			146 => 'av_timer',
			147 => 'baby_changing_station',
			148 => 'back_hand',
			149 => 'backpack',
			150 => 'backspace',
			151 => 'backup',
			152 => 'backup_table',
			153 => 'badge',
			154 => 'bakery_dining',
			155 => 'balcony',
			156 => 'ballot',
			157 => 'bar_chart',
			158 => 'batch_prediction',
			159 => 'bathroom',
			160 => 'bathtub',
			161 => 'battery_alert',
			162 => 'battery_charging_full',
			163 => 'battery_full',
			164 => 'battery_saver',
			165 => 'battery_std',
			166 => 'battery_unknown',
			167 => 'beach_access',
			168 => 'bed',
			169 => 'bedroom_baby',
			170 => 'bedroom_child',
			171 => 'bedroom_parent',
			172 => 'bedtime',
			173 => 'beenhere',
			174 => 'bento',
			175 => 'bike_scooter',
			176 => 'biotech',
			177 => 'blender',
			178 => 'blinds',
			179 => 'blinds_closed',
			180 => 'block',
			181 => 'bloodtype',
			182 => 'bluetooth',
			183 => 'bluetooth_audio',
			184 => 'bluetooth_connected',
			185 => 'bluetooth_disabled',
			186 => 'bluetooth_drive',
			187 => 'bluetooth_searching',
			188 => 'blur_circular',
			189 => 'blur_linear',
			190 => 'blur_off',
			191 => 'blur_on',
			192 => 'bolt',
			193 => 'book',
			194 => 'book_online',
			195 => 'bookmark',
			196 => 'bookmark_add',
			197 => 'bookmark_added',
			198 => 'bookmark_border',
			199 => 'bookmark_remove',
			200 => 'bookmarks',
			201 => 'border_all',
			202 => 'border_bottom',
			203 => 'border_clear',
			204 => 'border_color',
			205 => 'border_horizontal',
			206 => 'border_inner',
			207 => 'border_left',
			208 => 'border_outer',
			209 => 'border_right',
			210 => 'border_style',
			211 => 'border_top',
			212 => 'border_vertical',
			213 => 'branding_watermark',
			214 => 'breakfast_dining',
			215 => 'brightness_1',
			216 => 'brightness_2',
			217 => 'brightness_3',
			218 => 'brightness_4',
			219 => 'brightness_5',
			220 => 'brightness_6',
			221 => 'brightness_7',
			222 => 'brightness_auto',
			223 => 'brightness_high',
			224 => 'brightness_low',
			225 => 'brightness_medium',
			226 => 'broken_image',
			227 => 'browse_gallery',
			228 => 'browser_not_supported',
			229 => 'browser_updated',
			230 => 'brunch_dining',
			231 => 'brush',
			232 => 'bubble_chart',
			233 => 'bug_report',
			234 => 'build',
			235 => 'build_circle',
			236 => 'bungalow',
			237 => 'burst_mode',
			238 => 'bus_alert',
			239 => 'business',
			240 => 'business_center',
			241 => 'cabin',
			242 => 'cable',
			243 => 'cached',
			244 => 'cake',
			245 => 'calculate',
			246 => 'calendar_today',
			247 => 'calendar_view_day',
			248 => 'calendar_view_month',
			249 => 'calendar_view_week',
			250 => 'call',
			251 => 'call_end',
			252 => 'call_made',
			253 => 'call_merge',
			254 => 'call_missed',
			255 => 'call_missed_outgoing',
			256 => 'call_received',
			257 => 'call_split',
			258 => 'call_to_action',
			259 => 'camera',
			260 => 'camera_alt',
			261 => 'camera_enhance',
			262 => 'camera_front',
			263 => 'camera_indoor',
			264 => 'camera_outdoor',
			265 => 'camera_rear',
			266 => 'camera_roll',
			267 => 'cameraswitch',
			268 => 'campaign',
			269 => 'cancel',
			270 => 'cancel_presentation',
			271 => 'cancel_schedule_send',
			272 => 'candlestick_chart',
			273 => 'car_crash',
			274 => 'car_rental',
			275 => 'car_repair',
			276 => 'card_giftcard',
			277 => 'card_membership',
			278 => 'card_travel',
			279 => 'carpenter',
			280 => 'cases',
			281 => 'casino',
			282 => 'cast',
			283 => 'cast_connected',
			284 => 'cast_for_education',
			285 => 'catching_pokemon',
			286 => 'category',
			287 => 'celebration',
			288 => 'cell_wifi',
			289 => 'center_focus_strong',
			290 => 'center_focus_weak',
			291 => 'chair',
			292 => 'chair_alt',
			293 => 'chalet',
			294 => 'change_circle',
			295 => 'change_history',
			296 => 'charging_station',
			297 => 'chat',
			298 => 'chat_bubble',
			299 => 'chat_bubble_outline',
			300 => 'check',
			301 => 'check_box',
			302 => 'check_box_outline_blank',
			303 => 'check_circle',
			304 => 'check_circle_outline',
			305 => 'checklist',
			306 => 'checklist_rtl',
			307 => 'checkroom',
			308 => 'chevron_left',
			309 => 'chevron_right',
			310 => 'child_care',
			311 => 'child_friendly',
			312 => 'chrome_reader_mode',
			313 => 'circle',
			314 => 'circle_notifications',
			315 => 'class',
			316 => 'clean_hands',
			317 => 'cleaning_services',
			318 => 'clear',
			319 => 'clear_all',
			320 => 'close',
			321 => 'close_fullscreen',
			322 => 'closed_caption',
			323 => 'closed_caption_disabled',
			324 => 'closed_caption_off',
			325 => 'cloud',
			326 => 'cloud_circle',
			327 => 'cloud_done',
			328 => 'cloud_download',
			329 => 'cloud_off',
			330 => 'cloud_queue',
			331 => 'cloud_sync',
			332 => 'cloud_upload',
			333 => 'cloudy_snowing',
			334 => 'co2',
			335 => 'co_present',
			336 => 'code',
			337 => 'code_off',
			338 => 'coffee',
			339 => 'coffee_maker',
			340 => 'collections',
			341 => 'collections_bookmark',
			342 => 'color_lens',
			343 => 'colorize',
			344 => 'comment',
			345 => 'comment_bank',
			346 => 'comments_disabled',
			347 => 'commit',
			348 => 'commute',
			349 => 'compare',
			350 => 'compare_arrows',
			351 => 'compass_calibration',
			352 => 'compost',
			353 => 'compress',
			354 => 'computer',
			355 => 'confirmation_num',
			356 => 'confirmation_number',
			357 => 'connect_without_contact',
			358 => 'connected_tv',
			359 => 'connecting_airports',
			360 => 'construction',
			361 => 'contact_mail',
			362 => 'contact_page',
			363 => 'contact_phone',
			364 => 'contact_support',
			365 => 'contactless',
			366 => 'contacts',
			367 => 'content_copy',
			368 => 'content_cut',
			369 => 'content_paste',
			370 => 'content_paste_go',
			371 => 'content_paste_off',
			372 => 'content_paste_search',
			373 => 'contrast',
			374 => 'control_camera',
			375 => 'control_point',
			376 => 'control_point_duplicate',
			377 => 'cookie',
			378 => 'copy_all',
			379 => 'coronavirus',
			380 => 'corporate_fare',
			381 => 'cottage',
			382 => 'countertops',
			383 => 'create',
			384 => 'create_new_folder',
			385 => 'credit_card',
			386 => 'credit_card_off',
			387 => 'credit_score',
			388 => 'crib',
			389 => 'crop',
			390 => 'crop_16_9',
			391 => 'crop_3_2',
			392 => 'crop_5_4',
			393 => 'crop_7_5',
			394 => 'crop_din',
			395 => 'crop_free',
			396 => 'crop_landscape',
			397 => 'crop_original',
			398 => 'crop_portrait',
			399 => 'crop_rotate',
			400 => 'crop_square',
			401 => 'cruelty_free',
			402 => 'css',
			403 => 'currency_bitcoin',
			404 => 'currency_exchange',
			405 => 'currency_franc',
			406 => 'currency_lira',
			407 => 'currency_pound',
			408 => 'currency_ruble',
			409 => 'currency_rupee',
			410 => 'currency_yen',
			411 => 'currency_yuan',
			412 => 'curtains',
			413 => 'curtains_closed',
			414 => 'cyclone',
			415 => 'dangerous',
			416 => 'dark_mode',
			417 => 'dashboard',
			418 => 'dashboard_customize',
			419 => 'data_array',
			420 => 'data_exploration',
			421 => 'data_object',
			422 => 'data_saver_off',
			423 => 'data_saver_on',
			424 => 'data_thresholding',
			425 => 'data_usage',
			426 => 'dataset',
			427 => 'dataset_linked',
			428 => 'date_range',
			429 => 'deblur',
			430 => 'deck',
			431 => 'dehaze',
			432 => 'delete',
			433 => 'delete_forever',
			434 => 'delete_outline',
			435 => 'delete_sweep',
			436 => 'delivery_dining',
			437 => 'density_large',
			438 => 'density_medium',
			439 => 'density_small',
			440 => 'departure_board',
			441 => 'description',
			442 => 'deselect',
			443 => 'design_services',
			444 => 'desktop_access_disabled',
			445 => 'desktop_mac',
			446 => 'desktop_windows',
			447 => 'details',
			448 => 'developer_board',
			449 => 'developer_board_off',
			450 => 'developer_mode',
			451 => 'device_hub',
			452 => 'device_thermostat',
			453 => 'device_unknown',
			454 => 'devices',
			455 => 'devices_fold',
			456 => 'devices_other',
			457 => 'dialer_sip',
			458 => 'dialpad',
			459 => 'diamond',
			460 => 'difference',
			461 => 'dining',
			462 => 'dinner_dining',
			463 => 'directions',
			464 => 'directions_bike',
			465 => 'directions_boat',
			466 => 'directions_boat_filled',
			467 => 'directions_bus',
			468 => 'directions_bus_filled',
			469 => 'directions_car',
			470 => 'directions_car_filled',
			471 => 'directions_off',
			472 => 'directions_railway',
			473 => 'directions_railway_filled',
			474 => 'directions_run',
			475 => 'directions_subway',
			476 => 'directions_subway_filled',
			477 => 'directions_transit',
			478 => 'directions_transit_filled',
			479 => 'directions_walk',
			480 => 'dirty_lens',
			481 => 'disabled_by_default',
			482 => 'disabled_visible',
			483 => 'disc_full',
			484 => 'discount',
			485 => 'display_settings',
			486 => 'divide',
			487 => 'dns',
			488 => 'do_disturb',
			489 => 'do_disturb_alt',
			490 => 'do_disturb_off',
			491 => 'do_disturb_on',
			492 => 'do_not_disturb',
			493 => 'do_not_disturb_alt',
			494 => 'do_not_disturb_off',
			495 => 'do_not_disturb_on',
			496 => 'do_not_disturb_on_total_silence',
			497 => 'do_not_step',
			498 => 'do_not_touch',
			499 => 'dock',
			500 => 'document_scanner',
			501 => 'domain',
			502 => 'domain_add',
			503 => 'domain_disabled',
			504 => 'domain_verification',
			505 => 'done',
			506 => 'done_all',
			507 => 'done_outline',
			508 => 'donut_large',
			509 => 'donut_small',
			510 => 'door_back',
			511 => 'door_front',
			512 => 'door_sliding',
			513 => 'doorbell',
			514 => 'double_arrow',
			515 => 'downhill_skiing',
			516 => 'download',
			517 => 'download_done',
			518 => 'download_for_offline',
			519 => 'downloading',
			520 => 'drafts',
			521 => 'drag_handle',
			522 => 'drag_indicator',
			523 => 'draw',
			524 => 'drive_eta',
			525 => 'drive_file_move',
			526 => 'drive_file_move_rtl',
			527 => 'drive_file_rename_outline',
			528 => 'drive_folder_upload',
			529 => 'dry',
			530 => 'dry_cleaning',
			531 => 'duo',
			532 => 'dvr',
			533 => 'dynamic_feed',
			534 => 'dynamic_form',
			535 => 'e_mobiledata',
			536 => 'earbuds',
			537 => 'earbuds_battery',
			538 => 'east',
			539 => 'eco',
			540 => 'edgesensor_high',
			541 => 'edgesensor_low',
			542 => 'edit',
			543 => 'edit_calendar',
			544 => 'edit_location',
			545 => 'edit_location_alt',
			546 => 'edit_note',
			547 => 'edit_notifications',
			548 => 'edit_off',
			549 => 'edit_road',
			550 => 'egg',
			551 => 'egg_alt',
			552 => 'eject',
			553 => 'elderly',
			554 => 'electric_bike',
			555 => 'electric_car',
			556 => 'electric_moped',
			557 => 'electric_rickshaw',
			558 => 'electric_scooter',
			559 => 'electrical_services',
			560 => 'elevator',
			561 => 'email',
			562 => 'emergency',
			563 => 'emergency_recording',
			564 => 'emergency_share',
			565 => 'emoji_emotions',
			566 => 'emoji_events',
			567 => 'emoji_flags',
			568 => 'emoji_food_beverage',
			569 => 'emoji_nature',
			570 => 'emoji_objects',
			571 => 'emoji_people',
			572 => 'emoji_symbols',
			573 => 'emoji_transportation',
			574 => 'energy_savings_leaf',
			575 => 'engineering',
			576 => 'enhance_photo_translate',
			577 => 'enhanced_encryption',
			578 => 'equalizer',
			579 => 'error',
			580 => 'error_outline',
			581 => 'escalator',
			582 => 'escalator_warning',
			583 => 'euro',
			584 => 'euro_symbol',
			585 => 'ev_station',
			586 => 'event',
			587 => 'event_available',
			588 => 'event_busy',
			589 => 'event_note',
			590 => 'event_repeat',
			591 => 'event_seat',
			592 => 'exit_to_app',
			593 => 'expand',
			594 => 'expand_circle_down',
			595 => 'expand_less',
			596 => 'expand_more',
			597 => 'explicit',
			598 => 'explore',
			599 => 'explore_off',
			600 => 'exposure',
			601 => 'exposure_minus_1',
			602 => 'exposure_minus_2',
			603 => 'exposure_neg_1',
			604 => 'exposure_neg_2',
			605 => 'exposure_plus_1',
			606 => 'exposure_plus_2',
			607 => 'exposure_zero',
			608 => 'extension',
			609 => 'extension_off',
			610 => 'face',
			611 => 'face_retouching_natural',
			612 => 'face_retouching_off',
			613 => 'fact_check',
			614 => 'family_restroom',
			615 => 'fast_forward',
			616 => 'fast_rewind',
			617 => 'fastfood',
			618 => 'favorite',
			619 => 'favorite_border',
			620 => 'favorite_outline',
			621 => 'featured_play_list',
			622 => 'featured_video',
			623 => 'feed',
			624 => 'fence',
			625 => 'festival',
			626 => 'fiber_dvr',
			627 => 'fiber_manual_record',
			628 => 'fiber_new',
			629 => 'fiber_pin',
			630 => 'fiber_smart_record',
			631 => 'file_copy',
			632 => 'file_download',
			633 => 'file_download_done',
			634 => 'file_download_off',
			635 => 'file_open',
			636 => 'file_present',
			637 => 'file_upload',
			638 => 'filter',
			639 => 'filter_1',
			640 => 'filter_2',
			641 => 'filter_3',
			642 => 'filter_4',
			643 => 'filter_5',
			644 => 'filter_6',
			645 => 'filter_7',
			646 => 'filter_8',
			647 => 'filter_9',
			648 => 'filter_9_plus',
			649 => 'filter_alt',
			650 => 'filter_b_and_w',
			651 => 'filter_center_focus',
			652 => 'filter_drama',
			653 => 'filter_frames',
			654 => 'filter_hdr',
			655 => 'filter_list',
			656 => 'filter_list_off',
			657 => 'filter_none',
			658 => 'filter_tilt_shift',
			659 => 'filter_vintage',
			660 => 'find_in_page',
			661 => 'find_replace',
			662 => 'fingerprint',
			663 => 'fire_extinguisher',
			664 => 'fireplace',
			665 => 'first_page',
			666 => 'fit_screen',
			667 => 'fitness_center',
			668 => 'flag',
			669 => 'flag_circle',
			670 => 'flaky',
			671 => 'flare',
			672 => 'flash_auto',
			673 => 'flash_off',
			674 => 'flash_on',
			675 => 'flashlight_off',
			676 => 'flashlight_on',
			677 => 'flatware',
			678 => 'flight',
			679 => 'flight_class',
			680 => 'flight_land',
			681 => 'flight_takeoff',
			682 => 'flip',
			683 => 'flip_camera_android',
			684 => 'flip_camera_ios',
			685 => 'flip_to_back',
			686 => 'flip_to_front',
			687 => 'flood',
			688 => 'flourescent',
			689 => 'flutter_dash',
			690 => 'fmd_bad',
			691 => 'fmd_good',
			692 => 'folder',
			693 => 'folder_copy',
			694 => 'folder_delete',
			695 => 'folder_off',
			696 => 'folder_open',
			697 => 'folder_shared',
			698 => 'folder_special',
			699 => 'folder_zip',
			700 => 'follow_the_signs',
			701 => 'font_download',
			702 => 'font_download_off',
			703 => 'food_bank',
			704 => 'forest',
			705 => 'fork_left',
			706 => 'fork_right',
			707 => 'format_align_center',
			708 => 'format_align_justify',
			709 => 'format_align_left',
			710 => 'format_align_right',
			711 => 'format_bold',
			712 => 'format_clear',
			713 => 'format_color_fill',
			714 => 'format_color_reset',
			715 => 'format_color_text',
			716 => 'format_indent_decrease',
			717 => 'format_indent_increase',
			718 => 'format_italic',
			719 => 'format_line_spacing',
			720 => 'format_list_bulleted',
			721 => 'format_list_numbered',
			722 => 'format_list_numbered_rtl',
			723 => 'format_paint',
			724 => 'format_quote',
			725 => 'format_shapes',
			726 => 'format_size',
			727 => 'format_strikethrough',
			728 => 'format_textdirection_l_to_r',
			729 => 'format_textdirection_r_to_l',
			730 => 'format_underlined',
			731 => 'fort',
			732 => 'forum',
			733 => 'forward',
			734 => 'forward_10',
			735 => 'forward_30',
			736 => 'forward_5',
			737 => 'forward_to_inbox',
			738 => 'foundation',
			739 => 'free_breakfast',
			740 => 'free_cancellation',
			741 => 'front_hand',
			742 => 'fullscreen',
			743 => 'fullscreen_exit',
			744 => 'functions',
			745 => 'g_translate',
			746 => 'gamepad',
			747 => 'games',
			748 => 'garage',
			749 => 'gavel',
			750 => 'generating_tokens',
			751 => 'gesture',
			752 => 'get_app',
			753 => 'gif',
			754 => 'gite',
			755 => 'golf_course',
			756 => 'google',
			757 => 'gpp_bad',
			758 => 'gpp_good',
			759 => 'gpp_maybe',
			760 => 'gps_fixed',
			761 => 'gps_not_fixed',
			762 => 'gps_off',
			763 => 'grade',
			764 => 'gradient',
			765 => 'grading',
			766 => 'grain',
			767 => 'graphic_eq',
			768 => 'grass',
			769 => 'grid_3x3',
			770 => 'grid_4x4',
			771 => 'grid_goldenratio',
			772 => 'grid_off',
			773 => 'grid_on',
			774 => 'grid_view',
			775 => 'group',
			776 => 'group_add',
			777 => 'group_off',
			778 => 'group_remove',
			779 => 'group_work',
			780 => 'groups',
			781 => 'h_mobiledata',
			782 => 'h_plus_mobiledata',
			783 => 'hail',
			784 => 'handshake',
			785 => 'hardware',
			786 => 'hd',
			787 => 'hdr_auto',
			788 => 'hdr_auto_select',
			789 => 'hdr_enhanced_select',
			790 => 'hdr_off',
			791 => 'hdr_off_select',
			792 => 'hdr_on',
			793 => 'hdr_on_select',
			794 => 'hdr_plus',
			795 => 'hdr_strong',
			796 => 'hdr_weak',
			797 => 'headphones',
			798 => 'headphones_battery',
			799 => 'headset',
			800 => 'headset_mic',
			801 => 'headset_off',
			802 => 'healing',
			803 => 'health_and_safety',
			804 => 'hearing',
			805 => 'hearing_disabled',
			806 => 'heart_broken',
			807 => 'height',
			808 => 'help',
			809 => 'help_center',
			810 => 'help_outline',
			811 => 'high_quality',
			812 => 'highlight',
			813 => 'highlight_alt',
			814 => 'highlight_off',
			815 => 'highlight_remove',
			816 => 'hiking',
			817 => 'history',
			818 => 'history_edu',
			819 => 'history_toggle_off',
			820 => 'holiday_village',
			821 => 'home',
			822 => 'home_max',
			823 => 'home_mini',
			824 => 'home_repair_service',
			825 => 'home_work',
			826 => 'horizontal_distribute',
			827 => 'horizontal_rule',
			828 => 'horizontal_split',
			829 => 'hot_tub',
			830 => 'hotel',
			831 => 'hotel_class',
			832 => 'hourglass_bottom',
			833 => 'hourglass_disabled',
			834 => 'hourglass_empty',
			835 => 'hourglass_full',
			836 => 'hourglass_top',
			837 => 'house',
			838 => 'house_siding',
			839 => 'houseboat',
			840 => 'how_to_reg',
			841 => 'how_to_vote',
			842 => 'http',
			843 => 'https',
			844 => 'hub',
			848 => 'hvac',
			849 => 'ice_skating',
			850 => 'icecream',
			851 => 'image',
			852 => 'image_aspect_ratio',
			853 => 'image_not_supported',
			854 => 'image_search',
			855 => 'imagesearch_roller',
			856 => 'import_contacts',
			857 => 'import_export',
			858 => 'important_devices',
			859 => 'inbox',
			860 => 'info',
			861 => 'input',
			862 => 'insert_chart',
			863 => 'insert_chart_outlined',
			864 => 'insert_comment',
			865 => 'insert_drive_file',
			866 => 'insert_emoticon',
			867 => 'insert_invitation',
			868 => 'insert_link',
			869 => 'insert_photo',
			870 => 'insights',
			871 => 'install_desktop',
			872 => 'install_mobile',
			873 => 'integration_instructions',
			874 => 'interests',
			875 => 'interpreter_mode',
			876 => 'inventory',
			877 => 'inventory_2',
			878 => 'invert_colors',
			879 => 'invert_colors_off',
			880 => 'ios_share',
			881 => 'iron',
			882 => 'iso',
			883 => 'kayaking',
			884 => 'keyboard',
			885 => 'keyboard_alt',
			886 => 'keyboard_arrow_down',
			887 => 'keyboard_arrow_left',
			888 => 'keyboard_arrow_right',
			889 => 'keyboard_arrow_up',
			890 => 'keyboard_backspace',
			891 => 'keyboard_capslock',
			892 => 'keyboard_command_key',
			893 => 'keyboard_control_key',
			894 => 'keyboard_double_arrow_down',
			895 => 'keyboard_double_arrow_left',
			896 => 'keyboard_double_arrow_right',
			897 => 'keyboard_double_arrow_up',
			898 => 'keyboard_hide',
			899 => 'keyboard_option_key',
			900 => 'keyboard_return',
			901 => 'keyboard_tab',
			902 => 'keyboard_voice',
			903 => 'king_bed',
			904 => 'kitchen',
			905 => 'kitesurfing',
			906 => 'label',
			907 => 'label_important',
			908 => 'label_important_outline',
			909 => 'label_off',
			910 => 'label_outline',
			911 => 'lan',
			912 => 'landscape',
			913 => 'language',
			914 => 'laptop',
			915 => 'laptop_chromebook',
			916 => 'laptop_mac',
			917 => 'laptop_windows',
			918 => 'last_page',
			919 => 'launch',
			920 => 'layers',
			921 => 'layers_clear',
			922 => 'leaderboard',
			923 => 'leak_add',
			924 => 'leak_remove',
			925 => 'legend_toggle',
			926 => 'lens',
			927 => 'lens_blur',
			928 => 'library_add',
			929 => 'library_add_check',
			930 => 'library_books',
			931 => 'library_music',
			932 => 'light',
			933 => 'light_mode',
			934 => 'lightbulb',
			935 => 'lightbulb_circle',
			936 => 'lightbulb_outline',
			937 => 'line_axis',
			938 => 'line_style',
			939 => 'line_weight',
			940 => 'linear_scale',
			941 => 'link',
			942 => 'link_off',
			943 => 'linked_camera',
			944 => 'liquor',
			945 => 'list',
			946 => 'list_alt',
			947 => 'live_help',
			948 => 'live_tv',
			949 => 'living',
			950 => 'local_activity',
			951 => 'local_airport',
			952 => 'local_atm',
			953 => 'local_attraction',
			954 => 'local_bar',
			955 => 'local_cafe',
			956 => 'local_car_wash',
			957 => 'local_convenience_store',
			958 => 'local_dining',
			959 => 'local_drink',
			960 => 'local_fire_department',
			961 => 'local_florist',
			962 => 'local_gas_station',
			963 => 'local_grocery_store',
			964 => 'local_hospital',
			965 => 'local_hotel',
			966 => 'local_laundry_service',
			967 => 'local_library',
			968 => 'local_mall',
			969 => 'local_movies',
			970 => 'local_offer',
			971 => 'local_parking',
			972 => 'local_pharmacy',
			973 => 'local_phone',
			974 => 'local_pizza',
			975 => 'local_play',
			976 => 'local_police',
			977 => 'local_post_office',
			978 => 'local_print_shop',
			979 => 'local_printshop',
			980 => 'local_restaurant',
			981 => 'local_see',
			982 => 'local_shipping',
			983 => 'local_taxi',
			984 => 'location_city',
			985 => 'location_disabled',
			986 => 'location_off',
			987 => 'location_on',
			988 => 'location_searching',
			989 => 'lock',
			990 => 'lock_clock',
			991 => 'lock_open',
			992 => 'lock_outline',
			993 => 'lock_reset',
			994 => 'login',
			995 => 'logo_dev',
			996 => 'logout',
			997 => 'looks',
			998 => 'looks_3',
			999 => 'looks_4',
			1000 => 'looks_5',
			1001 => 'looks_6',
			1002 => 'looks_one',
			1003 => 'looks_two',
			1004 => 'loop',
			1005 => 'loupe',
			1006 => 'low_priority',
			1007 => 'loyalty',
			1008 => 'lte_mobiledata',
			1009 => 'lte_plus_mobiledata',
			1010 => 'luggage',
			1011 => 'lunch_dining',
			1012 => 'mail',
			1013 => 'mail_outline',
			1014 => 'male',
			1015 => 'man',
			1016 => 'manage_accounts',
			1017 => 'manage_search',
			1018 => 'map',
			1019 => 'maps_home_work',
			1020 => 'maps_ugc',
			1021 => 'margin',
			1022 => 'mark_as_unread',
			1023 => 'mark_chat_read',
			1024 => 'mark_chat_unread',
			1025 => 'mark_email_read',
			1026 => 'mark_email_unread',
			1027 => 'markunread',
			1028 => 'markunread_mailbox',
			1029 => 'masks',
			1030 => 'maximize',
			1031 => 'media_bluetooth_off',
			1032 => 'media_bluetooth_on',
			1033 => 'mediation',
			1034 => 'medical_services',
			1035 => 'medication',
			1036 => 'meeting_room',
			1037 => 'memory',
			1038 => 'menu',
			1039 => 'menu_book',
			1040 => 'menu_open',
			1041 => 'merge',
			1042 => 'merge_type',
			1043 => 'message',
			1044 => 'messenger',
			1045 => 'messenger_outline',
			1046 => 'mic',
			1047 => 'mic_external_off',
			1048 => 'mic_external_on',
			1049 => 'mic_none',
			1050 => 'mic_off',
			1051 => 'microwave',
			1052 => 'military_tech',
			1053 => 'minimize',
			1054 => 'miscellaneous_services',
			1055 => 'missed_video_call',
			1056 => 'mms',
			1057 => 'mobile_friendly',
			1058 => 'mobile_off',
			1059 => 'mobile_screen_share',
			1060 => 'mobiledata_off',
			1061 => 'mode',
			1062 => 'mode_comment',
			1063 => 'mode_edit',
			1064 => 'mode_edit_outline',
			1065 => 'mode_night',
			1066 => 'mode_of_travel',
			1067 => 'mode_standby',
			1068 => 'model_training',
			1069 => 'monetization_on',
			1070 => 'money',
			1071 => 'money_off',
			1072 => 'money_off_csred',
			1073 => 'monitor',
			1074 => 'monitor_heart',
			1075 => 'monitor_weight',
			1076 => 'monochrome_photos',
			1077 => 'mood',
			1078 => 'mood_bad',
			1079 => 'moped',
			1080 => 'more',
			1081 => 'more_horiz',
			1082 => 'more_time',
			1083 => 'more_vert',
			1084 => 'mosque',
			1085 => 'motion_photos_auto',
			1086 => 'motion_photos_off',
			1087 => 'motion_photos_on',
			1088 => 'motion_photos_pause',
			1089 => 'motion_photos_paused',
			1090 => 'motorcycle',
			1091 => 'mouse',
			1092 => 'move_down',
			1093 => 'move_to_inbox',
			1094 => 'move_up',
			1095 => 'movie',
			1096 => 'movie_creation',
			1097 => 'movie_filter',
			1098 => 'moving',
			1099 => 'mp',
			1100 => 'multiline_chart',
			1101 => 'multiple_stop',
			1102 => 'museum',
			1103 => 'music_note',
			1104 => 'music_off',
			1105 => 'music_video',
			1106 => 'my_library_add',
			1107 => 'my_library_books',
			1108 => 'my_library_music',
			1109 => 'my_location',
			1110 => 'nat',
			1111 => 'nature',
			1112 => 'nature_people',
			1113 => 'navigate_before',
			1114 => 'navigate_next',
			1115 => 'navigation',
			1116 => 'near_me',
			1117 => 'near_me_disabled',
			1118 => 'nearby_error',
			1119 => 'nearby_off',
			1120 => 'network_cell',
			1121 => 'network_check',
			1122 => 'network_locked',
			1123 => 'network_wifi',
			1124 => 'new_label',
			1125 => 'new_releases',
			1126 => 'next_plan',
			1127 => 'next_week',
			1128 => 'nfc',
			1129 => 'night_shelter',
			1130 => 'nightlife',
			1131 => 'nightlight',
			1132 => 'nightlight_round',
			1133 => 'nights_stay',
			1134 => 'no_accounts',
			1135 => 'no_backpack',
			1136 => 'no_cell',
			1137 => 'no_crash',
			1138 => 'no_drinks',
			1139 => 'no_encryption',
			1140 => 'no_encryption_gmailerrorred',
			1141 => 'no_flash',
			1142 => 'no_food',
			1143 => 'no_luggage',
			1144 => 'no_meals',
			1145 => 'no_meeting_room',
			1146 => 'no_photography',
			1147 => 'no_sim',
			1148 => 'no_stroller',
			1149 => 'no_transfer',
			1150 => 'nordic_walking',
			1151 => 'north',
			1152 => 'north_east',
			1153 => 'north_west',
			1154 => 'not_accessible',
			1155 => 'not_interested',
			1156 => 'not_listed_location',
			1157 => 'not_started',
			1158 => 'note',
			1159 => 'note_add',
			1160 => 'note_alt',
			1161 => 'notes',
			1162 => 'notification_add',
			1163 => 'notification_important',
			1164 => 'notifications',
			1165 => 'notifications_active',
			1166 => 'notifications_none',
			1167 => 'notifications_off',
			1168 => 'notifications_on',
			1169 => 'notifications_paused',
			1170 => 'now_wallpaper',
			1171 => 'now_widgets',
			1172 => 'numbers',
			1173 => 'offline_bolt',
			1174 => 'offline_pin',
			1175 => 'offline_share',
			1176 => 'ondemand_video',
			1177 => 'online_prediction',
			1178 => 'opacity',
			1179 => 'open_in_browser',
			1180 => 'open_in_full',
			1181 => 'open_in_new',
			1182 => 'open_in_new_off',
			1183 => 'open_with',
			1184 => 'other_houses',
			1185 => 'outbound',
			1186 => 'outbox',
			1187 => 'outdoor_grill',
			1188 => 'outlet',
			1189 => 'outlined_flag',
			1190 => 'output',
			1191 => 'padding',
			1192 => 'pages',
			1193 => 'pageview',
			1194 => 'paid',
			1195 => 'palette',
			1196 => 'pan_tool',
			1197 => 'pan_tool_alt',
			1198 => 'panorama',
			1199 => 'panorama_fish_eye',
			1200 => 'panorama_horizontal',
			1201 => 'panorama_horizontal_select',
			1202 => 'panorama_photosphere',
			1203 => 'panorama_photosphere_select',
			1204 => 'panorama_vertical',
			1205 => 'panorama_vertical_select',
			1206 => 'panorama_wide_angle',
			1207 => 'panorama_wide_angle_select',
			1208 => 'paragliding',
			1209 => 'park',
			1210 => 'party_mode',
			1211 => 'password',
			1212 => 'pattern',
			1213 => 'pause',
			1214 => 'pause_circle',
			1215 => 'pause_circle_filled',
			1216 => 'pause_circle_outline',
			1217 => 'pause_presentation',
			1218 => 'payment',
			1219 => 'payments',
			1220 => 'pedal_bike',
			1221 => 'pending',
			1222 => 'pending_actions',
			1223 => 'people',
			1224 => 'people_alt',
			1225 => 'people_outline',
			1226 => 'percent',
			1227 => 'perm_camera_mic',
			1228 => 'perm_contact_calendar',
			1229 => 'perm_data_setting',
			1230 => 'perm_device_information',
			1231 => 'perm_identity',
			1232 => 'perm_media',
			1233 => 'perm_phone_msg',
			1234 => 'perm_scan_wifi',
			1235 => 'person',
			1236 => 'person_add',
			1237 => 'person_add_alt',
			1238 => 'person_add_disabled',
			1239 => 'person_off',
			1240 => 'person_outline',
			1241 => 'person_pin',
			1242 => 'person_pin_circle',
			1243 => 'person_remove',
			1244 => 'person_remove_alt_1',
			1245 => 'person_search',
			1246 => 'personal_injury',
			1247 => 'personal_video',
			1248 => 'pest_control',
			1249 => 'pest_control_rodent',
			1250 => 'pets',
			1251 => 'phone',
			1252 => 'phone_android',
			1253 => 'phone_bluetooth_speaker',
			1254 => 'phone_callback',
			1255 => 'phone_disabled',
			1256 => 'phone_enabled',
			1257 => 'phone_forwarded',
			1258 => 'phone_in_talk',
			1259 => 'phone_iphone',
			1260 => 'phone_locked',
			1261 => 'phone_missed',
			1262 => 'phone_paused',
			1263 => 'phonelink',
			1264 => 'phonelink_erase',
			1265 => 'phonelink_lock',
			1266 => 'phonelink_off',
			1267 => 'phonelink_ring',
			1268 => 'phonelink_setup',
			1269 => 'photo',
			1270 => 'photo_album',
			1271 => 'photo_camera',
			1272 => 'photo_camera_back',
			1273 => 'photo_camera_front',
			1274 => 'photo_filter',
			1275 => 'photo_library',
			1276 => 'photo_size_select_actual',
			1277 => 'photo_size_select_large',
			1278 => 'photo_size_select_small',
			1279 => 'piano',
			1280 => 'piano_off',
			1281 => 'picture_as_pdf',
			1282 => 'picture_in_picture',
			1283 => 'picture_in_picture_alt',
			1284 => 'pie_chart',
			1285 => 'pie_chart_outline',
			1286 => 'pin',
			1287 => 'pin_drop',
			1288 => 'pin_end',
			1289 => 'pin_invoke',
			1290 => 'pivot_table_chart',
			1291 => 'place',
			1292 => 'plagiarism',
			1293 => 'play_arrow',
			1294 => 'play_circle',
			1295 => 'play_circle_filled',
			1296 => 'play_circle_outline',
			1297 => 'play_disabled',
			1298 => 'play_for_work',
			1299 => 'play_lesson',
			1300 => 'playlist_add',
			1301 => 'playlist_add_check',
			1302 => 'playlist_play',
			1303 => 'plumbing',
			1304 => 'plus_one',
			1305 => 'podcasts',
			1306 => 'point_of_sale',
			1307 => 'policy',
			1308 => 'poll',
			1309 => 'polymer',
			1310 => 'pool',
			1311 => 'portable_wifi_off',
			1312 => 'portrait',
			1313 => 'post_add',
			1314 => 'power',
			1315 => 'power_input',
			1316 => 'power_off',
			1317 => 'power_settings_new',
			1318 => 'precision_manufacturing',
			1319 => 'pregnant_woman',
			1320 => 'present_to_all',
			1321 => 'preview',
			1322 => 'price_change',
			1323 => 'price_check',
			1324 => 'print',
			1325 => 'print_disabled',
			1326 => 'priority_high',
			1327 => 'privacy_tip',
			1328 => 'private_connectivity',
			1329 => 'production_quantity_limits',
			1330 => 'psychology',
			1331 => 'public',
			1332 => 'public_off',
			1333 => 'publish',
			1334 => 'published_with_changes',
			1335 => 'punch_clock',
			1336 => 'push_pin',
			1337 => 'qr_code',
			1338 => 'qr_code_2',
			1339 => 'qr_code_scanner',
			1340 => 'query_builder',
			1341 => 'query_stats',
			1342 => 'question_answer',
			1343 => 'queue',
			1344 => 'queue_music',
			1345 => 'queue_play_next',
			1346 => 'quick_contacts_dialer',
			1347 => 'quick_contacts_mail',
			1348 => 'quickreply',
			1349 => 'quiz',
			1350 => 'r_mobiledata',
			1351 => 'radar',
			1352 => 'radio',
			1353 => 'radio_button_checked',
			1354 => 'radio_button_unchecked',
			1355 => 'railway_alert',
			1356 => 'ramen_dining',
			1357 => 'ramp_left',
			1358 => 'ramp_right',
			1359 => 'rate_review',
			1360 => 'raw_off',
			1361 => 'raw_on',
			1362 => 'read_more',
			1363 => 'real_estate_agent',
			1364 => 'receipt',
			1365 => 'receipt_long',
			1366 => 'recent_actors',
			1367 => 'recommend',
			1368 => 'record_voice_over',
			1369 => 'rectangle',
			1370 => 'recycling',
			1371 => 'redo',
			1372 => 'reduce_capacity',
			1373 => 'refresh',
			1374 => 'remember_me',
			1375 => 'remove',
			1376 => 'remove_circle',
			1377 => 'remove_circle_outline',
			1378 => 'remove_done',
			1379 => 'remove_from_queue',
			1380 => 'remove_moderator',
			1381 => 'remove_red_eye',
			1382 => 'remove_shopping_cart',
			1383 => 'reorder',
			1384 => 'repeat',
			1385 => 'repeat_on',
			1386 => 'repeat_one',
			1387 => 'repeat_one_on',
			1388 => 'replay',
			1389 => 'replay_10',
			1390 => 'replay_30',
			1391 => 'replay_5',
			1392 => 'replay_circle_filled',
			1393 => 'reply',
			1394 => 'reply_all',
			1395 => 'report',
			1396 => 'report_gmailerrorred',
			1397 => 'report_off',
			1398 => 'report_problem',
			1399 => 'request_page',
			1400 => 'request_quote',
			1401 => 'reset_tv',
			1402 => 'restart_alt',
			1403 => 'restaurant',
			1404 => 'restaurant_menu',
			1405 => 'restore',
			1406 => 'restore_from_trash',
			1407 => 'restore_page',
			1408 => 'reviews',
			1409 => 'rice_bowl',
			1410 => 'ring_volume',
			1411 => 'rocket',
			1412 => 'rocket_launch',
			1415 => 'roller_skating',
			1416 => 'roofing',
			1417 => 'room',
			1418 => 'room_preferences',
			1419 => 'room_service',
			1420 => 'rotate_90_degrees_ccw',
			1421 => 'rotate_left',
			1422 => 'rotate_right',
			1423 => 'rounded_corner',
			1424 => 'route',
			1425 => 'router',
			1426 => 'rowing',
			1427 => 'rss_feed',
			1428 => 'rsvp',
			1429 => 'rtt',
			1430 => 'rule',
			1431 => 'rule_folder',
			1432 => 'run_circle',
			1433 => 'running_with_errors',
			1434 => 'rv_hookup',
			1435 => 'safety_check',
			1436 => 'safety_divider',
			1437 => 'sailing',
			1438 => 'sanitizer',
			1439 => 'satellite',
			1440 => 'satellite_alt',
			1441 => 'save',
			1442 => 'save_alt',
			1443 => 'save_as',
			1444 => 'saved_search',
			1445 => 'savings',
			1446 => 'scale',
			1447 => 'scanner',
			1448 => 'scatter_plot',
			1449 => 'schedule',
			1450 => 'schedule_send',
			1451 => 'schema',
			1452 => 'school',
			1453 => 'science',
			1454 => 'score',
			1455 => 'screen_lock_landscape',
			1456 => 'screen_lock_portrait',
			1457 => 'screen_lock_rotation',
			1458 => 'screen_rotation',
			1459 => 'screen_search_desktop',
			1460 => 'screen_share',
			1461 => 'screenshot',
			1462 => 'screenshot_monitor',
			1463 => 'scuba_diving',
			1464 => 'sd',
			1465 => 'sd_card',
			1466 => 'sd_card_alert',
			1467 => 'sd_storage',
			1468 => 'search',
			1469 => 'search_off',
			1470 => 'security',
			1471 => 'security_update',
			1472 => 'security_update_good',
			1473 => 'security_update_warning',
			1474 => 'segment',
			1475 => 'select_all',
			1476 => 'self_improvement',
			1477 => 'sell',
			1478 => 'send',
			1479 => 'send_and_archive',
			1480 => 'send_time_extension',
			1481 => 'send_to_mobile',
			1482 => 'sensor_door',
			1483 => 'sensor_window',
			1484 => 'sensors',
			1485 => 'sensors_off',
			1486 => 'sentiment_dissatisfied',
			1487 => 'sentiment_neutral',
			1488 => 'sentiment_satisfied',
			1489 => 'sentiment_satisfied_alt',
			1490 => 'sentiment_very_dissatisfied',
			1491 => 'sentiment_very_satisfied',
			1492 => 'set_meal',
			1493 => 'settings',
			1494 => 'settings_accessibility',
			1495 => 'settings_applications',
			1496 => 'settings_backup_restore',
			1497 => 'settings_bluetooth',
			1498 => 'settings_brightness',
			1499 => 'settings_cell',
			1500 => 'settings_ethernet',
			1501 => 'settings_input_antenna',
			1502 => 'settings_input_component',
			1503 => 'settings_input_composite',
			1504 => 'settings_input_hdmi',
			1505 => 'settings_input_svideo',
			1506 => 'settings_overscan',
			1507 => 'settings_phone',
			1508 => 'settings_power',
			1509 => 'settings_remote',
			1510 => 'settings_system_daydream',
			1511 => 'settings_voice',
			1512 => 'share',
			1513 => 'share_location',
			1514 => 'shield',
			1515 => 'shield_moon',
			1516 => 'shop',
			1517 => 'shop_2',
			1518 => 'shop_two',
			1519 => 'shopify',
			1520 => 'shopping_bag',
			1521 => 'shopping_basket',
			1522 => 'shopping_cart',
			1523 => 'short_text',
			1524 => 'shortcut',
			1525 => 'show_chart',
			1526 => 'shower',
			1527 => 'shuffle',
			1528 => 'shuffle_on',
			1529 => 'shutter_speed',
			1530 => 'sick',
			1531 => 'signal_cellular_0_bar',
			1535 => 'signal_cellular_4_bar',
			1536 => 'signal_cellular_alt',
			1537 => 'signal_cellular_alt_1_bar',
			1538 => 'signal_cellular_alt_2_bar',
			1539 => 'signal_cellular_connected_no_internet_0_bar',
			1543 => 'signal_cellular_connected_no_internet_4_bar',
			1544 => 'signal_cellular_no_sim',
			1545 => 'signal_cellular_nodata',
			1546 => 'signal_cellular_null',
			1547 => 'signal_cellular_off',
			1548 => 'signal_wifi_0_bar',
			1555 => 'signal_wifi_4_bar',
			1556 => 'signal_wifi_4_bar_lock',
			1557 => 'signal_wifi_bad',
			1558 => 'signal_wifi_connected_no_internet_4',
			1559 => 'signal_wifi_off',
			1563 => 'signal_wifi_statusbar_4_bar',
			1567 => 'signal_wifi_statusbar_connected_no_internet_4',
			1569 => 'signpost',
			1570 => 'sim_card',
			1571 => 'sim_card_alert',
			1572 => 'sim_card_download',
			1573 => 'single_bed',
			1574 => 'sip',
			1575 => 'skateboarding',
			1576 => 'skip_next',
			1577 => 'skip_previous',
			1578 => 'sledding',
			1579 => 'slideshow',
			1580 => 'slow_motion_video',
			1581 => 'smart_button',
			1582 => 'smart_display',
			1583 => 'smart_screen',
			1584 => 'smart_toy',
			1585 => 'smartphone',
			1586 => 'smoke_free',
			1587 => 'smoking_rooms',
			1588 => 'sms',
			1589 => 'sms_failed',
			1590 => 'snapchat',
			1591 => 'snippet_folder',
			1592 => 'snooze',
			1593 => 'snowboarding',
			1594 => 'snowmobile',
			1595 => 'snowshoeing',
			1596 => 'soap',
			1597 => 'social_distance',
			1598 => 'solar_power',
			1599 => 'sort',
			1600 => 'sort_by_alpha',
			1601 => 'soup_kitchen',
			1602 => 'source',
			1603 => 'south',
			1604 => 'south_america',
			1605 => 'south_east',
			1606 => 'south_west',
			1607 => 'spa',
			1608 => 'space_bar',
			1609 => 'space_dashboard',
			1610 => 'spatial_audio',
			1611 => 'spatial_audio_off',
			1612 => 'spatial_tracking',
			1613 => 'speaker',
			1614 => 'speaker_group',
			1615 => 'speaker_notes',
			1616 => 'speaker_notes_off',
			1617 => 'speaker_phone',
			1618 => 'speed',
			1619 => 'spellcheck',
			1620 => 'splitscreen',
			1621 => 'spoke',
			1622 => 'sports',
			1623 => 'sports_bar',
			1624 => 'sports_baseball',
			1625 => 'sports_basketball',
			1626 => 'sports_cricket',
			1627 => 'sports_esports',
			1628 => 'sports_football',
			1629 => 'sports_golf',
			1630 => 'sports_gymnastics',
			1631 => 'sports_handball',
			1632 => 'sports_hockey',
			1633 => 'sports_kabaddi',
			1634 => 'sports_martial_arts',
			1635 => 'sports_mma',
			1636 => 'sports_motorsports',
			1637 => 'sports_rugby',
			1638 => 'sports_score',
			1639 => 'sports_soccer',
			1640 => 'sports_tennis',
			1641 => 'sports_volleyball',
			1642 => 'square',
			1643 => 'square_foot',
			1644 => 'ssid_chart',
			1645 => 'stacked_bar_chart',
			1646 => 'stacked_line_chart',
			1647 => 'stadium',
			1648 => 'stairs',
			1649 => 'star',
			1650 => 'star_border',
			1651 => 'star_border_purple500',
			1652 => 'star_half',
			1653 => 'star_outline',
			1654 => 'star_purple500',
			1655 => 'star_rate',
			1656 => 'stars',
			1657 => 'start',
			1658 => 'stay_current_landscape',
			1659 => 'stay_current_portrait',
			1660 => 'stay_primary_landscape',
			1661 => 'stay_primary_portrait',
			1662 => 'sticky_note_2',
			1663 => 'stop',
			1664 => 'stop_circle',
			1665 => 'stop_screen_share',
			1666 => 'storage',
			1667 => 'store',
			1668 => 'store_mall_directory',
			1669 => 'storefront',
			1670 => 'storm',
			1671 => 'straight',
			1672 => 'straighten',
			1673 => 'stream',
			1674 => 'streetview',
			1675 => 'strikethrough_s',
			1676 => 'stroller',
			1677 => 'style',
			1678 => 'subdirectory_arrow_left',
			1679 => 'subdirectory_arrow_right',
			1680 => 'subject',
			1681 => 'subscript',
			1682 => 'subscriptions',
			1683 => 'subtitles',
			1684 => 'subtitles_off',
			1685 => 'subway',
			1686 => 'summarize',
			1687 => 'sunny',
			1688 => 'sunny_snowing',
			1689 => 'superscript',
			1690 => 'supervised_user_circle',
			1691 => 'supervisor_account',
			1692 => 'support',
			1693 => 'support_agent',
			1694 => 'surfing',
			1695 => 'surround_sound',
			1696 => 'swap_calls',
			1697 => 'swap_horiz',
			1698 => 'swap_horizontal_circle',
			1699 => 'swap_vert',
			1700 => 'swap_vertical_circle',
			1701 => 'swipe',
			1702 => 'swipe_down',
			1703 => 'swipe_down_alt',
			1704 => 'swipe_left',
			1705 => 'swipe_left_alt',
			1706 => 'swipe_right',
			1707 => 'swipe_right_alt',
			1708 => 'swipe_up',
			1709 => 'swipe_up_alt',
			1710 => 'swipe_vertical',
			1711 => 'switch_access_shortcut',
			1712 => 'switch_access_shortcut_add',
			1713 => 'switch_account',
			1714 => 'switch_camera',
			1715 => 'switch_left',
			1716 => 'switch_right',
			1717 => 'switch_video',
			1718 => 'synagogue',
			1719 => 'sync',
			1720 => 'sync_alt',
			1721 => 'sync_disabled',
			1722 => 'sync_lock',
			1723 => 'sync_problem',
			1724 => 'system_security_update',
			1725 => 'system_security_update_good',
			1726 => 'system_security_update_warning',
			1727 => 'system_update',
			1728 => 'system_update_alt',
			1729 => 'system_update_tv',
			1730 => 'tab',
			1731 => 'tab_unselected',
			1732 => 'table_bar',
			1733 => 'table_chart',
			1734 => 'table_landscape',
			1735 => 'table_restaurant',
			1736 => 'table_rows',
			1737 => 'tablet',
			1738 => 'tablet_android',
			1739 => 'tablet_mac',
			1740 => 'tag',
			1741 => 'tag_faces',
			1742 => 'takeout_dining',
			1743 => 'tap_and_play',
			1744 => 'tapas',
			1745 => 'task',
			1746 => 'task_alt',
			1747 => 'taxi_alert',
			1748 => 'telegram',
			1749 => 'temple_buddhist',
			1750 => 'temple_hindu',
			1751 => 'terminal',
			1752 => 'terrain',
			1753 => 'text_decrease',
			1754 => 'text_fields',
			1755 => 'text_format',
			1756 => 'text_increase',
			1757 => 'text_rotate_up',
			1758 => 'text_rotate_vertical',
			1759 => 'text_rotation_angledown',
			1760 => 'text_rotation_angleup',
			1761 => 'text_rotation_down',
			1762 => 'text_rotation_none',
			1763 => 'text_snippet',
			1764 => 'textsms',
			1765 => 'texture',
			1766 => 'theater_comedy',
			1767 => 'theaters',
			1768 => 'thermostat',
			1769 => 'thermostat_auto',
			1770 => 'thumb_down',
			1771 => 'thumb_down_alt',
			1772 => 'thumb_down_off_alt',
			1773 => 'thumb_up',
			1774 => 'thumb_up_alt',
			1775 => 'thumb_up_off_alt',
			1776 => 'thumbs_up_down',
			1777 => 'thunderstorm',
			1778 => 'tiktok',
			1779 => 'time_to_leave',
			1780 => 'timelapse',
			1781 => 'timeline',
			1782 => 'timer',
			1783 => 'timer_10',
			1784 => 'timer_10_select',
			1785 => 'timer_3',
			1786 => 'timer_3_select',
			1787 => 'timer_off',
			1788 => 'tips_and_updates',
			1789 => 'tire_repair',
			1790 => 'title',
			1791 => 'toc',
			1792 => 'today',
			1793 => 'toggle_off',
			1794 => 'toggle_on',
			1795 => 'token',
			1796 => 'toll',
			1797 => 'tonality',
			1798 => 'topic',
			1799 => 'tornado',
			1800 => 'touch_app',
			1801 => 'tour',
			1802 => 'toys',
			1803 => 'track_changes',
			1804 => 'traffic',
			1805 => 'train',
			1806 => 'tram',
			1807 => 'transfer_within_a_station',
			1808 => 'transform',
			1809 => 'transgender',
			1810 => 'transit_enterexit',
			1811 => 'translate',
			1812 => 'travel_explore',
			1813 => 'trending_down',
			1814 => 'trending_flat',
			1815 => 'trending_up',
			1816 => 'trip_origin',
			1817 => 'troubleshoot',
			1818 => 'try',
			1819 => 'tsunami',
			1820 => 'tty',
			1821 => 'tune',
			1822 => 'tungsten',
			1823 => 'turn_left',
			1824 => 'turn_right',
			1825 => 'turn_sharp_left',
			1826 => 'turn_sharp_right',
			1827 => 'turn_slight_left',
			1828 => 'turn_slight_right',
			1829 => 'turned_in',
			1830 => 'turned_in_not',
			1831 => 'tv',
			1832 => 'tv_off',
			1833 => 'two_wheeler',
			1834 => 'u_turn_left',
			1835 => 'u_turn_right',
			1836 => 'umbrella',
			1837 => 'unarchive',
			1838 => 'undo',
			1839 => 'unfold_less',
			1840 => 'unfold_more',
			1841 => 'unpublished',
			1842 => 'unsubscribe',
			1843 => 'upcoming',
			1844 => 'update',
			1845 => 'update_disabled',
			1846 => 'upgrade',
			1847 => 'upload',
			1848 => 'upload_file',
			1849 => 'usb',
			1850 => 'usb_off',
			1851 => 'vaccines',
			1852 => 'vape_free',
			1853 => 'vaping_rooms',
			1854 => 'verified',
			1855 => 'verified_user',
			1856 => 'vertical_align_bottom',
			1857 => 'vertical_align_center',
			1858 => 'vertical_align_top',
			1859 => 'vertical_distribute',
			1860 => 'vertical_shades',
			1861 => 'vertical_shades_closed',
			1862 => 'vibration',
			1863 => 'video_call',
			1864 => 'video_camera_back',
			1865 => 'video_camera_front',
			1866 => 'video_file',
			1867 => 'video_label',
			1868 => 'video_library',
			1869 => 'video_settings',
			1870 => 'video_stable',
			1871 => 'videocam',
			1872 => 'videocam_off',
			1873 => 'videogame_asset',
			1874 => 'videogame_asset_off',
			1875 => 'view_agenda',
			1876 => 'view_array',
			1877 => 'view_carousel',
			1878 => 'view_column',
			1879 => 'view_comfy',
			1880 => 'view_comfy_alt',
			1881 => 'view_compact',
			1882 => 'view_compact_alt',
			1883 => 'view_cozy',
			1884 => 'view_day',
			1885 => 'view_headline',
			1886 => 'view_in_ar',
			1887 => 'view_kanban',
			1888 => 'view_list',
			1889 => 'view_module',
			1890 => 'view_quilt',
			1891 => 'view_sidebar',
			1892 => 'view_stream',
			1893 => 'view_timeline',
			1894 => 'view_week',
			1895 => 'vignette',
			1896 => 'villa',
			1897 => 'visibility',
			1898 => 'visibility_off',
			1899 => 'voice_chat',
			1900 => 'voice_over_off',
			1901 => 'voicemail',
			1902 => 'volume_down',
			1903 => 'volume_mute',
			1904 => 'volume_off',
			1905 => 'volume_up',
			1906 => 'volunteer_activism',
			1907 => 'vpn_key',
			1908 => 'vpn_key_off',
			1909 => 'vpn_lock',
			1910 => 'vrpano',
			1911 => 'wallet',
			1912 => 'wallet_giftcard',
			1913 => 'wallet_membership',
			1914 => 'wallet_travel',
			1915 => 'wallpaper',
			1916 => 'warehouse',
			1917 => 'warning',
			1918 => 'warning_amber',
			1919 => 'wash',
			1920 => 'watch',
			1921 => 'watch_later',
			1922 => 'watch_off',
			1923 => 'water',
			1924 => 'water_damage',
			1925 => 'water_drop',
			1926 => 'waterfall_chart',
			1927 => 'waves',
			1928 => 'waving_hand',
			1929 => 'wb_auto',
			1930 => 'wb_cloudy',
			1931 => 'wb_incandescent',
			1932 => 'wb_iridescent',
			1933 => 'wb_shade',
			1934 => 'wb_sunny',
			1935 => 'wb_twilight',
			1936 => 'wc',
			1937 => 'web',
			1938 => 'web_asset',
			1939 => 'web_asset_off',
			1940 => 'webhook',
			1941 => 'wechat',
			1942 => 'weekend',
			1943 => 'west',
			1945 => 'whatshot',
			1946 => 'wheelchair_pickup',
			1947 => 'where_to_vote',
			1948 => 'widgets',
			1949 => 'wifi',
			1950 => 'wifi_1_bar',
			1951 => 'wifi_2_bar',
			1952 => 'wifi_calling',
			1953 => 'wifi_calling_3',
			1954 => 'wifi_channel',
			1955 => 'wifi_find',
			1956 => 'wifi_lock',
			1957 => 'wifi_off',
			1958 => 'wifi_password',
			1959 => 'wifi_protected_setup',
			1960 => 'wifi_tethering',
			1961 => 'wifi_tethering_error',
			1962 => 'wifi_tethering_error_rounded',
			1963 => 'wifi_tethering_off',
			1964 => 'window',
			1965 => 'wine_bar',
			1966 => 'woman',
			1967 => 'woo_commerce',
			1968 => 'wordpress',
			1969 => 'work',
			1970 => 'work_history',
			1971 => 'work_off',
			1972 => 'work_outline',
			1973 => 'workspace_premium',
			1974 => 'workspaces',
			1975 => 'wrap_text',
			1976 => 'wrong_location',
			1977 => 'wysiwyg',
			1978 => 'yard',
			1979 => 'youtube_searched_for',
			1980 => 'zoom_in',
			1981 => 'zoom_in_map',
			1982 => 'zoom_out',
			1983 => 'zoom_out_map'
		);
	
		return $data;
	}
	
	public function iconosfa_array(){
		$data = array (
			0 => 'fa fa-bookmark',
			1 => 'fa fa-bookmark-o',
			2 => 'fa fa-envelope-open',
			3 => 'fa fa-envelope-open-o',
			4 => 'fa fa-id-badge',
			5 => 'fa fa-id-card',
			6 => 'fa fa-id-card-o',
			7 => 'fa fa-address-card',
			8 => 'fa fa-address-card-o',
			9 => 'fa fa-window-close',
			10 => 'fa fa-window-close-o',
			11 => 'fa fa-archive',
			12 => 'fa fa-area-chart',
			13 => 'fa fa-car',
			14 => 'fa fa-balance-scale',
			15 => 'fa fa-ban',
			16 => 'fa fa-university',
			17 => 'fa fa-bar-chart',
			18 => 'fa fa-bars',
			19 => 'fa fa-bell',
			20 => 'fa fa-bell-o',
			21 => 'fa fa-bell-slash',
			22 => 'fa fa-bell-slash-o',
			23 => 'fa fa-birthday-cake',
			24 => 'fa fa-book',
			25 => 'fa fa-briefcase',
			26 => 'fa fa-building',
			27 => 'fa fa-building-o',
			28 => 'fa fa-bus',
			29 => 'fa fa-taxi',
			30 => 'fa fa-calculator',
			31 => 'fa fa-calendar',
			32 => 'fa fa-calendar-check-o',
			33 => 'fa fa-calendar-times-o',
			34 => 'fa fa-camera',
			35 => 'fa fa-camera-retro',
			36 => 'fa fa-car',
			37 => 'fa fa-cart-arrow-down',
			38 => 'fa fa-cart-plus',
			39 => 'fa fa-check',
			40 => 'fa fa-check-circle',
			41 => 'fa fa-check-circle-o',
			42 => 'fa fa-check-square',
			43 => 'fa fa-check-square-o',
			44 => 'fa fa-times',
			45 => 'fa fa-cloud-download',
			46 => 'fa fa-cloud-upload',
			47 => 'fa fa-comment',
			48 => 'fa fa-comment-o',
			49 => 'fa fa-commenting',
			50 => 'fa fa-commenting-o',
			51 => 'fa fa-comments',
			52 => 'fa fa-comments-o',
			53 => 'fa fa-credit-card',
			54 => 'fa fa-credit-card-alt',
			55 => 'fa fa-database',
			56 => 'fa fa-desktop',
			57 => 'fa fa-download',
			58 => 'fa fa-envelope',
			59 => 'fa fa-envelope-o',
			60 => 'fa fa-envelope-open',
			61 => 'fa fa-envelope-open-o',
			62 => 'fa fa-exclamation-circle',
			63 => 'fa fa-exclamation-triangle',
			64 => 'fa fa-folder',
			65 => 'fa fa-folder-o',
			66 => 'fa fa-folder-open',
			67 => 'fa fa-folder-open-o',
			68 => 'fa fa-users',
			69 => 'fa fa-home',
			70 => 'fa fa-picture-o',
			71 => 'fa fa-line-chart',
			72 => 'fa fa-location-arrow',
			73 => 'fa fa-lock',
			74 => 'fa fa-map-marker',
			75 => 'fa fa-map-o',
			76 => 'fa fa-map-signs',
			77 => 'fa fa-microphone',
			78 => 'fa fa-microphone-slash',
			79 => 'fa fa-money',
			80 => 'fa fa-pie-chart',
			81 => 'fa fa-search',
			82 => 'fa fa-shopping-bag',
			83 => 'fa fa-shopping-basket',
			84 => 'fa fa-shopping-cart',
			85 => 'fa fa-tag',
			86 => 'fa fa-tags',
			87 => 'fa fa-trash',
			88 => 'fa fa-trash-o',
			89 => 'fa fa-user',
			90 => 'fa fa-user-circle',
			91 => 'fa fa-user-circle-o',
			92 => 'fa fa-user-o',
			93 => 'fa fa-user-plus',
			94 => 'fa fa-users',
			95 => 'fa fa-file',
			96 => 'fa fa-file-archive-o',
			97 => 'fa fa-file-audio-o',
			98 => 'fa fa-file-video-o',
			99 => 'fa fa-file-o',
			100 => 'fa fa-file-pdf-o',
			101 => 'fa fa-file-text',
			102 => 'fa fa-file-text-o',
			103 => 'fa fa-file-video-o',
			104 => 'fa fa-youtube-play',
			105 => 'fa fa-facebook',
			106 => 'fa fa-facebook-official',
			107 => 'fa fa-google',
			108 => 'fa fa-instagram',
			109 => 'fa fa-linkedin',
			110 => 'fa fa-linkedin-square',
			111 => 'fa fa-paypal',
			112 => 'fa fa-twitter',
			113 => 'fa fa-twitter-square',
			114 => 'fa fa-youtube',
			115 => 'fa fa-youtube-play',
			116 => 'fa fa-youtube-square',
			117 => 'fa fa-ambulance',
			118 => 'fa fa-heartbeat',
			119 => 'fa fa-stethoscope',
			120 => 'fa fa-hospital-o',
			121 => 'fa fa-user-md',
			122 => 'fa fa-heart',
			123 => 'fa fa-medkit',
			124 => 'fa fa-heart-o',
			125 => 'fa fa-plus-square',
			126 => 'fa fa-plane',
			127 => 'fa fa-clock'
		  );

		  return $data;
	}


} //fin de la Clse Funciones

?>
