<?php
require "../php/inicializandoDatosExterno.php";

 $pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
 $limite = 10;
 $cantenlaces = 7;
 $inicio = ($pagina - 1) * $limite;

 $sentencia = "";
 if (isset($_POST['n']) && $_POST['n'] != "") {
     $fecha = date("Y-m-d", strtotime($_POST['n']));
     $sentencia .= $entity->statement('fragment.proceso_electoral_lista.12.1') . $fecha . "%'";
 }
 $cadena = $entity->statement('proceso_electoral_lista.14.1') . $sentencia . $entity->statement('fragment.proceso_electoral_lista.14.2') . $inicio . "," . $limite . "";

 $cadena2 = $entity->statement('proceso_electoral_lista.16.2') . $sentencia . $entity->statement('fragment.proceso_electoral_lista.16.3');
 $totalRegistros = $entity->scalar($cadena2);
 $resul_lista = $entity->objects($cadena);
  
?>
    <div class="panel panel-default">
        <div class="panel-heading">

            <h4 class="panel-title">Listado Proceso Electoral</h4>
            <p></p>
        </div>
        <div class="panel-body">

            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha</th>
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
                            <td><strong><?php echo $resultado_fila->descripcion ?></strong></td>
                            <td><?php echo $resultado_fila->fecha ?></td>
                            <td><?php if ($resultado_fila->estatus == 1) echo 'Activo';
                                else echo "Inactivo"; ?></td>
                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success" onclick="proceso_electoral_registro(<?= $resultado_fila->id_proceso_electoral ?>)"><span class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?= $resultado_fila->id_proceso_electoral ?>,34)"><span class="fa fa-trash"></span></a></td>
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
                                    $datos = $pag->paginar($pagina, $totalRegistros);
                                    if ($datos) {
                                        echo 'Pagina: ' . $pagina . ' de ' . $pag->getCantidadPaginas() . '<br />';
                                        foreach ($datos as $enlace) {
                                            if ($enlace['active'] == false) {
                                    ?>
                                                <li>
                                                    <a href="javascript:proceso_electoral_lista(<?= $enlace['numero'] ?>);">
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
