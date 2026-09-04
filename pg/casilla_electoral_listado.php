<?php
require "../php/inicializandoDatosExterno.php";

$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$peticion_enlace = "";
$sentencia = "";
$query = "";


if ($id_municipio != 0) {
    $query .= " and c.id_municipio = " . $id_municipio . "";
} elseif (isset($_POST['municipio']) && $_POST['municipio'] != 0) {
    $query .= " and c.id_municipio = " . $_POST['municipio'] . "";
    $peticion_enlace .= "&municipio=" . $_POST['municipio'];
}
if ($id_estado != 0) {
    $query .= " and m.id_estado = " . $id_estado . "";
} elseif (isset($_POST['estado']) && $_POST['estado'] != 0) {
    $query .= " and m.id_estado = " . $_POST['estado'] . "";
    $peticion_enlace .= "&estado=" . $_POST['estado'];
}

if (isset($_POST['n']) && $_POST['n'] != "") {
    $query .= " AND c.seccion = '" . $_POST['n'] . "'";
    $peticion_enlace .= "&n=" . $_POST['n'];
}

if (isset($_POST['tipo']) && $_POST['tipo'] != "0") {
    $query .= " AND c.tipo = '" . $_POST['tipo'] . "'";
    $peticion_enlace .= "&tipo=" . $_POST['tipo'];
}

$cadena = "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.fecha_eliminado IS NULL" . $query . " ORDER BY seccion ASC, tipo ASC, num_contigua ASC LIMIT " . $inicio . "," . $limite . "";

$cadena2 = "SELECT COUNT(c.id_casilla) FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.fecha_eliminado IS NULL" . $query . " ORDER BY c.numero ASC";
$totalCirculares = $entity->scalar($cadena2);
$resul_lista = $entity->objects($cadena);
?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Listado Casillas Electorales</h4>
            <p></p>
        </div>
        <div class="panel-body">
            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Sección</th>
                        <th>Casilla</th>
                        <th>Tipo</th>
                        <th>Municipio</th>
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
                            <!--<td><?php //echo $funciones->llenarCasillatbl($resultado_fila->id_casilla); 
                                    ?></td>-->
                            <td><?php echo $resultado_fila->seccion; ?></td>
                            <td><?php echo $resultado_fila->nombre; ?></td>
                            <td><?php
                                echo $funciones->getcomboTipoEleccionText($resultado_fila->tipo);
                                ?></td>
                            <td><?php echo $entity->scalar("SELECT nombre FROM tblc_municipio WHERE id_municipio =" . $resultado_fila->id_municipio); ?></td>
                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success" onclick="casilla_electoral_registro(<?= $resultado_fila->id_casilla ?>)"><span class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?php echo $resultado_fila->id_casilla ?>,37)"><span class="fa fa-trash"></span></a></td>
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
                                                    <a href="javascript:casilla_electoral_lista(<?= $enlace['numero'] ?>);">
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
    </div>

