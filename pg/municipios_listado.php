<?php
require "../php/inicializandoDatosExterno.php";
$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$peticion_enlace = "";
$sentencia = "";

if (isset($_POST['nombre_busqueda'])) {
    $sentencia .= " AND nombre LIKE '%" . $_POST['nombre_busqueda'] . "%'";
    $peticion_enlace .= "&nombre_busqueda=" . $_POST['nombre_busqueda'];
}

$cadena = "SELECT * FROM tblc_municipio WHERE fecha_eliminado IS NULL" . $sentencia . " ORDER BY nombre ASC LIMIT " . $inicio . "," . $limite . "";
$cadena2 = "SELECT COUNT(id_municipio) FROM tblc_municipio WHERE fecha_eliminado IS NULL" . $sentencia;

$totalCirculares = $entity->scalar($cadena2);
$resul_lista = $entity->objects($cadena);
?>
    <div class="panel panel-default">
        <div class="panel-heading">

            <h4 class="panel-title">Listado de Municipios</h4>
            <p></p>
        </div>

        <div id="div_buscar">
            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Estado</th>
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
                            <td><?php echo htmlspecialchars($resultado_fila->clave, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo $resultado_fila->nombre; ?></td>
                            <td><?php echo $entity->scalar("select nombre from tblc_estado where id_estado=" . $resultado_fila->id_estado); ?></td>
                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success" onclick="municipios_registro(<?= $resultado_fila->id_municipio ?>)"><span class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar('',<?= $resultado_fila->id_municipio ?>,107)"><span class="fa fa-trash"></span></a></td>
                            <?php } ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7">
                            <div>
                                <ul class="pagination pagination-sm">
                                    <?php
                                    $pag = new Paginador();
                                    // Configuramos la cantidad de registros por pagina, por defecto son 10.
                                    // Debe de estar coordinado con la cantidad de registros traídos con la consulta MySQL.
                                    $pag->setCantidadRegistros($limite);
                                    // Configurar la cantidad de enlaces en la barra de navegación (por defecto son 10).
                                    $pag->setCantidadEnlaces($cantenlaces);
                                    //$pag->setMarcador('', '');
                                    // Y mandamos a paginar desde la pagina actual y le pasamos tambien el total
                                    // de registros de la consulta mysql.
                                    $datos = $pag->paginar($pagina, $totalCirculares);
                                    if ($datos) {
                                        echo 'Pagina: ' . $pagina . ' de ' . $pag->getCantidadPaginas() . '<br />';
                                        foreach ($datos as $enlace) {
                                            if ($enlace['active'] == false) {
                                    ?>
                                                <li>
                                                    <a href="javascript:municipios_lista(<?= $enlace['numero'] ?>);">
                                                        <?= $enlace['vista']; ?>
                                                    </a>
                                                </li>
                                            <?php
                                            } else {
                                            ?>
                                                <li class="active">
                                                    <span><?= $enlace['vista']; ?></span>
                                                </li>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div><!-- form-group -->
</div><!-- panel-body -->
</div><!-- panel -->
