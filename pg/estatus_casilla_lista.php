<?php
require "../php/inicializandoDatosExterno.php";

$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$peticion_enlace = "";
$sentencia = "";
$query = "";
$query .= $entity->electoralScope('ec.id_proceso_electoral');
if ($id_estado != 0) {
    $query .= $entity->statement('fragment.estatus_casilla_lista.13.1') . $id_estado . "";
}
if ($id_municipio != 0) {
    $query .= $entity->statement('fragment.estatus_casilla_lista.16.2') . $id_municipio . "";
}
if (isset($_POST['c'])) {
    $sentencia .= $entity->statement('fragment.estatus_casilla_lista.19.3') . $_POST['c'] . "'";
    $peticion_enlace .= "&c=" . $_POST['c'];
}
$cadena = $entity->statement('estatus_casilla_lista.22.1') . $sentencia . $query . $entity->statement('fragment.estatus_casilla_lista.22.4') . $inicio . "," . $limite . "";

$cadena2 = $entity->statement('estatus_casilla_lista.28.2') . $sentencia . $query;

$totalRegistros = $entity->scalar($cadena2);
$resul_lista = $entity->objects($cadena);

?>
    <div class="panel panel-default">
        <div class="panel-heading">

            <h4 class="panel-title">Listado de registros</h4>
            <p></p>
        </div>
        <div class="panel-body">
            <div class="panel-group" id="accordion2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                                <div style="color:#FFFFFF">Buscar</div>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne2" class="panel-collapse collapse <?php if (isset($_POST['bus']))
                        echo "in"; ?>">
                        <div class="panel-body">
                            <table width="100%" border="0">
                                <tr>
                                    <td width="100%">
                                        <div class="form-group">
                                            <form id="form_busqueda">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-12">Casilla :</label>

                                                    <select name="c" id="c" class="form-control">
                                                        <option value="0"> - Todas las Casilla -</option>
                                                        <?php
                                                        echo $funciones->llenarcombomodificaCasilla($entity->statement('estatus_casilla_lista.68.3') . $query . $entity->statement('fragment.estatus_casilla_lista.60.5'), $_GET['c']);
                                                        ?>
                                                    </select>

                                                </div><br>
                                                <input type="submit" class="btn btn-primary mr5" value="Buscar" />
                                                <input type="button" class="btn btn-secundary mr5"
                                                    onclick="window.location.href='estatus_casilla'" value="Cancelar">

                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div><!-- panel -->
            </div><!-- panel-group -->

            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Casilla</th>
                        <th>Tipo</th>
                        <th>Hora</th>
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
                            <td><strong>Sección <?php echo $resultado_fila->seccion ?> -
                                    <?php echo $resultado_fila->casilla ?></strong></td>
                            <td><?php echo $funciones->tipo_estatuscasilla($resultado_fila->tipo); ?></td>
                            <td><?php echo $funciones->ordenaFechaHora($resultado_fila->fecha_hora); ?></td>
                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success"
                                        onclick="estatus_casilla_registro(<?= $resultado_fila->id_estatus_casilla ?>)"><span
                                            class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger"
                                        href="javascript:eliminar(this,<?php echo $resultado_fila->id_estatus_casilla ?>,43)"><span
                                            class="fa fa-trash"></span></a></td>
                            <?php } ?>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
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
                                    $datos = $pag->paginar($pagina, $totalRegistros);
                                    echo $totalRegistros . ' registros encontrados<br>';
                                    if ($datos) {

                                        echo 'Pagina: ' . $pagina . ' de ' . $pag->getCantidadPaginas() . '<br />';
                                        foreach ($datos as $enlace) {
                                            if ($enlace['active'] == false) {
                                                ?>
                                                <li><a
                                                        href="?pag=<?php echo $enlace['numero'] . $peticion_enlace ?>"><?php echo $enlace['vista']; ?></a>
                                                </li><?php
                                            } else {
                                                ?>
                                                <li class="active"><span><?php echo $enlace['vista']; ?></span></li><?php
                                            }
                                        }
                                    }

                                    ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <td colspan="5">
                        <a class="btn btn-danger mr5" target="_blank"
                            href="php/excel_casillas_abiertas.php?valor=1<?php echo $peticion_enlace ?>">Exportar
                            casillas aperturadas</a>
                    </td>
                </tfoot>
            </table>

        </div><!-- body -->

    </div><!-- panel default -->
