<?php
  error_reporting(0);

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');
use Restserver\libraries\REST_Controller;
class Usuarios extends REST_Controller {

  public function __construct(){
    parent::__construct();
    header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    header("Access-Control-Allow-Origin: *");
      
      $this->load->database();
  }


  public function actualizar_usuario_post($id_representante){

    $data = $this->post();

    if(!isset($data['nombre']) OR !isset($data['paterno']) OR !isset($data['materno'])){

        $respuesta = array(
                        'error' => TRUE,
                        'mensaje'=> 'La información enviada no es válida'
                      );
        
        $this->response( $respuesta );
        return;
      }
      ////crea el nuevo password
        $newPass = '';
        if(trim($data['password'])!=""){
         
           $password = '((&876%!"·¿?!"·$'.$data['password'].'12$&¿?3%%9&/';
            $base = base64_encode($password);
            $md5 = md5($base);
            $newPass = password_hash($md5, PASSWORD_DEFAULT);
        }     
      //// fin crea el nuevo password

    $persona = array(
      'nombre' => $data['nombre'],
      'apellido_p' => $data['paterno'],
      'apellido_m' => $data['materno'],
      'correo' => $data['correo'],
      'telefono' => $data['telefono'],
      'usuario' => $data['usuario'],
      'password' => $newPass
    );

    if(trim($data['password'])==""){
      unset($persona['password']);
    }

       $actualizado = "done";
      $this->db->where('id_representante', $id_representante);
      $sql = $this->db->update('tblc_representante', $persona);
      if(!$this->db->affected_rows() >=1){
        $actualizado = "niguno"; 
      }
    if($actualizado=="done"){
       $usuario = $this->db->query("SELECT * FROM tblc_representante WHERE id_representante =".$id_representante);
       $dataUsuario = $usuario->row();
       
        $respuesta = array(
                'error' => FALSE,
                'usuario' => $dataUsuario,
                'mensaje' => 'Usuario actualizado correctamente'
              );
      }else{
        $respuesta = array(
                'error' => TRUE,
                'usuario' => '',
                'mensaje' => 'Error al actulizar'
              );
      }

    $this->response($respuesta);

  }


  ///////////////////////////////////////////////////////

    public function tipoEleccionText($value){
      $array_visible=array(1=>"Basica", 2=>"Contigua", 3=>"Extraordinaria", 4=>"Especial");
      foreach($array_visible as $t => $visible)
      {
        if($value==$t) return "<b>".$visible."</b>";
      }
    }

   public function login_post(){

      $data = $this->post();

      if(!isset($data['usuario']) OR !isset($data['password'])){
        $respuesta = array(
                        'error' => TRUE,
                        'mensaje'=> 'La información enviada no es válida'
                      );
        
        $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
        return;
      }
     
        $usuario = $this->db->query("SELECT * FROM tblc_representante WHERE usuario = '".$data['usuario']."' and estatus = 1");
     
        if($usuario->num_rows()>0){
          $dataUsuario = $usuario->row();
          /// verifica el password
          $salt = '123%45678"$9%%9&/((&87654321';
          $password_array = str_split($data['password'], 4);
          $hash = sha1($password_array[0].$password_array[3].$salt.$password_array[2].$password_array[1]);
          $md5 = md5($hash);
          /// verifica el password

            if ($dataUsuario->pass == $md5) {

              $respuesta = array(
                'error' => FALSE,
                'usuario' => $dataUsuario,
                'mensaje'=> ''
              );
            }else {
                $respuesta = array(
                  'error' => TRUE,
                  'usuario' => '',
                  'mensaje'=> 'Usuario y/o contrasena no son validos'
                );
            }
          /// fin vverifica el password
            
        }else{
            $respuesta = array(
              'error' => TRUE,
              'usuario' => '',
              'mensaje'=> 'Usuario y/o contrasena no son validos'
            );
        }
    
    $this->response($respuesta);
  }


  public function agregar_movil_post($id_representante){

    $data = $this->post();
    if(!isset($data['so']) OR !isset($data['marca']) OR !isset($data['modelo']) OR !isset($data['serie']) OR !isset($data['version']) OR !isset($data['udid'])){

        $respuesta = array(
                        'error' => TRUE,
                        'mensaje'=> 'La información enviada no es válida'
                      );
        
        $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
        return;
      }

    $movil = array(
      'so' => $data['so'],
      'marca' => $data['marca'],
      'modelo' => $data['modelo'],
      'version' => $data['version'],
      'uuid' => $data['udid'],
      'estatus' => 1,
      'fecha_acceso' => date("Y-m-d H:i:s"),
      'id_representante' => $id_representante
    );
     $res = "done";
     if(!$this->db->insert('tbl_representante_movil', $movil)){
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

  public function actualizar_movil_post($uuid,$id_representante){

    if($id_representante=="0" OR $id_representante==""){
      $respuesta = array(
                  'error' => TRUE,
                  'mensaje' => 'Usuario invalido'
                );
      $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
          return;
    }
    // verifica si ya exite un dispositivo de este usuaario
        $this->db->where('id_representante',$id_representante);
        $this->db->where('uuid',$uuid);
        $q = $this->db->get('tbl_representante_movil');
        if($q->num_rows()>0){
            $registrado = $q->row();
        }else{
            $registrado = "ninguno";
        }
    // fin verifica si ya exite un dispositivo de este usuaario
    if($registrado!="ninguno"){

      $id_representante_movil = $registrado->id_representante_movil;

      if($registrado->estatus==3){
        $respuesta = array(
                'error' => FALSE,
                'movil_usuario' => TRUE,
                'movil_desactivado' => TRUE,
                'movil_actualizado' => FALSE
              );
         $this->response($respuesta);
         return;
      }
                
      $movil = array(
            'estatus' => '1',
            'fecha_acceso' => date("Y-m-d H:i:s")
      );
      
        $actualizado = "done";
        $this->db->where('id_representante_movil', $id_representante_movil);
        $this->db->where('id_representante', $id_representante);
        $sql = $this->db->update('tbl_representante_movil', $movil);
        if(!$this->db->affected_rows() >=1){
          $actualizado = "niguno"; 
        }


      if($actualizado=="done"){
        $respuesta = array(
                'error' => FALSE,
                'movil_usuario' => TRUE,
                'movil_desactivado' => FALSE,
                'movil_actualizado' => TRUE
              );
      }else{
        $respuesta = array(
                'error' => FALSE,
                'movil_usuario' => TRUE,
                'movil_desactivado' => FALSE,
                'movil_actualizado' => FALSE
              );
      }

      $this->response($respuesta);
      
    }else{
      $respuesta = array(
                'error' => FALSE,
                'movil_usuario' => FALSE,
                'movil_desactivado' => FALSE,
                'movil_actualizado' => FALSE
              );
      $this->response($respuesta);
    }
    
  }

  public function cerrar_sesion_post($uuid,$id_representante){
    
    if($id_representante=="0" OR $id_representante==""){
      $respuesta = array(
                  'error' => TRUE,
                  'mensaje' => 'Usuario invalido'
                );
      $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
          return;
    }

     // verifica si ya exite un dispositivo de este usuaario
        $this->db->where('id_representante',$id_representante);
        $this->db->where('uuid',$uuid);
        $q = $this->db->get('tbl_representante_movil');
        if($q->num_rows()>0){
            $registrado = $q->row();
        }else{
            $registrado = "ninguno";
        }
    // fin verifica si ya exite un dispositivo de este usuaario

    if($registrado!="ninguno"){

      $id_representante_movil = $registrado->id_representante_movil;
                
      $movil = array(
            'estatus' => '0'
      );
      
        $actualizado = "done";
        $this->db->where('id_representante_movil', $id_representante_movil);
        $this->db->where('id_representante', $id_representante);
        $sql = $this->db->update('tbl_representante_movil', $movil);
        if(!$this->db->affected_rows() >=1){
          $actualizado = "niguno"; 
        }
      if($actualizado=="done"){
        $respuesta = array(
                'error' => FALSE,
                'movil_cerrado' => TRUE
              );
      }else{
        $respuesta = array(
                'error' => FALSE,
                'movil_cerrado' => FALSE
              );
      }

      $this->response($respuesta);
      
    }else{
      $respuesta = array(
                'error' => FALSE,
                'movil_cerrado' => TRUE
              );
      $this->response($respuesta);
    }
  }


}
