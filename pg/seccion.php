<script>
    window.onload = function () {
        seccion_lista();
        seccion_registro();
    }
</script>
<div class="pageheader">
    <div class="media">
        <div class="pageicon pull-left">
            <i class="fa fa-home"></i>
        </div>
        <div class="media-body">
            <ul class="breadcrumb">
                <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                <li>Sección</li>
            </ul>
            <h4>Sección</h4>
        </div>
    </div><!-- media -->
</div><!-- pageheader -->
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
                                        <label class="col-sm-12">Nombre :</label>

                                        <input type="text" name="n" id="n" class="form-control"
                                            value="" />

                                    </div><br>
                                    <input type="button" class="btn btn-primary mr5" value="Buscar" onclick="seccion_lista()" />
                                    <input type="button" class="btn btn-secundary mr5"
                                        onclick="window.location.href='seccion'" value="Cancelar">

                                </form>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div><!-- panel -->
</div><!-- panel-group -->
<div class="contentpanel">
    <div class="row">
        <div id="formulario_registro" class="col-md-4"></div>
        <div id="contenido" class="col-md-8"></div>
    </div>
</div>