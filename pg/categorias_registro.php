<?php
require "../php/inicializandoDatosExterno.php";
    if ($_POST['id'] > 0) {
        $id = $funciones->limpia($_POST['id']);
        $row = $entity->row($entity->statement('categorias_registro.5.1') . $id . " ");
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
                    <label class="col-sm-3">Nombre de la categoría :</label>
                    <div class="col-sm-8">

                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['nombre']; ?>" />
                    </div>
                </div><!-- form-group -->

            </div><!-- panel-body -->
            <div class="panel-footer">
                <button class="btn btn-primary mr5"><?php if (!empty($_POST['id'])) echo "Editar";
                                                    else echo "Guardar"; ?></button>
                <?php
                $redi = "location='categorias'";
                if (isset($_POST['id'])) echo '<button class="btn btn-danger mr5" onclick="' . $redi . '">Cancelar</button>';

                ?>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "4";
                                                                else echo "5"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />

    </form>
    <div id="cargando"></div>
</div><!-- col-md-6 -->