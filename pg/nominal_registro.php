<?php
require "../php/inicializandoDatosExterno.php";
if (!empty($_POST['id'])) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row($entity->statement('nominal_registro.5.1') . $id . " ");
}
?>
<div class="col-md-4">
    <form id="enviar_formulario" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php">
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
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($querys->comboprocesoelectoral(), $row['id_proceso_electoral']);
                            else echo $funciones->llenarcombo($querys->comboprocesoelectoral());
                            ?>
                        </select>
                    </div>
                </div>

                <?php if ($id_estado == 0 && $id_municipio == 0) { ?>
                    <div class="form-group">
                        <label class="col-sm-3">Estado :</label>
                        <div class="col-sm-9">
                            <select name="id_estado" id="id_estado" onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios2.php')" class="form-control" required>
                                <?php
                                if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($entity->statement('nominal_registro.38.4'), $row['id_estado']);
                                else echo $funciones->llenarcombo($entity->statement('nominal_registro.39.5'));
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3">Municipio :</label>
                        <div class="col-sm-9">
                            <select name="id_municipio" id="id_municipio" class="form-control" onchange="combodependiente('id_municipio', 'casilla', 'combo_dependiente/casillas.php')" required>
                                <?php
                                if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($entity->statement('nominal_registro.49.6') . $row['id_estado'] . $entity->statement('fragment.nominal_registro.47.3'), $row['id_municipio']);
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
                                if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($entity->statement('nominal_registro.60.7') . $id_estado . $entity->statement('fragment.nominal_registro.58.4'), $row['id_municipio']);
                                else echo $funciones->llenarcombo($entity->statement('nominal_registro.61.8') . $id_estado . $entity->statement('fragment.nominal_registro.59.5'));
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
                                    $query .= $entity->statement('fragment.nominal_registro.88.6') . $id_estado . "";
                                }
                                if ($id_municipio != 0) {
                                    $query .= $entity->statement('fragment.nominal_registro.91.7') . $id_municipio . "";
                                }

                                echo $funciones->llenarcombomodificaCasilla($entity->statement('nominal_registro.96.9') . $query . $entity->statement('fragment.nominal_registro.94.8'), $row['id_casilla']);
                            }
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
                    <label class="col-sm-3">Clave Elector :</label>
                    <div class="col-sm-9">
                        <input type="text" name="clave_elector" id="clave_elector" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['clave_elector']; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Consecutivo :</label>
                    <div class="col-sm-9">
                        <input type="text" name="folio" id="folio" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['folio']; ?>" />
                    </div>
                </div>

            </div><!-- panel-body -->
            <div class="panel-footer">
                <button class="btn btn-primary mr5"><?php if (!empty($_POST['id'])) echo "Editar";
                                                    else echo "Guardar"; ?></button>
                <?php
                $redi = "location='lista_nominal'";

                if (!empty($_POST['id'])) echo '<button class="btn btn-danger mr5" onclick="' . $redi . '">Cancelar</button>';

                ?>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "136";
                                                                else echo "137"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />

    </form>
    <div id="cargando"></div>
</div><!-- col-md-4 -->
<script>
    function generaFormulario(cant) {
        if (cant == '' || cant == 0) {
            $('#mostrarSave').hide();
            return false;
        }
        var formulario = '';
        for (let i = 1; cant >= i; i++) {
            formulario += ' <div class="form-group col-md-6">';
            formulario += '    <label class="col-sm-3">Nombre :</label>';
            formulario += '  <div class="col-sm-9">';
            formulario += '      <input type="text" name="nombre[]"  class="form-control" value="" />';
            formulario += '  </div>';
            formulario += '</div>';
            formulario += ' <div class="form-group col-md-6">';
            formulario += '    <label class="col-sm-3">Clave de Elector :</label>';
            formulario += '  <div class="col-sm-9">';
            formulario += '      <input type="text" name="clave_elector[]"  class="form-control" value="" />';
            formulario += '  </div>';
            formulario += '</div>';
        }
        $('#contFormulario').html(formulario);
        $('#mostrarSave').show();
    }
</script>
