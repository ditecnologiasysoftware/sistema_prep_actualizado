var params = '';
var cargando = "<center><img src='assets/images/cargando.gif' width='70px' alt='Cargando' /></center>";
var cargando_2 = "<center><img src='assets/images/cargando.gif' width='70px' alt='Cargando' /></center>";
var url = '';

function permisos_registro(id = '') {
    url = 'pg/permisos_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function permisos_lista(pagina = 1) {
    url = 'pg/permisos_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function estados_registro(id = '') {
    url = 'pg/estados_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function estados_lista(pagina = 1) {
    url = 'pg/estados_listado.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function municipios_registro(id = '') {
    url = 'pg/municipios_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function municipios_lista(pagina = 1) {
    url = 'pg/municipios_listado.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function categorias_registro(id = '') {
    url = 'pg/categorias_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function categorias_lista(pagina = 1) {
    url = 'pg/categorias_listado.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function etiquetas_registro(id = '') {
    url = 'pg/etiquetas_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function etiquetas_lista(pagina = 1) {
    url = 'pg/etiquetas_listado.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function casilla_electoral_registro(id = '') {
    url = 'pg/casilla_electoral_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function casilla_electoral_lista(pagina = 1) {
    url = 'pg/casilla_electoral_listado.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function proceso_electoral_registro(id = '') {
    url = 'pg/proceso_electoral_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function proceso_electoral_lista(pagina = 1) {
    url = 'pg/proceso_electoral_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function tipo_eleccion_registro(id = '') {
    url = 'pg/tipo_eleccion_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function tipo_eleccion_lista(pagina = 1) {
    url = 'pg/tipo_eleccion_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function partido_politico_registro(id = '') {
    url = 'pg/partido_politico_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function partido_politico_lista(pagina = 1) {
    url = 'pg/partido_politico_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function distrito_registro(id = '') {
    url = 'pg/distrito_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function distrito_lista(pagina = 1) {
    url = 'pg/distrito_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function seccion_registro(id = '') {
    url = 'pg/seccion_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function seccion_lista(pagina = 1) {
    url = 'pg/seccion_listado.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function reporte_listado(pagina = 1) {
    url = 'pg/reportes_listado.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}


function reporte_registro(id = '') {
    url = 'pg/reportes_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
            enviar_formulario();
            jQuery("select, #select-multi").select2();
            
            jQuery("#select-basic, #select-multi").select2();
            jQuery('#select-search-hide').select2({
                minimumResultsForSearch: -1
            });
        }
    });
}

function configurar_registro(id = '') {
    url = 'pg/configurar_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#formulario_registro").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#formulario_registro").html(data);
            enviar_formulario();
        }
    });
}

function incidencias_registro(id = '') {
    url = 'pg/incidencias_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
            enviar_formulario();
        }
    });
}

function resultados_lista() {
    url = 'pg/lista_candidatos.php';
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#listado").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#listado").html(data);
            enviar_formulario();
        }
    });
}

function resultados_registro() {
    var id = $("#id_proceso_electoral").val();
    url = 'pg/resultados_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function resultado_eleccion_lista(pagina = 1) {
    url = 'pg/resultado_eleccion_lista.php';

    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#listado").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#listado").html(data);
        }
    });
}

function casilla_resultados(c, cand){
    url    = 'pg/casilla_resultados.php';
    params = { 'c': c, 'cand': cand};
    $.ajax({
        beforeSend: function(){
        },
        type:    "post",
        url:     url,
        data:    params,
        success: function(data){  
            ocModal();          
            $("#contenidomodal").html(data);
            casilla_resultados_lista();
        }
    });
}

function graficas_resultado_modal(id){
    url    = 'pg/graficas_resultado_modal.php';
    params = { 'id': id};
    $.ajax({
        beforeSend: function(){
        },
        type:    "post",
        url:     url,
        data:    params,
        success: function(data){  
            ocModal();          
            $("#contenidomodal").html(data);
        }
    });
}

function casilla_resultados_lista(pagina = 1) {
    url = 'pg/casilla_resultados_lista.php';

    if(pagina != 0) $("#pagmodal").val(pagina);
    var params = $('#form_busqueda_modal').serialize();
    $.ajax({
        beforeSend: function () {
            $("#listadomodal").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#listadomodal").html(data);
        }
    });
}

function partido_resultados(c, cand){
    url    = 'pg/partido_resultados.php';
    params = { 'c': c, 'cand': cand};
    $.ajax({
        beforeSend: function(){
        },
        type:    "post",
        url:     url,
        data:    params,
        success: function(data){  
            ocModal();          
            $("#contenidomodal").html(data);
        }
    });
}

function candidato_resultados(c, cand){
    url    = 'pg/candidato_resultados.php';
    params = { 'c': c, 'cand': cand};
    $.ajax({
        beforeSend: function(){
        },
        type:    "post",
        url:     url,
        data:    params,
        success: function(data){  
            ocModal();          
            $("#contenidomodal").html(data);
        }
    });
}

function candidato_registro(id = '') {
    url = 'pg/candidato_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
            enviar_formulario();
            jQuery("select, #select-multi").select2();
        }
    });
}

function candidato_lista(pagina = 1) {
    url = 'pg/candidato_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#listado").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#listado").html(data);
        }
    });
}

function representante_registro(id = '') {
    url = 'pg/representante_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
            enviar_formulario();
        }
    });
}

function representante_lista(pagina = 1) {
    url = 'pg/representante_lista.php';
    if(pagina != 0) $("#pagina").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#listado").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#listado").html(data);
        }
    });
}

function nominal_registro(id = '') {
    url = 'pg/nominal_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
            enviar_formulario();
        }
    });
}

function nominal_lista(pagina = 1) {
    url = 'pg/nominal_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#listado").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#listado").html(data);
        }
    });
}

function captura_masiva_registro(id = '') {
    url = 'pg/nominal_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
            enviar_formulario();
        }
    });
}

function captura_masiva_lista(pagina = 1) {
    url = 'pg/captura_masiva_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#listado").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#listado").html(data);
        }
    });
}

function estatus_casilla_registro(id = '') {
    url = 'pg/estatus_casilla_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
            enviar_formulario();
        }
    });
}

function estatus_casilla_lista(pagina = 1) {
    url = 'pg/estatus_casilla_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#listado").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#listado").html(data);
        }
    });
}

function usuarios_registro(id = '') {
    url = 'pg/usuarios_registro.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
            enviar_formulario();
        }
    });
}

function usuarios_lista(pagina = 1) {
    url = 'pg/usuarios_lista.php';
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
        }
    });
}

function privilegios(id = '') {
    url = 'pg/privilegios_modulo.php';
    params = { 'id': id };
    $.ajax({
        beforeSend: function () {
            $("#contenido").html(cargando);
        },
        type: "post",
        url: url,
        data: params,
        success: function (data) {
            $("#contenido").html(data);
            enviar_formulario('_privilegios');
        }
    });
}

function ordenar_acta_lista(campo){
    url    = 'pg/ordenar_acta_lista.php';       
    params = { 'id': campo.value };

    $.ajax({
        beforeSend: function(){
            $("#listapartidos").html("<center><img src='assets/images/loaders/loader10.gif' alt='Cargando' /><br>Cargando ...</center>");
        },
        type:    "post",
        url:     url,
        data:    params,
        success: function(data){            
            $("#listapartidos").html(data);
        }
    });
}
