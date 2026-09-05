
<?php
require "../php/inicializandoDatosExterno.php";
if (!empty($_POST['id'])) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row($entity->statement('captura_masiva_registro.6.1') . $id . " ");
}
?>
<div class="col-md-4">

    <div class="panel panel-default">
        <div class="panel-heading">

            <h4 class="panel-title">Formulario de Registro</h4>
            <p></p>
        </div>
        <div class="panel-body">
            
            <div class="form-group">
                <label class="col-sm-3">Proceso Electoral :</label>
                <div class="col-sm-9">
                    <select name="id_proceso_electoral" id="id_proceso_electoral" class="form-control" required>
                        <?php
                        if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($entity->statement('captura_masiva_registro.26.2') . $query_pe . $entity->statement('fragment.captura_masiva_registro.24.1'), $row['id_proceso_electoral']);
                        else echo $funciones->llenarcombo($entity->statement('captura_masiva_registro.27.3') . $query_pe . $entity->statement('fragment.captura_masiva_registro.25.2'));
                        ?>
                    </select>
                </div>
            </div>

            <?php if ($id_estado == 0 && $id_municipio == 0) { ?>
                <div class="form-group">
                    <label class="col-sm-3">Estado :</label>
                    <div class="col-sm-9">
                        <select name="id_estado" id="id_estado" onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios2.php')" class="form-control" required>
                            <option value="0">-- Ninguna Estado --</option>

                            <?php
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($entity->statement('captura_masiva_registro.41.4'), $row['id_estado']);
                            else echo $funciones->llenarcombo($entity->statement('captura_masiva_registro.42.5'));
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Municipio :</label>
                    <div class="col-sm-9">
                        <select name="id_municipio" id="id_municipio" class="form-control" onchange="combodependiente('id_municipio', 'casilla', 'combo_dependiente/casillas.php')" required>
                            <?php
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($entity->statement('captura_masiva_registro.52.6') . $row['id_estado'] . $entity->statement('fragment.captura_masiva_registro.50.3'), $row['id_municipio']);
                            ?>
                        </select>
                    </div>
                </div>
            <?php } else if ($id_estado != 0 && $id_municipio == 0) { ?>
                <div class="form-group">
                    <label class="col-sm-3">Municipio :</label>
                    <div class="col-sm-9">
                        <select name="id_municipio" id="id_municipio" class="form-control" onchange="combodependiente('id_municipio', 'casilla', 'combo_dependiente/casillas.php')" required>
                            <?php
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($entity->statement('captura_masiva_registro.63.7') . $id_estado . $entity->statement('fragment.captura_masiva_registro.61.4'), $row['id_municipio']);
                            else echo $funciones->llenarcombo($entity->statement('captura_masiva_registro.64.8') . $id_estado . $entity->statement('fragment.captura_masiva_registro.62.5'));
                            ?>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="id_estado" id="id_estado" value="<?= $id_estado; ?>" />

            <?php } else { ?>
                <input type="hidden" name="id_municipio" id="id_municipio" value="<?= $id_municipio; ?>" />
                <input type="hidden" name="id_estado" id="id_estado" value="<?= $id_estado; ?>" />
                <?php if (!empty($_POST['id'])) { ?>
                    <script type="text/javascript">
                        window.setTimeout(function() {
                            dedependiente(<?= $id_municipio; ?>, 'casilla', 'combo_dependiente/casillas.php')
                        }, 700);
                    </script>
            <?php
                }
            } ?>

            <div class="form-group">
                <label class="col-sm-3">Casilla :</label>
                <div class="col-sm-9">
                    <select name="casilla" id="casilla" class="form-control" required>
                        <option value="0">-- Ninguna Casilla --</option>
                        <?php
                        if (!empty($_POST['id'])) {
                            $query = "";
                            if ($id_estado != 0) {
                                $query .= $entity->statement('fragment.captura_masiva_registro.91.6') . $id_estado . "";
                            }
                            if ($id_municipio != 0) {
                                $query .= $entity->statement('fragment.captura_masiva_registro.94.7') . $id_municipio . "";
                            }

                            echo $funciones->llenarcombomodificaCasilla($entity->statement('captura_masiva_registro.99.9') . $query . $entity->statement('fragment.captura_masiva_registro.97.8'), $row['id_casilla']);
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3">Cantidad :</label>
                <div class="col-sm-9">
                    <input type="number" onkeyup="generaFormulario(this.value)" name="cantidad" id="cantidad" class="form-control" value="" />
                </div>
            </div>


        </div><!-- panel-body -->

    </div><!-- panel-default -->
    <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "138";
                                                            else echo "139"; ?>" />
    <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />


</div><!-- col-md-4 -->