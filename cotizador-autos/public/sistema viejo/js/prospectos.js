$(document).ready(inicio);
function inicio()
{
	$('#nombre_asegurado').focusout(verificar_nombre_asegurado);
	$('#apellido_paterno').focusout(verificar_apellido_paterno);
	$('#apellido_materno').focusout(verificar_apellido_materno);

	$('#nombres_persona').focusout(verificar_nombres_persona);


	$('#tipo_prospecto').focusout(verificar_tipo_prospecto);	
	$('#codigo_postal').focusout(verificar_codigo_postal);
	$('#colonia').focusout(verificar_colonia);
	$('#estado').focusout(verificar_estado);



}


	window.addEventListener("load", function() 
	{
		var codigo_postal = document.querySelector('#codigo_postal');
	  	codigo_postal.addEventListener("keypress", soloNumeros, false);
	});

	//Solo permite introducir numeros.
	function soloNumeros(e){
	  var key = window.event ? e.which : e.keyCode;
	  if (key < 48 || key > 57)
	  {
	    e.preventDefault();
	  }
	}

$(document).on('change','#tipo_prospecto',function(e){

var tipo=$('#tipo_prospecto').val();
switch(tipo)
{
	case 'FISICA':
		$('#contenedor_nombre_empresa').slideUp('fast');
		$('#contenedor_resultado_nombres_persona').html('');
		$('#contenedor_resultado').html('');
		$('#error_nombre_asegurado').slideUp('fast');
		$('#nombre_asegurado').val('');		$('#error_apellido_paterno').slideUp('fast');		$('#error_apellido_materno').slideUp('fast');		$('#error_nombres_persona').slideUp('fast');



		$('#contenedor_nombre_empresa').slideUp('fast');
		$('#contenedor_apellido_paterno').slideDown('fast');
		$('#contenedor_apellido_materno').slideDown('fast');
		$('#contenedor_nombres_persona').slideDown('fast');
	break;
	case 'MORAL':

		$('#contenedor_resultado_nombres_persona').html('');
		$('#contenedor_resultado').html('');
		$('#error_nombre_asegurado').slideUp('fast');
		$('#nombre_asegurado').val('');

		$('#error_apellido_paterno').slideUp('fast');
		$('#error_apellido_materno').slideUp('fast');
		$('#error_nombres_persona').slideUp('fast');



		$('#contenedor_apellido_paterno').slideUp('fast');
		$('#contenedor_apellido_materno').slideUp('fast');
		$('#contenedor_nombres_persona').slideUp('fast');
		$('#contenedor_nombre_empresa').slideDown('fast');
	

	break;
}


});

//aqui para alta prospecto
$('.alta_prospecto').on('click',function(e){
document.getElementById('formulario_prospecto').reset();

$('#alerta_correcta_prospecto').slideUp('fast');
$('#alerta_error_prospecto').slideUp('fast');


$('#contenedor_lista_prospectos').slideUp('fast');
$('#contenedor_alta_prospecto').slideDown('fast');

$('contenedor_nombre_empresa').slideUp('fast');
$('#contenedor_apellido_paterno').slideUp('fast');
$('#contenedor_apellido_materno').slideUp('fast');
$('#contenedor_nombres_persona').slideUp('fast');



$('#tipo_prospecto').prop('disabled',false);
$('#tipo_prospecto option:eq(0)').prop('selected',true);
$('#estado option:eq(0)').prop('selected',true);
	$('#contenedor_resultado').html('');

	$('.guardar_prospecto').slideDown('fast');
	$('.actualizar_prospecto').slideUp('fast');


		var uno =$.typeahead({
	    input: '#nombre_asegurado',
	    minLength: 1,
	    maxItem: 20,
	    order: "asc",
	   // href: "https://en.wikipedia.org/?title={{display}}",
	    template: "{{display}} <small style='color:#999;'>{{group}}</small>",
	    source: {
	        prospectos: {
	            ajax: {
	                url: "metodos/prospectos_metodos.php",
	                type:"post",
	                data:{prospectos_lista:2},
	                path:"prospectos.nombre_prospecto",
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
	                text = "<strong>" + result.length + "</strong> prospecto(s) <strong>" + resultCount + '</strong> que contienen "' + query + '"';
	                 $('#resultado_nombre_contacto').val(1);
	                 $('.guardar_prospecto').prop('disabled',true);
	            } 
	            else if (result.length > 0) 
	            {
	                text = '<strong>' + result.length + '</strong> prospecto(s) que contienen "' + query + '"';
	                 $('#resultado_nombre_contacto').val(1);

	                 $('.guardar_prospecto').prop('disabled',true);
	            } 
	            else 
	            {
	                text = 'Puedes registrar el nombre del prospecto, no hay datos guardados con "' + query + '"';
	                 $('#resultado_nombre_contacto').val(0);

	                 $('.guardar_prospecto').prop('disabled',false);
	            }
	            $('#contenedor_resultado').html(text);

	 
	        },
	        onMouseEnter: function (node, a, item, event) {
	 
	            if (item.group === "prospectos") {
	                $(a).append('<span class="flag-chart flag-' + item.display.replace(' ', '-').toLowerCase() + '"></span>');
	            }
	 
	        },
	        onMouseLeave: function (node, a, item, event) {
	 
	            $(a).find('.flag-chart').remove();
	 
	        }
	    }
	});	
});


//aqui el boton de la tabla prospectos
$('.lista_prospectos').on('click',function(e){
	$('#alerta_correcta_prospecto').slideUp('fast');
	$('#alerta_error_prospecto').slideUp('fast');

	$('#contenedor_alta_prospecto').slideUp('fast');

	$('#contenedor_lista_prospectos').slideDown('fast');



	$('.tabla_lista_prospectos').DataTable({
		order:[[0,'asc']],
			"destroy":true,
			 "language": 
			 {
		      "emptyTable": "No se encontraron datos"
		    },
		    ajax:
			{
				url:'metodos/prospectos_metodos.php',
				type:"post",
				data:function(d)
				{
					d.prospectos_lista=1
				}		
			},
			 "columnDefs": [
		        {"className": "dt-center", "targets": "_all"}
		      ],
			"columns":
			[
				{"data":"numero"},
				{"data":"nombre"},
				{"data":"tipo_prospecto"},
				{"data":"codigo_postal"},
				{"data":"colonia"},
				{"data":"estado"},
				{"data":"fecha_alta"},
				{"data":"dio_alta"},
				{"data":"botones"},
				

			]
	});

});


$(document).on('click','.guardar_prospecto',function(e){
$('#alerta_error_prospecto').slideUp('fast');
$('#alerta_correcta_prospecto').slideUp('fast');

var parametros = new FormData(document.getElementById('formulario_prospecto'));
parametros.append('alta_prospecto',1);

if(
verificar_nombre_asegurado()==true &&
verificar_nombres_persona()==true &&
verificar_apellido_paterno()==true &&
verificar_apellido_materno()==true &&
verificar_tipo_prospecto()==true &&
verificar_codigo_postal()==true &&
verificar_colonia()==true &&
verificar_estado()==true
)
{

		$.ajax({
				data:parametros,
				url:'metodos/prospectos_metodos.php',
				type:'post',
				context: this,
				contentType:false,
				processData:false,
				cache:false,
				success:function(data)
				{
					if(data.trim()=='si_hay')
					{
						$('#texto_error_alerta_prospecto').html('¡La persona ya se cuentra registrada. Verifica la información!');
						$('#alerta_error_prospecto').slideDown('fast');
					}
					else if(data.trim()=='1')
					{
						$('#contenedor_apellido_paterno').slideUp('fast');
						$('#contenedor_apellido_materno').slideUp('fast');
						$('#contenedor_nombres_persona').slideUp('fast');
						$('#contenedor_nombre_empresa').slideUp('fast');
						
						$('#contenedor_resultado_nombres_persona').html('');
						$('#contenedor_resultado').html('');
						document.getElementById('formulario_prospecto').reset();
						$('#alerta_error_prospecto').slideUp('fast');
						$('#alerta_correcta_prospecto').slideDown('fast');
						$('#tipo_prospecto option:eq(0)').prop('selected',true);
						$('#estado option:eq(0)').prop('selected',true);

						var uno =$.typeahead({
				    input: '#nombre_asegurado',
				    minLength: 1,
				    maxItem: 20,
				    order: "asc",
				   // href: "https://en.wikipedia.org/?title={{display}}",
				    template: "{{display}} <small style='color:#999;'>{{group}}</small>",
				    source: {
				        prospectos: {
				            ajax: {
				                url: "metodos/prospectos_metodos.php",
				                type:"post",
				                data:{prospectos_lista:2},
				                path:"prospectos.nombre_prospecto",
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
				                text = "<strong>" + result.length + "</strong> prospecto(s) <strong>" + resultCount + '</strong> que contienen "' + query + '"';
				                 $('#resultado_nombre_contacto').val(1);
				                 $('.guardar_prospecto').prop('disabled',true);
				            } 
				            else if (result.length > 0) 
				            {
				                text = '<strong>' + result.length + '</strong> prospecto(s) que contienen "' + query + '"';
				                 $('#resultado_nombre_contacto').val(1);

				                 $('.guardar_prospecto').prop('disabled',true);
				            } 
				            else 
				            {
				                text = 'Puedes registrar el nombre del prospecto, no hay datos guardados con "' + query + '"';
				                 $('#resultado_nombre_contacto').val(0);

				                 $('.guardar_prospecto').prop('disabled',false);
				            }
				            $('#contenedor_resultado').html(text);

				 
				        },
				        onMouseEnter: function (node, a, item, event) {
				 
				            if (item.group === "prospectos") {
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
						$('#alerta_error_prospecto').slideDown('fast');
					}
				}
				
		});
}
else
{
	console.log('error_alta');
}

});


$(document).on('click','.editar_prospectos',function(e){

var datos =$(this).val();
var datos_separados=datos.split('*');

$('.actualizar_prospecto').attr('value',datos);
$('.guardar_prospecto').slideUp('fast');
$('.actualizar_prospecto').slideDown('fast');

		$.ajax({
			url:'metodos/prospectos_metodos.php',
			type:'post',
			data:{
				buscar_datos_para_actualizar_y_actualizar:datos_separados,
				buscando_y_actualizar:1
			},
			success:function(data)
			{
				$('#contenedor_lista_prospectos').slideUp('fast');
				$('#contenedor_alta_prospecto').slideDown('fast');
				
				var datos = JSON.parse(data);
				var i =0;
				
				if(datos.data[3]=='FISICA')
				{
						
						$('#contenedor_apellido_paterno').slideDown('fast');
						$('#contenedor_apellido_materno').slideDown('fast');
						$('#contenedor_nombres_persona').slideDown('fast');
						$('#contenedor_nombre_empresa').slideUp('fast');

					$('#tipo_prospecto  option').each(function()
					{
						
					  if (this.value == datos.data[3]) 
					  {
					  	$('#tipo_prospecto option:eq('+i+')').prop('selected',true);
					  }
					  i++;
					});
					$('#apellido_paterno').val(datos.data[0]);
					$('#apellido_materno').val(datos.data[1]);
					$('#nombres_persona').val(datos.data[2]);
					$('#codigo_postal').val(datos.data[4]);
						$('#colonia').val(datos.data[5]);
						i =0;
					$('#estado  option').each(function()
					{

					if (this.value == datos.data[6]) 
					{
						$('#estado option:eq('+i+')').prop('selected',true);
					}
					i++;
					});
					$('#tipo_prospecto').prop('disabled',true);
				}
				else if(datos.data[1]=='MORAL')
				{
					
					$('#contenedor_apellido_paterno').slideUp('fast');
					$('#contenedor_apellido_materno').slideUp('fast');
					$('#contenedor_nombres_persona').slideUp('fast');
					$('#contenedor_nombre_empresa').slideDown('fast');

					$('#nombre_asegurado').val(datos.data[0]);
					$('#tipo_prospecto  option').each(function()
					{
						
					  if (this.value == datos.data[1]) 
					  {
					  	$('#tipo_prospecto option:eq('+i+')').prop('selected',true);
					  }
					  i++;
					});

					
					$('#codigo_postal').val(datos.data[2]);
					$('#colonia').val(datos.data[3]);
						i =0;
					$('#estado  option').each(function()
					{

					if (this.value == datos.data[4]) 
					{
						$('#estado option:eq('+i+')').prop('selected',true);
					}
					i++;
					});
					$('#tipo_prospecto').prop('disabled',true);
				}
				

			}
		});
});

$(document).on('click','.eliminar_prospectos',function(e){

var id_contacto = $(this).val();
$('#id_confirmar').attr('value',id_contacto);
$('#titulo_modal_alerta').html('ALERTA');
$('#modal_alerta').modal({show:true});
});

$(document).on('click','#id_confirmar',function(e){

var id_contacto = $(this).val();
	$.ajax({
		url:'metodos/prospectos_metodos.php',
		type:'post',
		data:{eliminando_contacto:id_contacto},
		success:function(data)
		{
			if(data.trim()=='1')
			{
				$('#modal_alerta').modal('hide');

				$('.tabla_lista_prospectos').DataTable({
					order:[[0,'asc']],
						"destroy":true,
						 "language": 
						 {
					      "emptyTable": "No se encontraron datos"
					    },
					    ajax:
						{
							url:'metodos/prospectos_metodos.php',
							type:"post",
							data:function(d)
							{
								d.prospectos_lista=1
							}		
						},
						 "columnDefs": [
					        {"className": "dt-center", "targets": "_all"}
					      ],
						"columns":
						[
							{"data":"numero"},
							{"data":"nombre"},
							{"data":"tipo_prospecto"},
							{"data":"codigo_postal"},
							{"data":"colonia"},
							{"data":"estado"},
							{"data":"fecha_alta"},
							{"data":"dio_alta"},
							{"data":"botones"},
							

						]
				});
			}
			else
			{

			}
		}
	});
});



$(document).on('click','.actualizar_prospecto',function(e){

if(

	verificar_apellido_paterno()==true &&
	verificar_apellido_materno()==true &&
	verificar_nombres_persona()==true &&
	verificar_nombre_asegurado()==true &&
	verificar_tipo_prospecto()==true &&
	verificar_codigo_postal()==true &&
	verificar_colonia()==true &&
	verificar_estado()==true
)

	{



var datos =$(this).val();
var id_contacto_actualizar=datos.split('*');

var parametros = new FormData(document.getElementById('formulario_prospecto'));
parametros.append('buscar_datos_para_actualizar_y_actualizar',id_contacto_actualizar);
parametros.append('buscando_y_actualizar',2);

	$.ajax({	

			data:parametros,
			url:'metodos/prospectos_metodos.php',
			type:'post',
			context: this,
				contentType:false,
				processData:false,
				cache:false,
			success:function(data)
			{
				if(data.trim()=='si_hay')
				{
					$('#alerta_correcta_prospecto').slideUp('fast');
					$('#texto_error_alerta_prospecto').html('El nombre ingresado para actualizar, ya esta registrado.Verifica la información');
					$('#alerta_error_prospecto').slideDown('fast');
				}
				if(data.trim()=='1')
				{
					$('#tipo_prospecto').prop('disabled',false);
					$('#contenedor_nombre_empresa').slideUp('fast');
					$('#contenedor_apellido_paterno').slideUp('fast');
					$('#contenedor_apellido_materno').slideUp('fast');
					$('#contenedor_nombres_persona').slideUp('fast');
					$('#alerta_error_prospecto').slideUp('fast');
					
					$('#contenedor_resultado').html('');
						document.getElementById('formulario_prospecto').reset();


					$('#tipo_prospecto option:eq(0)').prop('selected',true);
					$('#estado option:eq(0)').prop('selected',true);
					$('.actualizar_prospecto').slideUp('fast');
					$('.guardar_prospecto').slideDown('fast');
					$('#texto_correcto_alerta_prospecto').html('Registro Actualizado');
					$('#alerta_correcta_prospecto').slideDown('fast');

					var uno =$.typeahead({
				    input: '#nombre_asegurado',
				    minLength: 1,
				    maxItem: 20,
				    order: "asc",
				   // href: "https://en.wikipedia.org/?title={{display}}",
				    template: "{{display}} <small style='color:#999;'>{{group}}</small>",
				    source: {
				        prospectos: {
				            ajax: {
				                url: "metodos/prospectos_metodos.php",
				                type:"post",
				                data:{prospectos_lista:2},
				                path:"prospectos.nombre_prospecto",
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
				                text = "<strong>" + result.length + "</strong> prospecto(s) <strong>" + resultCount + '</strong> que contienen "' + query + '"';
				                 $('#resultado_nombre_contacto').val(1);
				                 $('.guardar_prospecto').prop('disabled',true);
				            } 
				            else if (result.length > 0) 
				            {
				                text = '<strong>' + result.length + '</strong> prospecto(s) que contienen "' + query + '"';
				                 $('#resultado_nombre_contacto').val(1);

				                 $('.guardar_prospecto').prop('disabled',true);
				            } 
				            else 
				            {
				                text = 'Puedes registrar el nombre del prospecto, no hay datos guardados con "' + query + '"';
				                 $('#resultado_nombre_contacto').val(0);

				                 $('.guardar_prospecto').prop('disabled',false);
				            }
				            $('#contenedor_resultado').html(text);

				 
				        },
				        onMouseEnter: function (node, a, item, event) {
				 
				            if (item.group === "prospectos") {
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

				}
			
			}
	});
	}
	else
	{
		console.log('error_actualizar');
	}

});


	function verificar_nombre_asegurado()
{
	var nombre=$('#nombre_asegurado').val();
	var tipo_prospecto=$('#tipo_prospecto').val();
	if(tipo_prospecto=='FISICA')
	{
		return true;
	}
	else
	{
		if(nombre=='')
		{
			$('#error_nombre_asegurado').slideDown('fast');
			$('#error_nombre_asegurado').html('¡Debes ingresar un nombre!');
			
			$('#nombre_asegurado').focus();
			return false;
		}
		else 
		{
			if(!/^([a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\.\-])*$/.test(nombre))
			{
				$('#error_nombre_asegurado').slideDown('fast');
				$('#error_nombre_asegurado').html('¡Solo esta permitido letras!');
				$('#nombre_asegurado').focus();
				return false;	
			}
			else
			{
				$('#error_nombre_asegurado').slideUp('fast');
				return true;
			}
		}
	}
	
}



	function verificar_apellido_paterno()
{
	var nombre=$('#apellido_paterno').val();

	var tipo_prospecto=$('#tipo_prospecto').val();
	if(tipo_prospecto=='MORAL')
	{
		return true;
	}
	else
	{

		if(nombre=='')
		{
			$('#error_apellido_paterno').slideDown('fast');
			$('#error_apellido_paterno').html('¡Debes ingresar apellido paterno!');
			
			$('#apellido_paterno').focus();
			return false;
		}
		else 
		{
			if(!/^([a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\.\-])*$/.test(nombre))
			{
				$('#error_apellido_paterno').slideDown('fast');
				$('#error_apellido_paterno').html('¡Solo esta permitido letras!');
				$('#apellido_paterno').focus();
				return false;	
			}
			else
			{
				$('#error_apellido_paterno').slideUp('fast');
				return true;
			}
		}	
	}
}



	function verificar_apellido_materno()
{
	var nombre=$('#apellido_materno').val();

	var tipo_prospecto=$('#tipo_prospecto').val();
	if(tipo_prospecto=='MORAL')
	{
		return true;
	}
	else
	{
		if(nombre=='')
		{
			$('#error_apellido_materno').slideDown('fast');
			$('#error_apellido_materno').html('¡Debes ingresar apellido materno!');
			
			$('#apellido_materno').focus();
			return false;
		}
		else 
		{
			if(!/^([a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\.\-])*$/.test(nombre))
			{
				$('#error_apellido_materno').slideDown('fast');
				$('#error_apellido_materno').html('¡Solo esta permitido letras!');
				$('#apellido_materno').focus();
				return false;	
			}
			else
			{
				$('#error_apellido_materno').slideUp('fast');
				return true;
			}
		}	
	}
	
}

	function verificar_nombres_persona()
{

	var apellido_paterno_valor=$('#apellido_paterno').val();
	var apellido_materno_valor=$('#apellido_materno').val();
	var nombre=$('#nombres_persona').val();
	var tipo_prospecto=$('#tipo_prospecto').val();
	if(tipo_prospecto=='MORAL')
	{
		return true;
	}
	else
	{
			if(nombre=='')
		{
			$('#error_nombres_persona').slideDown('fast');
			$('#error_nombres_persona').html('¡Debes ingresar nombres!');
			
			$('#nombres_persona').focus();
			return false;
		}
		else 
		{
			if(!/^([a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\.\-])*$/.test(nombre))
			{
				$('#error_nombres_persona').slideDown('fast');
				$('#error_nombres_persona').html('¡Solo esta permitido letras!');
				$('#nombres_persona').focus();
				return false;	
			}
			else
			{
				$('#error_nombres_persona').slideUp('fast');
				return true;
			}
		}
	}
	
}

function verificar_tipo_prospecto()
{
	var tipo_prospecto =$('#tipo_prospecto').val();
	if(tipo_prospecto==0)
	{
		$('#error_tipo_prospecto').html('¡Debes seleccionar un tipo de prospecto!');
		$('#error_tipo_prospecto').slideDown('fast');
		$('#tipo_prospecto').focus();
		return false;
	}
	else
	{
		$('#error_tipo_prospecto').slideUp('fast');
		return true;
	}

}

function verificar_codigo_postal()
{
	var codigo_postal=$('#codigo_postal').val();
	if(codigo_postal=='' ||codigo_postal==0 || codigo_postal=="e")
	{
		$('#error_codigo_postal').slideDown('fast');
		$('#error_codigo_postal').html('¡Debes ingresar un código postal!');
		
		$('#codigo_postal').focus();
		return false;
	}
	else if (parseInt(codigo_postal)<0)
	{
		$('#error_codigo_postal').slideDown('fast');
		$('#error_codigo_postal').html('¡No esta permitido números negativos!');
		$('#codigo_postal').focus();
		return false;
	}
	else
	{
		$('#error_codigo_postal').slideUp('fast');
		return true;
	}
}

function verificar_colonia()
{
	var colonia=$('#colonia').val();
	if(colonia=='')
	{
		$('#error_colonia').slideDown('fast');
		$('#error_colonia').html('¡Debes ingresar una colonia!');
		
		$('#colonia').focus();
		return false;
	}
	else 
	{
		if(!/^([0-9a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\#\-\.])*$/.test(colonia))
		{
			$('#error_colonia').slideDown('fast');
			$('#error_colonia').html('¡No estan permitidos caracteres especialess!');
			$('#colonia').focus();
			return false;	
		}
		else
		{
			$('#error_colonia').slideUp('fast');
				return true;
		}
	}
}

function verificar_estado()
{
	var verificar_estado=$('#estado').val();
	if(verificar_estado=='0')
	{
		$('#error_estado').html('¡Debes seleccionar un estado!');
		$('#error_estado').slideDown('fast');
		$('#estado').focus();
		return false;
	}
	else
	{
		$('#error_estado').slideUp('fast');
		return true;
	}

}
