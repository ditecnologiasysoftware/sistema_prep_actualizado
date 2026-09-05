<?php
require "../php/inicializandoDatosExterno.php";

if ($_POST['id'] > 0) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row($entity->statement('etiquetas_registro.6.1') . $id . " ");
}
?>
<div class="col-md-4">

    <form class="form-horizontal" id="enviar_formulario" method="post" enctype="multipart/form-data" action="php/subir.php">
        <div class="panel panel-default">
            <div class="panel-heading">

                <h4 class="panel-title">Formulario de registro</h4>
                <p></p>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-3">Categoría :</label>
                    <div class="col-sm-8">
                        <select name="id_categoria" class="form-control" required>
                            <?php
                                if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($entity->statement('etiquetas_registro.25.2'), $row['id_categoria']);
                                else echo $funciones->llenarcombo($entity->statement('etiquetas_registro.26.3'));
                            ?>
                        </select>
                    </div>
                </div><!-- form-group -->

                <div class="form-group">
                    <label class="col-sm-3">Etiqueta :</label>
                    <div class="col-sm-8">
                        <input type="text" name="etiqueta" id="etiqueta" class="form-control" maxlength="100" required value="<?php if (isset($_POST['id'])) echo htmlspecialchars($row['etiqueta'], ENT_QUOTES, 'UTF-8'); ?>" />
                    </div>
                </div>

            </div><!-- panel-body -->
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary mr5" id="btn_guardar"><?php if (!empty($_POST['id'])) echo "Editar";
                                                    else echo "Guardar"; ?></button>
                <?php
                $redi = "window.location.href='etiquetas'";
                if (isset($_POST['id'])) echo '<button type="button" class="btn btn-danger mr5" onclick="' . $redi . '">Cancelar</button>';

                ?>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "9";
                                                                else echo "10"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />
    </form>
    <div id="cargando"></div>
</div><!-- col-md-6 -->
