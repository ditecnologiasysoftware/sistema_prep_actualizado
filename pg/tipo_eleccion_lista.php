<?php
require "../php/inicializandoDatosExterno.php";
$cadena = "SELECT * FROM tblc_tipo_eleccion WHERE fecha_eliminado IS NULL ORDER BY nombre ASC";
$resul_lista = $entity->objects($cadena);
?>
    <div class="panel panel-default">
        <div class="panel-heading">

            <h4 class="panel-title">Listado Tipo de Elección</h4>
            <p></p>
        </div>
        <div class="panel-body">

            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Nombre</th>
                        <th>Estatus</th>
                        <?php if ($editar == 1) { ?>
                            <th>Editar</th>
                        <?php } ?>
                        <?php if ($eliminar == 1) { ?>
                            <th>Eliminar</th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($resul_lista as $resultado_fila) {
                    ?>
                        <tr>
                            <td><strong><?php echo $resultado_fila->nombre ?></strong></td>
                            <td><?php if ($resultado_fila->estatus == 1) echo 'Activo';
                                else echo "Inactivo"; ?></td>
                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success" onclick="tipo_eleccion_registro(<?= $resultado_fila->id_tipo_eleccion ?>)"><span class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?= $resultado_fila->id_tipo_eleccion ?>,35)"><span class="fa fa-trash"></span></a></td>
                            <?php } ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

        </div><!-- body -->

    </div><!-- panel default -->
