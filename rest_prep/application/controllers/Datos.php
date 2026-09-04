<?php
error_reporting(0);

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');
use Restserver\libraries\REST_Controller;
class Datos extends REST_Controller {

  private $cantidad = 10;

    public function __construct(){
      parent::__construct();
      header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
      header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
      header("Access-Control-Allow-Origin: *");
      $this->load->database();
    }


    public function bingo_post(){

      $data = $this->post();
  
      if(!isset($data['busqueda']) OR !isset($data['idprocesoelectoral']) OR !isset($data['idcasilla'])){
          $respuesta = array(
                          'error' => TRUE,
                          'mensaje'=> 'La información enviada no es válida'
                        );
          
          $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
          return;
        }
        
        
        $this->load->model('datos_model');
        $lista = $this->datos_model->nominal($data['busqueda'], $data['idprocesoelectoral'], $data['idcasilla']);
  
        $respuesta = array(
                    'error' => FALSE,
                    'nominal' => $lista
                  );
        $this->response($respuesta);
   }
    
   public function guardar_votos_post(){

    $data = $this->post();

    if(!isset($data['id_representante']) OR !isset($data['idprocesoelectoral']) OR !isset($data['idcasilla']) OR !isset($data['no_registrados'])
      OR !isset($data['votos_nulos']) OR !isset($data['candidato']) OR !isset($data['partido']) OR !isset($data['votos']) 
      OR !isset($data['imagen'])){
        $respuesta = array(
                        'error' => TRUE,
                        'mensaje'=> 'La información enviada no es válida'
                      );
        
        $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
        return;
      }
      
       $candidatos = json_decode(stripslashes($data['candidato']));
       $partidos = json_decode(stripslashes($data['partido']));
       $votos = json_decode(stripslashes($data['votos']));

      foreach ($partidos as $i => $idpartido) {

            $total_votos = $votos[$i];
            $idcandidato = $candidatos[$i];

            $datos = array(
              'id_candidato' => $idcandidato,
              'id_casilla' => $data['idcasilla'],
              'id_partido_politico' => $idpartido,
              'resultado' => $total_votos,
              'id_representante' => $data['id_representante'],
              'fecha_registro' => date("Y-m-d H:i:s")
            );
        $this->db->replace('tbl_resultado', $datos);        
                                
      }

       $res = "done";
       $file = '';
       //imagen png codificada en base64
       if($data['imagen'] != ''){
        $dataimg = $data['imagen'];            
        $dataimg = str_replace('data:image/jpeg;base64,', '', $dataimg);
        $dataimg = str_replace(' ', '+', $dataimg);
        $dataimg = base64_decode($dataimg);
        $file = 'actas_eleccion/'.date('dmY').'_'.date('his').'.jpg';
        $success = file_put_contents('../archivos/'.$file, $dataimg);
       }
        
       //fin imagen png codificada en base64

       $actaquery = $this->db->query("SELECT id_acta FROM tbl_acta WHERE id_proceso_electoral = ".$data['idprocesoelectoral']." AND id_casilla = ".$data['idcasilla']);
   
       if($actaquery->num_rows() > 0){
          $acta = $actaquery->row()->id_acta;
       
          $datosActa = array(
            'archivo' => $file,
            'votos_nulos' => $data['votos_nulos'],
            'no_registrados' => $data['no_registrados'],
            'id_representante' => $data['id_representante']
          );

         $this->db->where('id_acta', $acta);
         $this->db->update('tbl_acta', $datosActa);

        if(!$this->db->affected_rows() >=1){
          $res = "niguno"; 
        }

      }else{
        $datosActa = array(
          'archivo' => $file,
          'id_proceso_electoral' => $data['idprocesoelectoral'],
          'id_casilla' => $data['idcasilla'],
          'no_registrados' => $data['no_registrados'],
          'id_representante' => $data['id_representante'],
          'votos_nulos' => $data['votos_nulos'],
          'fecha_registro' => date("Y-m-d H:i:s")
        );

        if(!$this->db->insert('tbl_acta', $datosActa)){
          $res = $this->db->error();
        }       

      }

      if($res=="done"){
   
        $respuesta = array(
                  'error' => FALSE,
                  'agregado' => TRUE,
                  'mensaje' =>''
                );
      }else{
        $respuesta = array(
                  'error' => TRUE,
                  'agregado' => FALSE,
                  'mensaje' => $res
                );
      }
      $this->response($respuesta);

  }


  public function guardar_estatus_casilla_post(){

    $data = $this->post();

    if(!isset($data['id_representante']) OR !isset($data['idprocesoelectoral']) OR !isset($data['idcasilla']) OR !isset($data['tipo'])){
        $respuesta = array(
                        'error' => TRUE,
                        'mensaje'=> 'La información enviada no es válida'
                      );
        
        $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
        return;
      }

      $actaquery = $this->db->query("SELECT * FROM tbl_estatus_casilla WHERE id_casilla = ".$data['idcasilla']." AND tipo = ".$data['tipo']." AND id_proceso_electoral = ".$data['idprocesoelectoral']);

      if($actaquery->num_rows()>0){
        $respuesta = array(
                    'error' => TRUE,
                    'mensaje'=> 'La información enviada ya existe'
                  );

          $this->response( $respuesta );
          return;
      }
      
      $datos = array(
        'id_proceso_electoral' => $data['idprocesoelectoral'],
        'id_casilla' => $data['idcasilla'],
        'tipo' => $data['tipo'],
        'observaciones' => '-',
        'id_usuario' => '0',
        'fecha_hora' => date("Y-m-d H:i:s"),
        'fecha_registro' => date("Y-m-d H:i:s")
      );

      if(!$this->db->insert('tbl_estatus_casilla', $datos)){
        $res = $this->db->error();
      }   
         
      if($res=="done"){   
        $respuesta = array(
                  'error' => FALSE,
                  'agregado' => TRUE,
                  'mensaje' =>''
                );
      }else{
        $respuesta = array(
                  'error' => TRUE,
                  'agregado' => FALSE,
                  'mensaje' => $res
                );
      }
      $this->response($respuesta);

  }


  public function guardar_reporte_post(){

    $data = $this->post();

    if(!isset($data['id_representante']) OR !isset($data['descripcion']) OR !isset($data['idcasilla']) OR !isset($data['imagen'])){
        $respuesta = array(
                        'error' => TRUE,
                        'mensaje'=> 'La información enviada no es válida'
                      );
        
        $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
        return;
      }

      $etiquetas = json_decode(stripslashes($data['etiquetas']));

     

      $repreQuery = $this->db->query("SELECT * FROM tblc_representante WHERE id_representante = ".$data['id_representante']);
      $representante = $repreQuery->row();

       //imagen png codificada en base64
       $dataimg = $data['imagen'];            
       $dataimg = str_replace('data:image/jpeg;base64,', '', $dataimg);
       $dataimg = str_replace(' ', '+', $dataimg);
       $dataimg = base64_decode($dataimg);
       $file = date('dmY').'_'.date('his').'.jpg';
       $success = file_put_contents('../archivos/'.$file, $dataimg);
      //fin imagen png codificada en base64

      $datos = array(
        'id_municipio' => $representante['id_municipio'],
        'nombre' => $representante['nombre'],
        'tipo_reporte' => '1',
        'descripcion' => $data['descripcion'],
        'foto' => $file,
        'tipo_registro' => '1',
        'id_casilla' => $data['idcasilla'],
        'fecha_registro' => date("Y-m-d H:i:s")
      );

      if(!$this->db->insert('tbl_reporte', $datos)){
        $res = $this->db->error();
      }   
      $id = $this->db->insert_id();

      if($res=="done"){   

          /////// folio   
          $folio = $this->db->query("SELECT id_reporte AS id, MONTH(fecha_registro) AS mes, YEAR(fecha_registro) AS anio FROM tbl_reporte WHERE id_reporte = ".$id);
          $folios = array(
                'folio' => $folio->row()->id.'-'.$folio->row()->mes.''.$folio->row()->anio
          );      
          $this->db->where('id_reporte', $id);
          $this->db->update('tbl_reporte', $folios);
          /////// fin folio
          /// etiquetas
            $reseliminados = "done";
            if(!$this->db->delete('tbl_reporte_etiqueta', array('id_reporte' => $id))){
                $reseliminados = $this->db->error();
            }
            if ($reseliminados == "done") {
              foreach ($etiquetas as $i => $idetiqueta) {
                $datosetiqueta = array(
                                          'id_reporte' => $id,
                                          'id_etiqueta' => $idetiqueta
                                          );
                $this->db->insert('tbl_reporte_etiqueta', $datosetiqueta);       
              }
            }      
          /// fin etiquetas

        $respuesta = array(
                  'error' => FALSE,
                  'agregado' => TRUE,
                  'mensaje' =>''
                );
      }else{
        $respuesta = array(
                  'error' => TRUE,
                  'agregado' => FALSE,
                  'mensaje' => $res
                );
      }
      $this->response($respuesta);

  }

  public function guardar_estatus_voto_post(){

    $data = $this->post();

    if(!isset($data['idlistanominal']) OR !isset($data['estatusvoto'])){
        $respuesta = array(
                        'error' => TRUE,
                        'mensaje'=> 'La información enviada no es válida'
                      );
        
        $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
        return;
      }

      $res = "done";

      $datos = array(
        'estatus_voto' => $data['estatusvoto']
      );

      $this->db->where('id_lista_nominal', $data['idlistanominal']);
      $this->db->update('tbl_lista_nominal', $datos);
      // if(!$this->db->affected_rows() >=1){
      //   $res = "niguno"; 
      // }

      if($res=="done"){   
        $respuesta = array(
                  'error' => FALSE,
                  'agregado' => TRUE,
                  'mensaje' =>''
                );
      }else{
        $respuesta = array(
                  'error' => TRUE,
                  'agregado' => FALSE,
                  'mensaje' => $res
                );
      }
      $this->response($respuesta);

  }

  

    public function lista_etiqueta_get(){
      $this->load->model('datos_model');
      $lista = $this->datos_model->etiqueta();

      $respuesta = array(
                  'error' => FALSE,
                  'etiqueta' => $lista
                );
      $this->response($respuesta);
    }

    public function proceso_electoral_get(){
      $this->load->model('datos_model');
      $lista = $this->datos_model->proceso_electoral();

      $respuesta = array(
                  'error' => FALSE,
                  'proceso_electoral' => $lista
                );
      $this->response($respuesta);
    }

    public function casillas_usuario_get($idmunicipio, $idcasilla = '0'){
      $this->load->model('datos_model');
      $lista = $this->datos_model->casilla_usuario($idmunicipio, $idcasilla);

      $respuesta = array(
                  'error' => FALSE,
                  'casilla_usuario' => $lista
                );
      $this->response($respuesta);
    }

    public function lista_partidos_get($idprocesoelec, $idcasilla){
      $this->load->model('datos_model');
      $lista = $this->datos_model->partidos_eleccion($idprocesoelec, $idcasilla);

      $respuesta = array(
                  'error' => FALSE,
                  'partidos_eleccion' => $lista
                );
      $this->response($respuesta);
    }

    public function lista_resultados_get($idrepresentante){
      $this->load->model('datos_model');
      $lista = $this->datos_model->resultado_eleccion($idrepresentante);

      $respuesta = array(
                  'error' => FALSE,
                  'resultado_eleccion' => $lista
                );
      $this->response($respuesta);
    }


    public function secciones_get(){
      $this->load->model('datos_model');
      $lista = $this->datos_model->secciones();

      $respuesta = array(
                  'error' => FALSE,
                  'secciones' => $lista
                );
      $this->response($respuesta);
    }

    public function casillas_get($seccion){
      $this->load->model('datos_model');
      $lista = $this->datos_model->casillas($seccion);

      $respuesta = array(
                  'error' => FALSE,
                  'casillas' => $lista
                );
      $this->response($respuesta);
    }


    public function lista_secciones_get(){
      $this->load->model('datos_model');
      $lista = $this->datos_model->secciones_pro();

      $respuesta = array(
                  'error' => FALSE,
                  'secciones' => $lista
                );
      $this->response($respuesta);
    }

    public function lista_casillas_get($seccion){
      $this->load->model('datos_model');
      $lista = $this->datos_model->casillas_pro($seccion);

      $respuesta = array(
                  'error' => FALSE,
                  'casillas' => $lista
                );
      $this->response($respuesta);
    }

    public function lista_promovidos_get($seccion, $casilla){
      $this->load->model('datos_model');
      $lista = $this->datos_model->promovidos_pro($seccion, $casilla);

      $respuesta = array(
                  'error' => FALSE,
                  'promovidos' => $lista
                );
      $this->response($respuesta);
    }

    public function lista_nopromovidos_get($seccion, $casilla){
      //$this->load->model('datos_model');
      //$lista = $this->datos_model->nopromovidos_pro($seccion, $casilla);
      $DB2 = $this->load->database('promovido', TRUE);

      $consecutivos = array();
      for ($i=1; $i <= 750; $i++) { 

        $existeReg = $DB2->query("SELECT * FROM tbl_registrado WHERE seccion_elector = '".$seccion."' AND id_casilla = '".$casilla."' AND consecutivo = ".$i);
        $existenoReg = $DB2->query("SELECT * FROM tbl_no_registrado WHERE seccion = '".$seccion."' AND id_casilla = '".$casilla."' AND consecutivo = ".$i);
        $voto = 0;
        if($existeReg->num_rows() > 0){
          $existeRegistrado = $existeReg->row();

          $voto = $existeRegistrado->estatus_voto;
        }
         if($existenoReg->num_rows() > 0){
          $voto = 1;
        }

        $consecutivos[] = array('consecutivo' => $i, 'estatus_voto' => $voto, 'existenoReg' => $existenoReg->num_rows(), 'existeReg' => $existeReg->num_rows()); 
      }

      $respuesta = array(
                  'error' => FALSE,
                  'promovidos' => $consecutivos
                );
      $this->response($respuesta);
    }
    
    
    public function guardar_estatus_voto_pro_post(){

      $data = $this->post();
  
      if(!isset($data['id_registrado']) OR !isset($data['estatusvoto'])){
          $respuesta = array(
                          'error' => TRUE,
                          'mensaje'=> 'La información enviada no es válida'
                        );
          
          $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
          return;
        }
  
        $res = "done";
  
        $datos = array(
          'estatus_voto' => $data['estatusvoto']
        );
        $DB2 = $this->load->database('promovido', TRUE);

        $DB2->where('id_registrado', $data['id_registrado']);
        $DB2->update('tbl_registrado', $datos);
        // if(!$this->db->affected_rows() >=1){
        //   $res = "niguno"; 
        // }
  
        if($res=="done"){   
          $respuesta = array(
                    'error' => FALSE,
                    'agregado' => TRUE,
                    'mensaje' =>''
                  );
        }else{
          $respuesta = array(
                    'error' => TRUE,
                    'agregado' => FALSE,
                    'mensaje' => $res
                  );
        }
        $this->response($respuesta);
  
    }

    public function guardar_estatus_voto_pro_a_post(){

      $data = $this->post();

  
      if(!isset($data['consecutivo']) OR !isset($data['estatusvoto']) OR !isset($data['seccion']) OR !isset($data['idcasilla'])){
          $respuesta = array(
                          'error' => TRUE,
                          'mensaje'=> 'La información enviada no es válida'
                        );
          
          $this->response( $respuesta );
          return;
        }
        $DB2 = $this->load->database('promovido', TRUE);

        $existeReg = $DB2->query("SELECT * FROM tbl_registrado WHERE seccion_elector = '".$data['seccion']."' AND id_casilla = '".$data['idcasilla']."' AND consecutivo = ".$data['consecutivo']);
        $existenoReg = $DB2->query("SELECT * FROM tbl_no_registrado WHERE seccion = '".$data['seccion']."' AND id_casilla = '".$data['idcasilla']."' AND consecutivo = ".$data['consecutivo']);

        $res = "done";  

     

        if($existeReg->num_rows()>0){
          // existe consecutivo con seccion y id_casilla

          $existeRegistrado = $existeReg->row();

          $datos = array(
                          'estatus_voto' => $data['estatusvoto']
                        );  
          $DB2->where('id_registrado', $existeRegistrado->id_registrado);
          $DB2->update('tbl_registrado', $datos);

          if($res=="done"){   
            $respuesta = array(
                      'error' => FALSE,
                      'agregado' => TRUE,
                      'mensaje' =>''
                    );
          }else{
            $respuesta = array(
                      'error' => TRUE,
                      'agregado' => FALSE,
                      'mensaje' => $res
                    );
          }
          $this->response($respuesta);

          return;
        // termina existe consecutivo con seccion y id_casilla

        }elseif($existenoReg->num_rows()>0){
          $existenoRegistrado = $existenoReg->row();

          if($data['estatusvoto'] == 0){
            $reseliminados = "done";
            if(!$DB2->delete('tbl_no_registrado', array('seccion' => $data['seccion'], 'id_casilla' => $data['idcasilla'], 'consecutivo' => $data['consecutivo']))){
                $reseliminados = $this->db->error();
            }

            if($reseliminados=="done"){   
              $respuesta = array(
                        'error' => FALSE,
                        'agregado' => TRUE,
                        'mensaje' =>''
                      );
            }else{
              $respuesta = array(
                        'error' => TRUE,
                        'agregado' => FALSE,
                        'mensaje' => $reseliminados
                      );
            }
            $this->response($respuesta);
            return;
          }else{
            $datos = array(
                'seccion' => $data['seccion'],
                'id_casilla' => $data['idcasilla'],
                'consecutivo' => $data['consecutivo'],
                'fecha_registro' => date("Y-m-d H:i:s")
              );
        
              if(!$DB2->insert('tbl_no_registrado', $datos)){
                $res = $DB2->error();
              }   
              if($res=="done"){   
                $respuesta = array(
                          'error' => FALSE,
                          'agregado' => TRUE,
                          'mensaje' =>''
                        );
              }else{
                $respuesta = array(
                          'error' => TRUE,
                          'agregado' => FALSE,
                          'mensaje' => $res
                        );
              }
              $this->response($respuesta);
              return;
          }

        }else{
          $datos = array(
            'seccion' => $data['seccion'],
            'id_casilla' => $data['idcasilla'],
            'consecutivo' => $data['consecutivo'],
            'fecha_registro' => date("Y-m-d H:i:s")
          );
    
          if(!$DB2->insert('tbl_no_registrado', $datos)){
            $res = $DB2->error();
          }   
          if($res=="done"){   
            $respuesta = array(
                      'error' => FALSE,
                      'agregado' => TRUE,
                      'mensaje' =>''
                    );
          }else{
            $respuesta = array(
                      'error' => TRUE,
                      'agregado' => FALSE,
                      'mensaje' => $res
                    );
          }
          $this->response($respuesta);
          return;
        }


  
    }
    

}