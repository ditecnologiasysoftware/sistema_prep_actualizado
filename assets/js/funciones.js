// JavaScript Document
//$("#contenido_chat").animate({scrollTop: $("#contenido_chat").scrollHeight});
//$("contenido_chat").scrollTop(1000);
/*$("#estado").val();
window.onload = combodependiente('estado', 'muni', 'combo_dependiente/municipios.php');
*/
function ocModal(){
    //$(".modal").fadeToggle(200);
    //$(".modal-content").html(""); 
    $(".modal").modal('show');   
}

function ocModalCerrar(){
    //$(".modal-content").html(""); 
    $('.modal').modal('hide');
}

$("input[name=checktodos]").change(function(){

		$('input[type=checkbox]').each( function() {			
			//alert("activo");
			if($("input[name=checktodos]:checked").length == 1){
				this.checked = true;
			} else {
				this.checked = false;
			}
		});
	});


function satisfactorio(msj,pagina){
	PrepAlert.notify(msj, 'Listo', 'success').then(function () {
		if (pagina) location.href = pagina;
	});
}

function error(msj){
	return PrepAlert.error(msj);
}

function eliminar(campo,id,opcion){
	PrepAlert.confirm({
		title: 'Eliminar registro',
		text: '¿Deseas eliminar este registro?',
		icon: 'warning',
		confirmButtonText: 'Sí, eliminar',
		cancelButtonText: 'Cancelar'
	}).then(function (result) {
		if (!result.isConfirmed) return;
		$.ajax({

			url:'php/eliminar.php',
			type:'post',
			data:"submit=&opcion="+opcion+"&id="+id+"&campo="+campo,

			success:function(datos){
				var fallo = String(datos).toUpperCase().indexOf('ERROR') >= 0;
				PrepAlert.notify(datos, fallo ? 'Error' : 'Listo', fallo ? 'error' : 'success').then(function () {
					if (!fallo) location.reload();
				});
			},
			error: PrepAlert.ajaxError
		});
	});
	return false;
}

function eliminar2(campo,id,id2,opcion){
	PrepAlert.confirm({
		title: 'Eliminar registro', text: '¿Deseas eliminar este registro?', icon: 'warning',
		confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
	}).then(function (result) {
		if (!result.isConfirmed) return;
		$.ajax({

			url:'php/eliminar.php',
			type:'post',
			data:"submit=&opcion="+opcion+"&id="+id+"&id2="+id2,
			success:function(datos){
				var fallo = String(datos).toUpperCase().indexOf('ERROR') >= 0;
				PrepAlert.notify(datos, fallo ? 'Error' : 'Listo', fallo ? 'error' : 'success').then(function () {
					if (!fallo) location.reload();
				});
			},
			error: PrepAlert.ajaxError
		});
	});

	return false;

}

function cambiarstatus_app(id,estatus){
		PrepAlert.confirm({ title: 'Cambiar estatus', text: '¿Deseas cambiar el estatus de este registro?', icon: 'question', confirmButtonText: 'Sí, continuar', cancelButtonText: 'Cancelar' }).then(function (result) {
			if (!result.isConfirmed) return;
			$.ajax({
				url:'php/subir.php',
				type:'post',
				data:"submit=&opcion=38&id="+id+"&estatus="+estatus,
				success:function(datos){
					PrepAlert.notify(datos, 'Aviso', 'success');
				},
				error: PrepAlert.ajaxError
				});			
		});

	return false;
}

function cambiar_estatus(campo,id,opcion){
	PrepAlert.confirm({ title: 'Actualizar estatus', text: '¿Deseas actualizar el estatus de la solicitud?', icon: 'question', confirmButtonText: 'Sí, actualizar', cancelButtonText: 'Cancelar' }).then(function (result) {
		if (!result.isConfirmed) return;
		$.ajax({

			url:'php/subir.php',
			type:'post',
			data:"submit=&opcion="+opcion+"&id="+id+"&estatus="+campo.value,
			success:function(datos){
				var fallo = String(datos).toUpperCase().indexOf('ERROR') >= 0;
				PrepAlert.notify(datos, fallo ? 'Error' : 'Listo', fallo ? 'error' : 'success').then(function () {
					if (!fallo) location.reload();
				});
			},
			error: PrepAlert.ajaxError
		});
	});

	return false;

}

function combodependientediv(padre,div,pagina){

  $("#"+padre+" option:selected").each(function () {

   id=$(this).val();
    $.post(pagina, { id: id}, function(data){
	//alert(data);
    $("#"+div).html(data);
    });

  })

 }

function combodependiente(padre,hijo,pagina){

  $("#"+padre+" option:selected").each(function () {

   id=$(this).val();
    $.post('php/'+pagina, { id: id}, function(data){
	//alert(data);
    $("#"+hijo).html(data);
    });

  })

 }

function dedependiente(id,hijo,pagina){
		if(id != 0 && id != ""){
		  $.post('php/'+pagina, { id: id}, function(data){
			//alert(data);
		    $("#"+hijo).html(data);
		    });
			}
		else
			$("#"+hijo).html('');
	}

function cambioestatusappModal(campo,id,opcion,idreprent){
		var estatus = campo.value;
			$.ajax({
				url:'../php/subir.php',
				type:'post',
				data:"submit=&opcion="+opcion+"&id="+id+"&idreprent="+idreprent+"&accesoapp="+estatus,
				success:function(datos){
			
					}
			
				});			
			
	return false;
}
var features = new Array();
var infowindow1 = new google.maps.InfoWindow();
var map;
var data = '';
function inicializar(latitud, longitud, zoomm) {
    map = new google.maps.Map(document.getElementById('google-map'), { //var
        zoom: zoomm, //10,
        center: new google.maps.LatLng(latitud, longitud), //(22.344, 114.048),
        //var latlng = new google.maps.LatLng(result[0].geometry.location.lat(), result[0].geometry.location.lng());
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        zoomControl: true
    });   
}


function mapaSecciones(params){
	var id = Math.random();
  $.getJSON('php/mapa_seccion.php'+params,function(data){ 
      features[id] = map.data.addGeoJson(data);
    });
  //OBTENCIÓN DE LAS PROPIEDADES PARA APLICARLAS A LAS FORMAS DEL DATA LAYER
    map.data.setStyle(function(feature){ 
      return {title:feature.getProperty('title'), icon:feature.getProperty('icon'), strokeColor:feature.getProperty('stroke'), strokeWeight:feature.getProperty('stroke-width'), fillColor:feature.getProperty('fill'), fillOpacity:feature.getProperty('fill-opacity'), clickable:feature.getProperty('clickable')};
   });
    //EVENTO PARA MOSTRAR LA VENTANA DE INFORMACIÓN AL HACER CLICK SOBRE ALGÚN PUNTO DE LAS CAPAS DE INFORMACIÓN
    map.data.addListener('click', function(event) {
        var myInfoPopUp = event.feature.getProperty("info");
        infowindow1.setContent(myInfoPopUp);
        infowindow1.setPosition(event.latLng);
        infowindow1.setOptions({pixelOffset: new google.maps.Size(0,0)});
        infowindow1.open(map);
    });
}


function lista_candidatos(pagina = 1){
    url    = 'pg/lista_candidatos.php';       
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function(){
            $("#listado").html("<center><img src='assets/images/loaders/loader10.gif' alt='Cargando' /><br>Cargando ...</center>");
        },
        type:    "post",
        url:     url,
        data:    params+"&pagina="+pagina,
        success: function(data){            
            $("#listado").html(data);
            enviar_formulario();
        }
    });
}


function lista_bingo(pagina=1){
    url    = 'pg/lista_bingo.php';       
    if(pagina != 0) $("#pag").val(pagina);
    var params = $('#form_busqueda').serialize();
    $.ajax({
        beforeSend: function(){
            $("#listado").html("<center><img src='assets/images/loaders/loader10.gif' alt='Cargando' /><br>Cargando ...</center>");
        },
        type:    "post",
        url:     url,
        data:    params+"&pagina="+pagina,
        success: function(data){            
            $("#listado").html(data);
        }
    });
}

function lista_candidatos_completo(campo){
    url    = 'pg/registro_resultados_completo_lista.php';       
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

function enviar_formulario(id = '') {
    var selectorFormulario = '#enviar_formulario' + id;

    $(selectorFormulario).off('submit.prep').on('submit.prep', function (e) {
        e.preventDefault();
        var formulario = this;
        var $formulario = $(formulario);
        var $botonGuardar = $('#btn_guardar' + id);
        var formData = new FormData(formulario);

        PrepAlert.confirm({
            title: 'Aviso',
            text: '¿Estás seguro de que deseas realizar esta acción?',
            icon: 'info',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: $formulario.attr('action'),
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        $botonGuardar.prop('disabled', true).attr('aria-busy', 'true');
                    },
                    success: function (datos) {
                        var respuesta;
                        try {
                            respuesta = PrepAlert.parseResponse(datos);
                        } catch (error) {
                            PrepAlert.error('El servidor devolvió una respuesta inválida. Revisa la operación e inténtalo nuevamente.');
                            return;
                        }
                        PrepAlert.fromResponse(respuesta).then(function () {
                            PrepAlert.runCallbacks(respuesta);
                        });
                    },
                    error: function (jqXHR, exception) {
                        PrepAlert.ajaxError(jqXHR, exception);
                    },
                    complete: function () {
                        $botonGuardar.prop('disabled', false).removeAttr('aria-busy');
                    }
                });
            }
        });
    });
}

function notificacion(msg, titulo, tipo){
    return PrepAlert.notify(msg, titulo, tipo);
}
