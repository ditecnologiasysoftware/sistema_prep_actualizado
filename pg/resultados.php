
<div class="pageheader">
    <div class="media">
        <div class="pageicon pull-left">
            <i class="fa fa-database"></i>
        </div>
        <div class="media-body">
            <ul class="breadcrumb">
                <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                <li>Registro de Resultados</li>
            </ul>
            <h4>Registro de Resultados</h4>
        </div>
    </div><!-- media -->
</div><!-- pageheader -->

<div class="content-panel row resultados-layout">
        
        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Formulario de busqueda</h4>
                    <p></p>
                </div>
                <div class="panel-body">
                    <form id="form_busqueda">

                        <div class="form-group">
                            <label class="col-sm-12"><b>Proceso Electoral :</b></label>
                            <select name="id_proceso_electoral" id="id_proceso_electoral" class="form-control" onchange="resultados_registro()">
                                <option value="" selected > - Seleccionar Proceso Electoral - </option>
                                <?php
                                echo $funciones->llenarcombo($querys->comboprocesoelectoral($id_proceso_electoral));
                                ?>
                            </select>
                        </div>

                        <div id="contenido"></div>

                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">

                    <h4 class="panel-title">Acta de escrutinio</h4>
                    <p></p>
                </div>
                <div class="panel-body">
                    <div id="listado"><center>Seleccionar Casilla</center></div>
                </div>
            </div>

        </div>
        
</div>
