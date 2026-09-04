<?php
    @session_start();
    $id_usuario = $_SESSION['id_usuario'];
    $id_estado = $_SESSION['id_estado'];
    $id_municipio = $_SESSION['id_municipio'];
    $autentificado = $_SESSION['autentificado'];
    $eliminar = $_SESSION['eliminar'];
    $editar = $_SESSION['editar'];
    $nombre = $_SESSION['nombre'];
    $id_sesion_sistema = $_SESSION['id_sesion_sistema'];
    $autoriza_apoyo = $_SESSION['autoriza_apoyo'];
    $autoriza_ordenpago = $_SESSION['autoriza_ordenpago'];

    require ("../php/clase_variables.php");
    require ("../php/clase_mysql.php");
    require ("../php/clase_funciones.php");
    date_default_timezone_set('America/Mexico_City');
    
    $funciones = new Funciones();
    //LLAMAMOS A LA CLASE CONEXION
    $entity = Entity::createInstance();    
    
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Sistema administrador</title>

        <link href="../assets/css/style.default.css" rel="stylesheet">
        <!-- <link href="../fancybox/jquery.fancybox.css" rel="stylesheet" /> -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <script src="../assets/js/jquery-1.11.1.min.js"></script>
        <?php if (!empty($_ENV['GOOGLE_MAPS_API_KEY'])): ?>
            <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo rawurlencode($_ENV['GOOGLE_MAPS_API_KEY']); ?>" defer></script>
        <?php endif; ?>

        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/jquery-ui-1.10.3.min.js"></script>  
        <!-- <script src="../fancybox/jquery.fancybox.js"></script> -->      
    </head>
    <body>
         <div class="pageheader">
                        <div class="media">
                            <div class="media-body">
                                <h4>Editar Resultado</h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <div class="contentpanel">
                     <div class="row">
                       <div class="col-md-12">
                                <form  id="form_menu" class="form-horizontal" method="post" enctype="multipart/form-data" action="../php/subir.php" target="mandar_formulario">
                                  <div class="col-md-8">

                                        <table id="basicTable" class="table table-striped table-bordered responsive">
                                                    <thead class="">
                                                        <tr>
                                                            <th>Partido</th>
                                                            <th>Candidatos</th>
                                                            <th width="140px">Total Votos</th>
                                                        </tr>
                                                    </thead>
                                             
                                                    <tbody>
                                                    
                                                    
                                                    <?php    
                                                    if(isset($_GET['id'])){
                                                    $id = $funciones->limpia(base64_decode($_GET['id'])); 
                                                    $tipoE = $funciones->limpia(base64_decode($_GET['tipoE'])); 

                                                    
                                                    $row = $entity->row("SELECT * FROM tbl_resultado WHERE id_resultado = ".$id);                                                                                           
                                                      $cadena = "SELECT * FROM vw_resultado_elecciones WHERE idrepresentante_r =".$row['id_representante']." and id_casilla=".$row['id_casilla']." and idt_eleccion_c = '".$tipoE."' ORDER BY resultado DESC "; 
                                                            $resul_lista = $entity->objects($cadena);
                                                             foreach($resul_lista as $resultado_fila){
                                                      ?>
                                                            <tr>
                                                                <td><img width="25px" height="25px" src="../archivos/partido_politico/<?php echo $resultado_fila->icono_pa ?>"/>&nbsp;&nbsp;&nbsp;<font color="<?= $resultado_fila->color_pa ?>"><b><?php echo $resultado_fila->nombre_pa ?></b></font></td>
                                                                <td>
                                                                    <?php echo $resultado_fila->nombre_c; ?>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="resultado[]" id="resultado[]" class="form-control" value="<?php echo $resultado_fila->id_resultado; ?>">
                                                                    <input type="number" name="votos_total<?php echo $resultado_fila->id_resultado; ?>" id="votos_total<?php echo $resultado_fila->id_resultado; ?>" class="form-control" value="<?php echo $resultado_fila->resultado; ?>">
                                                                </td>                                                  
                                                            </tr>
                                                            <?php 
                                                                }
                                                            }
                                                            ?>
                                                   </tbody>
                                                </table>


                                    </div><!-- col-md-4 -->
                                    <div class="col-md-4">
                                      <div class="panel panel-default">                                        
                                        <div class="panel-body text-center">
                                            <?php 
                                               if(isset($_GET['id'])){
                                                $id = $funciones->limpia(base64_decode($_GET['id'])); 
                                                $row = $entity->row("SELECT * FROM tbl_resultado WHERE id_resultado = ".$id." ");
                                               }
                                            ?>

                                            <div class="form-group">  
                                                <font color="#3E62A2"><b>Representante: </b><?php if(isset($_GET['id'])) echo $entity->scalar("SELECT nombre FROM tblc_representante WHERE id_representante =".$row['id_representante']);?></font>                                                
                                            </div>    

                                            <button class="btn btn-primary mr5"><?php if(isset($_GET['id'])) echo "Modificar"; ?></button>
                                            <?php 
                                            $redi = "javascript:history.back();";                                            
                                            if(isset($_GET['id'])) echo '<button class="btn btn-danger mr5" onclick="'.$redi.'">Cancelar</button>';                                            
                                            ?>
                                        </div><!-- panel-body -->                                      
                                    </div><!-- panel-default -->
                                    </div>
                                       
                                    <input type="hidden" name="opcion" id="opcion" value="130"/>
                                    <input type="hidden" name="id" id="id" value="<?php if(isset($_GET['id'])) echo $id;?>" />
                                    
                                </form>
                                <div id="cargando"></div>
                                <iframe name="mandar_formulario" id="mandar_formulario" width="100%" height="40px" frameborder="0" ></iframe>
                       </div>
                     </div><!-- row -->                            
                    </div><!-- contentpanel -->
        
    </body>
</html>
