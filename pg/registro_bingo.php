<?php 
    $query = "";
    $query_pe = "";

    if ($id_municipio != 0) {
         $query .= $entity->statement('fragment.registro_bingo.6.1').$id_municipio."";
       $query_pe .= $entity->statement('fragment.registro_bingo.7.2').$id_municipio."";
      }elseif ($id_estado != 0) {
         $query .= $entity->statement('fragment.registro_bingo.9.3').$id_estado."";
       $query_pe .= $entity->statement('fragment.registro_bingo.10.4').$id_estado."";
      }
 ?>
                  <!--  ARRIBA----------------------------------------------------------------------------------- -->

                   <div class="pageheader">
                        <div class="media">
                            <div class="pageicon pull-left">
                                <i class="fa fa-database"></i>
                            </div>
                            <div class="media-body">
                                <ul class="breadcrumb">
                                    <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                                    <li>Registro Bingo</li>
                                </ul>
                                <h4>Registro Bingo</h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA-------------------------------------------------------------------------------- -->
                    <div class="contentpanel">
                      <!-- CONTENIDO ----------------------------------------------------------------------- -->
					            <div class="row">
                       <div class="col-md-12">
                          <div class="col-md-4">
                              <div class="panel panel-default">                                        
                                <div class="panel-body ">
                                   	<form id="form_busqueda">
                												<label class="col-sm-12"><b>Proceso Electoral :</b></label>
                												<select name="p" id="p" class="form-control">
                                             <?php 
                                               echo $funciones->llenarcombomodifica($entity->statement('registro_bingo.42.1').$query_pe.$entity->statement('fragment.registro_bingo.42.5'), $_GET['p'] );
                                             ?>
                                        </select><br><br>

                                        <?php if ($id_estado == 0 && $id_municipio == 0) { ?>
                                        <label class="col-sm-12"><b>Estado de la casilla :</b></label>
                                            <select name="edo" id="id_estado" onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios2.php')" class="form-control" required>
                                                <option value="0">Todos los Estados</option>
                                                <?php 
                                                   if(isset($_GET['edo'])) echo $funciones->llenarcombomodifica($entity->statement('registro_bingo.51.2'), $_GET['edo']);
                                                    else echo $funciones->llenarcombo($entity->statement('registro_bingo.52.3'));
                                                ?>
                                            </select><br><br>
                                        <label class="col-sm-12"><b>Municipio :</b></label>
                                            <select name="mun" id="id_municipio" class="form-control" required onchange="combodependiente('id_municipio', 'c', 'combo_dependiente/casillas.php');">
                                              <option value="0">Todos los Municipios</option>
                                                <?php 
                                                if(isset($_GET['mun'])) echo $funciones->llenarcombomodifica($entity->statement('registro_bingo.59.4').$_GET['edo'].$entity->statement('fragment.registro_bingo.59.6'), $_GET['mun']);
                                                ?>
                                            </select><br><br>
                                        <?php }else if ($id_estado != 0 && $id_municipio == 0) { ?> 
                                        <label class="col-sm-12"><b>Municipio de la casilla:</b></label>
                                            <select name="mun" id="id_municipio" class="form-control" required onchange="combodependiente('id_municipio', 'c', 'combo_dependiente/casillas.php');">
                                              <option value="0">Todos los Municipios</option>
                                                <?php 

                                                if(isset($_GET['mun'])) echo $funciones->llenarcombomodifica($entity->statement('registro_bingo.68.5').$id_estado.$entity->statement('fragment.registro_bingo.68.7'), $_GET['mun']);
                                                else echo $funciones->llenarcombo($entity->statement('registro_bingo.69.6').$id_estado.$entity->statement('fragment.registro_bingo.69.8'));
                                                ?>
                                            </select><br><br>
                                        <?php } ?>

                                        <label class="col-sm-12"><b>Casilla :</b></label>
								                            <select name="c" id="c" class="form-control" onchange="lista_bingo();">
                                              <option value=""> - Seleccionar Casilla -</option>
                                             <?php                                                    
                                               echo $funciones->llenarcombomodificaCasilla($entity->statement('registro_bingo.78.7').$query.$entity->statement('fragment.registro_bingo.78.9'), 0 );
                                             ?>
                                        </select>

												<!-- <input type="submit" class="btn btn-primary mr5" value="Buscar" />
												<input type="button" class="btn btn-secundary mr5" onclick="window.location.href='registro_resultados'" value="Cancelar"> -->
											         </form>
                                            
                              </div><!-- panel-body -->                                      
                          </div><!-- panel-default -->
                        </div>
                        <div class="col-md-8" id="listado" align="center">
                          <h4>Seleccione los datos para iniciar el bingo</h4>

                       </div>
                     </div><!-- row -->  
                                
                                <!--FIN DE CONTENIDO-------------------------------------------------------->
                        
                    </div><!-- contentpanel -->