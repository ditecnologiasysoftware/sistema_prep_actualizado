<?php
require "../php/inicializandoDatosExterno.php";
$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 3;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$row = "SELECT * FROM tblc_permiso WHERE id_padre = 0 AND fecha_eliminado IS NULL ORDER BY ordenamiento ASC LIMIT " . $inicio . "," . $limite;
$conteo = "SELECT COUNT(id_permiso) FROM tblc_permiso WHERE id_padre = 0 AND fecha_eliminado IS NULL";
$totalCirculares = $entity->scalar($conteo);
$resul_lista = $entity->objects($row);
?>
<div class="col-md-7">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Listado de Menu</h4>
            <p></p>
        </div>
        <div class="panel-body">
            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Nombre</th>
                        <th>Ordenamiento</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($resul_lista as $resultado_fila) {
                    ?>
                        <tr>
                            <td><strong><?= $resultado_fila->nombre ?></strong></td>
                            <td><?= $resultado_fila->ordenamiento ?></td>
                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success" onclick="permisos_registro(<?= $resultado_fila->id_permiso ?>)"><span class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?= $resultado_fila->id_permiso ?>,11)"><span class="fa fa-trash"></span></a></td>
                            <?php } ?>
                        </tr>
                        <?php
                        $cadena2 = "SELECT * FROM tblc_permiso WHERE id_padre = " . $resultado_fila->id_permiso . " AND fecha_eliminado IS NULL ORDER BY ordenamiento ASC";
                        $resul_lista2 = $entity->objects($cadena2);
                        foreach ($resul_lista2 as $resultado_fila2) {
                        ?>
                            <tr>
                                <td> - <?php echo $resultado_fila2->nombre ?></td>
                                <td><?php echo $resultado_fila2->ordenamiento ?></td>
                                <?php if ($editar == 1) { ?>
                                    <td align="center"><a class="btn btn-success" onclick="permisos_registro(<?= $resultado_fila2->id_permiso ?>)"><span class="fa fa-pen"></span></a></td>
                                <?php } ?>
                                <?php if ($eliminar == 1) { ?>
                                    <td align="center"><a class="btn btn-danger" href="javascript:void(0);" onClick="eliminar(this,<?= $resultado_fila2->id_permiso ?>,11)"><span class="fa fa-trash"></span></a></td>
                                <?php } ?>
                            </tr>
                    <?php
                        }
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
                                            $datos = $pag->paginar($pagina, $totalCirculares);
                                            if($datos)
                                            {
                                                echo 'Pagina: ' .$pagina. ' de ' . $pag->getCantidadPaginas() . '<br />';
                                                foreach ($datos as $enlace)
                                                {
                                                    if($enlace['active'] == false)
                                                    {
                                                        ?>
                                                            <li>
                                                                <a href="javascript:permisos_lista(<?php echo $enlace['numero'] ?>);">
                                                                    <?php echo $enlace['vista']; ?>
                                                                </a>
                                                            </li>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                            <li class="active">
                                                                <span><?php echo $enlace['vista']; ?></span>
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
        </div><!-- body -->
    </div><!-- panel default -->
</div><!-- col 8 -->
