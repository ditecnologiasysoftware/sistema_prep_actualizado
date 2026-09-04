<?php
require "../php/inicializandoDatosExterno.php";

if (!empty($_POST['id'])) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row($querys->getcandidato($id));
}
?>        
    <form class="form-horizontal" id="enviar_formulario" method="post" action="php/subir.php">
        <div class="panel panel-default">
            <div class="panel-heading">

                <h4 class="panel-title">Formulario de Registro</h4>
                <p></p>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3">Proceso Electoral :</label>
                    <div class="col-sm-9">
                        <select name="proceso_electoral" id="proceso_electoral" class="form-control" required>
                            <?php
                            if (!empty($_POST['id']))
                                echo $funciones->llenarcombomodifica($querys->comboprocesoelectoral(), $row['id_proceso_electoral']);
                            else
                                echo $funciones->llenarcombo($querys->comboprocesoelectoral());

                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Partido(s) Politico(s) :</label>
                    <div class="col-sm-9">
                        <select class="form-control" multiple name="partido[]" id="partido" required>
                            <?php
                            if (!empty($_POST['id'])) {
                                $partidos = array();
                                $resultados = $entity->objects("SELECT id_partido_politico FROM tblc_candidato_partido WHERE id_candidato = " . $id);
                                foreach ($resultados as $resultado) {
                                    array_push($partidos, $resultado->id_partido_politico);
                                }
                                echo $funciones->llenarcombomodificaarreglo($querys->combopartidopolitico(), $partidos);
                            } else
                                echo $funciones->llenarcombo($querys->combopartidopolitico());
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Nombre :</label>
                    <div class="col-sm-9">
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['nombre']; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-6">¿ Candidato Princial ? </label>
                    <div class="col-sm-6">
                        <input type="radio" name="principal" id="principal" value="1" <?php if (!empty($row['principal']) && $row['principal'] == 1) echo 'checked'; ?> /> <label style="margin-top:0px; width:80%; float:none;" for="Si"> Si </label><br>
                        <input type="radio" name="principal" id="principal2" value="0" <?php if (!empty($row['principal']) && $row['principal'] == 0) echo 'checked'; ?> /> <label style="margin-top:0px; width:80%; float:none;" for="No"> No </label>
                    </div>
                </div>

            </div><!-- panel-body -->
            <div class="panel-footer">
                <input type="hidden" name="ordenamiento" id="ordenamiento" value="0" />
                <input type="submit" id="btn_guardar" class="btn btn-primary mr5" value="Guardar"/>
                <input type="button" class="btn btn-danger mr5" value="Cancelar" onclick="candidato_registro()"/>

            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "128";
                                                                else echo "129"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />
    </form>
    <div id="cargando"></div>
