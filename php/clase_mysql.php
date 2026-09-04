<?php
class DB_mysql extends Variables{
	/* identificador de conexión y consulta */
	
	var $Conexion_ID = 0;
	var $Consulta_ID = 0; 
	/* número de error y texto error */
	var $Errno = 0;
	var $Error = "";	 
	var $msjError = 'ERROR al realizar la consulta';	
	
	public string $sql = '';

	/* Método Constructor: Cada vez que creemos una variable	
	de esta clase, se ejecutará esta función*/
	/*Conexión a la base de datos*/
	
	public function __construct($opc = "1"){
		$this->opcion($opc);
		
		// Conectamos al servidor
			$this->Conexion_ID = @mysqli_connect($this->Servidor, $this->Usuario, $this->Clave, $this->BaseDatos);

		if (!$this->Conexion_ID) {		
			$this->Error = "Ha fallado la conexión.";
			throw new RuntimeException($this->Error);
			}	 
		//seleccionamos la base de datos
		/* Si hemos tenido éxito conectando devuelve 	
		el identificador de la conexión, sino devuelve 0 */
		mysqli_set_charset($this->Conexion_ID,"utf8mb4");
		return;	
	}
	
	function escapar_variable($var){
		//escapamos la variable
		$var = mysqli_real_escape_string($this->Conexion_ID, (string) $var);
		return $var;
	}
	
	/* Ejecuta un consulta */
	
	public function consulta($sql = ""){	 
	
		if ($sql == '')
		{		
			$this->Error = "No ha especificado una consulta SQL";		
			return 0;		
		}
			
		$this->sql = $sql;

		/* Ejecutamos la consulta */
		$this->Consulta_ID = @mysqli_query( $this->Conexion_ID, $sql);
	
		if (!$this->Consulta_ID) {		
			$this->Errno = mysqli_errno( $this->Conexion_ID);		
			$this->Error = mysqli_error( $this->Conexion_ID);		
		}
		
		/* Si hemos tenido éxito en la consulta devuelve 
		el identificador de la conexión, sino devuelve 0 */
	
		return $this->Consulta_ID;
	}
	
	//Conteo de registros de una consulta
	public function conteoconsulta($sql){	 
	
		if(!$this->consulta($sql))
		{
			echo $this->msjError;
			echo '<br>'.$this->Error;
			echo '<br>'.$sql;
			exit(0);
		}

		/* Si hemos tenido éxito en la consulta devuelve 
		el identificador de la conexión, sino devuelve 0 */
		
		return mysqli_num_rows($this->Consulta_ID);
	}	
	
	/* Devuelve el el ultimo id insertado */
	
	public function obtenerconexion() {
		return $this->Conexion_ID;
	}
	
	public function ultimoid() {
		return mysqli_insert_id($this->Conexion_ID);
	}
	
	/* Devuelve el número de campos de una consulta */
	
	public function numcampos() {
		return mysqli_num_fields($this->Consulta_ID);
	}
	
	/* Devuelve el número de registros de una consulta */
	
	public function numregistros(){
		return mysqli_num_rows($this->Consulta_ID);
	}

	public function obtenerlista($sql){
		if(!$this->consulta($sql)){
			echo $this->msjError;
			echo '<br>'.$this->Error;
			echo '<br>'.$sql;
			exit(0);
			}
					
		$array = array();
		while ($row = mysqli_fetch_object($this->Consulta_ID)){
			$array[] = $row;
		}
		return $array;	
	}
	
	public function consultadato($sql) {
		if(!$this->consulta($sql)){
			echo $this->msjError;
			echo '<br>'.$this->Error;
			echo '<br>'.$sql;
			exit(0);
			}
		// mostrarmos los registros
		$row = mysqli_fetch_array($this->Consulta_ID,MYSQLI_NUM);
		if(isset($row)) return $row[0];
		else return '';
	}

	public function consultaexistencia($sql) {
		if(!$this->consulta($sql)){
			echo $this->msjError;
			echo '<br>'.$this->Error;
			echo '<br>'.$sql;
			exit(0);
			}
		// mostrarmos los registros
		$row = mysqli_fetch_array($this->Consulta_ID,MYSQLI_NUM);
		//identifica si existen datos para enviar el ID de lo contrario regresa cero
		if($this->numregistros() >= 1)
			return $row[0];
		else
			return 0;
	}

	public function fetch_array($sql) {
		if(!$this->consulta($sql)){
			echo $this->msjError;
			echo '<br>'.$this->Error;
			echo '<br>'.$sql;
			exit(0);
			}
		// mostrarmos los registros
		return mysqli_fetch_array($this->Consulta_ID,MYSQLI_ASSOC);
	}
	
	public function fetch_objet($sql) {
		if(!$this->consulta($sql)){
			echo $this->msjError;
			echo '<br>'.$this->Error;
			echo '<br>'.$sql;
			exit(0);
			}
		// mostrarmos los registros
		return mysqli_fetch_object($this->Consulta_ID);
	}

	public function fetch_object($sql) {
		return $this->fetch_objet($sql);
	}

	//cerrar coexion
	public function cerrarconexion(){
		//@mysqli_free_result($this->Conexion_ID);
		@mysqli_close($this->Conexion_ID);		
		
		}
	
} //fin de la Clse DB_mysql
?>
