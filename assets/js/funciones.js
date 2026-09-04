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

	alert(msj);

	location.href=pagina;	

	}

function error(msj){

	alert(msj);

	}

function eliminar(campo,id,opcion){
	
	var msg = confirm("¿Desea eliminar este Registro?");
    if(msg) {
	$.ajax({

			url:'php/eliminar.php',
			type:'post',
			data:"submit=&opcion="+opcion+"&id="+id+"&campo="+campo,

			success:function(datos){
			   alert(datos);
			   if(datos != "ERROR al Eliminar registro"){
				   //campo.parentNode.parentNode.parentNode.removeChild(campo.parentNode.parentNode);
				    location.reload(); 
				   }
				void(0);
			}

		});

	}
	
}

function eliminar2(campo,id,id2,opcion){

	var msg = confirm("¿Desea eliminar este Registro?")

    if ( msg ) {

	$.ajax({

			url:'php/eliminar.php',
			type:'post',
			data:"submit=&opcion="+opcion+"&id="+id+"&id2="+id2,
			success:function(datos){

			   alert(datos);
			   if(datos != "ERROR al Eliminar registro"){
				   location.reload();
				   }

			   	/*$(campo).parent().parent().slideToggle($$.config.fxSpeed)*/

			}

		});

	}

	return false;

}

function cambiarstatus_app(id,estatus){
	
		var msg = confirm("¿Deseas cambiar el estatus de este registro?");

		if(msg){
			$.ajax({
				url:'php/subir.php',
				type:'post',
				data:"submit=&opcion=38&id="+id+"&estatus="+estatus,
				success:function(datos){
					alert(datos);
					}
				});			
			}

	return false;
}

function cambiar_estatus(campo,id,opcion){

	var msg = confirm("¿Desea actualizar el estatus de la solicitud? ")

    if ( msg ) {

	$.ajax({

			url:'php/subir.php',
			type:'post',
			data:"submit=&opcion="+opcion+"&id="+id+"&estatus="+campo.value,
			success:function(datos){

			   alert(datos);
			   if(datos != "ERROR al actualizar registro"){
				   location.reload();
				   }
			   	/*$(campo).parent().parent().slideToggle($$.config.fxSpeed)*/

			}

		});

	}

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
            $("#listado").html("<center><img src='images/loaders/loader10.gif' /><br>Cargando ...</center>");
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
            $("#listado").html("<center><img src='images/loaders/loader10.gif' /><br>Cargando ...</center>");
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
            $("#listapartidos").html("<center><img src='images/loaders/loader10.gif' /><br>Cargando ...</center>");
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
    var botton_cargando = $('<button class="btn btn-primary mb-1" type="button" disabled><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Enviando...</button>');
    var btn_guardar = $("#btn_guardar" + id);
    var btn_original = $("#btn_guardar" + id);
	
    $('#enviar_formulario' + id).submit(function (e) {
        e.preventDefault();
        formData = new FormData($('#enviar_formulario' + id)[0]);

        Swal.fire({
            title: 'Aviso',
            text: '¿Estás seguro de que deseas realizar esta acción?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, continuar',
            cancelButtonText: "No, cancelar",
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: 'POST',
                    url: $("#enviar_formulario" + id).attr('action'),
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        // btn_guardar.replaceWith(botton_cargando);
                        // mostrarOverlay();
                    },
                    success: function (datos) {
                        datos = JSON.parse(datos);

                        notificacion(datos.mensaje, datos.titulo, datos.tipo);
                        if (datos.funcion) {
                            for (i = 0; i < datos.funcion.length; i++) {
                                let param = [];
                                if (datos.params && datos.params[i]) {
                                    param = datos.params[i];
                                }
                                window[datos.funcion[i]](...param);
                            }
                        }

                        // Reemplazar el botón de cargando por el botón de guardar
                        // ocultarOverlay();
                        // botton_cargando.replaceWith(btn_original);
                    },
                    error: function (jqXHR, exception) {
                        // ocultarOverlay();
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect.\n Verify Network.';
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }

                        // botton_cargando.replaceWith(btn_original);

                        notificacion(msg, 'Error', 'error');
                    }
                });
            }
        });
    });
}

function notificacion(msg, titulo, tipo){
    Swal.fire({
      title: titulo,
      text: msg,
      icon: tipo
    });
}