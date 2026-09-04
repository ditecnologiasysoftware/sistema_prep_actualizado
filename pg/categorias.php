<script>
    window.onload = function() {
        categorias_lista();
        categorias_registro();
    }
</script>
<div class="pageheader">
    <div class="media">
        <div class="pageicon pull-left">
            <i class="fa fa-database"></i>
        </div>
        <div class="media-body">
            <ul class="breadcrumb">
                <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                <li>Catalogo de Categorías</li>
            </ul>
            <h4>Categorías de etiquetas</h4>
        </div>
    </div>
</div>
<div class="panel-group" id="accordion2">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                    <div style="color:#FFFFFF">Buscar</div>
                </a>
            </h4>
        </div>
        <div id="collapseOne2" class="panel-collapse collapse <?php if (isset($_POST['bus'])) echo "in"; ?>">
            <div class="panel-body">
                <table width="100%" border="0">
                    <tr>
                        <td width="100%">
                            <div class="form-group">
                                <label class="col-sm-3">Buscar :</label>
                                <form class="form-horizontal" enctype="multipart/form-data" name="form_busqueda" id="form_busqueda" method="post" action="categorias">
                                    <div class="col-sm-6">

                                        <input type="text" name="nombre_busqueda" id="nombre_busqueda" class="form-control" value="<?php if (isset($_GET['nombre_busqueda'])) echo $_GET['nombre_busqueda']; ?>" />

                                    </div>
                                    <input type="submit" class="btn btn-primary mr5" value="Buscar" />
                                    <input type="button" class="btn btn-secundary mr5" onclick="window.location.href='categorias'" value="Cancelar">

                                </form>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="contentpanel">
    <div class="row">
        <div id="formulario_registro"></div>
        <div id="contenido"></div>
    </div>
</div>