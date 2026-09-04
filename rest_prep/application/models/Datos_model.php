<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datos_model extends CI_Model
{

    public function __construct()
  	{
     	parent::__construct();
        $this->load->database();
    }

    

    public function etiqueta(){
      $query = $this->db->query("SELECT id_etiqueta as id, etiqueta as valor FROM tblc_etiqueta ORDER BY etiqueta");
      $res = $query->result_array();
      return $res;
    }

    public function proceso_electoral(){
      $query = $this->db->query("SELECT id_proceso_electoral as id, CONCAT('Proceso Electoral: ',fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1 ORDER BY fecha DESC");
      $res = $query->result_array();
      return $res;
    }

    public function tipoEleccionText($value){
      $array_visible=array(1=>"Basica", 2=>"Contigua", 3=>"Extraordinaria", 4=>"Especial");
      foreach($array_visible as $t => $visible)
      {
        if($value==$t) return $visible;
      }
    }

    public function casilla_usuario($idmunicipio, $idcasilla){
      $sentencia = ($idcasilla != '0') ? ' AND id_casilla ='.$idcasilla : '';

      $query = $this->db->query("SELECT * FROM tblc_casilla WHERE id_municipio = ".$idmunicipio.$sentencia);
      $res = $query->result_array();
      foreach ($res as $i => $cas) {
        $res[$i]['tipo_casilla'] = $this->tipoEleccionText($cas['tipo']);
      }
      return $res;
    }

    public function partidos_eleccion($idprocesoelec, $idcasilla){
      $query = $this->db->query("SELECT c.*, p.id_partido_politico, p.nombre as partido, p.icono, p.colo 
                                  FROM tblc_candidato_partido AS cp 
                                  INNER JOIN tblc_candidato AS c ON c.id_candidato = cp.id_candidato 
                                  INNER JOIN tblc_partido_politico AS p ON p.id_partido_politico = cp.id_partido_politico 
                                  WHERE c.id_proceso_electoral = ".$idprocesoelec." ORDER BY c.ordenamiento ASC");
      $res = $query->result_array();

      foreach ($res as $i => $pro) {
        $query2 = $this->db->query("SELECT resultado FROM tbl_resultado WHERE id_casilla = ".$idcasilla." AND id_candidato =".$pro['id_candidato']);
        $res[$i]['total_votos'] = (!$query2->row()->resultado) ? 0 : $query2->row()->resultado;
      }

     
      return $res;
    }

    public function resultado_eleccion($idrepresentante){
      $query = $this->db->query("SELECT c.* FROM tbl_resultado as r 
                                JOIN tblc_candidato as c ON(r.id_candidato = c.id_candidato) 
                                WHERE c.principal = 1 and r.id_representante = ".$idrepresentante);
      $res = $query->result_array();
      return $res;
    }

    public function nominal($busqueda = '', $idprocesoelec, $idcasilla){
      $sentencia = ($busqueda != '') ? " AND REPLACE(CONCAT(folio, '', nombre, '', curp), ' ', '') LIKE '%".str_replace(' ', '', $busqueda)."%'" : '';
      $sentencia .= ($idprocesoelec != '0') ? " AND id_proceso_electoral =".$idprocesoelec : ' AND id_proceso_electoral = 0';
      $sentencia .= ($idcasilla != '0') ? " AND id_casilla =".$idcasilla : ' AND id_casilla = 0';

      $query = $this->db->query("SELECT * FROM tbl_lista_nominal WHERE fecha_eliminado IS NULL".$sentencia);
      $res = $query->result_array();
      return $res;
    }

    //////////////////

    public function secciones(){

      $secciones = $this->db->query("SELECT * FROM tblc_seccion ORDER BY nombre ASC");
      $res = $secciones->result_array(); 
      
      return $res;
    }


    public function casillas($seccion){

      $casillas = $this->db->query("SELECT * FROM tblc_casilla WHERE seccion =".$seccion);
      $res = $casillas->result_array(); 
      
      return $res;
    }

    /////////////////

    public function secciones_pro(){
      $DB2 = $this->load->database('promovido', TRUE);

      $secciones = $DB2->query("SELECT id_seccional, nombre, id_distrito, id_municipio, meta, lista_nominal, id_prioridad_seccion, responsable, id_distrito_federal FROM tblc_seccional ORDER BY nombre ASC");
      $res = $secciones->result_array(); 
      
      return $res;
    }


    public function casillas_pro($seccion){
      $DB2 = $this->load->database('promovido', TRUE);

      $casillas = $DB2->query("SELECT * FROM tblc_casilla WHERE seccion =".$seccion);
      $res = $casillas->result_array(); 
      
      return $res;
    }


    public function promovidos_pro($seccion, $casilla){
      $DB2 = $this->load->database('promovido', TRUE);

      $promovidos = $DB2->query("SELECT * FROM tbl_registrado WHERE id_casilla =".$casilla." AND seccion_elector =".$seccion." ORDER BY consecutivo ASC");
      $res = $promovidos->result_array(); 
      
      return $res;
    }

    public function nopromovidos_pro($seccion, $casilla){
      $DB2 = $this->load->database('promovido', TRUE);

      $promovidos = $DB2->query("SELECT * FROM tbl_no_registrado WHERE id_casilla =".$casilla." AND seccion =".$seccion." ORDER BY consecutivo ASC");
      $res = $promovidos->result_array(); 
      
      return $res;
    }
    
    


}
