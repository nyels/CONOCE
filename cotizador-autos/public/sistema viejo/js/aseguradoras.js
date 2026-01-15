$(document).ready(inicio);
function inicio()
{

	$('#aseguradora_derecho').focusout(verificar_aseguradora_select);
	$('#aseguradora_recargo').focusout(verificar_aseguradora_select_recargo);
	$('#derecho_poliza').focusout(verificar_derecho_poliza);

	$('#aseguradora').blur(verificar_aseguradora);


	$('#forma_de_pago').blur(verificar_forma_de_pago);
	$('#recargo').focusout(verificar_recargo);

	var uno =$.typeahead({
	    input: '#aseguradora',
	    minLength: 1,
	    maxItem: 20,
	    order: "asc",
	   // href: "https://en.wikipedia.org/?title={{display}}",
	    template: "{{display}} <small style='color:#999;'>{{group}}</small>",
	    source: {
	        aseguradoras: {
	            ajax: {
	                url: "metodos/aseguradoras_metodos.php",
	                type:"post",
	                data:{aseguradoras:1},
	                path:"aseguradoras.nombre",
	            }
	        },
	       
	    },
	    callback: {
	        onNavigateAfter: function (node, lis, a, item, query, event) {
	            if (~[38,40].indexOf(event.keyCode)) {
	                var resultList = node.closest("formulario_familia").find("ul.typeahead__list"),
	                    activeLi = lis.filter("li.active"),
	                    offsetTop = activeLi[0] && activeLi[0].offsetTop - (resultList.height() / 2) || 0;
	 
	                resultList.scrollTop(offsetTop);
	            }
	 
	        },
	        onResult: function (node, query, result, resultCount) 
	        {
	            if (query === "") 
	            return;
	 
	            var text = "";
	            if (result.length > 0 && result.length < resultCount) 
	            {
	                text = "<strong>" + result.length + "</strong> aseguradora(s) <strong>" + resultCount + '</strong> que contienen "' + query + '"';
	                 $('#contenedor_resultado_aseguradora').val(1);
	                 $('.guardar_aseguradora').prop('disabled',true);
	                     $('.actualizar_aseguradora').prop('disabled',true);
	            } 
	            else if (result.length > 0) 
	            {
	                text = '<strong>' + result.length + '</strong> aseguradora(s) que contienen "' + query + '"';
	                 $('#resultado_aseguradora').val(1);

	                 $('.guardar_aseguradora').prop('disabled',true);
	                     $('.actualizar_aseguradora').prop('disabled',true);
	            } 
	            else 
	            {
	                text = 'Puedes registrar el nombre del aseguradora, no hay datos guardados con "' + query + '"';
	                 $('#resultado_aseguradora').val(0);

	                 $('.guardar_aseguradora').prop('disabled',false);
	                     $('.actualizar_aseguradora').prop('disabled',false);
	            }
	            $('#contenedor_resultado_aseguradora').html(text);

	 
	        },
	        onMouseEnter: function (node, a, item, event) {
	 
	            if (item.group === "aseguradoras") {
	                $(a).append('<span class="flag-chart flag-' + item.display.replace(' ', '-').toLowerCase() + '"></span>');
	            }
	 
	        },
	        onMouseLeave: function (node, a, item, event) {
	 
	            $(a).find('.flag-chart').remove();
	 
	        }
	    }
	});
}

window.addEventListener("load", function() 
{
var derecho_poliza = document.querySelector('#derecho_poliza');
  	derecho_poliza.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var recargo = document.querySelector('#recargo');
  	recargo.addEventListener("keypress", soloNumeros_decimales, false);
});

//Solo permite introducir numeros.
function soloNumeros_decimales(e){
  var key = window.event ? e.which : e.keyCode;
  if ( (key<46 || key>46)&&(key < 48 || key > 57))
  {
    e.preventDefault();
  }
}


$(document).on('click','.guardar_aseguradora',function(e){



	$('#alerta_correcta_contacto').slideUp('fast');
	$('#alerta_error_contacto').slideUp('fast');

	if(verificar_aseguradora()==true && verificar_imagen()==true)
	{
		var archivo = $('#file');
		var input = archivo[0];
		var file_final=input.files[0];
		var parametros = new FormData();
		parametros.append('alta_aseguradora',$('#aseguradora').val()),
		parametros.append('foto',file_final),


		$.ajax({
			url:'metodos/aseguradoras_metodos.php',	
			type:'post',
			data:parametros,
			contentType: false,
            processData: false,
			success:function(data)
			{
				if(data.trim()=='1')
				{
					$('#contenedor_imagen').html(' <img style="width: 100%;" id="foto"  src="img/foto_inicial.png">');
					$('#nombre_archivo').html('');
					$('#contenedor_resultado_aseguradora').html('');
					$('#aseguradora').val('');
					$('#alerta_correcta_contacto').slideDown('fast');

					var uno =$.typeahead({
				    input: '#aseguradora',
				    minLength: 1,
				    maxItem: 20,
				    order: "asc",
				   // href: "https://en.wikipedia.org/?title={{display}}",
				    template: "{{display}} <small style='color:#999;'>{{group}}</small>",
				    source: {
				        aseguradoras: {
				            ajax: {
				                url: "metodos/aseguradoras_metodos.php",
				                type:"post",
				                data:{aseguradoras:1},
				                path:"aseguradoras.nombre",
				            }
				        },
				       
				    },
				    callback: {
				        onNavigateAfter: function (node, lis, a, item, query, event) {
				            if (~[38,40].indexOf(event.keyCode)) {
				                var resultList = node.closest("formulario_familia").find("ul.typeahead__list"),
				                    activeLi = lis.filter("li.active"),
				                    offsetTop = activeLi[0] && activeLi[0].offsetTop - (resultList.height() / 2) || 0;
				 
				                resultList.scrollTop(offsetTop);
				            }
				 
				        },
				        onResult: function (node, query, result, resultCount) 
				        {
				            if (query === "") 
				            return;
				 
				            var text = "";
				            if (result.length > 0 && result.length < resultCount) 
				            {
				                text = "<strong>" + result.length + "</strong> aseguradora(s) <strong>" + resultCount + '</strong> que contienen "' + query + '"';
				                 $('#contenedor_resultado_aseguradora').val(1);
				                 $('.guardar_aseguradora').prop('disabled',true);
				                     $('.actualizar_aseguradora').prop('disabled',true);
				            } 
				            else if (result.length > 0) 
				            {
				                text = '<strong>' + result.length + '</strong> aseguradora(s) que contienen "' + query + '"';
				                 $('#resultado_aseguradora').val(1);

				                 $('.guardar_aseguradora').prop('disabled',true);
				                     $('.actualizar_aseguradora').prop('disabled',true);
				            } 
				            else 
				            {
				                text = 'Puedes registrar el nombre del aseguradora, no hay datos guardados con "' + query + '"';
				                 $('#resultado_aseguradora').val(0);

				                 $('.guardar_aseguradora').prop('disabled',false);
				                     $('.actualizar_aseguradora').prop('disabled',false);
				            }
				            $('#contenedor_resultado_aseguradora').html(text);

				 
				        },
				        onMouseEnter: function (node, a, item, event) {
				 
				            if (item.group === "aseguradoras") {
				                $(a).append('<span class="flag-chart flag-' + item.display.replace(' ', '-').toLowerCase() + '"></span>');
				            }
				 
				        },
				        onMouseLeave: function (node, a, item, event) {
				 
				            $(a).find('.flag-chart').remove();
				 
				        }
				    }
				});
				}
				else if(data.trim()=='0')
				{
					$('#alerta_error_contacto').slideDown('fast');
				}
				else if(data.trim()=='foto_no')
				{	
					$('#texto_error_alerta').html('¡Error de subida con la imagen.Intenta de nuevo!');
					$('#alerta_error_contacto').slideDown('fast');
				}
			}
		});
	}
	else
	{
		console.log('errar');
	}

});



$(document).on('click','.alta_aseguradora',function(e){

$('#foto').prop('src','img/foto_inicial.png');
$('#contenedor_lista_aseguradoras').slideUp('fast');
$('#contenedor_formulario_aseguradora').slideDown('fast');


$('#alerta_error_contacto').slideUp('fast');
$('#alerta_correcta_contacto').slideUp('fast');
$('.actualizar_aseguradora').slideUp('fast');
$('.guardar_aseguradora').slideDown('fast');
$('#contenedor_resultado_aseguradora').html('');
$('#aseguradora').val('');

});
$(document).on('click','.boton_lista_aseguradoras',function(e){

$('#contenedor_formulario_aseguradora').slideUp('fast');
$('#contenedor_lista_aseguradoras').slideDown('fast');
$('.tabla_aseguradoras').DataTable({
		order:[[1,'asc']],
		"destroy":true,
		"pageLength": 20,
		 "language": 
		 {
	      "emptyTable": "No se encontraron datos"
	}, 
	ajax:
	{
		url:'metodos/aseguradoras_metodos.php',
		type:"post",
		data:function(d)
		{
			d.lista_aseguradoras=1;
			
		}		
	},
	 "columnDefs": 
	 [
        {"className": "dt-center", "targets": "_all"}
      ],
	"columns":
	[

		{"data":"botones"},
		{"data":"foto"},
		{"data":"nombre_seguradora"},
		{"data":"fecha_alta"},
		{"data":"derecho_poliza"},
		{"data":"forma_de_pago"},	
		{"data":"recargo"},
	
	]
});



});

$(document).on('click','.aseguradora',function(e){
$('#foto').prop('src','img/foto_inicial.png');
$('#aseguradora').val('');
$('#contenedor_lista_aseguradoras').slideUp('fast');
$('#contenedor_formulario_aseguradora').slideDown('fast');


$('#contenedor_general_alta_derecho_poliza').slideUp('fast');
$('#contenedor_general_alta_recargo').slideUp('fast');
$('#contenedor_general_alta_aseguradora').slideDown('fast');



});

$(document).on('click','.derecho_poliza',function(e){
$('#derecho_poliza').val('');
$('.actualizar_derecho').slideUp('fast');
$('.guardar_derecho').slideDown('fast');

$('#alerta_correcta_derecho').slideUp('fast');
$('#alerta_error_derecho').slideUp('fast');

$('#contenedor_lista_derecho_pago').slideUp('fast');
$('#contenedor_formulario_derecho_pago').slideDown('fast');

$('#contenedor_general_alta_aseguradora').slideUp('fast');
$('#contenedor_general_alta_recargo').slideUp('fast');
$('#contenedor_general_alta_derecho_poliza').slideDown('fast');

	$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{lista_aseguradoras_select:2},
		success:function(data)
		{
			$('#aseguradora_derecho').html(data);
		}
	});

});

$(document).on('click','.recargo',function(e){
$('#derecho_poliza').val('');
$('.actualizar_derecho').slideUp('fast');
$('.guardar_derecho').slideDown('fast');

$('#alerta_correcta_derecho').slideUp('fast');
$('#alerta_error_derecho').slideUp('fast');

$('#contenedor_lista_derecho_pago').slideUp('fast');
$('#contenedor_formulario_derecho_pago').slideDown('fast');

$('#contenedor_general_alta_aseguradora').slideUp('fast');
$('#contenedor_general_alta_derecho_poliza').slideUp('fast');

$('#contenedor_general_alta_recargo').slideDown('fast');



	$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{lista_aseguradoras_select:2},
		success:function(data)
		{
			$('#aseguradora_recargo').html(data);
		}
	});

});



$(document).on('click','.alta_derecho_poliza',function(e){
	$('#derecho_poliza').val('');
$('.actualizar_derecho').slideUp('fast');
$('.guardar_derecho').slideDown('fast');
$('#alerta_correcta_derecho').slideUp('fast');
$('#alerta_error_derecho').slideUp('fast');

$('#contenedor_lista_derecho_pago').slideUp('fast');
$('#contenedor_formulario_derecho_pago').slideDown('fast');
	
	$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{lista_aseguradoras_select:2},
		success:function(data)
		{
			$('#aseguradora_derecho').html(data);
		}
	});

});

$(document).on('click','.boton_lista_derecho_poliza',function(e){
$('#alerta_correcta_derecho').slideUp('fast');
$('#alerta_error_derecho').slideUp('fast');
$('#contenedor_formulario_derecho_pago').slideUp('fast');
$('#contenedor_lista_derecho_pago').slideDown('fast');

$('.tabla_derecho').DataTable({
		order:[[1,'asc']],
		"destroy":true,
		"pageLength": 20,
		 "language": 
		 {
	      "emptyTable": "No se encontraron datos"
	}, 
	ajax:
	{
		url:'metodos/aseguradoras_metodos.php',
		type:"post",
		data:function(d)
		{
			d.lista_costo_historial=1;
			
		}		
	},
	 "columnDefs": 
	 [
        {"className": "dt-center", "targets": "_all"}
      ],
	"columns":
	[

		{"data":"botones"},
		
		{"data":"nombre_seguradora"},
		{"data":"costo"},
		{"data":"ultima_fecha"},
		{"data":"historial"}
	
	]
});


});

$(document).on('click','.guardar_derecho',function(e){
$('#alerta_correcta_derecho').slideUp('fast');
$('#alerta_error_derecho').slideUp('fast');

var aseguradora=$('#aseguradora_derecho').val();
var costo=$('#derecho_poliza').val();

if(verificar_aseguradora_select()==true && verificar_derecho_poliza()==true)
{

$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{
			insertando_costo:1,
			aseguradora:aseguradora,
			costo:costo
		},
		success:function(data)
		{
			if(data.trim()=='1')
			{
				$('#aseguradora_derecho option:eq(0)').prop('selected',true);
				$('#derecho_poliza').val('');
				$('#texto_correcto_alerta_derecho').html('Registro completo');
				$('#alerta_correcta_derecho').slideDown('fast');
			}
			else if(data.trim()=='ya')
			{
				$('#texto_error_alerta_derecho').html('¡La aseguradora ya tiene registrado un derecho de poliza, solo puedes modificarlo!');
				$('#alerta_error_derecho').slideDown('fast');
			}
			else
			{
				$('#texto_error_alerta_derecho').html('Se produjo un error.No se pudo registrar el dato.');
				$('#alerta_error_derecho').slideDown('fast');
			}
		}
	});
}
else
{
	console.log('error');
}

});

$(document).on('click','.editar_derecho',function(e){

var id_costo=$(this).val();
var costo_tabla = $(this).parents("tr").find("td").eq(2).text();
$('.guardar_derecho').slideUp('fast');
$('.actualizar_derecho').slideDown('fast');
$('#derecho_poliza').val(costo_tabla);
$('.actualizar_derecho').val(id_costo);

$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{lista_aseguradoras_select:2},
		success:function(data)
		{
			$('#aseguradora_derecho').html(data);
			$('#contenedor_lista_derecho_pago').slideUp('fast');
			$('#contenedor_formulario_derecho_pago').slideDown('fast');
			$('#aseguradora_derecho option').each(function(){
			if($(this).val()==id_costo)
			{
				console.log(id_costo);
				$('#aseguradora_derecho option[value="'+id_costo+'"]').prop('selected',true);
				$('#aseguradora_derecho').prop('disabled',true);
				$('#derecho_poliza').val(costo_tabla);
			}
			else
			{

			}
		});

		}
	});

		
});


$(document).on('click','.actualizar_derecho',function(e){

$('#alerta_correcta_derecho').slideUp('fast');
$('#alerta_error_derecho').slideUp('fast');

var aseguradora=$(this).val();
var costo=$('#derecho_poliza').val();

if(verificar_derecho_poliza()==true)
{

$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{
			actualizando_costo:1,
			aseguradora:aseguradora,
			costo:costo
		},
		success:function(data)
		{
			if(data.trim()=='1')
			{
				$('#aseguradora_derecho option:eq(0)').prop('selected',true);
				$('#derecho_poliza').val('');
				$('#texto_correcto_alerta_derecho').html('Se actualizo correctamente el dato');
				$('#alerta_correcta_derecho').slideDown('fast');
				$('.actualizar_derecho').slideUp('fast');
				$('.guardar_derecho').slideDown('fast');
				$('#aseguradora_derecho').prop('disabled',false);
			}
			else
			{
				$('#texto_error_alerta_derecho').html('Se produjo un error.No se pudo actualizar el dato.');
				$('#alerta_error_derecho').slideDown('fast');
			}
		}
	});
}
else
{
	console.log('error actualizar');
}

});


$(document).on('click','#eliminar_derecho',function(e){

var id_costo=$(this).val();

$('#cuerpo_modal_alerta').html('¿Estas seguro de elimnar el derecho de poliza?');
$('#id_confirmar').val(id_costo);
$('#modal_alerta').modal({show:true});

});



/*

$(document).on('click','#id_confirmar',function(e){

var id_costo=$(this).val();
$('#modal_alerta').modal('hide');

$('.tabla_derecho').DataTable({
		order:[[1,'asc']],
		"destroy":true,
		"pageLength": 20,
		 "language": 
		 {
	      "emptyTable": "No se encontraron datos"
	}, 
	ajax:
	{
		url:'metodos/aseguradoras_metodos.php',
		type:"post",
		data:function(d)
		{
			d.eliminando_derecho_poliza=id_costo;
			
		}		
	},
	 "columnDefs": 
	 [
        {"className": "dt-center", "targets": "_all"}
      ],
	"columns":
	[

		{"data":"botones"},
		
		{"data":"nombre_seguradora"},
		{"data":"costo"},
		{"data":"ultima_fecha"},
		{"data":"historial"}
	
	]
});

});
*/
$(document).on('click','.historial',function(e){

var id_aseguradora=$(this).val();


$.ajax({
	url:'metodos/aseguradoras_metodos.php',
	type:'post',
	data:{historial_derecho_costo:id_aseguradora},
	success:function(data)
	{
		$('#cuerpo_modal_historial').html(data);
		$('#modal_historial').modal({show:true});
	}
});


});



$(document).on('click','.eliminar_aseguradora',function(e){

var id_aseguradora=$(this).val();
console.log(id_aseguradora);
$('#cuerpo_modal_alerta').html('¿Estas seguro de elimnar la aseguradora con todos sus datos <br>(derecho de poliza, recargo)?');
$('#id_confirmar').val(id_aseguradora);
$('#modal_alerta').modal({show:true});

});

$(document).on('click','#id_confirmar',function(e){

var datos=$(this).val();
var datos_separados=datos.split('*');
$('#modal_alerta').modal('hide');

	switch(datos_separados[1])
	{
		case "derecho":
			$('.tabla_derecho').DataTable({
				order:[[1,'asc']],
				"destroy":true,
				"pageLength": 20,
				 "language": 
				 {
			      "emptyTable": "No se encontraron datos"
			}, 
			ajax:
			{
				url:'metodos/aseguradoras_metodos.php',
				type:"post",
				data:function(d)
				{
					d.eliminando_derecho_poliza=datos_separados[0];
					
				}		
			},
			 "columnDefs": 
			 [
		        {"className": "dt-center", "targets": "_all"}
		      ],
			"columns":
			[

				{"data":"botones"},
				
				{"data":"nombre_seguradora"},
				{"data":"costo"},
				{"data":"ultima_fecha"},
				{"data":"historial"}
			
			]
		});
		break;
		case "recargo":
		$('.tabla_recargo').DataTable({
			order:[[1,'asc']],
			"destroy":true,
			"pageLength": 20,
			 "language": 
			 {
		      "emptyTable": "No se encontraron datos"
				}, 
				ajax:
				{
					url:'metodos/aseguradoras_metodos.php',
					type:"post",
					data:function(d)
					{
						d.eliminando_recargo_poliza=datos_separados[0];
						
					}		
				},
				 "columnDefs": 
				 [
			        {"className": "dt-center", "targets": "_all"}
			      ],
				"columns":
				[

					{"data":"botones"},
					
					{"data":"nombre_seguradora"},
					{"data":"forma_pago"},
					{"data":"costo"},
					{"data":"ultima_fecha"},
					{"data":"historial"}
				
				]
			});

		break;
		default:
		$.ajax(
			{
				url:'metodos/aseguradoras_metodos.php',
				type:"post",
				data:{
						eliminando_aseguradora_con_todos_los_datos:datos_separados[0]
					},
				success:function(data)
				{
					if(data.trim()=='0')
					{
						$('.tabla_aseguradoras').DataTable({
						order:[[3,'asc']],
						"destroy":true,
						"pageLength": 20,
						 "language": 
						 {
					      "emptyTable": "No se encontraron datos"
							}, 
							ajax:
							{
								url:'metodos/aseguradoras_metodos.php',
								type:"post",
								data:function(d)
								{
									d.lista_aseguradoras=1;
									
								}		
							},
							 "columnDefs": 
							 [
						        {"className": "dt-center", "targets": "_all"}
						      ],
							"columns":
							[

								{"data":"botones"},
								{"data":"foto"},
								{"data":"nombre_seguradora"},
								{"data":"fecha_alta"},
								{"data":"derecho_poliza"},
								{"data":"forma_de_pago"},	
								{"data":"recargo"},
							
							]
						});
					}
					else
					{

					}
				}		
			});
		
		break;
	}

});


$(document).on('click','.editar_aseguradora',function(e){

var id_costo=$(this).val();
var nombre_empresa = $(this).parents("tr").find("td").eq(2).text();
$('.guardar_aseguradora').slideUp('fast');
$('.actualizar_aseguradora').slideDown('fast');



$('#contenedor_lista_aseguradoras').slideUp('fast');
$('#contenedor_formulario_aseguradora').slideDown('fast');
$('#aseguradora').val(nombre_empresa);
$('.actualizar_aseguradora').val(id_costo);
$('#alerta_correcta_contacto').slideUp('fast');
$('#alerta_error_contacto').slideUp('fast');
var direccion_foto = $(this).parents("tr").find('img').attr('src');
$('#foto').prop('src',direccion_foto);

});


$(document).on('click','.actualizar_aseguradora',function(e){

$('#alerta_error_contacto').slideUp('fast');
$('#alerta_correcta_contacto').slideUp('fast');


var aseguradora=$('#aseguradora').val();
var id_aseguradora=$(this).val();
var archivo = $('#file');
var input = archivo[0];
var file_final=input.files[0];

if(verificar_aseguradora()==true && verificar_imagen()==true)
{
	var parametros = new FormData();
	parametros.append('actualizando_aseguradora',1);
	parametros.append('aseguradora',id_aseguradora);
	parametros.append('nombre_aseguradora',aseguradora);
	parametros.append('aseguradora',id_aseguradora);
	parametros.append('foto',file_final);

$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:parametros,
		contentType: false,
        processData: false,
		success:function(data)
		{
			if(data.trim()=='1')
			{
				$('#contenedor_imagen').html(' <img style="width: 100%;" id="foto"  src="img/foto_inicial.png">');
				$('#nombre_archivo').html('');	
				$('#aseguradora').val('');
				$('#texto_correcto_alerta').html('Se actualizo correctamente el dato');
				$('#alerta_correcta_derecho').slideDown('fast');
				$('.actualizar_aseguradora').slideUp('fast');
				$('.guardar_aseguradora').slideDown('fast');
				$('#contenedor_resultado_aseguradora').html('');

				var uno =$.typeahead({
				    input: '#aseguradora',
				    minLength: 1,
				    maxItem: 20,
				    order: "asc",
				   // href: "https://en.wikipedia.org/?title={{display}}",
				    template: "{{display}} <small style='color:#999;'>{{group}}</small>",
				    source: {
				        aseguradoras: {
				            ajax: {
				                url: "metodos/aseguradoras_metodos.php",
				                type:"post",
				                data:{aseguradoras:1},
				                path:"aseguradoras.nombre",
				            }
				        },
				       
				    },
				    callback: {
				        onNavigateAfter: function (node, lis, a, item, query, event) {
				            if (~[38,40].indexOf(event.keyCode)) {
				                var resultList = node.closest("formulario_familia").find("ul.typeahead__list"),
				                    activeLi = lis.filter("li.active"),
				                    offsetTop = activeLi[0] && activeLi[0].offsetTop - (resultList.height() / 2) || 0;
				 
				                resultList.scrollTop(offsetTop);
				            }
				 
				        },
				        onResult: function (node, query, result, resultCount) 
				        {
				            if (query === "") 
				            return;
				 
				            var text = "";
				            if (result.length > 0 && result.length < resultCount) 
				            {
				                text = "<strong>" + result.length + "</strong> aseguradora(s) <strong>" + resultCount + '</strong> que contienen "' + query + '"';
				                 $('#contenedor_resultado_aseguradora').val(1);
				                 $('.guardar_aseguradora').prop('disabled',true);
				                 $('.actualizar_aseguradora').prop('disabled',true);

				            } 
				            else if (result.length > 0) 
				            {
				                text = '<strong>' + result.length + '</strong> aseguradora(s) que contienen "' + query + '"';
				                 $('#resultado_aseguradora').val(1);

				                 $('.guardar_aseguradora').prop('disabled',true);
				                     $('.actualizar_aseguradora').prop('disabled',true);
				            } 
				            else 
				            {
				                text = 'Puedes registrar el nombre del aseguradora, no hay datos guardados con "' + query + '"';
				                 $('#resultado_aseguradora').val(0);

				                 $('.guardar_aseguradora').prop('disabled',false);
				                     $('.actualizar_aseguradora').prop('disabled',false);
				            }
				            $('#contenedor_resultado_aseguradora').html(text);

				 
				        },
				        onMouseEnter: function (node, a, item, event) {
				 
				            if (item.group === "aseguradoras") {
				                $(a).append('<span class="flag-chart flag-' + item.display.replace(' ', '-').toLowerCase() + '"></span>');
				            }
				 
				        },
				        onMouseLeave: function (node, a, item, event) {
				 
				            $(a).find('.flag-chart').remove();
				 
				        }
				    }
				});
			}
			else
			{
				$('#texto_error_alerta').html('Se produjo un error.No se pudo actualizar el dato.');
				$('#alerta_error_contacto').slideDown('fast');
			}
		}
	});
}
else
{
	console.log('error actualizar');
}

});




$(document).on('click','.guardar_recargo',function(e){
$('#alerta_correcta_recargo').slideUp('fast');
$('#alerta_error_recargo').slideUp('fast');

var aseguradora=$('#aseguradora_recargo').val();
var forma_de_pago=$('#forma_de_pago').val();
var costo=$('#recargo').val();

if(verificar_aseguradora_select_recargo()==true && 
	verificar_forma_de_pago()==true &&
	verificar_recargo()==true
	)
{

$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{
			insertando_recargo:1,
			aseguradora:aseguradora,
			forma_pago:forma_de_pago,
			costo:costo
		},
		success:function(data)
		{
			if(data.trim()=='1')
			{
				$('#aseguradora_recargo option:eq(0)').prop('selected',true);
				$('#forma_de_pago option:eq(0)').prop('selected',true);
				$('#recargo').val('');
				$('#texto_correcto_alerta_recargo').html('Registro completo');
				$('#alerta_correcta_recargo').slideDown('fast');
			}
			else if(data.trim()=='ya')
			{
				$('#texto_error_alerta_recargo').html('¡La aseguradora ya tiene registrado de recargo en esa forma de pago, solo puedes modificarlo!');
				$('#alerta_error_recargo').slideDown('fast');
			}
			else
			{
				$('#texto_error_alerta_recargo').html('Se produjo un error.No se pudo registrar el dato.');
				$('#alerta_error_recargo').slideDown('fast');
			}
		}
	});
}
else
{
	console.log('error');
}

});

$(document).on('click','.alta_recargo',function(e){
	$('#recargo').val('');
		$('#aseguradora_recargo option:eq(0)').prop('selected',true);
		$('#forma_de_pago option:eq(0)').prop('selected',true);
		$('#aseguradora_recargo').prop('disabled',false);
				$('#forma_de_pago').prop('disabled',false);
$('.actualizar_recargo').slideUp('fast');
$('.guardar_recargo').slideDown('fast');
$('#alerta_correcta_recargo').slideUp('fast');
$('#alerta_error_recargo').slideUp('fast');

$('#contenedor_lista_recargo').slideUp('fast');
$('#contenedor_formulario_recargo').slideDown('fast');
	
	$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{lista_aseguradoras_select:2},
		success:function(data)
		{
			$('#aseguradora_recargo').html(data);
		}
	});

});

$(document).on('click','.boton_lista_recargo',function(e){
$('#alerta_correcta_recargo').slideUp('fast');
$('#alerta_error_recargo').slideUp('fast');
$('#contenedor_formulario_recargo').slideUp('fast');
$('#contenedor_lista_recargo').slideDown('fast');

$('.tabla_recargo').DataTable({
		order:[[1,'asc']],
		"destroy":true,
		"pageLength": 20,
		 "language": 
		 {
	      "emptyTable": "No se encontraron datos"
	}, 
	ajax:
	{
		url:'metodos/aseguradoras_metodos.php',
		type:"post",
		data:function(d)
		{
			d.lista_recargo_historial=1;
			
		}		
	},
	 "columnDefs": 
	 [
        {"className": "dt-center", "targets": "_all"}
      ],
	"columns":
	[

		{"data":"botones"},
		
		{"data":"nombre_seguradora"},
		{"data":"forma_pago"},
		{"data":"costo"},
		{"data":"ultima_fecha"},
		{"data":"historial"}
	
	]
});


});

$(document).on('click','.historial_recargo',function(e){

var id_aseguradora=$(this).val();


$.ajax({
	url:'metodos/aseguradoras_metodos.php',
	type:'post',
	data:{historial_recargo_costo:id_aseguradora},
	success:function(data)
	{
		$('#cuerpo_modal_historial').html(data);
		$('#modal_historial').modal({show:true});
	}
});


});




$(document).on('click','#eliminar_recargo',function(e){

var id_costo=$(this).val();

$('#cuerpo_modal_alerta').html('¿Estas seguro de elimnar el recargo?');
$('#id_confirmar').val(id_costo);
$('#modal_alerta').modal({show:true});

});




$(document).on('click','.editar_recargo',function(e){

var datos=$(this).val();
var datos_separados=datos.split('*');

$('.actualizar_recargo').val(datos_separados[0]);
var forma_pago = $(this).parents("tr").find("td").eq(2).text();
var costo_tabla = $(this).parents("tr").find("td").eq(3).text();
$('.guardar_recargo').slideUp('fast');
$('.actualizar_recargo').slideDown('fast');
$('.actualizar_recargo').val(datos_separados[0]);

$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{lista_aseguradoras_select:2},
		success:function(data)
		{
			$('#aseguradora_recargo').html(data);
			$('#contenedor_lista_recargo').slideUp('fast');
			$('#contenedor_formulario_recargo').slideDown('fast');
			$('#recargo').val(costo_tabla);
			$('#aseguradora_recargo option').each(function(){
			if($(this).val()==datos_separados[1])
			{
				$('#aseguradora_recargo option[value="'+datos_separados[1]+'"]').prop('selected',true);

				$('#aseguradora_recargo').prop('disabled',true);
			}
			
		});
			$('#forma_de_pago option').each(function(){
			if($(this).val()==forma_pago)
			{
				$('#forma_de_pago option[value="'+forma_pago+'"]').prop('selected',true);
				$('#forma_de_pago').prop('disabled',true);
			}
		});
			


		}
	});

		
});



$(document).on('click','.actualizar_recargo',function(e){

$('#alerta_correcta_recargo').slideUp('fast');
$('#alerta_error_recargo').slideUp('fast');

var id_recargo=$(this).val();
var recargo=$('#recargo').val();

if( verificar_recargo()==true)
{

$.ajax({
		url:'metodos/aseguradoras_metodos.php',
		type:'post',
		data:{
			actualizando_recargo:1,
			id_recargo:id_recargo,
			recargo:recargo
		},
		success:function(data)
		{
			if(data.trim()=='1')
			{
				$('#aseguradora_recargo option:eq(0)').prop('selected',true);
				$('#forma_de_pago option:eq(0)').prop('selected',true);
				$('#recargo').val('');
				$('#texto_correcto_alerta_recargo').html('Se actualizo correctamente el dato');
				$('#alerta_correcta_recargo').slideDown('fast');
				$('.actualizar_recargo').slideUp('fast');
				$('.guardar_recargo').slideDown('fast');
				$('#aseguradora_recargo').prop('disabled',false);
				$('#forma_de_pago').prop('disabled',false);
			}
			else
			{
				$('#texto_error_alerta_recargo').html('Se produjo un error.No se pudo actualizar el dato.');
				$('#alerta_error_recargo').slideDown('fast');
			}
		}
	});
}
else
{
	console.log('error actualizar');
}

});



$(document).on('change','#file',function(e){

var file = e.target.files[0];


if(file!=null)
{
	tipo_archivo = /image.*/;
	 $('#nombre_archivo').html(file.name);
	 if(!file.type.match(tipo_archivo))
	 {

	 	$('#error_imagen').html('¡El archivo no una imagen!');
		$('#error_imagen').slideDown('fast');
		return false;	
	 }
	 else if(file.size>4000000)
	 {
	 	$('#error_imagen').html('¡La imagen pesa mas de 3Mg!');
		$('#error_imagen').slideDown('fast');
		return false;	
	 }
	 else
	 {
	 	

	 	var filereader = new FileReader();

	 	filereader.onload = function(e){
	 		var resultado =e.target.result;
	 		$('#foto').prop('src',resultado);
	 	};

	 	filereader.readAsDataURL(file);
	
	 	$('#error_imagen').slideUp('fast');
		return true;
	 }	
}
else
{
	$('#contenedor_imagen').html(' <img style="width: 100%;" id="foto"  src="img/foto_inicial.png">');
	$('#nombre_archivo').html('');

}
 
});




function verificar_imagen()
{
	var archivo = $('#file');
	input = archivo[0];
	var file_final=input.files[0];

	var tipo_archivo = /image.*/;
	if(file_final!=null)
	{
		 if(!file_final.type.match(tipo_archivo))
		 {

		 	$('#error_imagen').html('¡El archivo no una imagen!');
			$('#error_imagen').slideDown('fast');
			return false;	
		 }
		 else if(file_final.size>4000000)
		 {
		 	$('#error_imagen').html('¡La imagen pesa mas de 3Mg!');
			$('#error_imagen').slideDown('fast');
			return false;	
		 }
		else
		 {
			$('#nombre_archivo').html(file.name);
		 	$('#error_imagen').slideUp('fast');
			return true;
		 }
	}
	else
	{
			if(archivo!='img/foto_inicial.png' && archivo!=null && $('.actualizar_aseguradora').is(':visible')===true)
			{
				$('#error_imagen').slideUp('fast');
				return true;
			}
			else
			{
				$('#error_imagen').html('¡Debes seleccinar una imagen!');
				$('#error_imagen').slideDown('fast');
				return false;
			}
		
	}	
}

function verificar_aseguradora_select()
{
	var aseguradora=$('#aseguradora_derecho').val();
	if(aseguradora==0)
	{
		$('#error_aseguradora_derecho').html('¡Debes seleccionar una aseguradora!');
		$('#error_aseguradora_derecho').slideDown('fast');
		$('#aseguradora_derecho').focus();
		return false;
	}
	else
	{
		$('#error_aseguradora_derecho').slideUp('fast');
		return true;
	}
}


function verificar_aseguradora_select_recargo()
{
	var aseguradora=$('#aseguradora_recargo').val();
	if(aseguradora==0)
	{
		$('#error_aseguradora_recargo').html('¡Debes seleccionar una aseguradora!');
		$('#error_aseguradora_recargo').slideDown('fast');
		$('#aseguradora_recargo').focus();
		return false;
	}
	else
	{
		$('#error_aseguradora_recargo').slideUp('fast');
		return true;
	}
}

function verificar_forma_de_pago()
{
	var aseguradora=$('#forma_de_pago').val();
	if(aseguradora==0)
	{
		$('#error_forma_de_pago').html('¡Debes seleccionar una forma de pago!');
		$('#error_forma_de_pago').slideDown('fast');
		$('#forma_de_pago').focus();
		return false;
	}
	else
	{
		$('#error_forma_de_pago').slideUp('fast');
		return true;
	}
}



function verificar_derecho_poliza()
{
	var costo=$('#derecho_poliza').val();
	costo=costo.replace(/,/g, "");
	if(costo=='')
	{
		$('#error_derecho_poliza').html('¡Debes ingresar una cantidad!');
		$('#error_derecho_poliza').slideDown('fast');
		$('#derecho_poliza').focus();
		return false;
	}
	else if (costo==0)
	{
		$('#error_derecho_poliza').html('¡El costo debe ser mayor a 0!');
		$('#error_derecho_poliza').slideDown('fast');
		$('#derecho_poliza').focus();
		return false;
	}
	else if(costo.split('.').length-1>1)
	{
		$('#error_derecho_poliza').html('¡Ingresa un número valido!');
		$('#error_derecho_poliza').slideDown('fast');
		$('#derecho_poliza').focus();
		return false;
	}
	else
	{
		costo=formatoMexico(costo);
		$('#derecho_poliza').val(costo);
		$('#error_derecho_poliza').slideUp('fast');
		return true;
	}
}

function verificar_recargo()
{
	var costo=$('#recargo').val();
	costo=costo.replace(/,/g, "");
	if(costo=='')
	{
		$('#error_recargo').html('¡Debes ingresar una cantidad!');
		$('#error_recargo').slideDown('fast');
		$('#recargo').focus();
		return false;
	}
	else if (costo==0)
	{
		$('#error_recargo').html('¡El recardo debe ser mayor a 0!');
		$('#error_recargo').slideDown('fast');
		$('#recargo').focus();
		return false;
	}
	else if (parseInt(costo)>100)
	{
		$('#error_recargo').html('¡El recardo no puede ser mayor de 100%!');
		$('#error_recargo').slideDown('fast');
		$('#recargo').focus();
		return false;
	}
	else if(costo.split('.').length-1>1)
	{
		$('#error_recargo').html('¡Ingresa un número valido!');
		$('#error_recargo').slideDown('fast');
		$('#recargo').focus();
		return false;
	}
	else
	{
		costo=formatoMexico(costo);
		$('#recargo').val(costo);
		$('#error_recargo').slideUp('fast');
		return true;
	}
}

function verificar_aseguradora()
{
	var valor = $('#aseguradora').val();
	if(valor=='')
	{
		$('#error_aseguradora').html('¡Debes ingresar un nombre de asegudora!');
		$('#error_aseguradora').slideDown('fast');
		$('#aseguradora').focus();
		return false;
	}
	else
	{
		if(!/^([a-zA-Z0-9 áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\+\-\_\.])*$/.test(valor))
		{
			$('#error_aseguradora').html('¡No se permiten caracteres especiales!');
			$('#error_aseguradora').slideDown('fast');
			$('#aseguradora').focus();
			return false;
		}
		else
		{
			$('#error_aseguradora').slideUp('fast');
			return true;
		}
	}

}

const formatoMexico = (number) => 
{
  const exp = /(\d)(?=(\d{3})+(?!\d))/g;
  const rep = '$1,';
  let arr = number.toString().split('.');
  arr[0] = arr[0].replace(exp,rep);
  return arr[1] ? arr.join('.'): arr[0];
}


function convertir_number(valor)
{	
	var numero=valor.split(',');
	var valor_final ="";

	for (var i = 0; i <numero.length ; i++) 
	{	
		valor_final=valor_final+numero[i];	
	}
	return valor_final;	
}