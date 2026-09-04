
<?php
require "../php/inicializandoDatosExterno.php";

$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$peticion_enlace = "";
$sentencia = "";
if ($id_estado != 0) {
    $sentencia .= " and d.id_estado = " . $id_estado . "";
}
if ($id_municipio != 0) {
    $sentencia .= " and d.id_municipio = " . $id_municipio . "";
}
if (!empty($_POST['n'])) {
    $sentencia .= " AND d.nombre LIKE '%" . $_POST['n'] . "%'";
    $peticion_enlace .= "&n=" . $_POST['n'];
}
$cadena = "SELECT d.*, e.nombre as estado FROM tblc_distrito as d 
JOIN tblc_estado as e ON(d.id_estado = e.id_estado) WHERE d.fecha_eliminado IS NULL" . $sentencia . " ORDER BY d.nombre ASC LIMIT " . $inicio . "," . $limite . "";

$cadena2 = "SELECT COUNT(d.id_distrito) FROM tblc_distrito as d WHERE d.fecha_eliminado IS NULL" . $sentencia . " ORDER BY d.nombre ASC";

$totalRegistros = $entity->scalar($cadena2);
$resul_lista = $entity->objects($cadena);
?>
    <div class="panel panel-default">
        <div class="panel-heading">

            <h4 class="panel-title">Listado Distrito</h4>
            <p></p>
        </div>
        <div class="panel-body">
            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Secciones</th>
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

                        $secciones = $entity->scalar("SELECT COUNT(id_seccion) FROM tblc_seccion WHERE id_distrito = " . $resultado_fila->id_distrito);
                    ?>
                        <tr>
                            <td><strong><?php echo $resultado_fila->nombre ?></strong></td>
                            <td><?php echo $resultado_fila->estado; ?></td>
                            <td><?php echo $secciones; ?></td>
                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success" onclick="distrito_registro(<?= $resultado_fila->id_distrito ?>)"><span class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?= $resultado_fila->id_distrito ?>,36)"><span class="fa fa-trash"></span></a></td>
                            <?php } ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
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
                                    ?><li><a href="javascript:distrito_lista(<?php echo $enlace['numero'] ?>)"><?php echo $enlace['vista']; ?></a></li><?php 
                                        } 
                                    }
                                }?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div><!-- body -->

    </div><!-- panel default -->