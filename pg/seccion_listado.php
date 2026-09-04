  
<?php
require "../php/inicializandoDatosExterno.php";

$pagina = isset($_GET['pag']) ? $funciones->limpia($_GET['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$sentencia = "";

if ($id_estado != 0) {
    $sentencia .= " and m.id_estado = ".$id_estado."";
}
if ($id_municipio != 0) {
    $sentencia .= " and s.id_municipio = ".$id_municipio."";
}
if(isset($_GET['n'])){
        $sentencia .= " AND s.nombre LIKE '%".$_GET['n']."%'";
    }
$cadena = "SELECT s.*, e.nombre as estado, m.nombre as muni FROM tblc_seccion as s 
JOIN tblc_municipio as m ON(s.id_municipio = m.id_municipio) 
JOIN tblc_estado as e ON(e.id_estado = m.id_estado) 
WHERE s.fecha_eliminado IS NULL".$sentencia." ORDER BY s.nombre ASC LIMIT ".$inicio.",".$limite."";

$cadena2 = "SELECT COUNT(s.id_seccion) FROM tblc_seccion as s JOIN tblc_distrito as d ON(s.id_distrito = d.id_distrito) JOIN tblc_municipio as m ON(d.id_municipio = m.id_municipio) WHERE s.fecha_eliminado IS NULL".$sentencia." ORDER BY s.nombre ASC";

$totalRegistros = $entity->scalar($cadena2);
$resul_lista = $entity->objects($cadena);
?>
                            
        <div class="panel panel-default">
            <div class="panel-heading">
                
                <h4 class="panel-title">Listado Sección</h4>
                <p></p>
            </div>
            <div class="panel-body">
                
                                                                    
                    <table id="basicTable" class="table table-striped table-bordered responsive">
                        <thead class="">
                            <tr>
                                <th>Nombre</th>
                                <th>Municipio</th>
                                <th>Distrito</th>
                                <?php if($editar == 1){ ?>
                                <th >Editar</th>
                                <?php } ?>
                                <?php if($eliminar == 1){ ?>
                                <th>Eliminar</th>
                                <?php } ?>
                            </tr>
                        </thead>
                    
                        <tbody>
                            <?php
                            foreach($resul_lista as $resultado_fila){

                                $distrito = $entity->scalar($querys->getdistrito($resultado_fila->id_distrito));
                            ?>
                            <tr>
                                <td><strong><?php echo $resultado_fila->nombre ?></strong></td>
                                <td><?php echo $resultado_fila->muni; ?>, <?php echo $resultado_fila->estado; ?></td>
                                <td><?php echo $distrito; ?></td>
                                <?php if($editar == 1){ ?>
                                <td align="center"><a class="btn btn-success" onclick="seccion_registro(<?= $resultado_fila->id_seccion ?>)"><span class="fa fa-pen"></span></a></td>
                                <?php } ?>
                                <?php if($eliminar == 1){ ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?php echo $resultado_fila->id_seccion ?>,38)"><span class="fa fa-trash"></span></a></td>
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
                                        
                                        if($datos) {
        
                                            echo 'Pagina: ' .$pagina. ' de ' . $pag->getCantidadPaginas() . '<br />';
                                            foreach ($datos as $enlace) {
                                                if($enlace['active'] == false){ 
                                                ?><li><a href="javascript:seccion_lista(<?php echo $enlace['numero'] ?>)"><?php echo $enlace['vista']; ?></a></li><?php
                                                    }
                                                else{
                                                ?><li class="active"><span><?php echo $enlace['vista']; ?></span></li><?php
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
    