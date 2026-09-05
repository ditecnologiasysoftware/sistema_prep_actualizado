<?php
    require "../php/inicializandoDatosExterno.php";

		$idcandidatoP = $funciones->limpia($_POST['cand']);
		$idprocesoE = $entity->scopedProcessId($funciones->limpia($_POST['c']));

        $candidato = ' Resultado de casillas  para el candidato - <b>'.$entity->scalar($entity->statement('casilla_resultados.7.1').$idcandidatoP).'</b>';  
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 id="titulo" class="modal-title"><?php echo $candidato ?></h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">

                    <div class="col-md-12 form-group">
                        <!--formulariooooooo busquedaaaaaaaa------------------------------------------------------------------>
                                            
                        <div class="panel-group" id="accordion2">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                                           <div style="color:#FFFFFF" >Buscar por Sección</div>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne2" class="panel-collapse collapse <?php if(isset($_POST['bus'])) echo "in"; ?>">
                                    <div class="panel-body">
                                        <form id="form_busqueda_modal">
                                            <div class="col-sm-6">
                                                <input type="hidden" name="pagina" id="pagmodal" value="1">
                                                <input type="hidden" name="c" value="<?php echo $idprocesoE;?>">
                                                <input type="hidden" name="cand" value="<?php echo $idcandidatoP;?>">
                                                <label class="col-sm-3">Número de sección:</label>
                                                <input type="text" name="seccion" id="seccion" class="form-control"/>
                                            </div>
                                            <input type="button" class="btn btn-primary mr5" value="Buscar" onclick="casilla_resultados_lista()" />                                                                
                                        </form>
                                    </div>
                                </div>
                            </div><!-- panel -->
                        </div><!-- panel-group -->
                        
                     <!--fin formulariooooooo busquedaaaaaaaa------------------------------------------------------------------>
                    </div>

                    <div class="col-md-12 form-group" id="listadomodal"></div>

                </div>
            </div>
        </div>
    </div>

</div>
