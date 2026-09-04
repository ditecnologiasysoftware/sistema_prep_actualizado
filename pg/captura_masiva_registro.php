
<?php
require "../php/inicializandoDatosExterno.php";
if (!empty($_POST['id'])) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row("SELECT pe.id_estado, pe.id_municipio, ln.* FROM tbl_lista_nominal as ln 
    JOIN tblc_proceso_electoral as pe ON(ln.id_proceso_electoral = pe.id_proceso_electoral)
    WHERE ln.id_lista_nominal = " . $id . " ");
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
                        if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica("SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1" . $query_pe . " ORDER BY fecha DESC", $row['id_proceso_electoral']);
                        else echo $funciones->llenarcombo("SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1" . $query_pe . " ORDER BY fecha DESC");
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
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre", $row['id_estado']);
                            else echo $funciones->llenarcombo("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre");
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Municipio :</label>
                    <div class="col-sm-9">
                        <select name="id_municipio" id="id_municipio" class="form-control" onchange="combodependiente('id_municipio', 'casilla', 'combo_dependiente/casillas.php')" required>
                            <?php
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = " . $row['id_estado'] . " ORDER BY nombre", $row['id_municipio']);
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
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = " . $id_estado . " ORDER BY nombre", $row['id_municipio']);
                            else echo $funciones->llenarcombo("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = " . $id_estado . " ORDER BY nombre");
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
                                $query .= " and m.id_estado = " . $id_estado . "";
                            }
                            if ($id_municipio != 0) {
                                $query .= " and c.id_municipio = " . $id_municipio . "";
                            }

                            echo $funciones->llenarcombomodificaCasilla("SELECT c.id_casilla as id, c.numero as numero_casilla, c.num_contigua as contigua_num_casilla, c.tipo as tipo_casilla, c.seccion as seccion_casilla FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0" . $query . "  ORDER BY c.id_casilla ASC", $row['id_casilla']);
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