<?php
require "../php/inicializandoDatosExterno.php";
if ($_POST['id'] > 0) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row("SELECT * FROM tblc_permiso WHERE id_permiso = " . $id);
}
?>
<div class="col-md-5">
    <form id="enviar_formulario" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Formulario de Menu</h4>
                <p></p>
            </div>
            <div class="panel-body">
                <div class="form-group col-lg-12">
                    <label>Menú:</label>
                    <input type="text" name="menu" id="menu" class="form-control" value="<?php echo $row['nombre'] ?? ""; ?>" />
                </div>
                <div class="form-group col-lg-12">
                    <label>Menú principal:</label>
                    <select class="select2-container form-control" name="menu2" id="menu2" data-placeholder="Elige un Menu" style="width:100%">
                        <option value="0" selected>Ninguno</option>
                        <?php
                            $funciones->llenarcombomodificaiconoMostrarMenu('SELECT id_permiso AS id, nombre AS valor, icono AS nombre_icono FROM tblc_permiso WHERE id_padre = 0 AND fecha_eliminado IS NULL ORDER BY nombre ASC', $row['id_padre']);
                        ?>
                    </select>
                </div>
                <div class="form-group col-lg-12">
                    <label>Archivo:</label>
                    <input type="text" name="archivo" id="archivo" class="form-control" <?php echo (isset($row['archivo']) && $row['archivo'] == NULL && isset($id)) ? "disabled" : ""; ?> value="<?php echo isset($row['archivo']) ? $row['archivo'] : ""; ?>" />
                </div>
                <div class="form-group col-lg-12">
                    <label>Ordenamiento:</label>
                    <input type="number" name="ordenamiento" id="ordenamiento" class="form-control" value="<?php echo $row['ordenamiento'] ?? ""; ?>" />
                </div>
                <div class="form-group col-lg-12">
                    <label>Icono:</label>
                    <select class="select2-container form-control" name="icono" id="icono" data-placeholder="Elige un Icono" style="width:100%">
                        <option value="">Sin icono</option>
                        <?php
                            $funciones->llenarcombomodificaicono('SELECT id_icono AS id, nombre AS valor FROM tblc_iconos', $row['icono']);
                        ?>
                    </select>
                </div>
            </div><!-- panel-body -->
            <div class="panel-footer">
                <button class="btn btn-primary mr5">
                    <?php if (!empty($_POST['id'])) echo "Editar";
                    else echo "Guardar"; ?></button>
                <?php
                $redi = "location='permisos'";
                    if (isset($_POST['id'])) echo '<button class="btn btn-danger mr5" onclick="' . $redi . '">Cancelar</button>';
                ?>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($id)) echo "6";
                                                                                    else echo "7"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($id)) echo $id; ?>" />
    </form>
</div>
