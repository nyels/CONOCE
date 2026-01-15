$(document).ready(inicio);

function inicio()
{
	$('#apellido_paterno_contacto').focusout(verificar_apellido_paterno);
	$('#apellido_materno_contacto').focusout(verificar_apellido_materno);
	$('#nombre_contacto').focusout(verificar_nombres_persona);
}

$(document).on('click','.editar_contacto',function(e){

var id_contacto = $(this).val();
$('.actualizar').attr('value',id_contacto);
$('.guardar_contacto').slideUp('fast');
$('.actualizar').slideDown('fast');

		$.ajax({
			url:'metodos/alta_contactos_metodos.php',
			type:'post',
			data:{
				buscar_datos_para_actualizar_y_actualizar:id_contacto,
				buscando_y_actualizar:1
			},
			success:function(data)
			{
				$('#contenedor_lista_contactos').slideUp('fast');
				$('#contenedor_alta_contacto').slideDown('fast');
				
				var datos = JSON.parse(data);
				console.log(datos);
				$('#apellido_paterno_contacto').val(datos.data[0]);
				$('#apellido_materno_contacto').val(datos.data[1]);
				$('#nombre_contacto').val(datos.data[2]);
				switch(datos.data[3])
				{
					case'AGENTE':
					$('#tipo_contacto option:eq(1)').prop('selected',true);
					break;

					case'SUBAGENTE':	
					$('#tipo_contacto option:eq(2)').prop('selected',true);
					break;
					
					case'EMPLEADO':

					$('#tipo_contacto option:eq(3)').prop('selected',true);
					break;
					
					case'CLIENTE DIRECTO':

					$('#tipo_contacto option:eq(4)').prop('selected',true);
					break;
					

				}

				$('#telefono_contacto').val(datos.data[4]);
				$('#correo_contacto').val(datos.data[5]);
				

			}
		});
});

$(document).on('click','.eliminar_contacto',function(e){

var id_contacto = $(this).val();
$('#id_confirmar').attr('value',id_contacto);
$('#titulo_modal_alerta').html('ALERTA');
$('#modal_alerta').modal({show:true});
});

$(document).on('click','#id_confirmar',function(e){

var id_contacto = $(this).val();
	$.ajax({
		url:'metodos/alta_contactos_metodos.php',
		type:'post',
		data:{eliminando_contacto:id_contacto},
		success:function(data)
		{
			if(data.trim()=='1')
			{
				$('#modal_alerta').modal('hide');

				$('.tabla_lista_contactos').DataTable({
					order:[[0,'asc']],
						"destroy":true,
						 "language": 
						 {
					      "emptyTable": "No se encontraron datos"
					    },
					    ajax:
						{
							url:'metodos/alta_contactos_metodos.php',
							type:"post",
							data:function(d)
							{
								d.contactos_lista=3
							}		
						},
						 "columnDefs": [
					        {"className": "dt-center", "targets": "_all"}
					      ],
						"columns":
						[
							{"data":"numero"},
							{"data":"nombre_contacto"},
							{"data":"tipo_contacto"},
							{"data":"telefono"},
							{"data":"correo"},
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



function verificar_apellido_paterno()
{
	var nombre=$('#apellido_paterno_contacto').val();

		if(nombre=='')
		{
			$('#error_apellido_paterno_contacto').slideDown('fast');
			$('#error_apellido_paterno_contacto').html('¡Debes ingresar apellido paterno!');
			
			$('#apellido_paterno_contacto').focus();
			return false;
		}
		else 
		{
			if(!/^([a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\.\-])*$/.test(nombre))
			{
				$('#error_apellido_paterno_contacto').slideDown('fast');
				$('#error_apellido_paterno_contacto').html('¡Solo esta permitido letras!');
				$('#apellido_paterno_contacto').focus();
				return false;	
			}
			else
			{
				$('#apellido_materno_contacto').prop('disabled',false);
				$('#error_apellido_paterno_contacto').slideUp('fast');
				return true;
			}
		}	
	
}

function verificar_apellido_materno()
{
	var nombre=$('#apellido_materno_contacto').val();

	
		if(nombre=='')
		{
			$('#error_apellido_materno_contacto').slideDown('fast');
			$('#error_apellido_materno_contacto').html('¡Debes ingresar apellido materno!');
			
			$('#apellido_materno_contacto').focus();
			return false;
		}
		else 
		{
			if(!/^([a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\.\-])*$/.test(nombre))
			{
				$('#error_apellido_materno_contacto').slideDown('fast');
				$('#error_apellido_materno_contacto').html('¡Solo esta permitido letras!');
				$('#apellido_materno_contacto').focus();
				return false;	
			}
			else
			{
				$('#nombre_contacto').prop('disabled',false);
				$('#error_apellido_materno_contacto').slideUp('fast');
				return true;
			}
		}	
}

function verificar_nombres_persona()
{
			var nombre=$('#nombres_persona').val();
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