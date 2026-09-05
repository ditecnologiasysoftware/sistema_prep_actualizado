<script>
    window.onload = function() {
        candidato_registro();
        candidato_lista();
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
                <li>Candidato Electoral</li>
            </ul>
            <h4>Candidato Electoral</h4>
        </div>
    </div>
</div>

<div class="contentpanel">

    <div class="panel-group" id="accordion2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                        <div style="color:#FFFFFF">Buscar</div>
                    </a>
                </h4>
            </div>
            <div id="collapseOne2" class="panel-collapse collapse <?php if (!empty($_POST['bus'])) echo "in"; ?>">
                <div class="panel-body">
                    <table width="100%" border="0">
                        <tr>
                            <td width="100%">
                                <div class="form-group">
                                    <form id="form_busqueda">
                                        <div class="col-sm-6">
                                            <label class="col-sm-12">Nombre :</label>

                                            <input type="text" name="n" id="n" class="form-control" value="<?php if (!empty($_GET['n'])) echo $_POST['n']; ?>" />

                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-12">Proceso Electoral :</label>
                                            <select name="pe" id="pe" style="width: 100%;">
                                                <option value="">-- Ninguno --</option>
                                                <?php
                                                echo $funciones->llenarcombomodifica($entity->statement('candidato.51.1'), $_POST['pe']);
                                                ?>
                                            </select>
                                        </div>
                                        <input type="button" class="btn btn-primary mr5" value="Buscar" onclick="candidato_lista()" />
                                        <input type="button" class="btn btn-secundary mr5" onclick="window.location.href='candidato'" value="Cancelar">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div><!-- panel -->
    </div><!-- panel-group -->

    <div class="row">
        <div id="contenido" class="col-md-5"></div>
        <div id="listado" class="col-md-7"></div>
    </div>
</div>
