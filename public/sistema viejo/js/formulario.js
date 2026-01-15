$(document).ready(inicio);

function inicio()
{
    $.ajax({
    		url:'metodos/alta_contactos_metodos.php',
			type:'post',
			data:{
					contactos_lista:2
			},
			success:function(data)
			{
				$('#contactos').html(data);
			}	
    });


    $.ajax({
    		url:'metodos/prospectos_metodos.php',
			type:'post',
			data:{
					prospectos_lista:3
			},
			success:function(data)
			{
				$('#prospectos_asegurados').html(data);
			}	
    });



	$('#contactos').select2({
  
    language: {
    noResults: function (params) {
      return '<span style="text-align:center;color:#b71c1c; font-weight: bold; padding-top: 10px;font-size: 15px;" >No se encontraron resultados. Debes agregar al contacto</span>';
    }
  },
  escapeMarkup: function(markup) {
      return markup;
    },
 });

		$('#prospectos_asegurados').select2({
  
    language: {
    noResults: function (params) {
      return '<span style="text-align:center;color:#b71c1c; font-weight: bold; padding-top: 10px;font-size: 15px;" >No se encontraron resultados. Debes agregar al prospecto</span>';
    }
  },
  escapeMarkup: function(markup) {
      return markup;
    },
 });


	$('#tipo_cotizacion').focusout(verificar_tipo_cotizacion);
	$('#hora_solicitada').focusout(verificar_hora_solicitada);
	
	$('#marca').focusout(verificar_marca);
	$('#descripcion').focusout(verificar_descripcion);
	$('#modelo').focusout(verificar_modelo);
	$('#uso_de_unidad').focusout(verificar_uso_de_unidad);
	$('#tipo_auto').focusout(verificar_tipo_auto);
	$('#carga').focusout(verificar_carga);
	$('#compañia_actual').focusout(verificar_compañia_actual);
	$('#fecha_vigencia').focusout(verificar_fecha_vigencia);
	$('#poliza_a_renovar').focusout(verificar_poliza_a_renovar);
	$('#prima_año').focusout(verificar_prima_año);
	$('#cantidad_aseguradoras').focusout(verificar_cantidad_aseguradoras);
	$('#paquete').focusout(verificar_paquete);
	$('#empresas_opcion1').focusout(verificar_empresas_opcion1);
	$('#empresas_opcion2').focusout(verificar_empresas_opcion2);
	$('#empresas_opcion3').focusout(verificar_empresas_opcion3);
	$('#empresas_opcion4').focusout(verificar_empresas_opcion4);
	$('#empresas_opcion5').focusout(verificar_empresas_opcion5);

	$('#daños_opcion1_selec').focusout(verificar_daños_opcion1_selec);
	$('#daños_opcion2_selec').focusout(verificar_daños_opcion2_selec);
	$('#daños_opcion3_selec').focusout(verificar_daños_opcion3_selec);
	$('#daños_opcion4_selec').focusout(verificar_daños_opcion4_selec);
	$('#daños_opcion5_selec').focusout(verificar_daños_opcion5_selec);

	$('#daños_material_importe_factura_1').focusout(verificar_daños_material_importe_factura_1);
	$('#daños_material_importe_factura_2').focusout(verificar_daños_material_importe_factura_2);
	$('#daños_material_importe_factura_3').focusout(verificar_daños_material_importe_factura_3);
	$('#daños_material_importe_factura_4').focusout(verificar_daños_material_importe_factura_4);
	$('#daños_material_importe_factura_5').focusout(verificar_daños_material_importe_factura_5);

	


	$('#deducible_opcion1').focusout(verificar_deducible_opcion1);
	$('#deducible_opcion2').focusout(verificar_deducible_opcion2);
	$('#deducible_opcion3').focusout(verificar_deducible_opcion3);
	$('#deducible_opcion4').focusout(verificar_deducible_opcion4);
	$('#deducible_opcion5').focusout(verificar_deducible_opcion5);

	$('#cristales_opcion1_selec').focusout(verificar_cristales_opcion1_selec);
	$('#cristales_opcion2_selec').focusout(verificar_cristales_opcion2_selec);
	$('#cristales_opcion3_selec').focusout(verificar_cristales_opcion3_selec);
	$('#cristales_opcion4_selec').focusout(verificar_cristales_opcion4_selec);
	$('#cristales_opcion5_selec').focusout(verificar_cristales_opcion5_selec);

	$('#deducible_rt1').focusout(verificar_deducible_rt_opcion1);
	$('#deducible_rt2').focusout(verificar_deducible_rt_opcion2);
	$('#deducible_rt3').focusout(verificar_deducible_rt_opcion3);
	$('#deducible_rt4').focusout(verificar_deducible_rt_opcion4);
	$('#deducible_rt5').focusout(verificar_deducible_rt_opcion5);

	$('#robo_opcion1_selec').focusout(verificar_robo_opcion1_selec);
	$('#robo_opcion2_selec').focusout(verificar_robo_opcion2_selec);
	$('#robo_opcion3_selec').focusout(verificar_robo_opcion3_selec);
	$('#robo_opcion4_selec').focusout(verificar_robo_opcion4_selec);
	$('#robo_opcion5_selec').focusout(verificar_robo_opcion5_selec);


	$('#robo_importe_factura_1').focusout(verificar_robo_importe_factura_1);
	$('#robo_importe_factura_2').focusout(verificar_robo_importe_factura_2);
	$('#robo_importe_factura_3').focusout(verificar_robo_importe_factura_3);
	$('#robo_importe_factura_4').focusout(verificar_robo_importe_factura_4);
	$('#robo_importe_factura_5').focusout(verificar_robo_importe_factura_5);

	$('#daños_tercero_opcion_1').focusout(verificar_daños_tercero_opcion_1);
	$('#daños_tercero_opcion_2').focusout(verificar_daños_tercero_opcion_2);
	$('#daños_tercero_opcion_3').focusout(verificar_daños_tercero_opcion_3);
	$('#daños_tercero_opcion_4').focusout(verificar_daños_tercero_opcion_4);
	$('#daños_tercero_opcion_5').focusout(verificar_daños_tercero_opcion_5);

	$('#deducible_de_rc_opcion1').focusout(verificar_deducible_de_rc1);
	$('#deducible_de_rc_opcion2').focusout(verificar_deducible_de_rc2);
	$('#deducible_de_rc_opcion3').focusout(verificar_deducible_de_rc3);
	$('#deducible_de_rc_opcion4').focusout(verificar_deducible_de_rc4);
	$('#deducible_de_rc_opcion5').focusout(verificar_deducible_de_rc5);

	$('#gastos_medicos_opcion_1').focusout(verificar_gastos_medicos_opcion_1);
	$('#gastos_medicos_opcion_2').focusout(verificar_gastos_medicos_opcion_2);
	$('#gastos_medicos_opcion_3').focusout(verificar_gastos_medicos_opcion_3);
	$('#gastos_medicos_opcion_4').focusout(verificar_gastos_medicos_opcion_4);
	$('#gastos_medicos_opcion_5').focusout(verificar_gastos_medicos_opcion_5);

	$('#accidente_conducir_opcion_1').focusout(verificar_accidente_conducir_opcion_1);
	$('#accidente_conducir_opcion_2').focusout(verificar_accidente_conducir_opcion_2);
	$('#accidente_conducir_opcion_3').focusout(verificar_accidente_conducir_opcion_3);
	$('#accidente_conducir_opcion_4').focusout(verificar_accidente_conducir_opcion_4);
	$('#accidente_conducir_opcion_5').focusout(verificar_accidente_conducir_opcion_5);

	

	$('#fallecimiento_opcion_1').focusout(verificar_fallecimiento_opcion_1);
	$('#fallecimiento_opcion_2').focusout(verificar_fallecimiento_opcion_2);
	$('#fallecimiento_opcion_3').focusout(verificar_fallecimiento_opcion_3);
	$('#fallecimiento_opcion_4').focusout(verificar_fallecimiento_opcion_4);
	$('#fallecimiento_opcion_5').focusout(verificar_fallecimiento_opcion_5);

	

	$('#asistencia_vial_opcion1_selec').focusout(verificar_asistencia_vial_opcion1_selec);
	$('#asistencia_vial_opcion2_selec').focusout(verificar_asistencia_vial_opcion2_selec);
	$('#asistencia_vial_opcion3_selec').focusout(verificar_asistencia_vial_opcion3_selec);
	$('#asistencia_vial_opcion4_selec').focusout(verificar_asistencia_vial_opcion4_selec);
	$('#asistencia_vial_opcion5_selec').focusout(verificar_asistencia_vial_opcion5_selec);

	$('#proteccion_opcion1_selec').focusout(verificar_proteccion_opcion1_selec);
	$('#proteccion_opcion2_selec').focusout(verificar_proteccion_opcion2_selec);
	$('#proteccion_opcion3_selec').focusout(verificar_proteccion_opcion3_selec);
	$('#proteccion_opcion4_selec').focusout(verificar_proteccion_opcion4_selec);
	$('#proteccion_opcion5_selec').focusout(verificar_proteccion_opcion5_selec);


	$('#daños_carga_opcion_selec_1').focusout(verificar_daños_carga_opcion_selec_1);
	$('#daños_carga_opcion_selec_2').focusout(verificar_daños_carga_opcion_selec_2);
	$('#daños_carga_opcion_selec_3').focusout(verificar_daños_carga_opcion_selec_3);
	$('#daños_carga_opcion_selec_4').focusout(verificar_daños_carga_opcion_selec_4);
	$('#daños_carga_opcion_selec_5').focusout(verificar_daños_carga_opcion_selec_5);

	$('#adaptaciones_opcion_1').focusout(verificar_adaptaciones_opcion_1);
	$('#adaptaciones_opcion_2').focusout(verificar_adaptaciones_opcion_2);
	$('#adaptaciones_opcion_3').focusout(verificar_adaptaciones_opcion_3);
	$('#adaptaciones_opcion_4').focusout(verificar_adaptaciones_opcion_4);
	$('#adaptaciones_opcion_5').focusout(verificar_adaptaciones_opcion_5);

	$('#extension_rc_opcion1').focusout(verificar_extension_rc_opcion1);
	$('#extension_rc_opcion2').focusout(verificar_extension_rc_opcion2);
	$('#extension_rc_opcion3').focusout(verificar_extension_rc_opcion3);
	$('#extension_rc_opcion4').focusout(verificar_extension_rc_opcion4);
	$('#extension_rc_opcion5').focusout(verificar_extension_rc_opcion5);


	
	$('#cobertura_opcion_1').focusout(verificar_cobertura_opcion_1);
	
	$('#cobertura_opcion_1_select').focusout(verificar_cobertura_opcion_1_select);
	$('#cobertura_opcion_2_select').focusout(verificar_cobertura_opcion_2_select);
	$('#cobertura_opcion_3_select').focusout(verificar_cobertura_opcion_3_select);
	$('#cobertura_opcion_4_select').focusout(verificar_cobertura_opcion_4_select);
	$('#cobertura_opcion_5_select').focusout(verificar_cobertura_opcion_5_select);


	$('#cobertura_opcion_2').focusout(verificar_cobertura_opcion_2);
	
	$('#cobertura_opcion_2_1_select').focusout(verificar_cobertura_opcion_2_1_select);
	$('#cobertura_opcion_2_2_select').focusout(verificar_cobertura_opcion_2_2_select);
	$('#cobertura_opcion_2_3_select').focusout(verificar_cobertura_opcion_2_3_select);
	$('#cobertura_opcion_2_4_select').focusout(verificar_cobertura_opcion_2_4_select);
	$('#cobertura_opcion_2_5_select').focusout(verificar_cobertura_opcion_2_5_select);



	$('#forma_de_pago').focusout(verificar_forma_de_pago);

	$('#cantidad_prima_neta_opcion1').focusout(verificar_cantidad_prima_neta_opcion1);
	$('#cantidad_prima_neta_opcion2').focusout(verificar_cantidad_prima_neta_opcion2);
	$('#cantidad_prima_neta_opcion3').focusout(verificar_cantidad_prima_neta_opcion3);
	$('#cantidad_prima_neta_opcion4').focusout(verificar_cantidad_prima_neta_opcion4);
	$('#cantidad_prima_neta_opcion5').focusout(verificar_cantidad_prima_neta_opcion5);

	$('#primer_pago_opcion_1').focusout(verificar_primer_pago_opcion_1);
	$('#primer_pago_opcion_2').focusout(verificar_primer_pago_opcion_2);
	$('#primer_pago_opcion_3').focusout(verificar_primer_pago_opcion_3);
	$('#primer_pago_opcion_4').focusout(verificar_primer_pago_opcion_4);
	$('#primer_pago_opcion_5').focusout(verificar_primer_pago_opcion_5);

	$('#cantidad_total_anual_opcion_1').focusout(verificar_cantidad_total_anual_opcion_1);
	$('#cantidad_total_anual_opcion_2').focusout(verificar_cantidad_total_anual_opcion_2);
	$('#cantidad_total_anual_opcion_3').focusout(verificar_cantidad_total_anual_opcion_3);
	$('#cantidad_total_anual_opcion_4').focusout(verificar_cantidad_total_anual_opcion_4);
	$('#cantidad_total_anual_opcion_5').focusout(verificar_cantidad_total_anual_opcion_5);



	$('#nombre_contacto').focusout(verificar_nombre_contacto);
	$('#tipo_contacto').focusout(verificar_tipo_contacto);
	$('#telefono_contacto').focusout(verificar_telefono_contacto);	
	$('#correo_contacto').focusout(verficando_correo);	

	$('#contactos').focusout(verificando_contactos);

	$('.tabla_lista_contactos').DataTable({
		order:[[0,'asc']],
			"destroy":true,
			 "language": 
			 {
		      "emptyTable": "No se encontraron datos"
		    },
	});
}


window.addEventListener("load", function() 
{
	var telefono_contacto = document.querySelector('#modelo');
  	telefono_contacto.addEventListener("keypress", soloNumeros, false);
});

window.addEventListener("load", function() 
{
var daños_material_importe_factura_1 = document.querySelector('#daños_material_importe_factura_1');
  	daños_material_importe_factura_1.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var daños_material_importe_factura_2 = document.querySelector('#daños_material_importe_factura_2');
  	daños_material_importe_factura_2.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var daños_material_importe_factura_3 = document.querySelector('#daños_material_importe_factura_3');
  	daños_material_importe_factura_3.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var daños_material_importe_factura_4 = document.querySelector('#daños_material_importe_factura_4');
  	daños_material_importe_factura_4.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var daños_material_importe_factura_5 = document.querySelector('#daños_material_importe_factura_5');
  	daños_material_importe_factura_5.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var robo_importe_factura_1 = document.querySelector('#robo_importe_factura_1');
  	robo_importe_factura_1.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var robo_importe_factura_2 = document.querySelector('#robo_importe_factura_2');
  	robo_importe_factura_2.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var robo_importe_factura_3 = document.querySelector('#robo_importe_factura_3');
  	robo_importe_factura_3.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var robo_importe_factura_4 = document.querySelector('#robo_importe_factura_4');
  	robo_importe_factura_4.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var robo_importe_factura_5 = document.querySelector('#robo_importe_factura_5');
  	robo_importe_factura_5.addEventListener("keypress", soloNumeros_decimales, false);
});


window.addEventListener("load", function() 
{
var daños_tercero_opcion_1 = document.querySelector('#daños_tercero_opcion_1');
  	daños_tercero_opcion_1.addEventListener("keypress", soloNumeros_decimales, false);
});


window.addEventListener("load", function() 
{
var daños_tercero_opcion_2 = document.querySelector('#daños_tercero_opcion_2');
  	daños_tercero_opcion_2.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var daños_tercero_opcion_3 = document.querySelector('#daños_tercero_opcion_3');
  	daños_tercero_opcion_3.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var daños_tercero_opcion_4 = document.querySelector('#daños_tercero_opcion_4');
  	daños_tercero_opcion_4.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var daños_tercero_opcion_5 = document.querySelector('#daños_tercero_opcion_5');
  	daños_tercero_opcion_5.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var fallecimiento_opcion_1 = document.querySelector('#fallecimiento_opcion_1');
  	fallecimiento_opcion_1.addEventListener("keypress", soloNumeros_decimales, false);
});


window.addEventListener("load", function() 
{
var fallecimiento_opcion_2 = document.querySelector('#fallecimiento_opcion_2');
  	fallecimiento_opcion_2.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var fallecimiento_opcion_3 = document.querySelector('#fallecimiento_opcion_3');
  	fallecimiento_opcion_3.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var fallecimiento_opcion_4 = document.querySelector('#fallecimiento_opcion_4');
  	fallecimiento_opcion_4.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var fallecimiento_opcion_5 = document.querySelector('#fallecimiento_opcion_5');
  	fallecimiento_opcion_5.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var cantidad_prima_neta_opcion1 = document.querySelector('#cantidad_prima_neta_opcion1');
  	cantidad_prima_neta_opcion1.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var cantidad_prima_neta_opcion2 = document.querySelector('#cantidad_prima_neta_opcion2');
  	cantidad_prima_neta_opcion2.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var cantidad_prima_neta_opcion3 = document.querySelector('#cantidad_prima_neta_opcion3');
  	cantidad_prima_neta_opcion3.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var cantidad_prima_neta_opcion4 = document.querySelector('#cantidad_prima_neta_opcion4');
  	cantidad_prima_neta_opcion4.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var cantidad_prima_neta_opcion5 = document.querySelector('#cantidad_prima_neta_opcion5');
  	cantidad_prima_neta_opcion5.addEventListener("keypress", soloNumeros_decimales, false);
});


window.addEventListener("load", function() 
{
var primer_pago_opcion_1 = document.querySelector('#primer_pago_opcion_1');
  	primer_pago_opcion_1.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var primer_pago_opcion_2 = document.querySelector('#primer_pago_opcion_2');
  	primer_pago_opcion_2.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var primer_pago_opcion_3 = document.querySelector('#primer_pago_opcion_3');
  	primer_pago_opcion_3.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var primer_pago_opcion_4 = document.querySelector('#primer_pago_opcion_4');
  	primer_pago_opcion_4.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var primer_pago_opcion_5 = document.querySelector('#primer_pago_opcion_5');
  	primer_pago_opcion_5.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var gastos_medicos_opcion_1 = document.querySelector('#gastos_medicos_opcion_1');
  	gastos_medicos_opcion_1.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var gastos_medicos_opcion_2 = document.querySelector('#gastos_medicos_opcion_2');
  	gastos_medicos_opcion_2.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var gastos_medicos_opcion_3 = document.querySelector('#gastos_medicos_opcion_3');
  	gastos_medicos_opcion_3.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var gastos_medicos_opcion_4 = document.querySelector('#gastos_medicos_opcion_4');
  	gastos_medicos_opcion_4.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var gastos_medicos_opcion_5 = document.querySelector('#gastos_medicos_opcion_5');
  	gastos_medicos_opcion_5.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var accidente_conducir_opcion_1 = document.querySelector('#accidente_conducir_opcion_1');
  	accidente_conducir_opcion_1.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var accidente_conducir_opcion_2 = document.querySelector('#accidente_conducir_opcion_2');
  	accidente_conducir_opcion_2.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var accidente_conducir_opcion_3 = document.querySelector('#accidente_conducir_opcion_3');
  	accidente_conducir_opcion_3.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var accidente_conducir_opcion_4 = document.querySelector('#accidente_conducir_opcion_4');
  	accidente_conducir_opcion_4.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var accidente_conducir_opcion_5 = document.querySelector('#accidente_conducir_opcion_5');
  	accidente_conducir_opcion_5.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var adaptaciones_opcion_1 = document.querySelector('#adaptaciones_opcion_1');
  	adaptaciones_opcion_1.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var adaptaciones_opcion_2 = document.querySelector('#adaptaciones_opcion_2');
  	adaptaciones_opcion_2.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var adaptaciones_opcion_3 = document.querySelector('#adaptaciones_opcion_3');
  	adaptaciones_opcion_3.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var adaptaciones_opcion_4 = document.querySelector('#adaptaciones_opcion_4');
  	adaptaciones_opcion_4.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var adaptaciones_opcion_5 = document.querySelector('#adaptaciones_opcion_5');
  	adaptaciones_opcion_5.addEventListener("keypress", soloNumeros_decimales, false);
});



window.addEventListener("load", function() 
{
var deducible_de_rc_opcion1 = document.querySelector('#deducible_de_rc_opcion1');
  	deducible_de_rc_opcion1.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var deducible_de_rc_opcion2 = document.querySelector('#deducible_de_rc_opcion2');
  	deducible_de_rc_opcion2.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var deducible_de_rc_opcion3 = document.querySelector('#deducible_de_rc_opcion3');
  	deducible_de_rc_opcion3.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var deducible_de_rc_opcion4 = document.querySelector('#deducible_de_rc_opcion4');
  	deducible_de_rc_opcion4.addEventListener("keypress", soloNumeros_decimales, false);
});
window.addEventListener("load", function() 
{
var deducible_de_rc_opcion5 = document.querySelector('#deducible_de_rc_opcion5');
  	deducible_de_rc_opcion5.addEventListener("keypress", soloNumeros_decimales, false);
});

window.addEventListener("load", function() 
{
var prima_año = document.querySelector('#prima_año');
  	prima_año.addEventListener("keypress", soloNumeros_decimales, false);
});

//Solo permite introducir numeros.
function soloNumeros_decimales(e){
  var key = window.event ? e.which : e.keyCode;
  if ( (key<46 || key>46)&&(key < 48 || key > 57))
  {
    e.preventDefault();
  }
}
function soloNumeros(e)
{
  var key = window.event ? e.which : e.keyCode;
  if (key < 48 || key > 57)
  {
    e.preventDefault();
  }
}

$('.secciones_contacto').on('click',function(e){
	$('.contenedor_card_contacto').slideDown('fast');
	$('.contenedor_card_formulario').slideUp('fast');



});

$('.alta_contacto').on('click',function(e){
	$('#alerta_correcta_contacto').slideUp('fast');
	$('#alerta_error_contacto').slideUp('fast');

	$('#contenedor_alta_contacto').slideDown('fast');

	$('#contenedor_lista_contactos').slideUp('fast');

	$('#contenedor_resultado').html('');
	$('#nombre_contacto').val('');
	$('#tipo_contacto option:eq(0)').prop('selected',true);
	$('#telefono_contacto').val('');
	$('#correo_contacto').val('');

	$('.guardar_contacto').slideDown('fast');
	$('.actualizar').slideUp('fast');
});

$('.lista_contactos').on('click',function(e){
	$('#alerta_correcta_contacto').slideUp('fast');
	$('#alerta_error_contacto').slideUp('fast');


	$('#contenedor_lista_contactos').slideDown('fast');

	$('#contenedor_alta_contacto').slideUp('fast');

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

});

$(document).on('change','#prospectos_asegurados',function(e){

	var id_prospecto = $(this).val();
	if(id_prospecto==0)
	{
		$('#contenedor_general_prospectos').slideUp('fast');
		$('#error_prospectos_asegurados').slideDown('fast');
		$('#error_prospectos_asegurados').html('¡Debe seleccionar un prospecto!');
		$('#prospectos_asegurados').focus();
	}
	else
	{
		$('#error_prospectos_asegurados').slideUp('fast');
		$('#error_prospectos_asegurados').html('¡Debe seleccionar un prospecto!');
		$.ajax({
		url:'metodos/prospectos_metodos.php',
		type:'post',
		data:{
			llenando_informacion_cotizacion:id_prospecto
		},
		success:function(e)
		{
				var datos = JSON.parse(e);
			$('#contenedor_general_prospectos').slideDown('fast');
			if(datos.prospecto[0]=='FISICA')
			{

				$('#apellido_paterno').val(datos.prospecto[1]);
				$('#apellido_materno').val(datos.prospecto[2]);
				$('#nombre_asegurado').val(datos.prospecto[3]);
				$('#codigo_postal').val(datos.prospecto[5]);
				$('#colonia').val(datos.prospecto[6]);
				$('#estado').val(datos.prospecto[7]);

				$('#contenedor_paterno_dato').slideDown('fast');
				$('#contenedor_materno_dato').slideDown('fast');
			}
			else
			{
				$('#nombre_asegurado').val(datos.prospecto[4]);
				$('#codigo_postal').val(datos.prospecto[5]);
				$('#colonia').val(datos.prospecto[6]);
				$('#estado').val(datos.prospecto[7]);
				$('#contenedor_paterno_dato').slideUp('fast');
				$('#contenedor_materno_dato').slideUp('fast');
			}

		}
	});

	}
	
});



$('.fomulario').on('click',function(e){
$('#alerta_correcta_contacto').slideUp('fast');
	$('#alerta_error_contacto').slideUp('fast');


$('#contenedor_alta_contacto').slideDown('fast');
$('#contenedor_lista_contactos').slideUp('fast');

$('.contenedor_card_contacto').slideUp('fast');
$('.contenedor_card_formulario').slideDown('fast');
});


//guardar contactos
$('.guardar_contacto').on('click',function(e){

$('#alerta_correcta_contacto').slideUp('fast');
$('#alerta_error_contacto').slideUp('fast');



	if(verificar_nombre_contacto()==true && verificar_tipo_contacto()==true && verificar_telefono_contacto()==true & verficando_correo()==true)
	{

		var apellido_paterno_contacto=$('#apellido_paterno_contacto').val();
		var apellido_materno_contacto=$('#apellido_materno_contacto').val();
		var nombre_contacto=$('#nombre_contacto').val();
		var tipo_contacto=$('#tipo_contacto').val();
		var telefono_contacto=$('#telefono_contacto').val();
		var correo =$('#correo_contacto').val();

		$.ajax({
			url:'metodos/alta_contactos_metodos.php',
			type:'post',
			data:{
					registrar_contacto:1,
					apellido_paterno_contacto:apellido_paterno_contacto,
					apellido_materno_contacto:apellido_materno_contacto,
					nombre_contacto:nombre_contacto,
					tipo_contacto:tipo_contacto,
					telefono_contacto:telefono_contacto,
					correo:correo

			},
			success:function(data)
			{
				/*if(data.trim()=='te')
				{	
					$('#texto_error_alerta').html('¡El telefono ya se encuentra registrado.Verifica la información');	
					$('#alerta_error_contacto').slideDown('fast');

				}
				else if (data.trim()=='co')
				{

					$('#texto_error_alerta').html('¡El correo ya se encuentra registrado.Verifica la información');	
					$('#alerta_error_contacto').slideDown('fast');
				}
				else */
				if(data.trim()=='si_hay')
				{
					$('#texto_error_alerta').html('Ya se encuentra registrato el contacto. Verifica la informacion');
					$('#alerta_correcta_contacto').slideUp('fast');
					$('#alerta_error_contacto').slideDown('fast');
				}
				else if(data.trim()=='1')
				{
					$('#alerta_error_contacto').slideUp('fast');
					$('#apellido_paterno_contacto').val('');
					$('#apellido_materno_contacto').val('');
					$('#nombre_contacto').val('');
					$('#tipo_contacto option:eq(0)').prop('selected',true);
					$('#telefono_contacto').val('');
					$('#correo_contacto').val('');
					$('#texto_correcto_alerta').html('Registro completo');
					$('#alerta_correcta_contacto').slideDown('fast');
				}
				else
				{

				}
			}

		});
	}
	else
	{
		console.log("error_registro contacto");	
	}

});


$(document).on('click','.actualizar',function(e){

if(verificar_nombre_contacto()==true && verificar_tipo_contacto()==true && verificar_telefono_contacto()==true & verficando_correo()==true)
	{

	var id_contacto_actualizar=$(this).val();
	var nombre_contacto=$('#nombre_contacto').val();
	var tipo_contacto=$('#tipo_contacto').val();
	var telefono_contacto=$('#telefono_contacto').val();
	var correo =$('#correo_contacto').val();

	$.ajax({
			url:'metodos/alta_contactos_metodos.php',
			type:'post',
			data:{
				buscar_datos_para_actualizar_y_actualizar:id_contacto_actualizar,
				buscando_y_actualizar:2,
				nombre_contacto:nombre_contacto,
				tipo_contacto:tipo_contacto,
				telefono_contacto:telefono_contacto,
				correo:correo
			},
			success:function(data)
			{
				if(data.trim()=='1')
				{
					$('#contenedor_resultado').html('');
					$('#apellido_paterno_contacto').val('');
					$('#apellido_materno_contacto').val('');
					$('#nombre_contacto').val('');
					$('#tipo_contacto option:eq(0)').prop('selected',true);
					$('#telefono_contacto').val('');
					$('#correo_contacto').val('');

					$('.actualizar').slideUp('fast');
					$('.guardar_contacto').slideDown('fast');
					$('#texto_correcto_alerta').html('Registro Actualizado');
					$('#alerta_correcta_contacto').slideDown('fast');

					var uno =$.typeahead({
					    input: '#nombre_contacto',
					    minLength: 1,
					    maxItem: 20,
					    order: "asc",
					   // href: "https://en.wikipedia.org/?title={{display}}",
					    template: "{{display}} <small style='color:#999;'>{{group}}</small>",
					    source: {
					        contacto: {
					            ajax: {
					                url: "metodos/alta_contactos_metodos.php",
					                type:"post",
					                data:{contactos_lista:1},
					                path:"contacto.nombre_contacto",
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
					                text = "<strong>" + result.length + "</strong> contacto(s) <strong>" + resultCount + '</strong> que contienen "' + query + '"';
					                 $('#resultado_nombre_contacto').val(1);
					                 $('.guardar_contacto').prop('disabled',true);
					            } 
					            else if (result.length > 0) 
					            {
					                text = '<strong>' + result.length + '</strong> contacto(s) que contienen "' + query + '"';
					                 $('#resultado_nombre_contacto').val(1);

					                 $('.guardar_contacto').prop('disabled',true);
					            } 
					            else 
					            {
					                text = 'Puedes registrar el nombre del contacto, no hay datos guardados con "' + query + '"';
					                 $('#resultado_nombre_contacto').val(0);

					                 $('.guardar_contacto').prop('disabled',false);
					            }
					            $('#contenedor_resultado').html(text);

					 
					        },
					        onMouseEnter: function (node, a, item, event) {
					 
					            if (item.group === "contactos") {
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



$('.guardar').on('click',function(e){
	var contactos = $('#contactos').val();
});

$(document).on('change','#tipo_cotizacion',function(e)
{
	var opcion=$(this).val();
	if(opcion=='NUEVA' || opcion==0)
	{	
		$('#hora_solicitada').prop('disabled',false);
		$('#contenedor_formacion_poliza_renovar').slideUp('fast');
	}
	else if(opcion=='RENOVACION')
	{
		$('#hora_solicitada').val('');
		$('#hora_solicitada').prop('disabled',true);
		$('#contenedor_formacion_poliza_renovar').slideDown('fast');
	}
});

$(document).on('change','#tipo_auto',function(e)
{
	var opcion=$(this).val();
	if(opcion=='AUTO' || opcion=='MOTO' || opcion==0)
	{
		$('#contenedor_descripcion_de_la_carga').slideUp('fast');
		$('#carga option:eq(0)').prop('selected',true);
		
	}
	else
	{
		$('#contenedor_descripcion_de_la_carga').slideDown('fast');
		$('#carga option:eq(0)').prop('selected',true);
	}
});



$(document).on('change','#paquete',function(e)
{


	var cantidad=$('#cantidad_aseguradoras').val();
	var paquete=$('#paquete').val();
	if(cantidad==0|| paquete==0)
	{
			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion2').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);

			$('#error_empresas_opcion1').slideUp('fast');
			$('#error_empresas_opcion2').slideUp('fast');
			$('#error_empresas_opcion3').slideUp('fast');
			$('#error_empresas_opcion4').slideUp('fast');
			$('#error_empresas_opcion5').slideUp('fast');

			$('#daños_opcion1_selec').prop('disabled',true);
			$('#daños_opcion2_selec').prop('disabled',true);
			$('#daños_opcion3_selec').prop('disabled',true);
			$('#daños_opcion4_selec').prop('disabled',true);
			$('#daños_opcion5_selec').prop('disabled',true);

			$('#error_daños_opcion1_selec').slideUp('fast');
			$('#error_daños_opcion2_selec').slideUp('fast');
			$('#error_daños_opcion3_selec').slideUp('fast');
			$('#error_daños_opcion4_selec').slideUp('fast');
			$('#error_daños_opcion5_selec').slideUp('fast');

			$('#daños_material_importe_factura_1').prop('disabled',true);
			$('#daños_material_importe_factura_2').prop('disabled',true);
			$('#daños_material_importe_factura_3').prop('disabled',true);
			$('#daños_material_importe_factura_4').prop('disabled',true);
			$('#daños_material_importe_factura_5').prop('disabled',true);

			$('#error_daños_material_importe_factura_1').slideUp('fast');
			$('#error_daños_material_importe_factura_2').slideUp('fast');
			$('#error_daños_material_importe_factura_3').slideUp('fast');
			$('#error_daños_material_importe_factura_4').slideUp('fast');
			$('#error_daños_material_importe_factura_5').slideUp('fast');


			$('#deducible_opcion1').prop('disabled',true);
			$('#deducible_opcion2').prop('disabled',true);
			$('#deducible_opcion3').prop('disabled',true);
			$('#deducible_opcion4').prop('disabled',true);
			$('#deducible_opcion5').prop('disabled',true);

			$('#error_deducible_opcion1').slideUp('fast');
			$('#error_deducible_opcion2').slideUp('fast');
			$('#error_deducible_opcion3').slideUp('fast');
			$('#error_deducible_opcion4').slideUp('fast');
			$('#error_deducible_opcion5').slideUp('fast');

			$('#cristales_opcion1_selec').prop('disabled',true);
			$('#cristales_opcion2_selec').prop('disabled',true);
			$('#cristales_opcion3_selec').prop('disabled',true);
			$('#cristales_opcion4_selec').prop('disabled',true);
			$('#cristales_opcion5_selec').prop('disabled',true);

			$('#error_cristales_opcion1_selec').slideUp('fast');
			$('#error_cristales_opcion2_selec').slideUp('fast');
			$('#error_cristales_opcion3_selec').slideUp('fast');
			$('#error_cristales_opcion4_selec').slideUp('fast');
			$('#error_cristales_opcion5_selec').slideUp('fast');

			$('#robo_opcion1_selec').prop('disabled',true);	
			$('#robo_opcion2_selec').prop('disabled',true);
			$('#robo_opcion3_selec').prop('disabled',true);
			$('#robo_opcion4_selec').prop('disabled',true);
			$('#robo_opcion5_selec').prop('disabled',true);

			$('#error_robo_opcion1_selec').slideUp('fast');
			$('#error_robo_opcion2_selec').slideUp('fast');
			$('#error_robo_opcion3_selec').slideUp('fast');
			$('#error_robo_opcion4_selec').slideUp('fast');
			$('#error_robo_opcion5_selec').slideUp('fast');

			$('#robo_importe_factura_1').prop('disabled',true);
			$('#robo_importe_factura_2').prop('disabled',true);
			$('#robo_importe_factura_3').prop('disabled',true);
			$('#robo_importe_factura_4').prop('disabled',true);
			$('#robo_importe_factura_5').prop('disabled',true);

			$('#error_robo_importe_factura_1').slideUp('fast');
			$('#error_robo_importe_factura_2').slideUp('fast');
			$('#error_robo_importe_factura_3').slideUp('fast');
			$('#error_robo_importe_factura_4').slideUp('fast');
			$('#error_robo_importe_factura_5').slideUp('fast');

			$('#deducible_rt1').prop('disabled',true);
			$('#deducible_rt2').prop('disabled',true);
			$('#deducible_rt3').prop('disabled',true);
			$('#deducible_rt4').prop('disabled',true);
			$('#deducible_rt5').prop('disabled',true);

			$('#error_deducible_rt1').slideUp('fast');
			$('#error_deducible_rt2').slideUp('fast');
			$('#error_deducible_rt3').slideUp('fast');
			$('#error_deducible_rt4').slideUp('fast');
			$('#error_deducible_rt5').slideUp('fast');


			$('#daños_tercero_opcion_1').prop('disabled',true);
			$('#daños_tercero_opcion_2').prop('disabled',true);
			$('#daños_tercero_opcion_3').prop('disabled',true);
			$('#daños_tercero_opcion_4').prop('disabled',true);
			$('#daños_tercero_opcion_5').prop('disabled',true);

			$('#error_daños_tercero_opcion_1').slideUp('fast');
			$('#error_daños_tercero_opcion_2').slideUp('fast');
			$('#error_daños_tercero_opcion_3').slideUp('fast');
			$('#error_daños_tercero_opcion_4').slideUp('fast');
			$('#error_daños_tercero_opcion_5').slideUp('fast');
			
			$('#deducible_de_rc_opcion1').prop('disabled',true);
			$('#deducible_de_rc_opcion2').prop('disabled',true);
			$('#deducible_de_rc_opcion3').prop('disabled',true);
			$('#deducible_de_rc_opcion4').prop('disabled',true);
			$('#deducible_de_rc_opcion5').prop('disabled',true);

			$('#error_deducible_de_rc_opcion1').slideUp('fast');
			$('#error_deducible_de_rc_opcion2').slideUp('fast');
			$('#error_deducible_de_rc_opcion3').slideUp('fast');		
			$('#error_deducible_de_rc_opcion4').slideUp('fast');
			$('#error_deducible_de_rc_opcion5').slideUp('fast');


			$('#fallecimiento_opcion_1').prop('disabled',true);
			$('#fallecimiento_opcion_2').prop('disabled',true);
			$('#fallecimiento_opcion_3').prop('disabled',true);
			$('#fallecimiento_opcion_4').prop('disabled',true);
			$('#fallecimiento_opcion_5').prop('disabled',true);

			$('#error_fallecimiento_opcion_1').slideUp('fast');
			$('#error_fallecimiento_opcion_2').slideUp('fast');
			$('#error_fallecimiento_opcion_3').slideUp('fast');
			$('#error_fallecimiento_opcion_4').slideUp('fast');
			$('#error_fallecimiento_opcion_5').slideUp('fast');


			$('#gastos_medicos_opcion_1').prop('disabled',true);
			$('#gastos_medicos_opcion_2').prop('disabled',true);
			$('#gastos_medicos_opcion_3').prop('disabled',true);
			$('#gastos_medicos_opcion_4').prop('disabled',true);
			$('#gastos_medicos_opcion_5').prop('disabled',true);

			$('#error_gastos_medicos_opcion_1').slideUp('fast');
			$('#error_gastos_medicos_opcion_2').slideUp('fast');
			$('#error_gastos_medicos_opcion_3').slideUp('fast');
			$('#error_gastos_medicos_opcion_4').slideUp('fast');
			$('#error_gastos_medicos_opcion_5').slideUp('fast');

			$('#accidente_conducir_opcion_1').prop('disabled',true);
			$('#accidente_conducir_opcion_2').prop('disabled',true);
			$('#accidente_conducir_opcion_3').prop('disabled',true);
			$('#accidente_conducir_opcion_4').prop('disabled',true);
			$('#accidente_conducir_opcion_5').prop('disabled',true);
			
			$('#error_accidente_conducir_opcion_1').slideUp('fast');
			$('#error_accidente_conducir_opcion_2').slideUp('fast');
			$('#error_accidente_conducir_opcion_3').slideUp('fast');
			$('#error_accidente_conducir_opcion_4').slideUp('fast');
			$('#error_accidente_conducir_opcion_5').slideUp('fast');

			$('#proteccion_opcion1_selec').prop('disabled',true);
			$('#proteccion_opcion2_selec').prop('disabled',true);
			$('#proteccion_opcion3_selec').prop('disabled',true);
			$('#proteccion_opcion4_selec').prop('disabled',true);
			$('#proteccion_opcion5_selec').prop('disabled',true);

			$('#error_proteccion_opcion1_selec').slideUp('fast');
			$('#error_proteccion_opcion2_selec').slideUp('fast');
			$('#error_proteccion_opcion3_selec').slideUp('fast');
			$('#error_proteccion_opcion4_selec').slideUp('fast');
			$('#error_proteccion_opcion5_selec').slideUp('fast');
			
			
			$('#asistencia_vial_opcion1_selec').prop('disabled',true);	
			$('#asistencia_vial_opcion2_selec').prop('disabled',true);
			$('#asistencia_vial_opcion3_selec').prop('disabled',true);
			$('#asistencia_vial_opcion4_selec').prop('disabled',true);
			$('#asistencia_vial_opcion5_selec').prop('disabled',true);

			$('#error_asistencia_vial_opcion1_selec').slideUp('fast');
			$('#error_asistencia_vial_opcion2_selec').slideUp('fast');
			$('#error_asistencia_vial_opcion3_selec').slideUp('fast');
			$('#error_asistencia_vial_opcion4_selec').slideUp('fast');
			$('#error_asistencia_vial_opcion5_selec').slideUp('fast');
			
			
			$('#daños_carga_opcion_selec_1').prop('disabled',true);
			$('#daños_carga_opcion_selec_2').prop('disabled',true);
			$('#daños_carga_opcion_selec_3').prop('disabled',true);
			$('#daños_carga_opcion_selec_4').prop('disabled',true);
			$('#daños_carga_opcion_selec_5').prop('disabled',true);

			$('#error_daños_carga_opcion_selec_1').slideUp('fast');
			$('#error_daños_carga_opcion_selec_2').slideUp('fast');
			$('#error_daños_carga_opcion_selec_3').slideUp('fast');
			$('#error_daños_carga_opcion_selec_4').slideUp('fast');
			$('#error_daños_carga_opcion_selec_5').slideUp('fast');

			$('#adaptaciones_opcion_1').prop('disabled',true);
			$('#adaptaciones_opcion_2').prop('disabled',true);
			$('#adaptaciones_opcion_3').prop('disabled',true);
			$('#adaptaciones_opcion_4').prop('disabled',true);
			$('#adaptaciones_opcion_5').prop('disabled',true);	

			$('#error_adaptaciones_opcion_1').slideUp('fast');
			$('#error_adaptaciones_opcion_2').slideUp('fast');
			$('#error_adaptaciones_opcion_3').slideUp('fast');
			$('#error_adaptaciones_opcion_4').slideUp('fast');
			$('#error_adaptaciones_opcion_5').slideUp('fast');

			$('#descripcion_tabla').prop('disabled',true);

			$('#extension_rc_opcion1').prop('disabled',true);
			$('#extension_rc_opcion2').prop('disabled',true);
			$('#extension_rc_opcion3').prop('disabled',true);
			$('#extension_rc_opcion4').prop('disabled',true);
			$('#extension_rc_opcion5').prop('disabled',true);

			$('#error_extension_rc_opcion1').slideUp('fast');
			$('#error_extension_rc_opcion2').slideUp('fast');
			$('#error_extension_rc_opcion3').slideUp('fast');
			$('#error_extension_rc_opcion4').slideUp('fast');
			$('#error_extension_rc_opcion5').slideUp('fast');


			$('#cobertura_opcion_1').prop('disabled',true);

			$('#cobertura_opcion_1_select').prop('disabled',true);
			$('#cobertura_opcion_2_select').prop('disabled',true);
			$('#cobertura_opcion_3_select').prop('disabled',true);
			$('#cobertura_opcion_4_select').prop('disabled',true);
			$('#cobertura_opcion_5_select').prop('disabled',true);

			$('#error_cobertura_opcion_1_select').slideUp('fast');
			$('#error_cobertura_opcion_2_select').slideUp('fast');
			$('#error_cobertura_opcion_3_select').slideUp('fast');
			$('#error_cobertura_opcion_4_select').slideUp('fast');		
			$('#error_cobertura_opcion_5_select').slideUp('fast');

			$('#cobertura_opcion_2').prop('disabled',true);

			$('#cobertura_opcion_2_1_select').prop('disabled',true);
			$('#cobertura_opcion_2_2_select').prop('disabled',true);
			$('#cobertura_opcion_2_3_select').prop('disabled',true);
			$('#cobertura_opcion_2_4_select').prop('disabled',true);
			$('#cobertura_opcion_2_5_select').prop('disabled',true);

			$('#error_cobertura_opcion_2').slideUp('fast');
			$('#error_cobertura_opcion_2_2_select').slideUp('fast');
			$('#error_cobertura_opcion_2_3_select').slideUp('fast');
			$('#error_cobertura_opcion_2_4_select').slideUp('fast');
			$('#error_cobertura_opcion_2_5_select').slideUp('fast');

			$('#forma_de_pago').prop('disabled',true);


			$('#cantidad_prima_neta_opcion1').prop('disabled',true);
			$('#cantidad_prima_neta_opcion2').prop('disabled',true);
			$('#cantidad_prima_neta_opcion3').prop('disabled',true);
			$('#cantidad_prima_neta_opcion4').prop('disabled',true);
			$('#cantidad_prima_neta_opcion5').prop('disabled',true);


			$('#error_cantidad_prima_neta_opcion1').slideUp('fast');
			$('#error_cantidad_prima_neta_opcion2').slideUp('fast');
			$('#error_cantidad_prima_neta_opcion3').slideUp('fast');
			$('#error_cantidad_prima_neta_opcion4').slideUp('fast');
			$('#error_cantidad_prima_neta_opcion5').slideUp('fast');
		


			$('#cantidad_total_anual_opcion_1').prop('disabled',true);
			$('#cantidad_total_anual_opcion_2').prop('disabled',true);
			$('#cantidad_total_anual_opcion_3').prop('disabled',true);
			$('#cantidad_total_anual_opcion_4').prop('disabled',true);
			$('#cantidad_total_anual_opcion_5').prop('disabled',true);

			$('#error_cantidad_total_anual_opcion_1').slideUp('fast');
			$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
			$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
			$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
			$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
			
			
			$('#primer_pago_opcion_1').prop('disabled',true);
			$('#primer_pago_opcion_2').prop('disabled',true);
			$('#primer_pago_opcion_3').prop('disabled',true);
			$('#primer_pago_opcion_4').prop('disabled',true);
			$('#primer_pago_opcion_5').prop('disabled',true);


			$('#error_primer_pago_opcion_1').slideUp('fast');
			$('#error_primer_pago_opcion_2').slideUp('fast');
			$('#error_primer_pago_opcion_3').slideUp('fast');
			$('#error_primer_pago_opcion_4').slideUp('fast');
			$('#error_primer_pago_opcion_5').slideUp('fast');

			$('#subsecuente_opcion1').prop('disabled',true);
			$('#subsecuente_opcion2').prop('disabled',true);
			$('#subsecuente_opcion3').prop('disabled',true);
			$('#subsecuente_opcion4').prop('disabled',true);
			$('#subsecuente_opcion5').prop('disabled',true);

			$('.guardar').prop('disabled',true);
			
	}
	else
	{
		$('.guardar').prop('disabled',false);
		$('#descripcion_tabla').prop('disabled',false);
		$('#forma_de_pago').prop('disabled',false);
		$('#cobertura_opcion_1').prop('disabled',false);
		$('#cobertura_opcion_2').prop('disabled',false);
		switch(cantidad)
		{
			case'1':

				$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
					}
				});


				$('#empresas_opcion1').prop('disabled',false);
				$('#empresas_opcion2').prop('disabled',true);
				$('#empresas_opcion3').prop('disabled',true);
				$('#empresas_opcion4').prop('disabled',true);
				$('#empresas_opcion5').prop('disabled',true);
				switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_1').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}
					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);

					if($('#robo_opcion1_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_1').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_1').prop('disabled',true);	
					}	

					$('#deducible_rt1').prop('disabled',false);

					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					
					break;
					
				}
					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_opcion5_selec').prop('disabled',true);

					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);

					$('#deducible_opcion2').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					$('#cristales_opcion3_selec').prop('disabled',true);
					$('#cristales_opcion4_selec').prop('disabled',true);
					$('#cristales_opcion5_selec').prop('disabled',true);


					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_opcion5_selec').prop('disabled',true);


					$('#robo_importe_factura_2').prop('disabled',true);
					$('#robo_importe_factura_3').prop('disabled',true);
					$('#robo_importe_factura_4').prop('disabled',true);
					$('#robo_importe_factura_5').prop('disabled',true);


					$('#deducible_rt2').prop('disabled',true);
					$('#deducible_rt3').prop('disabled',true);
					$('#deducible_rt4').prop('disabled',true);
					$('#deducible_rt5').prop('disabled',true);


					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',true);
					$('#daños_tercero_opcion_3').prop('disabled',true);
					$('#daños_tercero_opcion_4').prop('disabled',true);
					$('#daños_tercero_opcion_5').prop('disabled',true);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',true);
					$('#deducible_de_rc_opcion3').prop('disabled',true);
					$('#deducible_de_rc_opcion4').prop('disabled',true);
					$('#deducible_de_rc_opcion5').prop('disabled',true);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',true);
					$('#fallecimiento_opcion_3').prop('disabled',true);
					$('#fallecimiento_opcion_4').prop('disabled',true);
					$('#fallecimiento_opcion_5').prop('disabled',true);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',true);
					$('#gastos_medicos_opcion_3').prop('disabled',true);
					$('#gastos_medicos_opcion_4').prop('disabled',true);
					$('#gastos_medicos_opcion_5').prop('disabled',true);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',true);
					$('#accidente_conducir_opcion_3').prop('disabled',true);
					$('#accidente_conducir_opcion_4').prop('disabled',true);
					$('#accidente_conducir_opcion_5').prop('disabled',true);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',true);
					$('#proteccion_opcion3_selec').prop('disabled',true);
					$('#proteccion_opcion4_selec').prop('disabled',true);
					$('#proteccion_opcion5_selec').prop('disabled',true);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',true);
					$('#asistencia_vial_opcion3_selec').prop('disabled',true);
					$('#asistencia_vial_opcion4_selec').prop('disabled',true);
					$('#asistencia_vial_opcion5_selec').prop('disabled',true);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',true);
					$('#daños_carga_opcion_selec_3').prop('disabled',true);
					$('#daños_carga_opcion_selec_4').prop('disabled',true);
					$('#daños_carga_opcion_selec_5').prop('disabled',true);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',true);
					$('#adaptaciones_opcion_3').prop('disabled',true);
					$('#adaptaciones_opcion_4').prop('disabled',true);
					$('#adaptaciones_opcion_5').prop('disabled',true);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',true);
					$('#extension_rc_opcion3').prop('disabled',true);
					$('#extension_rc_opcion4').prop('disabled',true);
					$('#extension_rc_opcion5').prop('disabled',true);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',true);
					$('#cobertura_opcion_3_select').prop('disabled',true);
					$('#cobertura_opcion_4_select').prop('disabled',true);
					$('#cobertura_opcion_5_select').prop('disabled',true);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',true);
					$('#cobertura_opcion_2_3_select').prop('disabled',true);
					$('#cobertura_opcion_2_4_select').prop('disabled',true);
					$('#cobertura_opcion_2_5_select').prop('disabled',true);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',true);
					$('#cantidad_prima_neta_opcion3').prop('disabled',true);
					$('#cantidad_prima_neta_opcion4').prop('disabled',true);
					$('#cantidad_prima_neta_opcion5').prop('disabled',true);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',true);
					$('#cantidad_total_anual_opcion_3').prop('disabled',true);
					$('#cantidad_total_anual_opcion_4').prop('disabled',true);
					$('#cantidad_total_anual_opcion_5').prop('disabled',true);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',true);
					$('#primer_pago_opcion_3').prop('disabled',true);
					$('#primer_pago_opcion_4').prop('disabled',true);
					$('#primer_pago_opcion_5').prop('disabled',true);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',true);
					$('#subsecuente_opcion3').prop('disabled',true);
					$('#subsecuente_opcion4').prop('disabled',true);
					$('#subsecuente_opcion5').prop('disabled',true);
											
					
			break;
			case'2':
				$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
						$('#empresas_opcion2').html(data);
					}
				});
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);

					switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',false);
					$('#deducible_opcion2').prop('disabled',false);

					$('#cristales_opcion2_selec').prop('disabled',false);

					$('#robo_opcion2_selec').prop('disabled',false);
					$('#deducible_rt2').prop('disabled',false);

					
					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_1').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}

					if($('#daños_opcion2_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_2').prop('disabled',false);
						$('#robo_importe_factura_2').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_2').prop('disabled',true);
					}


					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);	
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					
					$('#robo_opcion2_selec').prop('disabled',false);	
					if($('#robo_opcion2_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_2').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_2').prop('disabled',true);	
					}
					$('#deducible_rt2').prop('disabled',false);

					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);

					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_importe_factura_2').prop('disabled',true);
					$('#deducible_rt2').prop('disabled',true);

					
					break;
					
				}
					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_opcion5_selec').prop('disabled',true);

					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);

					$('#deducible_opcion3').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);
					$('#cristales_opcion4_selec').prop('disabled',true);
					$('#cristales_opcion5_selec').prop('disabled',true);


					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_opcion5_selec').prop('disabled',true);


					$('#robo_importe_factura_3').prop('disabled',true);
					$('#robo_importe_factura_4').prop('disabled',true);
					$('#robo_importe_factura_5').prop('disabled',true);


					$('#deducible_rt3').prop('disabled',true);
					$('#deducible_rt4').prop('disabled',true);
					$('#deducible_rt5').prop('disabled',true);


					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',false);
					$('#daños_tercero_opcion_3').prop('disabled',true);
					$('#daños_tercero_opcion_4').prop('disabled',true);
					$('#daños_tercero_opcion_5').prop('disabled',true);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',false);
					$('#deducible_de_rc_opcion3').prop('disabled',true);
					$('#deducible_de_rc_opcion4').prop('disabled',true);
					$('#deducible_de_rc_opcion5').prop('disabled',true);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',false);
					$('#fallecimiento_opcion_3').prop('disabled',true);
					$('#fallecimiento_opcion_4').prop('disabled',true);
					$('#fallecimiento_opcion_5').prop('disabled',true);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',false);
					$('#gastos_medicos_opcion_3').prop('disabled',true);
					$('#gastos_medicos_opcion_4').prop('disabled',true);
					$('#gastos_medicos_opcion_5').prop('disabled',true);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',false);
					$('#accidente_conducir_opcion_3').prop('disabled',true);
					$('#accidente_conducir_opcion_4').prop('disabled',true);
					$('#accidente_conducir_opcion_5').prop('disabled',true);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',false);
					$('#proteccion_opcion3_selec').prop('disabled',true);
					$('#proteccion_opcion4_selec').prop('disabled',true);
					$('#proteccion_opcion5_selec').prop('disabled',true);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',false);
					$('#asistencia_vial_opcion3_selec').prop('disabled',true);
					$('#asistencia_vial_opcion4_selec').prop('disabled',true);
					$('#asistencia_vial_opcion5_selec').prop('disabled',true);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',false);
					$('#daños_carga_opcion_selec_3').prop('disabled',true);
					$('#daños_carga_opcion_selec_4').prop('disabled',true);
					$('#daños_carga_opcion_selec_5').prop('disabled',true);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',false);
					$('#adaptaciones_opcion_3').prop('disabled',true);
					$('#adaptaciones_opcion_4').prop('disabled',true);
					$('#adaptaciones_opcion_5').prop('disabled',true);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',false);
					$('#extension_rc_opcion3').prop('disabled',true);
					$('#extension_rc_opcion4').prop('disabled',true);
					$('#extension_rc_opcion5').prop('disabled',true);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',false);
					$('#cobertura_opcion_3_select').prop('disabled',true);
					$('#cobertura_opcion_4_select').prop('disabled',true);
					$('#cobertura_opcion_5_select').prop('disabled',true);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',false);
					$('#cobertura_opcion_2_3_select').prop('disabled',true);
					$('#cobertura_opcion_2_4_select').prop('disabled',true);
					$('#cobertura_opcion_2_5_select').prop('disabled',true);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',false);
					$('#cantidad_prima_neta_opcion3').prop('disabled',true);
					$('#cantidad_prima_neta_opcion4').prop('disabled',true);
					$('#cantidad_prima_neta_opcion5').prop('disabled',true);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',false);
					$('#cantidad_total_anual_opcion_3').prop('disabled',true);
					$('#cantidad_total_anual_opcion_4').prop('disabled',true);
					$('#cantidad_total_anual_opcion_5').prop('disabled',true);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',false);
					$('#primer_pago_opcion_3').prop('disabled',true);
					$('#primer_pago_opcion_4').prop('disabled',true);
					$('#primer_pago_opcion_5').prop('disabled',true);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',false);
					$('#subsecuente_opcion3').prop('disabled',true);
					$('#subsecuente_opcion4').prop('disabled',true);
					$('#subsecuente_opcion5').prop('disabled',true);
			break;

			case'3':



				$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
						$('#empresas_opcion2').html(data);
						$('#empresas_opcion3').html(data);
					}
				});


			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);

					switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',false);
					$('#deducible_opcion2').prop('disabled',false);

					$('#cristales_opcion2_selec').prop('disabled',false);

					$('#robo_opcion2_selec').prop('disabled',false);
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',false);
					$('#deducible_opcion3').prop('disabled',false);

					$('#cristales_opcion3_selec').prop('disabled',false);

					$('#robo_opcion3_selec').prop('disabled',false);
					$('#deducible_rt3').prop('disabled',false);

					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_1').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}
					if($('#daños_opcion2_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_2').prop('disabled',false);
						$('#robo_importe_factura_2').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_2').prop('disabled',true);
					}

					if($('#daños_opcion3_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_3').prop('disabled',false);
						$('#robo_importe_factura_3').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_3').prop('disabled',true);
						$('#robo_importe_factura_3').prop('disabled',true);
					}
					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);	
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					
					$('#robo_opcion2_selec').prop('disabled',false);	
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);
					
					$('#robo_opcion3_selec').prop('disabled',false);
					if($('#robo_opcion3_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_3').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_3').prop('disabled',true);	
					}	
					$('#deducible_rt3').prop('disabled',false);

					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);

					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_importe_factura_2').prop('disabled',true);
					$('#deducible_rt2').prop('disabled',true);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);

					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_importe_factura_3').prop('disabled',true);
					$('#deducible_rt3').prop('disabled',true);

					
					break;
					
				}
					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_opcion5_selec').prop('disabled',true);

					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);

					$('#deducible_opcion4').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);
					$('#cristales_opcion5_selec').prop('disabled',true);


					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_opcion5_selec').prop('disabled',true);


					$('#robo_importe_factura_4').prop('disabled',true);
					$('#robo_importe_factura_5').prop('disabled',true);


					$('#deducible_rt4').prop('disabled',true);
					$('#deducible_rt5').prop('disabled',true);


					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',false);
					$('#daños_tercero_opcion_3').prop('disabled',false);
					$('#daños_tercero_opcion_4').prop('disabled',true);
					$('#daños_tercero_opcion_5').prop('disabled',true);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',false);
					$('#deducible_de_rc_opcion3').prop('disabled',false);
					$('#deducible_de_rc_opcion4').prop('disabled',true);
					$('#deducible_de_rc_opcion5').prop('disabled',true);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',false);
					$('#fallecimiento_opcion_3').prop('disabled',false);
					$('#fallecimiento_opcion_4').prop('disabled',true);
					$('#fallecimiento_opcion_5').prop('disabled',true);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',false);
					$('#gastos_medicos_opcion_3').prop('disabled',false);
					$('#gastos_medicos_opcion_4').prop('disabled',true);
					$('#gastos_medicos_opcion_5').prop('disabled',true);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',false);
					$('#accidente_conducir_opcion_3').prop('disabled',false);
					$('#accidente_conducir_opcion_4').prop('disabled',true);
					$('#accidente_conducir_opcion_5').prop('disabled',true);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',false);
					$('#proteccion_opcion3_selec').prop('disabled',false);
					$('#proteccion_opcion4_selec').prop('disabled',true);
					$('#proteccion_opcion5_selec').prop('disabled',true);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',false);
					$('#asistencia_vial_opcion3_selec').prop('disabled',false);
					$('#asistencia_vial_opcion4_selec').prop('disabled',true);
					$('#asistencia_vial_opcion5_selec').prop('disabled',true);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',false);
					$('#daños_carga_opcion_selec_3').prop('disabled',false);
					$('#daños_carga_opcion_selec_4').prop('disabled',true);
					$('#daños_carga_opcion_selec_5').prop('disabled',true);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',false);
					$('#adaptaciones_opcion_3').prop('disabled',false);
					$('#adaptaciones_opcion_4').prop('disabled',true);
					$('#adaptaciones_opcion_5').prop('disabled',true);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',false);
					$('#extension_rc_opcion3').prop('disabled',false);
					$('#extension_rc_opcion4').prop('disabled',true);
					$('#extension_rc_opcion5').prop('disabled',true);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',false);
					$('#cobertura_opcion_3_select').prop('disabled',false);
					$('#cobertura_opcion_4_select').prop('disabled',true);
					$('#cobertura_opcion_5_select').prop('disabled',true);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',false);
					$('#cobertura_opcion_2_3_select').prop('disabled',false);
					$('#cobertura_opcion_2_4_select').prop('disabled',true);
					$('#cobertura_opcion_2_5_select').prop('disabled',true);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',false);
					$('#cantidad_prima_neta_opcion3').prop('disabled',false);
					$('#cantidad_prima_neta_opcion4').prop('disabled',true);
					$('#cantidad_prima_neta_opcion5').prop('disabled',true);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',false);
					$('#cantidad_total_anual_opcion_3').prop('disabled',false);
					$('#cantidad_total_anual_opcion_4').prop('disabled',true);
					$('#cantidad_total_anual_opcion_5').prop('disabled',true);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',false);
					$('#primer_pago_opcion_3').prop('disabled',false);
					$('#primer_pago_opcion_4').prop('disabled',true);
					$('#primer_pago_opcion_5').prop('disabled',true);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',false);
					$('#subsecuente_opcion3').prop('disabled',false);
					$('#subsecuente_opcion4').prop('disabled',true);
					$('#subsecuente_opcion5').prop('disabled',true);
			break;

			case'4':

				$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
						$('#empresas_opcion2').html(data);
						$('#empresas_opcion3').html(data);
						$('#empresas_opcion4').html(data);

					}
				});


			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);
			$('#empresas_opcion5').prop('disabled',true);

					switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',false);
					$('#deducible_opcion2').prop('disabled',false);

					$('#cristales_opcion2_selec').prop('disabled',false);

					$('#robo_opcion2_selec').prop('disabled',false);
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',false);
					$('#deducible_opcion3').prop('disabled',false);

					$('#cristales_opcion3_selec').prop('disabled',false);

					$('#robo_opcion3_selec').prop('disabled',false);
					$('#deducible_rt3').prop('disabled',false);

					$('#daños_opcion4_selec').prop('disabled',false);
					$('#deducible_opcion4').prop('disabled',false);

					$('#cristales_opcion4_selec').prop('disabled',false);

					$('#robo_opcion4_selec').prop('disabled',false);
					$('#deducible_rt4').prop('disabled',false);

					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_1').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}
					if($('#daños_opcion2_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_2').prop('disabled',false);
						$('#robo_importe_factura_2').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_2').prop('disabled',true);
					}
					if($('#daños_opcion3_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_3').prop('disabled',false);
						$('#robo_importe_factura_3').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_3').prop('disabled',true);
						$('#robo_importe_factura_3').prop('disabled',true);
					}
					if($('#daños_opcion4_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_4').prop('disabled',false);
						$('#robo_importe_factura_4').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_4').prop('disabled',true);
						$('#robo_importe_factura_4').prop('disabled',true);
					}

					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);	
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					
					$('#robo_opcion2_selec').prop('disabled',false);	
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);
					
					$('#robo_opcion3_selec').prop('disabled',false);	
					$('#deducible_rt3').prop('disabled',false);

					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);
					
					$('#robo_opcion4_selec').prop('disabled',false);
					if($('#robo_opcion4_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_4').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_4').prop('disabled',true);	
					}	
					$('#deducible_rt4').prop('disabled',false);

					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);

					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_importe_factura_2').prop('disabled',true);
					$('#deducible_rt2').prop('disabled',true);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);

					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_importe_factura_3').prop('disabled',true);
					$('#deducible_rt3').prop('disabled',true);

					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);

					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_importe_factura_4').prop('disabled',true);
					$('#deducible_rt4').prop('disabled',true);

					
					break;
					
				}
					$('#daños_opcion5_selec').prop('disabled',true);

					$('#daños_material_importe_factura_5').prop('disabled',true);

					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion5_selec').prop('disabled',true);


					$('#robo_opcion5_selec').prop('disabled',true);


					$('#robo_importe_factura_5').prop('disabled',true);


					$('#deducible_rt5').prop('disabled',true);


					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',false);
					$('#daños_tercero_opcion_3').prop('disabled',false);
					$('#daños_tercero_opcion_4').prop('disabled',false);
					$('#daños_tercero_opcion_5').prop('disabled',true);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',false);
					$('#deducible_de_rc_opcion3').prop('disabled',false);
					$('#deducible_de_rc_opcion4').prop('disabled',false);
					$('#deducible_de_rc_opcion5').prop('disabled',true);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',false);
					$('#fallecimiento_opcion_3').prop('disabled',false);
					$('#fallecimiento_opcion_4').prop('disabled',false);
					$('#fallecimiento_opcion_5').prop('disabled',true);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',false);
					$('#gastos_medicos_opcion_3').prop('disabled',false);
					$('#gastos_medicos_opcion_4').prop('disabled',false);
					$('#gastos_medicos_opcion_5').prop('disabled',true);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',false);
					$('#accidente_conducir_opcion_3').prop('disabled',false);
					$('#accidente_conducir_opcion_4').prop('disabled',false);
					$('#accidente_conducir_opcion_5').prop('disabled',true);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',false);
					$('#proteccion_opcion3_selec').prop('disabled',false);
					$('#proteccion_opcion4_selec').prop('disabled',false);
					$('#proteccion_opcion5_selec').prop('disabled',true);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',false);
					$('#asistencia_vial_opcion3_selec').prop('disabled',false);
					$('#asistencia_vial_opcion4_selec').prop('disabled',false);
					$('#asistencia_vial_opcion5_selec').prop('disabled',true);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',false);
					$('#daños_carga_opcion_selec_3').prop('disabled',false);
					$('#daños_carga_opcion_selec_4').prop('disabled',false);
					$('#daños_carga_opcion_selec_5').prop('disabled',true);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',false);
					$('#adaptaciones_opcion_3').prop('disabled',false);
					$('#adaptaciones_opcion_4').prop('disabled',false);
					$('#adaptaciones_opcion_5').prop('disabled',true);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',false);
					$('#extension_rc_opcion3').prop('disabled',false);
					$('#extension_rc_opcion4').prop('disabled',false);
					$('#extension_rc_opcion5').prop('disabled',true);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',false);
					$('#cobertura_opcion_3_select').prop('disabled',false);
					$('#cobertura_opcion_4_select').prop('disabled',false);
					$('#cobertura_opcion_5_select').prop('disabled',true);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',false);
					$('#cobertura_opcion_2_3_select').prop('disabled',false);
					$('#cobertura_opcion_2_4_select').prop('disabled',false);
					$('#cobertura_opcion_2_5_select').prop('disabled',true);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',false);
					$('#cantidad_prima_neta_opcion3').prop('disabled',false);
					$('#cantidad_prima_neta_opcion4').prop('disabled',false);
					$('#cantidad_prima_neta_opcion5').prop('disabled',true);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',false);
					$('#cantidad_total_anual_opcion_3').prop('disabled',false);
					$('#cantidad_total_anual_opcion_4').prop('disabled',false);
					$('#cantidad_total_anual_opcion_5').prop('disabled',true);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',false);
					$('#primer_pago_opcion_3').prop('disabled',false);
					$('#primer_pago_opcion_4').prop('disabled',false);
					$('#primer_pago_opcion_5').prop('disabled',true);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',false);
					$('#subsecuente_opcion3').prop('disabled',false);
					$('#subsecuente_opcion4').prop('disabled',false);
					$('#subsecuente_opcion5').prop('disabled',true);
			break;

			case'5':

			$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
						$('#empresas_opcion2').html(data);
						$('#empresas_opcion3').html(data);
						$('#empresas_opcion4').html(data);
						$('#empresas_opcion5').html(data);

					}
				});

			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);
			$('#empresas_opcion5').prop('disabled',false);

					switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',false);
					$('#deducible_opcion2').prop('disabled',false);

					$('#cristales_opcion2_selec').prop('disabled',false);

					$('#robo_opcion2_selec').prop('disabled',false);
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',false);
					$('#deducible_opcion3').prop('disabled',false);

					$('#cristales_opcion3_selec').prop('disabled',false);

					$('#robo_opcion3_selec').prop('disabled',false);
					$('#deducible_rt3').prop('disabled',false);

					$('#daños_opcion4_selec').prop('disabled',false);
					$('#deducible_opcion4').prop('disabled',false);

					$('#cristales_opcion4_selec').prop('disabled',false);

					$('#robo_opcion4_selec').prop('disabled',false);
					$('#deducible_rt4').prop('disabled',false);

					$('#daños_opcion5_selec').prop('disabled',false);
					$('#deducible_opcion5').prop('disabled',false);

					$('#cristales_opcion5_selec').prop('disabled',false);

					$('#robo_opcion5_selec').prop('disabled',false);
					$('#deducible_rt5').prop('disabled',false);

					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_1').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}
					if($('#daños_opcion2_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_2').prop('disabled',false);
						$('#robo_importe_factura_2').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_2').prop('disabled',true);
					}
					if($('#daños_opcion3_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_3').prop('disabled',false);
						$('#robo_importe_factura_3').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_3').prop('disabled',true);
						$('#robo_importe_factura_3').prop('disabled',true);
					}
					if($('#daños_opcion4_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_4').prop('disabled',false);
						$('#robo_importe_factura_4').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_4').prop('disabled',true);
						$('#robo_importe_factura_4').prop('disabled',true);
					}
					if($('#daños_opcion5_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_5').prop('disabled',false);
						$('#robo_importe_factura_5').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_5').prop('disabled',true);
						$('#robo_importe_factura_5').prop('disabled',true);
					}
					

					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);	
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					
					$('#robo_opcion2_selec').prop('disabled',false);	
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);
					
					$('#robo_opcion3_selec').prop('disabled',false);	
					$('#deducible_rt3').prop('disabled',false);

					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);
					
					$('#robo_opcion4_selec').prop('disabled',false);	
					$('#deducible_rt4').prop('disabled',false);

					$('#daños_opcion5_selec').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion5_selec').prop('disabled',true);
					
					$('#robo_opcion5_selec').prop('disabled',false);
					if($('#robo_opcion5_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_5').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_5').prop('disabled',true);	
					}	
					$('#deducible_rt5').prop('disabled',false);
					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);

					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_importe_factura_2').prop('disabled',true);
					$('#deducible_rt2').prop('disabled',true);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);

					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_importe_factura_3').prop('disabled',true);
					$('#deducible_rt3').prop('disabled',true);

					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);

					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_importe_factura_4').prop('disabled',true);
					$('#deducible_rt4').prop('disabled',true);

					$('#daños_opcion5_selec').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion5_selec').prop('disabled',true);

					$('#robo_opcion5_selec').prop('disabled',true);
					$('#robo_importe_factura_5').prop('disabled',true);
					$('#deducible_rt5').prop('disabled',true);

					
					break;
					
				}

					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',false);
					$('#daños_tercero_opcion_3').prop('disabled',false);
					$('#daños_tercero_opcion_4').prop('disabled',false);
					$('#daños_tercero_opcion_5').prop('disabled',false);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',false);
					$('#deducible_de_rc_opcion3').prop('disabled',false);
					$('#deducible_de_rc_opcion4').prop('disabled',false);
					$('#deducible_de_rc_opcion5').prop('disabled',false);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',false);
					$('#fallecimiento_opcion_3').prop('disabled',false);
					$('#fallecimiento_opcion_4').prop('disabled',false);
					$('#fallecimiento_opcion_5').prop('disabled',false);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',false);
					$('#gastos_medicos_opcion_3').prop('disabled',false);
					$('#gastos_medicos_opcion_4').prop('disabled',false);
					$('#gastos_medicos_opcion_5').prop('disabled',false);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',false);
					$('#accidente_conducir_opcion_3').prop('disabled',false);
					$('#accidente_conducir_opcion_4').prop('disabled',false);
					$('#accidente_conducir_opcion_5').prop('disabled',false);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',false);
					$('#proteccion_opcion3_selec').prop('disabled',false);
					$('#proteccion_opcion4_selec').prop('disabled',false);
					$('#proteccion_opcion5_selec').prop('disabled',false);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',false);
					$('#asistencia_vial_opcion3_selec').prop('disabled',false);
					$('#asistencia_vial_opcion4_selec').prop('disabled',false);
					$('#asistencia_vial_opcion5_selec').prop('disabled',false);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',false);
					$('#daños_carga_opcion_selec_3').prop('disabled',false);
					$('#daños_carga_opcion_selec_4').prop('disabled',false);
					$('#daños_carga_opcion_selec_5').prop('disabled',false);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',false);
					$('#adaptaciones_opcion_3').prop('disabled',false);
					$('#adaptaciones_opcion_4').prop('disabled',false);
					$('#adaptaciones_opcion_5').prop('disabled',false);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',false);
					$('#extension_rc_opcion3').prop('disabled',false);
					$('#extension_rc_opcion4').prop('disabled',false);
					$('#extension_rc_opcion5').prop('disabled',false);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',false);
					$('#cobertura_opcion_3_select').prop('disabled',false);
					$('#cobertura_opcion_4_select').prop('disabled',false);
					$('#cobertura_opcion_5_select').prop('disabled',false);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',false);
					$('#cobertura_opcion_2_3_select').prop('disabled',false);
					$('#cobertura_opcion_2_4_select').prop('disabled',false);
					$('#cobertura_opcion_2_5_select').prop('disabled',false);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',false);
					$('#cantidad_prima_neta_opcion3').prop('disabled',false);
					$('#cantidad_prima_neta_opcion4').prop('disabled',false);
					$('#cantidad_prima_neta_opcion5').prop('disabled',false);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',false);
					$('#cantidad_total_anual_opcion_3').prop('disabled',false);
					$('#cantidad_total_anual_opcion_4').prop('disabled',false);
					$('#cantidad_total_anual_opcion_5').prop('disabled',false);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',false);
					$('#primer_pago_opcion_3').prop('disabled',false);
					$('#primer_pago_opcion_4').prop('disabled',false);
					$('#primer_pago_opcion_5').prop('disabled',false);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',false);
					$('#subsecuente_opcion3').prop('disabled',false);
					$('#subsecuente_opcion4').prop('disabled',false);
					$('#subsecuente_opcion5').prop('disabled',false);
			break;
		}
	}
	
});

$(document).on('change','#cantidad_aseguradoras',function(e)
{

	var cantidad=$(this).val();
	var paquete=$('#paquete').val();
	if(cantidad==0|| paquete==0)
	{
			$('#empresas_opcion1 option:eq(0)').prop('selected',true);
			$('#empresas_opcion2 option:eq(0)').prop('selected',true);
			$('#empresas_opcion3 option:eq(0)').prop('selected',true);
			$('#empresas_opcion4 option:eq(0)').prop('selected',true);
			$('#empresas_opcion5 option:eq(0)').prop('selected',true);

			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion2').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);

			$('#error_empresas_opcion1').slideUp('fast');
			$('#error_empresas_opcion2').slideUp('fast');
			$('#error_empresas_opcion3').slideUp('fast');
			$('#error_empresas_opcion4').slideUp('fast');
			$('#error_empresas_opcion5').slideUp('fast');

			$('#daños_opcion1_selec').prop('disabled',true);
			$('#daños_opcion2_selec').prop('disabled',true);
			$('#daños_opcion3_selec').prop('disabled',true);
			$('#daños_opcion4_selec').prop('disabled',true);
			$('#daños_opcion5_selec').prop('disabled',true);

			$('#error_daños_opcion1_selec').slideUp('fast');
			$('#error_daños_opcion2_selec').slideUp('fast');
			$('#error_daños_opcion3_selec').slideUp('fast');
			$('#error_daños_opcion4_selec').slideUp('fast');
			$('#error_daños_opcion5_selec').slideUp('fast');

			$('#daños_material_importe_factura_1').prop('disabled',true);
			$('#daños_material_importe_factura_2').prop('disabled',true);
			$('#daños_material_importe_factura_3').prop('disabled',true);
			$('#daños_material_importe_factura_4').prop('disabled',true);
			$('#daños_material_importe_factura_5').prop('disabled',true);

			$('#error_daños_material_importe_factura_1').slideUp('fast');
			$('#error_daños_material_importe_factura_2').slideUp('fast');
			$('#error_daños_material_importe_factura_3').slideUp('fast');
			$('#error_daños_material_importe_factura_4').slideUp('fast');
			$('#error_daños_material_importe_factura_5').slideUp('fast');


			$('#deducible_opcion1').prop('disabled',true);
			$('#deducible_opcion2').prop('disabled',true);
			$('#deducible_opcion3').prop('disabled',true);
			$('#deducible_opcion4').prop('disabled',true);
			$('#deducible_opcion5').prop('disabled',true);

			$('#error_deducible_opcion1').slideUp('fast');
			$('#error_deducible_opcion2').slideUp('fast');
			$('#error_deducible_opcion3').slideUp('fast');
			$('#error_deducible_opcion4').slideUp('fast');
			$('#error_deducible_opcion5').slideUp('fast');

			$('#cristales_opcion1_selec').prop('disabled',true);
			$('#cristales_opcion2_selec').prop('disabled',true);
			$('#cristales_opcion3_selec').prop('disabled',true);
			$('#cristales_opcion4_selec').prop('disabled',true);
			$('#cristales_opcion5_selec').prop('disabled',true);

			$('#error_cristales_opcion1_selec').slideUp('fast');
			$('#error_cristales_opcion2_selec').slideUp('fast');
			$('#error_cristales_opcion3_selec').slideUp('fast');
			$('#error_cristales_opcion4_selec').slideUp('fast');
			$('#error_cristales_opcion5_selec').slideUp('fast');

			$('#robo_opcion1_selec').prop('disabled',true);	
			$('#robo_opcion2_selec').prop('disabled',true);
			$('#robo_opcion3_selec').prop('disabled',true);
			$('#robo_opcion4_selec').prop('disabled',true);
			$('#robo_opcion5_selec').prop('disabled',true);

			$('#error_robo_opcion1_selec').slideUp('fast');
			$('#error_robo_opcion2_selec').slideUp('fast');
			$('#error_robo_opcion3_selec').slideUp('fast');
			$('#error_robo_opcion4_selec').slideUp('fast');
			$('#error_robo_opcion5_selec').slideUp('fast');

			$('#robo_importe_factura_1').prop('disabled',true);
			$('#robo_importe_factura_2').prop('disabled',true);
			$('#robo_importe_factura_3').prop('disabled',true);
			$('#robo_importe_factura_4').prop('disabled',true);
			$('#robo_importe_factura_5').prop('disabled',true);

			$('#error_robo_importe_factura_1').slideUp('fast');
			$('#error_robo_importe_factura_2').slideUp('fast');
			$('#error_robo_importe_factura_3').slideUp('fast');
			$('#error_robo_importe_factura_4').slideUp('fast');
			$('#error_robo_importe_factura_5').slideUp('fast');

			$('#deducible_rt1').prop('disabled',true);
			$('#deducible_rt2').prop('disabled',true);
			$('#deducible_rt3').prop('disabled',true);
			$('#deducible_rt4').prop('disabled',true);
			$('#deducible_rt5').prop('disabled',true);

			$('#error_deducible_rt1').slideUp('fast');
			$('#error_deducible_rt2').slideUp('fast');
			$('#error_deducible_rt3').slideUp('fast');
			$('#error_deducible_rt4').slideUp('fast');
			$('#error_deducible_rt5').slideUp('fast');


			$('#daños_tercero_opcion_1').prop('disabled',true);
			$('#daños_tercero_opcion_2').prop('disabled',true);
			$('#daños_tercero_opcion_3').prop('disabled',true);
			$('#daños_tercero_opcion_4').prop('disabled',true);
			$('#daños_tercero_opcion_5').prop('disabled',true);

			$('#error_daños_tercero_opcion_1').slideUp('fast');
			$('#error_daños_tercero_opcion_2').slideUp('fast');
			$('#error_daños_tercero_opcion_3').slideUp('fast');
			$('#error_daños_tercero_opcion_4').slideUp('fast');
			$('#error_daños_tercero_opcion_5').slideUp('fast');
			
			$('#deducible_de_rc_opcion1').prop('disabled',true);
			$('#deducible_de_rc_opcion2').prop('disabled',true);
			$('#deducible_de_rc_opcion3').prop('disabled',true);
			$('#deducible_de_rc_opcion4').prop('disabled',true);
			$('#deducible_de_rc_opcion5').prop('disabled',true);

			$('#error_deducible_de_rc_opcion1').slideUp('fast');
			$('#error_deducible_de_rc_opcion2').slideUp('fast');
			$('#error_deducible_de_rc_opcion3').slideUp('fast');		
			$('#error_deducible_de_rc_opcion4').slideUp('fast');
			$('#error_deducible_de_rc_opcion5').slideUp('fast');


			$('#fallecimiento_opcion_1').prop('disabled',true);
			$('#fallecimiento_opcion_2').prop('disabled',true);
			$('#fallecimiento_opcion_3').prop('disabled',true);
			$('#fallecimiento_opcion_4').prop('disabled',true);
			$('#fallecimiento_opcion_5').prop('disabled',true);

			$('#error_fallecimiento_opcion_1').slideUp('fast');
			$('#error_fallecimiento_opcion_2').slideUp('fast');
			$('#error_fallecimiento_opcion_3').slideUp('fast');
			$('#error_fallecimiento_opcion_4').slideUp('fast');
			$('#error_fallecimiento_opcion_5').slideUp('fast');


			$('#gastos_medicos_opcion_1').prop('disabled',true);
			$('#gastos_medicos_opcion_2').prop('disabled',true);
			$('#gastos_medicos_opcion_3').prop('disabled',true);
			$('#gastos_medicos_opcion_4').prop('disabled',true);
			$('#gastos_medicos_opcion_5').prop('disabled',true);

			$('#error_gastos_medicos_opcion_1').slideUp('fast');
			$('#error_gastos_medicos_opcion_2').slideUp('fast');
			$('#error_gastos_medicos_opcion_3').slideUp('fast');
			$('#error_gastos_medicos_opcion_4').slideUp('fast');
			$('#error_gastos_medicos_opcion_5').slideUp('fast');

			$('#accidente_conducir_opcion_1').prop('disabled',true);
			$('#accidente_conducir_opcion_2').prop('disabled',true);
			$('#accidente_conducir_opcion_3').prop('disabled',true);
			$('#accidente_conducir_opcion_4').prop('disabled',true);
			$('#accidente_conducir_opcion_5').prop('disabled',true);
			
			$('#error_accidente_conducir_opcion_1').slideUp('fast');
			$('#error_accidente_conducir_opcion_2').slideUp('fast');
			$('#error_accidente_conducir_opcion_3').slideUp('fast');
			$('#error_accidente_conducir_opcion_4').slideUp('fast');
			$('#error_accidente_conducir_opcion_5').slideUp('fast');

			$('#proteccion_opcion1_selec').prop('disabled',true);
			$('#proteccion_opcion2_selec').prop('disabled',true);
			$('#proteccion_opcion3_selec').prop('disabled',true);
			$('#proteccion_opcion4_selec').prop('disabled',true);
			$('#proteccion_opcion5_selec').prop('disabled',true);

			$('#error_proteccion_opcion1_selec').slideUp('fast');
			$('#error_proteccion_opcion2_selec').slideUp('fast');
			$('#error_proteccion_opcion3_selec').slideUp('fast');
			$('#error_proteccion_opcion4_selec').slideUp('fast');
			$('#error_proteccion_opcion5_selec').slideUp('fast');
			
			
			$('#asistencia_vial_opcion1_selec').prop('disabled',true);	
			$('#asistencia_vial_opcion2_selec').prop('disabled',true);
			$('#asistencia_vial_opcion3_selec').prop('disabled',true);
			$('#asistencia_vial_opcion4_selec').prop('disabled',true);
			$('#asistencia_vial_opcion5_selec').prop('disabled',true);

			$('#error_asistencia_vial_opcion1_selec').slideUp('fast');
			$('#error_asistencia_vial_opcion2_selec').slideUp('fast');
			$('#error_asistencia_vial_opcion3_selec').slideUp('fast');
			$('#error_asistencia_vial_opcion4_selec').slideUp('fast');
			$('#error_asistencia_vial_opcion5_selec').slideUp('fast');
			
			
			$('#daños_carga_opcion_selec_1').prop('disabled',true);
			$('#daños_carga_opcion_selec_2').prop('disabled',true);
			$('#daños_carga_opcion_selec_3').prop('disabled',true);
			$('#daños_carga_opcion_selec_4').prop('disabled',true);
			$('#daños_carga_opcion_selec_5').prop('disabled',true);

			$('#error_daños_carga_opcion_selec_1').slideUp('fast');
			$('#error_daños_carga_opcion_selec_2').slideUp('fast');
			$('#error_daños_carga_opcion_selec_3').slideUp('fast');
			$('#error_daños_carga_opcion_selec_4').slideUp('fast');
			$('#error_daños_carga_opcion_selec_5').slideUp('fast');

			$('#adaptaciones_opcion_1').prop('disabled',true);
			$('#adaptaciones_opcion_2').prop('disabled',true);
			$('#adaptaciones_opcion_3').prop('disabled',true);
			$('#adaptaciones_opcion_4').prop('disabled',true);
			$('#adaptaciones_opcion_5').prop('disabled',true);	

			$('#error_adaptaciones_opcion_1').slideUp('fast');
			$('#error_adaptaciones_opcion_2').slideUp('fast');
			$('#error_adaptaciones_opcion_3').slideUp('fast');
			$('#error_adaptaciones_opcion_4').slideUp('fast');
			$('#error_adaptaciones_opcion_5').slideUp('fast');

			$('#descripcion_tabla').prop('disabled',true);

			$('#extension_rc_opcion1').prop('disabled',true);
			$('#extension_rc_opcion2').prop('disabled',true);
			$('#extension_rc_opcion3').prop('disabled',true);
			$('#extension_rc_opcion4').prop('disabled',true);
			$('#extension_rc_opcion5').prop('disabled',true);

			$('#error_extension_rc_opcion1').slideUp('fast');
			$('#error_extension_rc_opcion2').slideUp('fast');
			$('#error_extension_rc_opcion3').slideUp('fast');
			$('#error_extension_rc_opcion4').slideUp('fast');
			$('#error_extension_rc_opcion5').slideUp('fast');


			$('#cobertura_opcion_1').prop('disabled',true);

			$('#cobertura_opcion_1_select').prop('disabled',true);
			$('#cobertura_opcion_2_select').prop('disabled',true);
			$('#cobertura_opcion_3_select').prop('disabled',true);
			$('#cobertura_opcion_4_select').prop('disabled',true);
			$('#cobertura_opcion_5_select').prop('disabled',true);

			$('#error_cobertura_opcion_1_select').slideUp('fast');
			$('#error_cobertura_opcion_2_select').slideUp('fast');
			$('#error_cobertura_opcion_3_select').slideUp('fast');
			$('#error_cobertura_opcion_4_select').slideUp('fast');		
			$('#error_cobertura_opcion_5_select').slideUp('fast');

			$('#cobertura_opcion_2').prop('disabled',true);

			$('#cobertura_opcion_2_1_select').prop('disabled',true);
			$('#cobertura_opcion_2_2_select').prop('disabled',true);
			$('#cobertura_opcion_2_3_select').prop('disabled',true);
			$('#cobertura_opcion_2_4_select').prop('disabled',true);
			$('#cobertura_opcion_2_5_select').prop('disabled',true);

			$('#error_cobertura_opcion_2').slideUp('fast');
			$('#error_cobertura_opcion_2_2_select').slideUp('fast');
			$('#error_cobertura_opcion_2_3_select').slideUp('fast');
			$('#error_cobertura_opcion_2_4_select').slideUp('fast');
			$('#error_cobertura_opcion_2_5_select').slideUp('fast');

			$('#forma_de_pago').prop('disabled',true);


			$('#cantidad_prima_neta_opcion1').prop('disabled',true);
			$('#cantidad_prima_neta_opcion2').prop('disabled',true);
			$('#cantidad_prima_neta_opcion3').prop('disabled',true);
			$('#cantidad_prima_neta_opcion4').prop('disabled',true);
			$('#cantidad_prima_neta_opcion5').prop('disabled',true);


			$('#error_cantidad_prima_neta_opcion1').slideUp('fast');
			$('#error_cantidad_prima_neta_opcion2').slideUp('fast');
			$('#error_cantidad_prima_neta_opcion3').slideUp('fast');
			$('#error_cantidad_prima_neta_opcion4').slideUp('fast');
			$('#error_cantidad_prima_neta_opcion5').slideUp('fast');
		


			$('#cantidad_total_anual_opcion_1').prop('disabled',true);
			$('#cantidad_total_anual_opcion_2').prop('disabled',true);
			$('#cantidad_total_anual_opcion_3').prop('disabled',true);
			$('#cantidad_total_anual_opcion_4').prop('disabled',true);
			$('#cantidad_total_anual_opcion_5').prop('disabled',true);

			$('#error_cantidad_total_anual_opcion_1').slideUp('fast');
			$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
			$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
			$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
			$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
			
			
			$('#primer_pago_opcion_1').prop('disabled',true);
			$('#primer_pago_opcion_2').prop('disabled',true);
			$('#primer_pago_opcion_3').prop('disabled',true);
			$('#primer_pago_opcion_4').prop('disabled',true);
			$('#primer_pago_opcion_5').prop('disabled',true);


			$('#error_primer_pago_opcion_1').slideUp('fast');
			$('#error_primer_pago_opcion_2').slideUp('fast');
			$('#error_primer_pago_opcion_3').slideUp('fast');
			$('#error_primer_pago_opcion_4').slideUp('fast');
			$('#error_primer_pago_opcion_5').slideUp('fast');

			$('#subsecuente_opcion1').prop('disabled',true);
			$('#subsecuente_opcion2').prop('disabled',true);
			$('#subsecuente_opcion3').prop('disabled',true);
			$('#subsecuente_opcion4').prop('disabled',true);
			$('#subsecuente_opcion5').prop('disabled',true);

			$('.guardar').prop('disabled',true);
			
	}
	else 
	{
		$('.guardar').prop('disabled',false);
		$('#descripcion_tabla').prop('disabled',false);
		$('#forma_de_pago').prop('disabled',false);
		$('#cobertura_opcion_1').prop('disabled',false);
		$('#cobertura_opcion_2').prop('disabled',false);
		var i=0;
		switch(cantidad)
		{
			case'1':
				$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
					}
				});
				
				$('#empresas_opcion2 option:eq(0)').prop('selected',true);
				$('#empresas_opcion3 option:eq(0)').prop('selected',true);
				$('#empresas_opcion4 option:eq(0)').prop('selected',true);
				$('#empresas_opcion5 option:eq(0)').prop('selected',true);
			
				
				$('#empresas_opcion1').prop('disabled',false);
				$('#empresas_opcion2').prop('disabled',true);
				$('#empresas_opcion3').prop('disabled',true);
				$('#empresas_opcion4').prop('disabled',true);
				$('#empresas_opcion5').prop('disabled',true);
				switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_1').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}



					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);	
					if($('#robo_opcion1_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_1').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_1').prop('disabled',true);	
					}
					$('#deducible_rt1').prop('disabled',false);

					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					
					break;
					
				}

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_opcion5_selec').prop('disabled',true);

					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);

					$('#deducible_opcion2').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					$('#cristales_opcion3_selec').prop('disabled',true);
					$('#cristales_opcion4_selec').prop('disabled',true);
					$('#cristales_opcion5_selec').prop('disabled',true);


					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_opcion5_selec').prop('disabled',true);


					$('#robo_importe_factura_2').prop('disabled',true);
					$('#robo_importe_factura_3').prop('disabled',true);
					$('#robo_importe_factura_4').prop('disabled',true);
					$('#robo_importe_factura_5').prop('disabled',true);


					$('#deducible_rt2').prop('disabled',true);
					$('#deducible_rt3').prop('disabled',true);
					$('#deducible_rt4').prop('disabled',true);
					$('#deducible_rt5').prop('disabled',true);


					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',true);
					$('#daños_tercero_opcion_3').prop('disabled',true);
					$('#daños_tercero_opcion_4').prop('disabled',true);
					$('#daños_tercero_opcion_5').prop('disabled',true);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',true);
					$('#deducible_de_rc_opcion3').prop('disabled',true);
					$('#deducible_de_rc_opcion4').prop('disabled',true);
					$('#deducible_de_rc_opcion5').prop('disabled',true);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',true);
					$('#fallecimiento_opcion_3').prop('disabled',true);
					$('#fallecimiento_opcion_4').prop('disabled',true);
					$('#fallecimiento_opcion_5').prop('disabled',true);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',true);
					$('#gastos_medicos_opcion_3').prop('disabled',true);
					$('#gastos_medicos_opcion_4').prop('disabled',true);
					$('#gastos_medicos_opcion_5').prop('disabled',true);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',true);
					$('#accidente_conducir_opcion_3').prop('disabled',true);
					$('#accidente_conducir_opcion_4').prop('disabled',true);
					$('#accidente_conducir_opcion_5').prop('disabled',true);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',true);
					$('#proteccion_opcion3_selec').prop('disabled',true);
					$('#proteccion_opcion4_selec').prop('disabled',true);
					$('#proteccion_opcion5_selec').prop('disabled',true);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',true);
					$('#asistencia_vial_opcion3_selec').prop('disabled',true);
					$('#asistencia_vial_opcion4_selec').prop('disabled',true);
					$('#asistencia_vial_opcion5_selec').prop('disabled',true);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',true);
					$('#daños_carga_opcion_selec_3').prop('disabled',true);
					$('#daños_carga_opcion_selec_4').prop('disabled',true);
					$('#daños_carga_opcion_selec_5').prop('disabled',true);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',true);
					$('#adaptaciones_opcion_3').prop('disabled',true);
					$('#adaptaciones_opcion_4').prop('disabled',true);
					$('#adaptaciones_opcion_5').prop('disabled',true);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',true);
					$('#extension_rc_opcion3').prop('disabled',true);
					$('#extension_rc_opcion4').prop('disabled',true);
					$('#extension_rc_opcion5').prop('disabled',true);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',true);
					$('#cobertura_opcion_3_select').prop('disabled',true);
					$('#cobertura_opcion_4_select').prop('disabled',true);
					$('#cobertura_opcion_5_select').prop('disabled',true);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',true);
					$('#cobertura_opcion_2_3_select').prop('disabled',true);
					$('#cobertura_opcion_2_4_select').prop('disabled',true);
					$('#cobertura_opcion_2_5_select').prop('disabled',true);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',true);
					$('#cantidad_prima_neta_opcion3').prop('disabled',true);
					$('#cantidad_prima_neta_opcion4').prop('disabled',true);
					$('#cantidad_prima_neta_opcion5').prop('disabled',true);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',true);
					$('#cantidad_total_anual_opcion_3').prop('disabled',true);
					$('#cantidad_total_anual_opcion_4').prop('disabled',true);
					$('#cantidad_total_anual_opcion_5').prop('disabled',true);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',true);
					$('#primer_pago_opcion_3').prop('disabled',true);
					$('#primer_pago_opcion_4').prop('disabled',true);
					$('#primer_pago_opcion_5').prop('disabled',true);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',true);
					$('#subsecuente_opcion3').prop('disabled',true);
					$('#subsecuente_opcion4').prop('disabled',true);
					$('#subsecuente_opcion5').prop('disabled',true);

$('#error_empresas_opcion2').slideUp('fast');
$('#error_empresas_opcion3').slideUp('fast');
$('#error_empresas_opcion4').slideUp('fast');
$('#error_empresas_opcion5').slideUp('fast');

$('#error_daños_opcion2_selec').slideUp('fast');
$('#error_daños_opcion3_selec').slideUp('fast');
$('#error_daños_opcion4_selec').slideUp('fast');
$('#error_daños_opcion5_selec').slideUp('fast');

$('#error_daños_material_importe_factura_2').slideUp('fast');
$('#error_daños_material_importe_factura_3').slideUp('fast');
$('#error_daños_material_importe_factura_4').slideUp('fast');
$('#error_daños_material_importe_factura_5').slideUp('fast');

$('#error_deducible_opcion2').slideUp('fast');
$('#error_deducible_opcion3').slideUp('fast');
$('#error_deducible_opcion4').slideUp('fast');
$('#error_deducible_opcion5').slideUp('fast');

$('#error_cristales_opcion2_selec').slideUp('fast');
$('#error_cristales_opcion3_selec').slideUp('fast');
$('#error_cristales_opcion4_selec').slideUp('fast');
$('#error_cristales_opcion5_selec').slideUp('fast');

$('#error_robo_opcion2_selec').slideUp('fast');
$('#error_robo_opcion3_selec').slideUp('fast');
$('#error_robo_opcion4_selec').slideUp('fast');
$('#error_robo_opcion5_selec').slideUp('fast');

$('#error_robo_importe_factura_2').slideUp('fast');
$('#error_robo_importe_factura_3').slideUp('fast');
$('#error_robo_importe_factura_4').slideUp('fast');
$('#error_robo_importe_factura_5').slideUp('fast');

$('#error_deducible_rt2').slideUp('fast');
$('#error_deducible_rt3').slideUp('fast');
$('#error_deducible_rt4').slideUp('fast');
$('#error_deducible_rt5').slideUp('fast');

$('#error_daños_tercero_opcion_2').slideUp('fast');
$('#error_daños_tercero_opcion_3').slideUp('fast');
$('#error_daños_tercero_opcion_4').slideUp('fast');
$('#error_daños_tercero_opcion_5').slideUp('fast');

$('#error_deducible_de_rc_opcion2').slideUp('fast');
$('#error_deducible_de_rc_opcion3').slideUp('fast');		
$('#error_deducible_de_rc_opcion4').slideUp('fast');
$('#error_deducible_de_rc_opcion5').slideUp('fast');

$('#error_fallecimiento_opcion_2').slideUp('fast');
$('#error_fallecimiento_opcion_3').slideUp('fast');
$('#error_fallecimiento_opcion_4').slideUp('fast');
$('#error_fallecimiento_opcion_5').slideUp('fast');

$('#error_gastos_medicos_opcion_2').slideUp('fast');
$('#error_gastos_medicos_opcion_3').slideUp('fast');
$('#error_gastos_medicos_opcion_4').slideUp('fast');
$('#error_gastos_medicos_opcion_5').slideUp('fast');

$('#error_accidente_conducir_opcion_2').slideUp('fast');
$('#error_accidente_conducir_opcion_3').slideUp('fast');
$('#error_accidente_conducir_opcion_4').slideUp('fast');
$('#error_accidente_conducir_opcion_5').slideUp('fast');

$('#error_proteccion_opcion2_selec').slideUp('fast');
$('#error_proteccion_opcion3_selec').slideUp('fast');
$('#error_proteccion_opcion4_selec').slideUp('fast');
$('#error_proteccion_opcion5_selec').slideUp('fast');

$('#error_asistencia_vial_opcion2_selec').slideUp('fast');
$('#error_asistencia_vial_opcion3_selec').slideUp('fast');
$('#error_asistencia_vial_opcion4_selec').slideUp('fast');
$('#error_asistencia_vial_opcion5_selec').slideUp('fast');

$('#error_daños_carga_opcion_selec_2').slideUp('fast');
$('#error_daños_carga_opcion_selec_3').slideUp('fast');
$('#error_daños_carga_opcion_selec_4').slideUp('fast');
$('#error_daños_carga_opcion_selec_5').slideUp('fast');

$('#error_adaptaciones_opcion_2').slideUp('fast');
$('#error_adaptaciones_opcion_3').slideUp('fast');
$('#error_adaptaciones_opcion_4').slideUp('fast');
$('#error_adaptaciones_opcion_5').slideUp('fast');

$('#error_extension_rc_opcion2').slideUp('fast');
$('#error_extension_rc_opcion3').slideUp('fast');
$('#error_extension_rc_opcion4').slideUp('fast');
$('#error_extension_rc_opcion5').slideUp('fast');

$('#error_cobertura_opcion_2_select').slideUp('fast');
$('#error_cobertura_opcion_3_select').slideUp('fast');
$('#error_cobertura_opcion_4_select').slideUp('fast');		
$('#error_cobertura_opcion_5_select').slideUp('fast');

$('#error_cobertura_opcion_2_2_select').slideUp('fast');
$('#error_cobertura_opcion_2_3_select').slideUp('fast');
$('#error_cobertura_opcion_2_4_select').slideUp('fast');
$('#error_cobertura_opcion_2_5_select').slideUp('fast');

$('#error_cantidad_prima_neta_opcion2').slideUp('fast');
$('#error_cantidad_prima_neta_opcion3').slideUp('fast');
$('#error_cantidad_prima_neta_opcion4').slideUp('fast');
$('#error_cantidad_prima_neta_opcion5').slideUp('fast');

$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
$('#error_cantidad_total_anual_opcion_5').slideUp('fast');

$('#error_primer_pago_opcion_2').slideUp('fast');
$('#error_primer_pago_opcion_3').slideUp('fast');
$('#error_primer_pago_opcion_4').slideUp('fast');
$('#error_primer_pago_opcion_5').slideUp('fast');											
					
			break;
			case'2':
			$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
						$('#empresas_opcion2').html(data);
					}
				});
			
			$('#empresas_opcion3 option:eq(0)').prop('selected',true);
			$('#empresas_opcion4 option:eq(0)').prop('selected',true);
			$('#empresas_opcion5 option:eq(0)').prop('selected',true);
		
			
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);



					switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',false);
					$('#deducible_opcion2').prop('disabled',false);

					$('#cristales_opcion2_selec').prop('disabled',false);

					$('#robo_opcion2_selec').prop('disabled',false);
					$('#deducible_rt2').prop('disabled',false);

					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}

					if($('#daños_opcion2_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_2').prop('disabled',false);
						$('#robo_importe_factura_2').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_2').prop('disabled',true);
					}


					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);	
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					
					$('#robo_opcion2_selec').prop('disabled',false);

					if($('#robo_opcion2_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_2').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_2').prop('disabled',true);	
					}

					$('#deducible_rt2').prop('disabled',false);

					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);

					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_importe_factura_2').prop('disabled',true);
					$('#deducible_rt2').prop('disabled',true);

					
					break;
					
				}
					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_opcion5_selec').prop('disabled',true);

					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);

					$('#deducible_opcion3').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);
					$('#cristales_opcion4_selec').prop('disabled',true);
					$('#cristales_opcion5_selec').prop('disabled',true);


					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_opcion5_selec').prop('disabled',true);


					$('#robo_importe_factura_3').prop('disabled',true);
					$('#robo_importe_factura_4').prop('disabled',true);
					$('#robo_importe_factura_5').prop('disabled',true);


					$('#deducible_rt3').prop('disabled',true);
					$('#deducible_rt4').prop('disabled',true);
					$('#deducible_rt5').prop('disabled',true);


					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',false);
					$('#daños_tercero_opcion_3').prop('disabled',true);
					$('#daños_tercero_opcion_4').prop('disabled',true);
					$('#daños_tercero_opcion_5').prop('disabled',true);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',false);
					$('#deducible_de_rc_opcion3').prop('disabled',true);
					$('#deducible_de_rc_opcion4').prop('disabled',true);
					$('#deducible_de_rc_opcion5').prop('disabled',true);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',false);
					$('#fallecimiento_opcion_3').prop('disabled',true);
					$('#fallecimiento_opcion_4').prop('disabled',true);
					$('#fallecimiento_opcion_5').prop('disabled',true);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',false);
					$('#gastos_medicos_opcion_3').prop('disabled',true);
					$('#gastos_medicos_opcion_4').prop('disabled',true);
					$('#gastos_medicos_opcion_5').prop('disabled',true);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',false);
					$('#accidente_conducir_opcion_3').prop('disabled',true);
					$('#accidente_conducir_opcion_4').prop('disabled',true);
					$('#accidente_conducir_opcion_5').prop('disabled',true);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',false);
					$('#proteccion_opcion3_selec').prop('disabled',true);
					$('#proteccion_opcion4_selec').prop('disabled',true);
					$('#proteccion_opcion5_selec').prop('disabled',true);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',false);
					$('#asistencia_vial_opcion3_selec').prop('disabled',true);
					$('#asistencia_vial_opcion4_selec').prop('disabled',true);
					$('#asistencia_vial_opcion5_selec').prop('disabled',true);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',false);
					$('#daños_carga_opcion_selec_3').prop('disabled',true);
					$('#daños_carga_opcion_selec_4').prop('disabled',true);
					$('#daños_carga_opcion_selec_5').prop('disabled',true);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',false);
					$('#adaptaciones_opcion_3').prop('disabled',true);
					$('#adaptaciones_opcion_4').prop('disabled',true);
					$('#adaptaciones_opcion_5').prop('disabled',true);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',false);
					$('#extension_rc_opcion3').prop('disabled',true);
					$('#extension_rc_opcion4').prop('disabled',true);
					$('#extension_rc_opcion5').prop('disabled',true);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',false);
					$('#cobertura_opcion_3_select').prop('disabled',true);
					$('#cobertura_opcion_4_select').prop('disabled',true);
					$('#cobertura_opcion_5_select').prop('disabled',true);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',false);
					$('#cobertura_opcion_2_3_select').prop('disabled',true);
					$('#cobertura_opcion_2_4_select').prop('disabled',true);
					$('#cobertura_opcion_2_5_select').prop('disabled',true);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',false);
					$('#cantidad_prima_neta_opcion3').prop('disabled',true);
					$('#cantidad_prima_neta_opcion4').prop('disabled',true);
					$('#cantidad_prima_neta_opcion5').prop('disabled',true);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',false);
					$('#cantidad_total_anual_opcion_3').prop('disabled',true);
					$('#cantidad_total_anual_opcion_4').prop('disabled',true);
					$('#cantidad_total_anual_opcion_5').prop('disabled',true);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',false);
					$('#primer_pago_opcion_3').prop('disabled',true);
					$('#primer_pago_opcion_4').prop('disabled',true);
					$('#primer_pago_opcion_5').prop('disabled',true);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',false);
					$('#subsecuente_opcion3').prop('disabled',true);
					$('#subsecuente_opcion4').prop('disabled',true);
					$('#subsecuente_opcion5').prop('disabled',true);

$('#error_empresas_opcion3').slideUp('fast');
$('#error_empresas_opcion4').slideUp('fast');
$('#error_empresas_opcion5').slideUp('fast');

$('#error_daños_opcion3_selec').slideUp('fast');
$('#error_daños_opcion4_selec').slideUp('fast');
$('#error_daños_opcion5_selec').slideUp('fast');

$('#error_daños_material_importe_factura_3').slideUp('fast');
$('#error_daños_material_importe_factura_4').slideUp('fast');
$('#error_daños_material_importe_factura_5').slideUp('fast');

$('#error_deducible_opcion3').slideUp('fast');
$('#error_deducible_opcion4').slideUp('fast');
$('#error_deducible_opcion5').slideUp('fast');

$('#error_cristales_opcion3_selec').slideUp('fast');
$('#error_cristales_opcion4_selec').slideUp('fast');
$('#error_cristales_opcion5_selec').slideUp('fast');

$('#error_robo_opcion3_selec').slideUp('fast');
$('#error_robo_opcion4_selec').slideUp('fast');
$('#error_robo_opcion5_selec').slideUp('fast');

$('#error_robo_importe_factura_3').slideUp('fast');
$('#error_robo_importe_factura_4').slideUp('fast');
$('#error_robo_importe_factura_5').slideUp('fast');

$('#error_deducible_rt3').slideUp('fast');
$('#error_deducible_rt4').slideUp('fast');
$('#error_deducible_rt5').slideUp('fast');

$('#error_daños_tercero_opcion_3').slideUp('fast');
$('#error_daños_tercero_opcion_4').slideUp('fast');
$('#error_daños_tercero_opcion_5').slideUp('fast');

$('#error_deducible_de_rc_opcion3').slideUp('fast');		
$('#error_deducible_de_rc_opcion4').slideUp('fast');
$('#error_deducible_de_rc_opcion5').slideUp('fast');

$('#error_fallecimiento_opcion_3').slideUp('fast');
$('#error_fallecimiento_opcion_4').slideUp('fast');
$('#error_fallecimiento_opcion_5').slideUp('fast');

$('#error_gastos_medicos_opcion_3').slideUp('fast');
$('#error_gastos_medicos_opcion_4').slideUp('fast');
$('#error_gastos_medicos_opcion_5').slideUp('fast');

$('#error_accidente_conducir_opcion_3').slideUp('fast');
$('#error_accidente_conducir_opcion_4').slideUp('fast');
$('#error_accidente_conducir_opcion_5').slideUp('fast');

$('#error_proteccion_opcion3_selec').slideUp('fast');
$('#error_proteccion_opcion4_selec').slideUp('fast');
$('#error_proteccion_opcion5_selec').slideUp('fast');

$('#error_asistencia_vial_opcion3_selec').slideUp('fast');
$('#error_asistencia_vial_opcion4_selec').slideUp('fast');
$('#error_asistencia_vial_opcion5_selec').slideUp('fast');

$('#error_daños_carga_opcion_selec_3').slideUp('fast');
$('#error_daños_carga_opcion_selec_4').slideUp('fast');
$('#error_daños_carga_opcion_selec_5').slideUp('fast');

$('#error_adaptaciones_opcion_3').slideUp('fast');
$('#error_adaptaciones_opcion_4').slideUp('fast');
$('#error_adaptaciones_opcion_5').slideUp('fast');

$('#error_extension_rc_opcion3').slideUp('fast');
$('#error_extension_rc_opcion4').slideUp('fast');
$('#error_extension_rc_opcion5').slideUp('fast');

$('#error_cobertura_opcion_3_select').slideUp('fast');
$('#error_cobertura_opcion_4_select').slideUp('fast');		
$('#error_cobertura_opcion_5_select').slideUp('fast');

$('#error_cobertura_opcion_2_3_select').slideUp('fast');
$('#error_cobertura_opcion_2_4_select').slideUp('fast');
$('#error_cobertura_opcion_2_5_select').slideUp('fast');

$('#error_cantidad_prima_neta_opcion3').slideUp('fast');
$('#error_cantidad_prima_neta_opcion4').slideUp('fast');
$('#error_cantidad_prima_neta_opcion5').slideUp('fast');

$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
$('#error_cantidad_total_anual_opcion_5').slideUp('fast');

$('#error_primer_pago_opcion_3').slideUp('fast');
$('#error_primer_pago_opcion_4').slideUp('fast');
$('#error_primer_pago_opcion_5').slideUp('fast');
			break;

			case'3':
			
				$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
						$('#empresas_opcion2').html(data);
						$('#empresas_opcion3').html(data);
					}
				});
				
				$('#empresas_opcion4 option:eq(0)').prop('selected',true);
				$('#empresas_opcion5 option:eq(0)').prop('selected',true);
			 
			
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);

					switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',false);
					$('#deducible_opcion2').prop('disabled',false);

					$('#cristales_opcion2_selec').prop('disabled',false);

					$('#robo_opcion2_selec').prop('disabled',false);
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',false);
					$('#deducible_opcion3').prop('disabled',false);

					$('#cristales_opcion3_selec').prop('disabled',false);

					$('#robo_opcion3_selec').prop('disabled',false);
					$('#deducible_rt3').prop('disabled',false);

					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}

					if($('#daños_opcion2_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_2').prop('disabled',false);
						$('#robo_importe_factura_2').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_2').prop('disabled',true);
					}

					if($('#daños_opcion3_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_3').prop('disabled',false);
						$('#robo_importe_factura_3').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_3').prop('disabled',true);
						$('#robo_importe_factura_3').prop('disabled',true);
					}
					
					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);	
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					
					$('#robo_opcion2_selec').prop('disabled',false);	
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);
					
					$('#robo_opcion3_selec').prop('disabled',false);
					if($('#robo_opcion3_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_3').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_3').prop('disabled',true);	
					}
					$('#deducible_rt3').prop('disabled',false);

					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);

					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_importe_factura_2').prop('disabled',true);
					$('#deducible_rt2').prop('disabled',true);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);

					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_importe_factura_3').prop('disabled',true);
					$('#deducible_rt3').prop('disabled',true);

					
					break;
					
				}
					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_opcion5_selec').prop('disabled',true);

					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);

					$('#deducible_opcion4').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);
					$('#cristales_opcion5_selec').prop('disabled',true);


					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_opcion5_selec').prop('disabled',true);


					$('#robo_importe_factura_4').prop('disabled',true);
					$('#robo_importe_factura_5').prop('disabled',true);


					$('#deducible_rt4').prop('disabled',true);
					$('#deducible_rt5').prop('disabled',true);


					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',false);
					$('#daños_tercero_opcion_3').prop('disabled',false);
					$('#daños_tercero_opcion_4').prop('disabled',true);
					$('#daños_tercero_opcion_5').prop('disabled',true);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',false);
					$('#deducible_de_rc_opcion3').prop('disabled',false);
					$('#deducible_de_rc_opcion4').prop('disabled',true);
					$('#deducible_de_rc_opcion5').prop('disabled',true);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',false);
					$('#fallecimiento_opcion_3').prop('disabled',false);
					$('#fallecimiento_opcion_4').prop('disabled',true);
					$('#fallecimiento_opcion_5').prop('disabled',true);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',false);
					$('#gastos_medicos_opcion_3').prop('disabled',false);
					$('#gastos_medicos_opcion_4').prop('disabled',true);
					$('#gastos_medicos_opcion_5').prop('disabled',true);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',false);
					$('#accidente_conducir_opcion_3').prop('disabled',false);
					$('#accidente_conducir_opcion_4').prop('disabled',true);
					$('#accidente_conducir_opcion_5').prop('disabled',true);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',false);
					$('#proteccion_opcion3_selec').prop('disabled',false);
					$('#proteccion_opcion4_selec').prop('disabled',true);
					$('#proteccion_opcion5_selec').prop('disabled',true);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',false);
					$('#asistencia_vial_opcion3_selec').prop('disabled',false);
					$('#asistencia_vial_opcion4_selec').prop('disabled',true);
					$('#asistencia_vial_opcion5_selec').prop('disabled',true);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',false);
					$('#daños_carga_opcion_selec_3').prop('disabled',false);
					$('#daños_carga_opcion_selec_4').prop('disabled',true);
					$('#daños_carga_opcion_selec_5').prop('disabled',true);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',false);
					$('#adaptaciones_opcion_3').prop('disabled',false);
					$('#adaptaciones_opcion_4').prop('disabled',true);
					$('#adaptaciones_opcion_5').prop('disabled',true);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',false);
					$('#extension_rc_opcion3').prop('disabled',false);
					$('#extension_rc_opcion4').prop('disabled',true);
					$('#extension_rc_opcion5').prop('disabled',true);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',false);
					$('#cobertura_opcion_3_select').prop('disabled',false);
					$('#cobertura_opcion_4_select').prop('disabled',true);
					$('#cobertura_opcion_5_select').prop('disabled',true);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',false);
					$('#cobertura_opcion_2_3_select').prop('disabled',false);
					$('#cobertura_opcion_2_4_select').prop('disabled',true);
					$('#cobertura_opcion_2_5_select').prop('disabled',true);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',false);
					$('#cantidad_prima_neta_opcion3').prop('disabled',false);
					$('#cantidad_prima_neta_opcion4').prop('disabled',true);
					$('#cantidad_prima_neta_opcion5').prop('disabled',true);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',false);
					$('#cantidad_total_anual_opcion_3').prop('disabled',false);
					$('#cantidad_total_anual_opcion_4').prop('disabled',true);
					$('#cantidad_total_anual_opcion_5').prop('disabled',true);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',false);
					$('#primer_pago_opcion_3').prop('disabled',false);
					$('#primer_pago_opcion_4').prop('disabled',true);
					$('#primer_pago_opcion_5').prop('disabled',true);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',false);
					$('#subsecuente_opcion3').prop('disabled',false);
					$('#subsecuente_opcion4').prop('disabled',true);
					$('#subsecuente_opcion5').prop('disabled',true);

$('#error_empresas_opcion4').slideUp('fast');
$('#error_empresas_opcion5').slideUp('fast');

$('#error_daños_opcion4_selec').slideUp('fast');
$('#error_daños_opcion5_selec').slideUp('fast');

$('#error_daños_material_importe_factura_4').slideUp('fast');
$('#error_daños_material_importe_factura_5').slideUp('fast');

$('#error_deducible_opcion4').slideUp('fast');
$('#error_deducible_opcion5').slideUp('fast');

$('#error_cristales_opcion4_selec').slideUp('fast');
$('#error_cristales_opcion5_selec').slideUp('fast');

$('#error_robo_opcion4_selec').slideUp('fast');
$('#error_robo_opcion5_selec').slideUp('fast');

$('#error_robo_importe_factura_4').slideUp('fast');
$('#error_robo_importe_factura_5').slideUp('fast');

$('#error_deducible_rt4').slideUp('fast');
$('#error_deducible_rt5').slideUp('fast');

$('#error_daños_tercero_opcion_4').slideUp('fast');
$('#error_daños_tercero_opcion_5').slideUp('fast');

$('#error_deducible_de_rc_opcion4').slideUp('fast');
$('#error_deducible_de_rc_opcion5').slideUp('fast');

$('#error_fallecimiento_opcion_4').slideUp('fast');
$('#error_fallecimiento_opcion_5').slideUp('fast');

$('#error_gastos_medicos_opcion_4').slideUp('fast');
$('#error_gastos_medicos_opcion_5').slideUp('fast');

$('#error_accidente_conducir_opcion_4').slideUp('fast');
$('#error_accidente_conducir_opcion_5').slideUp('fast');

$('#error_proteccion_opcion4_selec').slideUp('fast');
$('#error_proteccion_opcion5_selec').slideUp('fast');

$('#error_asistencia_vial_opcion4_selec').slideUp('fast');
$('#error_asistencia_vial_opcion5_selec').slideUp('fast');

$('#error_daños_carga_opcion_selec_4').slideUp('fast');
$('#error_daños_carga_opcion_selec_5').slideUp('fast');

$('#error_adaptaciones_opcion_4').slideUp('fast');
$('#error_adaptaciones_opcion_5').slideUp('fast');

$('#error_extension_rc_opcion4').slideUp('fast');
$('#error_extension_rc_opcion5').slideUp('fast');

$('#error_cobertura_opcion_4_select').slideUp('fast');		
$('#error_cobertura_opcion_5_select').slideUp('fast');

$('#error_cobertura_opcion_2_4_select').slideUp('fast');
$('#error_cobertura_opcion_2_5_select').slideUp('fast');

$('#error_cantidad_prima_neta_opcion4').slideUp('fast');
$('#error_cantidad_prima_neta_opcion5').slideUp('fast');

$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
$('#error_cantidad_total_anual_opcion_5').slideUp('fast');

$('#error_primer_pago_opcion_4').slideUp('fast');
$('#error_primer_pago_opcion_5').slideUp('fast');
			break;


			case'4':
			$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
						$('#empresas_opcion2').html(data);
						$('#empresas_opcion3').html(data);
						$('#empresas_opcion4').html(data);
					}
				});

			
			$('#empresas_opcion5 option:eq(0)').prop('selected',true);
		
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);
			$('#empresas_opcion5').prop('disabled',true);

					switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',false);
					$('#deducible_opcion2').prop('disabled',false);

					$('#cristales_opcion2_selec').prop('disabled',false);

					$('#robo_opcion2_selec').prop('disabled',false);
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',false);
					$('#deducible_opcion3').prop('disabled',false);

					$('#cristales_opcion3_selec').prop('disabled',false);

					$('#robo_opcion3_selec').prop('disabled',false);
					$('#deducible_rt3').prop('disabled',false);

					$('#daños_opcion4_selec').prop('disabled',false);
					$('#deducible_opcion4').prop('disabled',false);

					$('#cristales_opcion4_selec').prop('disabled',false);

					$('#robo_opcion4_selec').prop('disabled',false);
					
					$('#deducible_rt4').prop('disabled',false);

					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}

					if($('#daños_opcion2_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_2').prop('disabled',false);
						$('#robo_importe_factura_2').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_2').prop('disabled',true);
					}

					if($('#daños_opcion3_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_3').prop('disabled',false);
						$('#robo_importe_factura_3').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_3').prop('disabled',true);
						$('#robo_importe_factura_3').prop('disabled',true);
					}
					if($('#daños_opcion4_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_4').prop('disabled',false);
						$('#robo_importe_factura_4').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_4').prop('disabled',true);
						$('#robo_importe_factura_4').prop('disabled',true);
					}

					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);	
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					
					$('#robo_opcion2_selec').prop('disabled',false);	
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);
					
					$('#robo_opcion3_selec').prop('disabled',false);	
					$('#deducible_rt3').prop('disabled',false);

					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);
					
					$('#robo_opcion4_selec').prop('disabled',false);
					if($('#robo_opcion4_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_4').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_4').prop('disabled',true);	
					}	
					$('#deducible_rt4').prop('disabled',false);

					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);

					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_importe_factura_2').prop('disabled',true);
					$('#deducible_rt2').prop('disabled',true);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);

					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_importe_factura_3').prop('disabled',true);
					$('#deducible_rt3').prop('disabled',true);

					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);

					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_importe_factura_4').prop('disabled',true);
					$('#deducible_rt4').prop('disabled',true);

					
					break;
					
				}
					$('#daños_opcion5_selec').prop('disabled',true);

					$('#daños_material_importe_factura_5').prop('disabled',true);

					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion5_selec').prop('disabled',true);


					$('#robo_opcion5_selec').prop('disabled',true);


					$('#robo_importe_factura_5').prop('disabled',true);


					$('#deducible_rt5').prop('disabled',true);


					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',false);
					$('#daños_tercero_opcion_3').prop('disabled',false);
					$('#daños_tercero_opcion_4').prop('disabled',false);
					$('#daños_tercero_opcion_5').prop('disabled',true);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',false);
					$('#deducible_de_rc_opcion3').prop('disabled',false);
					$('#deducible_de_rc_opcion4').prop('disabled',false);
					$('#deducible_de_rc_opcion5').prop('disabled',true);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',false);
					$('#fallecimiento_opcion_3').prop('disabled',false);
					$('#fallecimiento_opcion_4').prop('disabled',false);
					$('#fallecimiento_opcion_5').prop('disabled',true);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',false);
					$('#gastos_medicos_opcion_3').prop('disabled',false);
					$('#gastos_medicos_opcion_4').prop('disabled',false);
					$('#gastos_medicos_opcion_5').prop('disabled',true);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',false);
					$('#accidente_conducir_opcion_3').prop('disabled',false);
					$('#accidente_conducir_opcion_4').prop('disabled',false);
					$('#accidente_conducir_opcion_5').prop('disabled',true);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',false);
					$('#proteccion_opcion3_selec').prop('disabled',false);
					$('#proteccion_opcion4_selec').prop('disabled',false);
					$('#proteccion_opcion5_selec').prop('disabled',true);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',false);
					$('#asistencia_vial_opcion3_selec').prop('disabled',false);
					$('#asistencia_vial_opcion4_selec').prop('disabled',false);
					$('#asistencia_vial_opcion5_selec').prop('disabled',true);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',false);
					$('#daños_carga_opcion_selec_3').prop('disabled',false);
					$('#daños_carga_opcion_selec_4').prop('disabled',false);
					$('#daños_carga_opcion_selec_5').prop('disabled',true);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',false);
					$('#adaptaciones_opcion_3').prop('disabled',false);
					$('#adaptaciones_opcion_4').prop('disabled',false);
					$('#adaptaciones_opcion_5').prop('disabled',true);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',false);
					$('#extension_rc_opcion3').prop('disabled',false);
					$('#extension_rc_opcion4').prop('disabled',false);
					$('#extension_rc_opcion5').prop('disabled',true);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',false);
					$('#cobertura_opcion_3_select').prop('disabled',false);
					$('#cobertura_opcion_4_select').prop('disabled',false);
					$('#cobertura_opcion_5_select').prop('disabled',true);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',false);
					$('#cobertura_opcion_2_3_select').prop('disabled',false);
					$('#cobertura_opcion_2_4_select').prop('disabled',false);
					$('#cobertura_opcion_2_5_select').prop('disabled',true);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',false);
					$('#cantidad_prima_neta_opcion3').prop('disabled',false);
					$('#cantidad_prima_neta_opcion4').prop('disabled',false);
					$('#cantidad_prima_neta_opcion5').prop('disabled',true);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',false);
					$('#cantidad_total_anual_opcion_3').prop('disabled',false);
					$('#cantidad_total_anual_opcion_4').prop('disabled',false);
					$('#cantidad_total_anual_opcion_5').prop('disabled',true);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',false);
					$('#primer_pago_opcion_3').prop('disabled',false);
					$('#primer_pago_opcion_4').prop('disabled',false);
					$('#primer_pago_opcion_5').prop('disabled',true);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',false);
					$('#subsecuente_opcion3').prop('disabled',false);
					$('#subsecuente_opcion4').prop('disabled',false);
					$('#subsecuente_opcion5').prop('disabled',true);

$('#error_empresas_opcion5').slideUp('fast');

$('#error_daños_opcion5_selec').slideUp('fast');

$('#error_daños_material_importe_factura_5').slideUp('fast');

$('#error_deducible_opcion5').slideUp('fast');

$('#error_cristales_opcion5_selec').slideUp('fast');

$('#error_robo_opcion5_selec').slideUp('fast');

$('#error_robo_importe_factura_5').slideUp('fast');

$('#error_deducible_rt5').slideUp('fast');

$('#error_daños_tercero_opcion_5').slideUp('fast');

$('#error_deducible_de_rc_opcion5').slideUp('fast');

$('#error_fallecimiento_opcion_5').slideUp('fast');

$('#error_gastos_medicos_opcion_5').slideUp('fast');

$('#error_accidente_conducir_opcion_5').slideUp('fast');

$('#error_proteccion_opcion5_selec').slideUp('fast');

$('#error_asistencia_vial_opcion5_selec').slideUp('fast');

$('#error_daños_carga_opcion_selec_5').slideUp('fast');

$('#error_adaptaciones_opcion_5').slideUp('fast');

$('#error_extension_rc_opcion5').slideUp('fast');

$('#error_cobertura_opcion_5_select').slideUp('fast');

$('#error_cobertura_opcion_2_5_select').slideUp('fast');

$('#error_cantidad_prima_neta_opcion5').slideUp('fast');

$('#error_cantidad_total_anual_opcion_5').slideUp('fast');

$('#error_primer_pago_opcion_5').slideUp('fast');
			break;

			case'5':
			$.ajax({
					url:'metodos/cotizacion_metodos.php',
					type:'post',
					data:{lista_aseguradoras_select:1},
					success:function(data)
					{
						$('#empresas_opcion1').html(data);
						$('#empresas_opcion2').html(data);
						$('#empresas_opcion3').html(data);
						$('#empresas_opcion4').html(data);
						$('#empresas_opcion5').html(data);
					}
				});
			


			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);
			$('#empresas_opcion5').prop('disabled',false);

					switch(paquete)
				{
					case'AMPLIA':
					$('#daños_opcion1_selec').prop('disabled',false);
					$('#deducible_opcion1').prop('disabled',false);

					$('#cristales_opcion1_selec').prop('disabled',false);

					$('#robo_opcion1_selec').prop('disabled',false);
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',false);
					$('#deducible_opcion2').prop('disabled',false);

					$('#cristales_opcion2_selec').prop('disabled',false);

					$('#robo_opcion2_selec').prop('disabled',false);
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',false);
					$('#deducible_opcion3').prop('disabled',false);

					$('#cristales_opcion3_selec').prop('disabled',false);

					$('#robo_opcion3_selec').prop('disabled',false);
					$('#deducible_rt3').prop('disabled',false);

					$('#daños_opcion4_selec').prop('disabled',false);
					$('#deducible_opcion4').prop('disabled',false);

					$('#cristales_opcion4_selec').prop('disabled',false);

					$('#robo_opcion4_selec').prop('disabled',false);
					$('#deducible_rt4').prop('disabled',false);

					$('#daños_opcion5_selec').prop('disabled',false);
					$('#deducible_opcion5').prop('disabled',false);

					$('#cristales_opcion5_selec').prop('disabled',false);

					$('#robo_opcion5_selec').prop('disabled',false);
					$('#deducible_rt5').prop('disabled',false);

					if($('#daños_opcion1_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_1').prop('disabled',false);
						$('#robo_importe_factura_1').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_1').prop('disabled',true);
					}

					if($('#daños_opcion2_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_2').prop('disabled',false);
						$('#robo_importe_factura_2').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_2').prop('disabled',true);
						$('#robo_importe_factura_2').prop('disabled',true);
					}

					if($('#daños_opcion3_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_3').prop('disabled',false);
						$('#robo_importe_factura_3').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_3').prop('disabled',true);
						$('#robo_importe_factura_3').prop('disabled',true);
					}
					if($('#daños_opcion4_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_4').prop('disabled',false);
						$('#robo_importe_factura_4').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_4').prop('disabled',true);
						$('#robo_importe_factura_4').prop('disabled',true);
					}
					if($('#daños_opcion5_selec option:eq(3)').is(':selected',true)===true)
					{
						$('#daños_material_importe_factura_5').prop('disabled',false);
						$('#robo_importe_factura_5').prop('disabled',false);
							
					}
					else
					{
						$('#daños_material_importe_factura_5').prop('disabled',true);
						$('#robo_importe_factura_5').prop('disabled',true);
					}


					break;
					case'LIMITADA':

					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);
					
					$('#robo_opcion1_selec').prop('disabled',false);	
					$('#deducible_rt1').prop('disabled',false);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);
					
					$('#robo_opcion2_selec').prop('disabled',false);	
					$('#deducible_rt2').prop('disabled',false);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);
					
					$('#robo_opcion3_selec').prop('disabled',false);	
					$('#deducible_rt3').prop('disabled',false);

					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);
					
					$('#robo_opcion4_selec').prop('disabled',false);	
					$('#deducible_rt4').prop('disabled',false);

					$('#daños_opcion5_selec').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion5_selec').prop('disabled',true);
					
					$('#robo_opcion5_selec').prop('disabled',false);
					if($('#robo_opcion5_selec option:selected').val()=='V.FACTURA')
					{
						$('#robo_importe_factura_5').prop('disabled',false);
					}
					else
					{
						$('#robo_importe_factura_5').prop('disabled',true);	
					}	
					$('#deducible_rt5').prop('disabled',false);
					
					break;
					case'RESPONSABILIDAD CIVIL':
					$('#daños_opcion1_selec').prop('disabled',true);
					$('#daños_material_importe_factura_1').prop('disabled',true);
					$('#deducible_opcion1').prop('disabled',true);

					$('#cristales_opcion1_selec').prop('disabled',true);

					$('#robo_opcion1_selec').prop('disabled',true);
					$('#robo_importe_factura_1').prop('disabled',true);
					$('#deducible_rt1').prop('disabled',true);

					$('#daños_opcion2_selec').prop('disabled',true);
					$('#daños_material_importe_factura_2').prop('disabled',true);
					$('#deducible_opcion2').prop('disabled',true);

					$('#cristales_opcion2_selec').prop('disabled',true);

					$('#robo_opcion2_selec').prop('disabled',true);
					$('#robo_importe_factura_2').prop('disabled',true);
					$('#deducible_rt2').prop('disabled',true);

					$('#daños_opcion3_selec').prop('disabled',true);
					$('#daños_material_importe_factura_3').prop('disabled',true);
					$('#deducible_opcion3').prop('disabled',true);

					$('#cristales_opcion3_selec').prop('disabled',true);

					$('#robo_opcion3_selec').prop('disabled',true);
					$('#robo_importe_factura_3').prop('disabled',true);
					$('#deducible_rt3').prop('disabled',true);

					$('#daños_opcion4_selec').prop('disabled',true);
					$('#daños_material_importe_factura_4').prop('disabled',true);
					$('#deducible_opcion4').prop('disabled',true);

					$('#cristales_opcion4_selec').prop('disabled',true);

					$('#robo_opcion4_selec').prop('disabled',true);
					$('#robo_importe_factura_4').prop('disabled',true);
					$('#deducible_rt4').prop('disabled',true);

					$('#daños_opcion5_selec').prop('disabled',true);
					$('#daños_material_importe_factura_5').prop('disabled',true);
					$('#deducible_opcion5').prop('disabled',true);

					$('#cristales_opcion5_selec').prop('disabled',true);

					$('#robo_opcion5_selec').prop('disabled',true);
					$('#robo_importe_factura_5').prop('disabled',true);
					$('#deducible_rt5').prop('disabled',true);

					
					break;
					
				}


					$('#daños_tercero_opcion_1').prop('disabled',false);
					$('#daños_tercero_opcion_2').prop('disabled',false);
					$('#daños_tercero_opcion_3').prop('disabled',false);
					$('#daños_tercero_opcion_4').prop('disabled',false);
					$('#daños_tercero_opcion_5').prop('disabled',false);

					$('#deducible_de_rc_opcion1').prop('disabled',false);
					$('#deducible_de_rc_opcion2').prop('disabled',false);
					$('#deducible_de_rc_opcion3').prop('disabled',false);
					$('#deducible_de_rc_opcion4').prop('disabled',false);
					$('#deducible_de_rc_opcion5').prop('disabled',false);

					$('#fallecimiento_opcion_1').prop('disabled',false);
					$('#fallecimiento_opcion_2').prop('disabled',false);
					$('#fallecimiento_opcion_3').prop('disabled',false);
					$('#fallecimiento_opcion_4').prop('disabled',false);
					$('#fallecimiento_opcion_5').prop('disabled',false);

					$('#gastos_medicos_opcion_1').prop('disabled',false);
					$('#gastos_medicos_opcion_2').prop('disabled',false);
					$('#gastos_medicos_opcion_3').prop('disabled',false);
					$('#gastos_medicos_opcion_4').prop('disabled',false);
					$('#gastos_medicos_opcion_5').prop('disabled',false);


					$('#accidente_conducir_opcion_1').prop('disabled',false);
					$('#accidente_conducir_opcion_2').prop('disabled',false);
					$('#accidente_conducir_opcion_3').prop('disabled',false);
					$('#accidente_conducir_opcion_4').prop('disabled',false);
					$('#accidente_conducir_opcion_5').prop('disabled',false);

					$('#proteccion_opcion1_selec').prop('disabled',false);
					$('#proteccion_opcion2_selec').prop('disabled',false);
					$('#proteccion_opcion3_selec').prop('disabled',false);
					$('#proteccion_opcion4_selec').prop('disabled',false);
					$('#proteccion_opcion5_selec').prop('disabled',false);
					
					$('#asistencia_vial_opcion1_selec').prop('disabled',false);	
					$('#asistencia_vial_opcion2_selec').prop('disabled',false);
					$('#asistencia_vial_opcion3_selec').prop('disabled',false);
					$('#asistencia_vial_opcion4_selec').prop('disabled',false);
					$('#asistencia_vial_opcion5_selec').prop('disabled',false);	
					
					$('#daños_carga_opcion_selec_1').prop('disabled',false);
					$('#daños_carga_opcion_selec_2').prop('disabled',false);
					$('#daños_carga_opcion_selec_3').prop('disabled',false);
					$('#daños_carga_opcion_selec_4').prop('disabled',false);
					$('#daños_carga_opcion_selec_5').prop('disabled',false);

					$('#adaptaciones_opcion_1').prop('disabled',false);
					$('#adaptaciones_opcion_2').prop('disabled',false);
					$('#adaptaciones_opcion_3').prop('disabled',false);
					$('#adaptaciones_opcion_4').prop('disabled',false);
					$('#adaptaciones_opcion_5').prop('disabled',false);	



					$('#extension_rc_opcion1').prop('disabled',false);
					$('#extension_rc_opcion2').prop('disabled',false);
					$('#extension_rc_opcion3').prop('disabled',false);
					$('#extension_rc_opcion4').prop('disabled',false);
					$('#extension_rc_opcion5').prop('disabled',false);

					

					$('#cobertura_opcion_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_select').prop('disabled',false);
					$('#cobertura_opcion_3_select').prop('disabled',false);
					$('#cobertura_opcion_4_select').prop('disabled',false);
					$('#cobertura_opcion_5_select').prop('disabled',false);


					

					$('#cobertura_opcion_2_1_select').prop('disabled',false);
					$('#cobertura_opcion_2_2_select').prop('disabled',false);
					$('#cobertura_opcion_2_3_select').prop('disabled',false);
					$('#cobertura_opcion_2_4_select').prop('disabled',false);
					$('#cobertura_opcion_2_5_select').prop('disabled',false);



					$('#cantidad_prima_neta_opcion1').prop('disabled',false);
					$('#cantidad_prima_neta_opcion2').prop('disabled',false);
					$('#cantidad_prima_neta_opcion3').prop('disabled',false);
					$('#cantidad_prima_neta_opcion4').prop('disabled',false);
					$('#cantidad_prima_neta_opcion5').prop('disabled',false);


					$('#cantidad_total_anual_opcion_1').prop('disabled',false);
					$('#cantidad_total_anual_opcion_2').prop('disabled',false);
					$('#cantidad_total_anual_opcion_3').prop('disabled',false);
					$('#cantidad_total_anual_opcion_4').prop('disabled',false);
					$('#cantidad_total_anual_opcion_5').prop('disabled',false);
					
					$('#primer_pago_opcion_1').prop('disabled',false);
					$('#primer_pago_opcion_2').prop('disabled',false);
					$('#primer_pago_opcion_3').prop('disabled',false);
					$('#primer_pago_opcion_4').prop('disabled',false);
					$('#primer_pago_opcion_5').prop('disabled',false);

					$('#subsecuente_opcion1').prop('disabled',false);
					$('#subsecuente_opcion2').prop('disabled',false);
					$('#subsecuente_opcion3').prop('disabled',false);
					$('#subsecuente_opcion4').prop('disabled',false);
					$('#subsecuente_opcion5').prop('disabled',false);
			break;
		}
	}
});

$(document).on('change','#forma_de_pago',function(e){

	var tipo=$(this).val();
	switch(tipo)
	{
		case 'ANUAL':
		$('#cantidad_subsecuentes').html('0');
		break;
		case 'SEMESTRAL':
		$('#cantidad_subsecuentes').html('1');
		break;
		case 'TRIMESTRAL':
		$('#cantidad_subsecuentes').html('3');
		break;
		case 'MENSUAL':
		$('#cantidad_subsecuentes').html('11');
		break;
	}

	var cantidad=$('#cantidad_aseguradoras').val();
	switch(parseInt(cantidad))
	{
		case 1:
		
		verificar_cantidad_total_anual_opcion_1();
		verificar_primer_pago_opcion_1();

		break;
		case 2:
		verificar_cantidad_total_anual_opcion_1();
		verificar_primer_pago_opcion_1();
		verificar_cantidad_total_anual_opcion_2();
		verificar_primer_pago_opcion_2();


		break;
		case 3:
		verificar_cantidad_total_anual_opcion_1();
		verificar_primer_pago_opcion_1();
		verificar_cantidad_total_anual_opcion_2();
		verificar_primer_pago_opcion_2();
		verificar_cantidad_total_anual_opcion_3();
		verificar_primer_pago_opcion_3();

		break;
		case 4:
		verificar_cantidad_total_anual_opcion_1();
		verificar_primer_pago_opcion_1();
		verificar_cantidad_total_anual_opcion_2();
		verificar_primer_pago_opcion_2();
		verificar_cantidad_total_anual_opcion_3();
		verificar_primer_pago_opcion_3();
		verificar_cantidad_total_anual_opcion_4();
		verificar_primer_pago_opcion_4();

		break;
		case 5:
		verificar_cantidad_total_anual_opcion_1();
		verificar_primer_pago_opcion_1();
		verificar_cantidad_total_anual_opcion_2();
		verificar_primer_pago_opcion_2();
		verificar_cantidad_total_anual_opcion_3();
		verificar_primer_pago_opcion_3();
		verificar_cantidad_total_anual_opcion_4();
		verificar_primer_pago_opcion_4();
		verificar_cantidad_total_anual_opcion_5();
		verificar_primer_pago_opcion_5();
		break;
	}
});

///* boton guardar////****//
$(document).on('click','.guardar_formulario_cotizacion',function(e){

var parametros = new FormData(document.getElementById('formulario_cotizacion'));
parametros.append('cotizacion_autos_alta',1);	


	if(
		verificar_tipo_cotizacion()==true &&
		verificar_hora_solicitada()==true &&
		verificando_contactos()==true &&
		verificar_nombre_contacto()==true &&
		verificar_marca()==true &&
		verificar_descripcion()==true &&
		verificar_modelo()==true&&
		verificar_uso_de_unidad()==true &&
		verificar_tipo_auto()==true &&
		verificar_carga()==true &&
		verificar_compañia_actual()==true &&
		verificar_fecha_vigencia()==true &&
		verificar_poliza_a_renovar()==true &&
		verificar_prima_año()==true &&
		verificar_paquete()==true &&
		verificar_cantidad_aseguradoras()==true
	)
	{

		var paquete=$('#paquete').val();
		var cantidad=$('#cantidad_aseguradoras').val();
		
		switch(cantidad)
		{
			case '1':
		
			//	console.log(verificar_cantidad_total_anual_opcion_1());
			//	console.log(verificar_empresas_opcion1());
					console.log(verificar_daños_opcion1_selec());
					console.log(verificar_daños_material_importe_factura_1());
					console.log(verificar_deducible_opcion1());
					console.log(verificar_cristales_opcion1_selec());
					console.log(verificar_robo_opcion1_selec());
					console.log(verificar_robo_importe_factura_1());
					console.log(verificar_deducible_rt_opcion1());
					console.log(verificar_daños_tercero_opcion_1());
					console.log(verificar_deducible_de_rc1());
					console.log(verificar_fallecimiento_opcion_1());
					console.log(verificar_gastos_medicos_opcion_1());
					console.log(verificar_accidente_conducir_opcion_1());
					console.log(verificar_daños_carga_opcion_selec_1());
					console.log(verificar_adaptaciones_opcion_1());
					console.log(verificar_extension_rc_opcion1());
					console.log(verificar_cobertura_opcion_1());
					console.log(verificar_cobertura_opcion_1_select());
					console.log(verificar_cobertura_opcion_2());
					console.log(verificar_cobertura_opcion_2_1_select());
					console.log(verificar_forma_de_pago());
					//verificar_cantidad_prima_neta_opcion1()==true &&
					//verificar_cantidad_total_anual_opcion_1()==true &&
					console.log(verificar_primer_pago_opcion_1());
				if(
					verificar_empresas_opcion1()==true &&
					verificar_daños_opcion1_selec()==true &&
					verificar_daños_material_importe_factura_1()==true &&
					verificar_deducible_opcion1()==true &&
					verificar_cristales_opcion1_selec()==true &&
					verificar_robo_opcion1_selec()==true &&
					verificar_robo_importe_factura_1()==true &&
					verificar_deducible_rt_opcion1()==true &&
					verificar_daños_tercero_opcion_1()==true &&
					verificar_deducible_de_rc1()==true &&
					verificar_fallecimiento_opcion_1()==true &&
					verificar_gastos_medicos_opcion_1()==true &&
					verificar_accidente_conducir_opcion_1()==true &&
					verificar_daños_carga_opcion_selec_1()==true &&
					verificar_adaptaciones_opcion_1()==true &&
					verificar_extension_rc_opcion1()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_1_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_1_select()==true &&
					verificar_forma_de_pago()==true &&
					//verificar_cantidad_prima_neta_opcion1()==true &&
					//verificar_cantidad_total_anual_opcion_1()==true &&
					verificar_primer_pago_opcion_1()==true
					)
				{
					//console.log('si paso');
						/*parametros.forEach((value,key)=>{
						console.log('nombre: '+key+' - valor: '+value);
					});*/
					$.ajax({
						data:parametros,
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						context:this,
						contentType:false,
						processData:false,
						cache:false,
						success:function(data)
						{
							var datos_separados=data.split('*');
							if(data[0].trim()=='1')
							{

								$('#boton_pdf').val(datos_separados[1]+'*'+datos_separados[2]);
								document.getElementById('formulario_cotizacion').reset();
								$('#contenedor_formacion_poliza_renovar').slideUp('fast');
								$('#contenedor_general_prospectos').slideUp('fast');
								$('#contactos option:eq(0)').prop('selected',true);
								$('#prospectos_asegurados option:eq(0)').prop('selected',true);
								$('#cantidad_subsecuentes').html('');

								$.ajax({
							    		url:'metodos/alta_contactos_metodos.php',
										type:'post',
										data:{
												contactos_lista:2
										},
										success:function(data)
										{
											$('#contactos').html(data);
										}	
							    });


							    $.ajax({
							    		url:'metodos/prospectos_metodos.php',
										type:'post',
										data:{
												prospectos_lista:3
										},
										success:function(data)
										{
											$('#prospectos_asegurados').html(data);
										}	
							    });

							    $('#cotizacion_capturada').modal({show:true});
							}
							else
							{

							}
						}
					});

				}
				else
				{
					console.log("error");
				}
			break;

			case '2':
					if(
					verificar_empresas_opcion1()==true &&
					verificar_daños_opcion1_selec()==true &&
					verificar_daños_material_importe_factura_1()==true &&
					verificar_deducible_opcion1()==true &&
					verificar_cristales_opcion1_selec()==true &&
					verificar_robo_opcion1_selec()==true &&
					verificar_robo_importe_factura_1()==true &&
					verificar_deducible_rt_opcion1()==true &&
					verificar_daños_tercero_opcion_1()==true &&
					verificar_deducible_de_rc1()==true &&
					verificar_fallecimiento_opcion_1()==true &&
					verificar_gastos_medicos_opcion_1()==true &&
					verificar_accidente_conducir_opcion_1()==true &&
					verificar_daños_carga_opcion_selec_1()==true &&
					verificar_adaptaciones_opcion_1()==true &&
					verificar_extension_rc_opcion1()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_1_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_1_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion1()==true &&
				//	verificar_cantidad_total_anual_opcion_1()==true &&
					verificar_primer_pago_opcion_1()==true &&

					verificar_empresas_opcion2()==true &&
					verificar_daños_opcion2_selec()==true &&
					verificar_daños_material_importe_factura_2()==true &&
					verificar_deducible_opcion2()==true &&
					verificar_cristales_opcion2_selec()==true &&
					verificar_robo_opcion2_selec()==true &&
					verificar_robo_importe_factura_2()==true &&
					verificar_deducible_rt_opcion2()==true &&
					verificar_daños_tercero_opcion_2()==true &&
					verificar_deducible_de_rc2()==true &&
					verificar_fallecimiento_opcion_2()==true &&
					verificar_gastos_medicos_opcion_2()==true &&
					verificar_accidente_conducir_opcion_2()==true &&
					verificar_daños_carga_opcion_selec_2()==true &&
					verificar_adaptaciones_opcion_2()==true &&
					verificar_extension_rc_opcion2()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_2_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion2()==true &&
				//	verificar_cantidad_total_anual_opcion_2()==true &&
					verificar_primer_pago_opcion_2()==true
					)
				{
					//console.log('si paso');
						/*parametros.forEach((value,key)=>{
						console.log('nombre: '+key+' - valor: '+value);
					});*/
					$.ajax({
						data:parametros,
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						context:this,
						contentType:false,
						processData:false,
						cache:false,
						success:function(data)
						{
							var datos_separados=data.split('*');
							if(data[0].trim()=='1')
							{

								$('#boton_pdf').val(datos_separados[1]+'*'+datos_separados[2]);
								document.getElementById('formulario_cotizacion').reset();
								$('#contenedor_formacion_poliza_renovar').slideUp('fast');
								$('#contenedor_general_prospectos').slideUp('fast');
								$('#contactos option:eq(0)').prop('selected',true);
								$('#prospectos_asegurados option:eq(0)').prop('selected',true);
								$('#cantidad_subsecuentes').html('');

								$.ajax({
							    		url:'metodos/alta_contactos_metodos.php',
										type:'post',
										data:{
												contactos_lista:2
										},
										success:function(data)
										{
											$('#contactos').html(data);
										}	
							    });


							    $.ajax({
							    		url:'metodos/prospectos_metodos.php',
										type:'post',
										data:{
												prospectos_lista:3
										},
										success:function(data)
										{
											$('#prospectos_asegurados').html(data);
										}	
							    });

							    $('#cotizacion_capturada').modal({show:true});
							}
							else
							{

							}
						}
					});

				}
				else
				{
					console.log("error");
				}
			break;

			case '3':
					if(
					verificar_empresas_opcion1()==true &&
					verificar_daños_opcion1_selec()==true &&
					verificar_daños_material_importe_factura_1()==true &&
					verificar_deducible_opcion1()==true &&
					verificar_cristales_opcion1_selec()==true &&
					verificar_robo_opcion1_selec()==true &&
					verificar_robo_importe_factura_1()==true &&
					verificar_deducible_rt_opcion1()==true &&
					verificar_daños_tercero_opcion_1()==true &&
					verificar_deducible_de_rc1()==true &&
					verificar_fallecimiento_opcion_1()==true &&
					verificar_gastos_medicos_opcion_1()==true &&
					verificar_accidente_conducir_opcion_1()==true &&
					verificar_daños_carga_opcion_selec_1()==true &&
					verificar_adaptaciones_opcion_1()==true &&
					verificar_extension_rc_opcion1()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_1_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_1_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion1()==true &&
				//	verificar_cantidad_total_anual_opcion_1()==true &&
					verificar_primer_pago_opcion_1()==true &&

					verificar_empresas_opcion2()==true &&
					verificar_daños_opcion2_selec()==true &&
					verificar_daños_material_importe_factura_2()==true &&
					verificar_deducible_opcion2()==true &&
					verificar_cristales_opcion2_selec()==true &&
					verificar_robo_opcion2_selec()==true &&
					verificar_robo_importe_factura_2()==true &&
					verificar_deducible_rt_opcion2()==true &&
					verificar_daños_tercero_opcion_2()==true &&
					verificar_deducible_de_rc2()==true &&
					verificar_fallecimiento_opcion_2()==true &&
					verificar_gastos_medicos_opcion_2()==true &&
					verificar_accidente_conducir_opcion_2()==true &&
					verificar_daños_carga_opcion_selec_2()==true &&
					verificar_adaptaciones_opcion_2()==true &&
					verificar_extension_rc_opcion2()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_2_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion2()==true &&
				//	verificar_cantidad_total_anual_opcion_2()==true &&
					verificar_primer_pago_opcion_2()==true&&

					verificar_empresas_opcion3()==true &&
					verificar_daños_opcion3_selec()==true &&
					verificar_daños_material_importe_factura_3()==true &&
					verificar_deducible_opcion3()==true &&
					verificar_cristales_opcion3_selec()==true &&
					verificar_robo_opcion3_selec()==true &&
					verificar_robo_importe_factura_3()==true &&
					verificar_deducible_rt_opcion3()==true &&
					verificar_daños_tercero_opcion_3()==true &&
					verificar_deducible_de_rc3()==true &&
					verificar_fallecimiento_opcion_3()==true &&
					verificar_gastos_medicos_opcion_3()==true &&
					verificar_accidente_conducir_opcion_3()==true &&
					verificar_daños_carga_opcion_selec_3()==true &&
					verificar_adaptaciones_opcion_3()==true &&
					verificar_extension_rc_opcion3()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_3_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_3_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion3()==true &&
				//	verificar_cantidad_total_anual_opcion_3()==true &&
					verificar_primer_pago_opcion_3()==true
					)
				{
					//console.log('si paso');
					/*	parametros.forEach((value,key)=>{
						console.log('nombre: '+key+' - valor: '+value);
					});*/
					$.ajax({
						data:parametros,
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						context:this,
						contentType:false,
						processData:false,
						cache:false,
						success:function(data)
						{
							var datos_separados=data.split('*');
							if(data[0].trim()=='1')
							{

								$('#boton_pdf').val(datos_separados[1]+'*'+datos_separados[2]);
								document.getElementById('formulario_cotizacion').reset();
								$('#contenedor_formacion_poliza_renovar').slideUp('fast');
								$('#contenedor_general_prospectos').slideUp('fast');
								$('#contactos option:eq(0)').prop('selected',true);
								$('#prospectos_asegurados option:eq(0)').prop('selected',true);
								$('#cantidad_subsecuentes').html('');

								$.ajax({
							    		url:'metodos/alta_contactos_metodos.php',
										type:'post',
										data:{
												contactos_lista:2
										},
										success:function(data)
										{
											$('#contactos').html(data);
										}	
							    });


							    $.ajax({
							    		url:'metodos/prospectos_metodos.php',
										type:'post',
										data:{
												prospectos_lista:3
										},
										success:function(data)
										{
											$('#prospectos_asegurados').html(data);
										}	
							    });

							    $('#cotizacion_capturada').modal({show:true});
							}
							else
							{

							}
						}
					});

				}
				else
				{
					console.log("error");
				}
			break;

			case '4':
					if(
					verificar_empresas_opcion1()==true &&
					verificar_daños_opcion1_selec()==true &&
					verificar_daños_material_importe_factura_1()==true &&
					verificar_deducible_opcion1()==true &&
					verificar_cristales_opcion1_selec()==true &&
					verificar_robo_opcion1_selec()==true &&
					verificar_robo_importe_factura_1()==true &&
					verificar_deducible_rt_opcion1()==true &&
					verificar_daños_tercero_opcion_1()==true &&
					verificar_deducible_de_rc1()==true &&
					verificar_fallecimiento_opcion_1()==true &&
					verificar_gastos_medicos_opcion_1()==true &&
					verificar_accidente_conducir_opcion_1()==true &&
					verificar_daños_carga_opcion_selec_1()==true &&
					verificar_adaptaciones_opcion_1()==true &&
					verificar_extension_rc_opcion1()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_1_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_1_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion1()==true &&
				//	verificar_cantidad_total_anual_opcion_1()==true &&
					verificar_primer_pago_opcion_1()==true &&

					verificar_empresas_opcion2()==true &&
					verificar_daños_opcion2_selec()==true &&
					verificar_daños_material_importe_factura_2()==true &&
					verificar_deducible_opcion2()==true &&
					verificar_cristales_opcion2_selec()==true &&
					verificar_robo_opcion2_selec()==true &&
					verificar_robo_importe_factura_2()==true &&
					verificar_deducible_rt_opcion2()==true &&
					verificar_daños_tercero_opcion_2()==true &&
					verificar_deducible_de_rc2()==true &&
					verificar_fallecimiento_opcion_2()==true &&
					verificar_gastos_medicos_opcion_2()==true &&
					verificar_accidente_conducir_opcion_2()==true &&
					verificar_daños_carga_opcion_selec_2()==true &&
					verificar_adaptaciones_opcion_2()==true &&
					verificar_extension_rc_opcion2()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_2_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion2()==true &&
				//	verificar_cantidad_total_anual_opcion_2()==true &&
					verificar_primer_pago_opcion_2()==true&&

					verificar_empresas_opcion3()==true &&
					verificar_daños_opcion3_selec()==true &&
					verificar_daños_material_importe_factura_3()==true &&
					verificar_deducible_opcion3()==true &&
					verificar_cristales_opcion3_selec()==true &&
					verificar_robo_opcion3_selec()==true &&
					verificar_robo_importe_factura_3()==true &&
					verificar_deducible_rt_opcion3()==true &&
					verificar_daños_tercero_opcion_3()==true &&
					verificar_deducible_de_rc3()==true &&
					verificar_fallecimiento_opcion_3()==true &&
					verificar_gastos_medicos_opcion_3()==true &&
					verificar_accidente_conducir_opcion_3()==true &&
					verificar_daños_carga_opcion_selec_3()==true &&
					verificar_adaptaciones_opcion_3()==true &&
					verificar_extension_rc_opcion3()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_3_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_3_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion3()==true &&
				//	verificar_cantidad_total_anual_opcion_3()==true &&
					verificar_primer_pago_opcion_3()==true &&


					verificar_empresas_opcion4()==true &&
					verificar_daños_opcion4_selec()==true &&
					verificar_daños_material_importe_factura_4()==true &&
					verificar_deducible_opcion4()==true &&
					verificar_cristales_opcion4_selec()==true &&
					verificar_robo_opcion4_selec()==true &&
					verificar_robo_importe_factura_4()==true &&
					verificar_deducible_rt_opcion4()==true &&
					verificar_daños_tercero_opcion_4()==true &&
					verificar_deducible_de_rc4()==true &&
					verificar_fallecimiento_opcion_4()==true &&
					verificar_gastos_medicos_opcion_4()==true &&
					verificar_accidente_conducir_opcion_4()==true &&
					verificar_daños_carga_opcion_selec_4()==true &&
					verificar_adaptaciones_opcion_4()==true &&
					verificar_extension_rc_opcion4()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_4_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_4_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion4()==true &&
				//	verificar_cantidad_total_anual_opcion_4()==true &&
					verificar_primer_pago_opcion_4()==true
					)
				{
					//console.log('si paso');
						/*parametros.forEach((value,key)=>{
						console.log('nombre: '+key+' - valor: '+value);
					});*/
					$.ajax({
						data:parametros,
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						context:this,
						contentType:false,
						processData:false,
						cache:false,
						success:function(data)
						{
							var datos_separados=data.split('*');
							if(data[0].trim()=='1')
							{

								$('#boton_pdf').val(datos_separados[1]+'*'+datos_separados[2]);
								document.getElementById('formulario_cotizacion').reset();
								$('#contenedor_formacion_poliza_renovar').slideUp('fast');
								$('#contenedor_general_prospectos').slideUp('fast');
								$('#contactos option:eq(0)').prop('selected',true);
								$('#prospectos_asegurados option:eq(0)').prop('selected',true);
								$('#cantidad_subsecuentes').html('');

								$.ajax({
							    		url:'metodos/alta_contactos_metodos.php',
										type:'post',
										data:{
												contactos_lista:2
										},
										success:function(data)
										{
											$('#contactos').html(data);
										}	
							    });


							    $.ajax({
							    		url:'metodos/prospectos_metodos.php',
										type:'post',
										data:{
												prospectos_lista:3
										},
										success:function(data)
										{
											$('#prospectos_asegurados').html(data);
										}	
							    });

							    $('#cotizacion_capturada').modal({show:true});
							}
							else
							{

							}
						}
					});

				}
				else
				{
					console.log("error");
				}
			break;

			case '5':
					if(
					verificar_empresas_opcion1()==true &&
					verificar_daños_opcion1_selec()==true &&
					verificar_daños_material_importe_factura_1()==true &&
					verificar_deducible_opcion1()==true &&
					verificar_cristales_opcion1_selec()==true &&
					verificar_robo_opcion1_selec()==true &&
					verificar_robo_importe_factura_1()==true &&
					verificar_deducible_rt_opcion1()==true &&
					verificar_daños_tercero_opcion_1()==true &&
					verificar_deducible_de_rc1()==true &&
					verificar_fallecimiento_opcion_1()==true &&
					verificar_gastos_medicos_opcion_1()==true &&
					verificar_accidente_conducir_opcion_1()==true &&
					verificar_daños_carga_opcion_selec_1()==true &&
					verificar_adaptaciones_opcion_1()==true &&
					verificar_extension_rc_opcion1()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_1_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_1_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion1()==true &&
				//	verificar_cantidad_total_anual_opcion_1()==true &&
					verificar_primer_pago_opcion_1()==true &&

					verificar_empresas_opcion2()==true &&
					verificar_daños_opcion2_selec()==true &&
					verificar_daños_material_importe_factura_2()==true &&
					verificar_deducible_opcion2()==true &&
					verificar_cristales_opcion2_selec()==true &&
					verificar_robo_opcion2_selec()==true &&
					verificar_robo_importe_factura_2()==true &&
					verificar_deducible_rt_opcion2()==true &&
					verificar_daños_tercero_opcion_2()==true &&
					verificar_deducible_de_rc2()==true &&
					verificar_fallecimiento_opcion_2()==true &&
					verificar_gastos_medicos_opcion_2()==true &&
					verificar_accidente_conducir_opcion_2()==true &&
					verificar_daños_carga_opcion_selec_2()==true &&
					verificar_adaptaciones_opcion_2()==true &&
					verificar_extension_rc_opcion2()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_2_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion2()==true &&
				//	verificar_cantidad_total_anual_opcion_2()==true &&
					verificar_primer_pago_opcion_2()==true&&

					verificar_empresas_opcion3()==true &&
					verificar_daños_opcion3_selec()==true &&
					verificar_daños_material_importe_factura_3()==true &&
					verificar_deducible_opcion3()==true &&
					verificar_cristales_opcion3_selec()==true &&
					verificar_robo_opcion3_selec()==true &&
					verificar_robo_importe_factura_3()==true &&
					verificar_deducible_rt_opcion3()==true &&
					verificar_daños_tercero_opcion_3()==true &&
					verificar_deducible_de_rc3()==true &&
					verificar_fallecimiento_opcion_3()==true &&
					verificar_gastos_medicos_opcion_3()==true &&
					verificar_accidente_conducir_opcion_3()==true &&
					verificar_daños_carga_opcion_selec_3()==true &&
					verificar_adaptaciones_opcion_3()==true &&
					verificar_extension_rc_opcion3()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_3_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_3_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion3()==true &&
				//	verificar_cantidad_total_anual_opcion_3()==true &&
					verificar_primer_pago_opcion_3()==true &&


					verificar_empresas_opcion4()==true &&
					verificar_daños_opcion4_selec()==true &&
					verificar_daños_material_importe_factura_4()==true &&
					verificar_deducible_opcion4()==true &&
					verificar_cristales_opcion4_selec()==true &&
					verificar_robo_opcion4_selec()==true &&
					verificar_robo_importe_factura_4()==true &&
					verificar_deducible_rt_opcion4()==true &&
					verificar_daños_tercero_opcion_4()==true &&
					verificar_deducible_de_rc4()==true &&
					verificar_fallecimiento_opcion_4()==true &&
					verificar_gastos_medicos_opcion_4()==true &&
					verificar_accidente_conducir_opcion_4()==true &&
					verificar_daños_carga_opcion_selec_4()==true &&
					verificar_adaptaciones_opcion_4()==true &&
					verificar_extension_rc_opcion4()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_4_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_4_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion4()==true &&
				//	verificar_cantidad_total_anual_opcion_4()==true &&
					verificar_primer_pago_opcion_4()==true&&


					verificar_empresas_opcion5()==true &&
					verificar_daños_opcion5_selec()==true &&
					verificar_daños_material_importe_factura_5()==true &&
					verificar_deducible_opcion5()==true &&
					verificar_cristales_opcion5_selec()==true &&
					verificar_robo_opcion5_selec()==true &&
					verificar_robo_importe_factura_5()==true &&
					verificar_deducible_rt_opcion5()==true &&
					verificar_daños_tercero_opcion_5()==true &&
					verificar_deducible_de_rc5()==true &&
					verificar_fallecimiento_opcion_5()==true &&
					verificar_gastos_medicos_opcion_5()==true &&
					verificar_accidente_conducir_opcion_5()==true &&
					verificar_daños_carga_opcion_selec_5()==true &&
					verificar_adaptaciones_opcion_5()==true &&
					verificar_extension_rc_opcion5()==true &&
					verificar_cobertura_opcion_1()==true &&
					verificar_cobertura_opcion_5_select()==true &&
					verificar_cobertura_opcion_2()==true &&
					verificar_cobertura_opcion_2_5_select()==true &&
					verificar_forma_de_pago()==true &&
				//	verificar_cantidad_prima_neta_opcion5()==true &&
				//	verificar_cantidad_total_anual_opcion_5()==true &&
					verificar_primer_pago_opcion_5()==true
					)
				{
					//console.log('si paso');
					/*	parametros.forEach((value,key)=>{
						console.log('nombre: '+key+' - valor: '+value);
					});*/
					$.ajax({
						data:parametros,
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						context:this,
						contentType:false,
						processData:false,
						cache:false,
						success:function(data)
						{
							var datos_separados=data.split('*');
							if(data[0].trim()=='1')
							{

								$('#boton_pdf').val(datos_separados[1]+'*'+datos_separados[2]);
								document.getElementById('formulario_cotizacion').reset();
								$('#contenedor_formacion_poliza_renovar').slideUp('fast');
								$('#contenedor_general_prospectos').slideUp('fast');
								$('#contactos option:eq(0)').prop('selected',true);
								$('#prospectos_asegurados option:eq(0)').prop('selected',true);
								$('#cantidad_subsecuentes').html('');

								$.ajax({
							    		url:'metodos/alta_contactos_metodos.php',
										type:'post',
										data:{
												contactos_lista:2
										},
										success:function(data)
										{
											$('#contactos').html(data);
										}	
							    });


							    $.ajax({
							    		url:'metodos/prospectos_metodos.php',
										type:'post',
										data:{
												prospectos_lista:3
										},
										success:function(data)
										{
											$('#prospectos_asegurados').html(data);
										}	
							    });

							    $('#cotizacion_capturada').modal({show:true});
							}
							else
							{

							}
						}
					});

				}
				else
				{
					console.log("error");
				}
			break;

			default:
				console.log("no entra en los cases");
			break;
		}
		
	}
	else
	{
		console.log("no paso");
	}
});

function verificar_empresas_opcion1()
{
	var opcion1=$('#empresas_opcion1').val();
	var opcion2=$('#empresas_opcion2').val();
	var opcion3=$('#empresas_opcion3').val();
	var opcion4=$('#empresas_opcion4').val();
	var opcion5=$('#empresas_opcion5').val();
	var j=0;
	var cantidad=$('#cantidad_aseguradoras').val();
	if(opcion1==0)
	{
		switch(cantidad)
		{
			case 2:
			$('#empresas_opcion2').prop('disabled',true);
			break;

			case 3:
			$('#empresas_opcion2').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);
			break;
			
			case 4:
			$('#empresas_opcion2').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);

			break;
			
			case 5:
			$('#empresas_opcion2').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);

			break;

		}
		

		$('#error_empresas_opcion1').slideDown('fast');
		$('#error_empresas_opcion1').html('¡Debes seleccionar una aseguradora!');
		$('#empresas_opcion1').focus();
		return false;
	}
	else
	{
		switch(cantidad)
		{
			case 2:
			$('#empresas_opcion2').prop('disabled',false);
			break;

			case 3:
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			break;
			
			case 4:
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);

			break;
			
			case 5:
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);
			$('#empresas_opcion5').prop('disabled',false);

			break;

		}
		
			if(opcion2!='0' && opcion2==opcion1) 
			{
				$('#error_empresas_opcion1').slideDown('fast');
				$('#error_empresas_opcion1').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion1').focus();
				return false;
			}
			else if(opcion3!='0' && opcion3==opcion1)
			{
					$('#error_empresas_opcion1').slideDown('fast');
				$('#error_empresas_opcion1').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion1').focus();
				return false;
	
			}		
			else if(opcion4!='0' && opcion4==opcion1)
			{
				$('#error_empresas_opcion1').slideDown('fast');
				$('#error_empresas_opcion1').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion1').focus();
				return false;

			}
			else if(opcion5!='0' && opcion5==opcion1)
			{
				$('#error_empresas_opcion1').slideDown('fast');
				$('#error_empresas_opcion1').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion1').focus();
				return false;

			}
			else
			{
				$('#error_empresas_opcion1').slideUp('fast');
				return true;
			}

		
	}
}

function verificar_empresas_opcion2()
{
	var opcion1=$('#empresas_opcion1').val();
	var opcion2=$('#empresas_opcion2').val();
	var opcion3=$('#empresas_opcion3').val();
	var opcion4=$('#empresas_opcion4').val();
	var opcion5=$('#empresas_opcion5').val();
	var j=0;
	var cantidad=$('#cantidad_aseguradoras').val();

	if(opcion2==0)
	{
		switch(cantidad)
		{
			case 2:
			$('#empresas_opcion1').prop('disabled',true);
			break;

			case 3:
			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);
			break;
			
			case 4:
			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);

			break;
			
			case 5:
			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);

			break;

		}

		$('#error_empresas_opcion2').slideDown('fast');
		$('#error_empresas_opcion2').html('¡Debes seleccionar una aseguradora!');
		$('#empresas_opcion2').focus();
		return false;
	}
	else
	{
		switch(cantidad)
		{
			case 2:
			$('#empresas_opcion1').prop('disabled',false);
			break;

			case 3:
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			break;
			
			case 4:
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);

			break;
			
			case 5:
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);
			$('#empresas_opcion5').prop('disabled',false);

			break;

		}
		
			if(opcion1!='0' && opcion1==opcion2) 
			{
				$('#error_empresas_opcion2').slideDown('fast');
				$('#error_empresas_opcion2').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion2').focus();
				return false;
			}
			else if(opcion3!='0' && opcion3==opcion2)
			{
					$('#error_empresas_opcion2').slideDown('fast');
				$('#error_empresas_opcion2').html('¡Ya se encuentra selecciona la asegurada.<br> Selecciona una diferente!');
				$('#empresas_opcion2').focus();
				return false;
	
			}		
			else if(opcion4!='0' && opcion4==opcion2)
			{
						$('#error_empresas_opcion2').slideDown('fast');
				$('#error_empresas_opcion2').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion2').focus();
				return false;

			}
			else if(opcion5!='0' && opcion5==opcion2)
			{
								$('#error_empresas_opcion2').slideDown('fast');
				$('#error_empresas_opcion2').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion2').focus();
				return false;

			}
			else
			{
				$('#error_empresas_opcion2').slideUp('fast');
				return true;
			}
	}
}
function verificar_empresas_opcion3()
{
	var i=0;
	var opcion1=$('#empresas_opcion1').val();
	var opcion2=$('#empresas_opcion2').val();
	var opcion3=$('#empresas_opcion3').val();
	var opcion4=$('#empresas_opcion4').val();
	var opcion5=$('#empresas_opcion5').val();
	var cantidad=$('#cantidad_aseguradoras').val();

	if(opcion3==0)
	{

		switch(cantidad)
		{
			case 3:
			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion2').prop('disabled',true);
			break;
			
			case 4:
			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion2').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);

			break;
			
			case 5:
			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion2').prop('disabled',true);
			$('#empresas_opcion4').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);

			break;

		}
		
		
		$('#error_empresas_opcion3').slideDown('fast');
		$('#error_empresas_opcion3').html('¡Debes seleccionar una aseguradora!');
		
		$('#empresas_opcion3').focus();
		return false;
	}
	else
	{
		switch(cantidad)
		{
			case 3:
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			break;
			
			case 4:
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);

			break;
			
			case 5:
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion4').prop('disabled',false);
			$('#empresas_opcion5').prop('disabled',false);

			break;
		}
		
		
			if(opcion2!='0' && opcion2==opcion3) 
			{
				$('#error_empresas_opcion3').slideDown('fast');
				$('#error_empresas_opcion3').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion3').focus();
				return false;
			}
			else if(opcion1!='0' && opcion1==opcion3)
			{
					$('#error_empresas_opcion3').slideDown('fast');
				$('#error_empresas_opcion3').html('¡Ya se encuentra selecciona la asegurada. <br>Selecciona una diferente!');
				$('#empresas_opcion3').focus();
				return false;
	
			}		
			else if(opcion4!='0' && opcion4==opcion3)
			{
						$('#error_empresas_opcion3').slideDown('fast');
				$('#error_empresas_opcion3').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion3').focus();
				return false;

			}
			else if(opcion5!='0' && opcion5==opcion3)
			{
								$('#error_empresas_opcion3').slideDown('fast');
				$('#error_empresas_opcion3').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion3').focus();
				return false;

			}
			else
			{
				$('#error_empresas_opcion3').slideUp('fast');
				return true;
			}
	}
}
function verificar_empresas_opcion4()
{
	var j=1;
	var opcion1=$('#empresas_opcion1').val();
	var opcion2=$('#empresas_opcion2').val();
	var opcion3=$('#empresas_opcion3').val();
	var opcion4=$('#empresas_opcion4').val();
	var opcion5=$('#empresas_opcion5').val();

	var cantidad=$('#cantidad_aseguradoras').val();
	if(opcion4==0)
	{

		switch(cantidad)
		{
			case 4:
			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion2').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);

			break;
			
			case 5:
			$('#empresas_opcion1').prop('disabled',true);
			$('#empresas_opcion2').prop('disabled',true);
			$('#empresas_opcion3').prop('disabled',true);
			$('#empresas_opcion5').prop('disabled',true);

			break;
		}
		
		$('#error_empresas_opcion4').slideDown('fast');
		$('#error_empresas_opcion4').html('¡Debes seleccionar una aseguradora!');
		
		$('#empresas_opcion4').focus();
		return false;
	}
	else
	{
		switch(cantidad)
		{
			case 4:
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);

			break;
			
			case 5:
			$('#empresas_opcion1').prop('disabled',false);
			$('#empresas_opcion2').prop('disabled',false);
			$('#empresas_opcion3').prop('disabled',false);
			$('#empresas_opcion5').prop('disabled',false);

			break;
		}

			if(opcion2!='0' && opcion2==opcion4) 
			{
				$('#error_empresas_opcion4').slideDown('fast');
				$('#error_empresas_opcion4').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion4').focus();
				return false;
			}
			else if(opcion3!='0' && opcion3==opcion4)
			{
					$('#error_empresas_opcion4').slideDown('fast');
				$('#error_empresas_opcion4').html('¡Ya se encuentra selecciona la asegurada.<br> Selecciona una diferente!');
				$('#empresas_opcion4').focus();
				return false;
	
			}		
			else if(opcion1!='0' && opcion1==opcion4)
			{
						$('#error_empresas_opcion4').slideDown('fast');
				$('#error_empresas_opcion4').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion4').focus();
				return false;

			}
			else if(opcion5!='0' && opcion5==opcion4)
			{
								$('#error_empresas_opcion4').slideDown('fast');
				$('#error_empresas_opcion4').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion4').focus();
				return false;

			}
			else
			{
				$('#error_empresas_opcion4').slideUp('fast');
				return true;
			}
	}
}
function verificar_empresas_opcion5()
{
	var j=0;
	var opcion1=$('#empresas_opcion1').val();
	var opcion2=$('#empresas_opcion2').val();
	var opcion3=$('#empresas_opcion3').val();
	var opcion4=$('#empresas_opcion4').val();
	var opcion5=$('#empresas_opcion5').val();
	if(opcion5==0)
	{
			
		$('#empresas_opcion1').prop('disabled',true);
		$('#empresas_opcion2').prop('disabled',true);
		$('#empresas_opcion3').prop('disabled',true);
		$('#empresas_opcion4').prop('disabled',true);

		$('#error_empresas_opcion5').slideDown('fast');
		$('#error_empresas_opcion5').html('¡Debes seleccionar una aseguradora!');
		
		$('#empresas_opcion5').focus();
		return false;
	}
	else
	{

		$('#empresas_opcion1').prop('disabled',false);
		$('#empresas_opcion2').prop('disabled',false);
		$('#empresas_opcion3').prop('disabled',false);
		$('#empresas_opcion4').prop('disabled',false);

		if(opcion2!='0' && opcion2==opcion5) 
			{
				$('#error_empresas_opcion5').slideDown('fast');
				$('#error_empresas_opcion5').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion1').focus();
				return false;
			}
			else if(opcion3!='0' && opcion3==opcion5)
			{
					$('#error_empresas_opcion5').slideDown('fast');
				$('#error_empresas_opcion5').html('¡Ya se encuentra selecciona la asegurada. Selecciona una diferente!');
				$('#empresas_opcion5').focus();
				return false;
	
			}		
			else if(opcion4!='0' && opcion4==opcion5)
			{
						$('#error_empresas_opcion5').slideDown('fast');
				$('#error_empresas_opcion5').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion5').focus();
				return false;

			}
			else if(opcion1!='0' && opcion1==opcion5)
			{
								$('#error_empresas_opcion5').slideDown('fast');
				$('#error_empresas_opcion5').html('¡Ya se encuentra selecciona la asegurada.<br>Selecciona una diferente!');
				$('#empresas_opcion5').focus();
				return false;

			}
			else
			{
				$('#error_empresas_opcion5').slideUp('fast');
				return true;
			}
	}
}


function verificar_tipo_cotizacion()
{
	var opcion=$('#tipo_cotizacion').val();
	if(opcion==0)
	{
		$('#error_tipo_cotizacion').slideDown('fast');
		$('#error_tipo_cotizacion').html('¡Debes seleccionar un tipo de cotizacion!');
		
		$('#tipo_cotizacion').focus();
		return false;
	}
	else
	{

		$('#error_tipo_cotizacion').slideUp('fast');
		return true;
	}
}

function verificar_daños_opcion1_selec()
{
	if($('#daños_opcion1_selec').is(':enabled')===true)
	{
		var opcion=$('#daños_opcion1_selec').val();
		if(opcion==0)
		{
			$('#daños_material_importe_factura_1').prop('disabled',true);
				$('#robo_opcion1_selec option:eq(0)').prop('selected',true);
				$('#robo_importe_factura_1').prop('disabled',true);
			$('#error_daños_opcion1_selec').slideDown('fast');
			$('#error_daños_opcion1_selec').html('¡Debes seleccionar un tipo de daño!');
			
			$('#daños_opcion1_selec').focus();
			return false;
		}
		else
		{

			switch(opcion)
			{
				

				case'V.COMERCIAL':
				$('#daños_material_importe_factura_1').prop('disabled',true);
				$('#robo_opcion1_selec option:eq(1)').prop('selected',true);
				$('#robo_importe_factura_1').prop('disabled',true);
				break;

				case'V.CONVENIDO':
				$('#daños_material_importe_factura_1').prop('disabled',true);
				$('#robo_opcion1_selec option:eq(2)').prop('selected',true);
				$('#robo_importe_factura_1').prop('disabled',true);
				break;
				
				case'V.FACTURA':
				$('#daños_material_importe_factura_1').prop('disabled',false);
				$('#robo_opcion1_selec option:eq(3)').prop('selected',true);
				$('#robo_importe_factura_1').prop('disabled',false);
				break;
			}

			$('#error_daños_opcion1_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_daños_opcion1_selec').slideUp('fast');
			return true;
	}
	
}
function verificar_daños_opcion2_selec()
{
	if($('#daños_opcion2_selec').is(':enabled')===true)
	{
		var opcion=$('#daños_opcion2_selec').val();
		if(opcion==0)
		{
				$('#daños_material_importe_factura_2').prop('disabled',true);
				$('#robo_opcion2_selec option:eq(0)').prop('selected',true);
				$('#robo_importe_factura_2').prop('disabled',true);

			$('#error_daños_opcion2_selec').slideDown('fast');
			$('#error_daños_opcion2_selec').html('¡Debes seleccionar un tipo de daño!');
			
			$('#daños_opcion2_selec').focus();
			return false;
		}
		else
		{

			switch(opcion)
			{
				

				case'V.COMERCIAL':
				$('#daños_material_importe_factura_2').prop('disabled',true);
				$('#robo_opcion2_selec option:eq(1)').prop('selected',true);
				$('#robo_importe_factura_2').prop('disabled',true);
				break;

				case'V.CONVENIDO':
				$('#daños_material_importe_factura_2').prop('disabled',true);
				$('#robo_opcion2_selec option:eq(2)').prop('selected',true);
				$('#robo_importe_factura_2').prop('disabled',true);
				break;
				
				case'V.FACTURA':
				$('#daños_material_importe_factura_2').prop('disabled',false);
				$('#robo_opcion2_selec option:eq(3)').prop('selected',true);
				$('#robo_importe_factura_2').prop('disabled',false);
				break;
			}

			$('#error_daños_opcion2_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_daños_opcion2_selec').slideUp('fast');
		return true;
	}
}
function verificar_daños_opcion3_selec()
{
	if($('#daños_opcion3_selec').is(':enabled')===true)
	{
		var opcion=$('#daños_opcion3_selec').val();
		if(opcion==0)
		{
			$('#daños_material_importe_factura_3').prop('disabled',true);
			$('#robo_opcion3_selec option:eq(0)').prop('selected',true);
			$('#robo_importe_factura_3').prop('disabled',true);

			$('#error_daños_opcion3_selec').slideDown('fast');
			$('#error_daños_opcion3_selec').html('¡Debes seleccionar un tipo de daño!');
			
			$('#daños_opcion3_selec').focus();
			return false;
		}
		else
		{
			switch(opcion)
			{
				

				case'V.COMERCIAL':
				$('#daños_material_importe_factura_3').prop('disabled',true);
				$('#robo_opcion3_selec option:eq(1)').prop('selected',true);
				$('#robo_importe_factura_3').prop('disabled',true);
				break;

				case'V.CONVENIDO':
				$('#daños_material_importe_factura_3').prop('disabled',true);
				$('#robo_opcion3_selec option:eq(2)').prop('selected',true);
				$('#robo_importe_factura_3').prop('disabled',true);
				break;
				
				case'V.FACTURA':
				$('#daños_material_importe_factura_3').prop('disabled',false);
				$('#robo_opcion3_selec option:eq(3)').prop('selected',true);
				$('#robo_importe_factura_3').prop('disabled',false);
				break;
			}
			$('#error_daños_opcion3_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
			$('#error_daños_opcion3_selec').slideUp('fast');
			return true;
	}
}
function verificar_daños_opcion4_selec()
{
if($('#daños_opcion4_selec').is(':enabled')===true)
{
	var opcion=$('#daños_opcion4_selec').val();
	if(opcion==0)
	{
		$('#daños_material_importe_factura_4').prop('disabled',true);
		$('#robo_opcion4_selec option:eq(0)').prop('selected',true);
		$('#robo_importe_factura_2').prop('disabled',true);

		$('#error_daños_opcion4_selec').slideDown('fast');
		$('#error_daños_opcion4_selec').html('¡Debes seleccionar un tipo de daño!');
		
		$('#daños_opcion4_selec').focus();
		return false;
	}
	else
	{
		switch(opcion)
		{
			

			case'V.COMERCIAL':
			$('#daños_material_importe_factura_4').prop('disabled',true);
			$('#robo_opcion4_selec option:eq(1)').prop('selected',true);
			$('#robo_importe_factura_4').prop('disabled',true);
			break;

			case'V.CONVENIDO':
			$('#daños_material_importe_factura_4').prop('disabled',true);
			$('#robo_opcion4_selec option:eq(2)').prop('selected',true);
			$('#robo_importe_factura_4').prop('disabled',true);
			break;
			
			case'V.FACTURA':
			$('#daños_material_importe_factura_4').prop('disabled',false);
			$('#robo_opcion4_selec option:eq(3)').prop('selected',true);
			$('#robo_importe_factura_4').prop('disabled',false);
			break;
		}
		$('#error_daños_opcion4_selec').slideUp('fast');
		return true;
	}
}
else
{
	$('#error_daños_opcion4_selec').slideUp('fast');
	return true;
}
}
function verificar_daños_opcion5_selec()
{
	if($('#daños_opcion5_selec').is(':enabled')===true)
	{
		var opcion=$('#daños_opcion5_selec').val();
		if(opcion==0)
		{
			$('#daños_material_importe_factura_5').prop('disabled',true);
			$('#robo_opcion5_selec option:eq(0)').prop('selected',true);
			$('#robo_importe_factura_5').prop('disabled',true);

			$('#error_daños_opcion5_selec').slideDown('fast');
			$('#error_daños_opcion5_selec').html('¡Debes seleccionar un tipo de daño!');
			
			$('#daños_opcion5_selec').focus();
			return false;
		}
		else
		{
			switch(opcion)
			{
				case'V.COMERCIAL':
				$('#daños_material_importe_factura_5').prop('disabled',true);
				$('#robo_opcion5_selec option:eq(1)').prop('selected',true);
				$('#robo_importe_factura_5').prop('disabled',true);
				break;

				case'V.CONVENIDO':
				$('#daños_material_importe_factura_5').prop('disabled',true);
				$('#robo_opcion5_selec option:eq(2)').prop('selected',true);
				$('#robo_importe_factura_5').prop('disabled',true);
				break;
				
				case'V.FACTURA':
				$('#daños_material_importe_factura_5').prop('disabled',false);
				$('#robo_opcion5_selec option:eq(3)').prop('selected',true);
				$('#robo_importe_factura_5').prop('disabled',false);
				break;
			}
			$('#error_daños_opcion5_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_daños_opcion5_selec').slideUp('fast');
			return true;
	}
}


function verificar_daños_material_importe_factura_1()
{

	var dato=$('#daños_material_importe_factura_1').val();
	dato=dato.replace(/,/g, "");
	if($('#daños_opcion1_selec option:selected').val()=='V.FACTURA')
	{
		if(dato=='' ||dato==0)
		{
			$('#robo_importe_factura_1').val('');
			$('#error_daños_material_importe_factura_1').slideDown('fast');
			$('#error_daños_material_importe_factura_1').html('¡Debes ingresar una cantidad!');
			
			$('#daños_material_importe_factura_1').focus();
			return false;
		}
		else
		{

			if((dato.split('.').length-1)>1)
			{
				$('#error_daños_material_importe_factura_1').slideDown('fast');
				$('#error_daños_material_importe_factura_1').html('¡Ingresa un número valido!');
				$('#daños_material_importe_factura_1').focus();
				return false;
			}
			else
			{
				dato=formatoMexico(dato);
				//$('#robo_importe_factura_1').val('');
				$('#daños_material_importe_factura_1').val(dato);
				$('#robo_importe_factura_1').val(dato);
				$('#error_daños_material_importe_factura_1').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
		$('#error_daños_material_importe_factura_1').slideUp('fast');
		return true;
	}
	
}

function verificar_daños_material_importe_factura_2()
{
	var dato2=$('#daños_material_importe_factura_2').val();
	dato2=dato2.replace(/,/g, "");
	if($('#daños_opcion2_selec option:selected').val()=='V.FACTURA')
	{
		if(dato2=='' ||dato2==0)
		{
			$('#robo_importe_factura_2').val('');
			$('#error_daños_material_importe_factura_2').slideDown('fast');
			$('#error_daños_material_importe_factura_2').html('¡Debes ingresar una cantidad!');
			
			$('#daños_material_importe_factura_2').focus();
			return false;
		}
		else
		{

			if((dato2.split('.').length-1)>1)
			{
				$('#error_daños_material_importe_factura_2').slideDown('fast');
				$('#error_daños_material_importe_factura_2').html('¡Ingresa un número valido!');
				$('#daños_material_importe_factura_2').focus();
				return false;
			}
			else
			{
				dato2=formatoMexico(dato2);
				//$('#robo_importe_factura_1').val('');
				$('#daños_material_importe_factura_2').val(dato2);
				$('#robo_importe_factura_2').val(dato2);
				$('#error_daños_material_importe_factura_2').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
		$('#error_daños_material_importe_factura_2').slideUp('fast');
		return true;
	}
}

function verificar_daños_material_importe_factura_3()
{
	var dato3=$('#daños_material_importe_factura_3').val();
	dato3=dato3.replace(/,/g, "");
	if($('#daños_opcion3_selec option:selected').val()=='V.FACTURA')
	{
		if(dato3=='' ||dato3==0)
		{
			$('#robo_importe_factura_3').val('');
			$('#error_daños_material_importe_factura_3').slideDown('fast');
			$('#error_daños_material_importe_factura_3').html('¡Debes ingresar una cantidad!');
			
			$('#daños_material_importe_factura_3').focus();
			return false;
		}
		else
		{

			if((dato3.split('.').length-1)>1)
			{
				$('#error_daños_material_importe_factura_3').slideDown('fast');
				$('#error_daños_material_importe_factura_3').html('¡Ingresa un número valido!');
				$('#daños_material_importe_factura_3').focus();
				return false;
			}
			else
			{
				dato3=formatoMexico(dato3);
				//$('#robo_importe_factura_1').val('');
				$('#daños_material_importe_factura_3').val(dato3);
				$('#robo_importe_factura_3').val(dato3);
				$('#error_daños_material_importe_factura_3').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
		$('#error_daños_material_importe_factura_3').slideUp('fast');
		return true;
	}
}

function verificar_daños_material_importe_factura_4()
{
	var dato4=$('#daños_material_importe_factura_4').val();
	dato4=dato4.replace(/,/g, "");
	if($('#daños_opcion3_selec option:selected').val()=='V.FACTURA')
	{
		if(dato4=='' ||dato4==0)
		{
			$('#robo_importe_factura_4').val('');
			$('#error_daños_material_importe_factura_4').slideDown('fast');
			$('#error_daños_material_importe_factura_4').html('¡Debes ingresar una cantidad!');
			
			$('#daños_material_importe_factura_4').focus();
			return false;
		}
		else
		{

			if((dato4.split('.').length-1)>1)
			{
				$('#error_daños_material_importe_factura_4').slideDown('fast');
				$('#error_daños_material_importe_factura_4').html('¡Ingresa un número valido!');
				$('#daños_material_importe_factura_4').focus();
				return false;
			}
			else
			{
				dato4=formatoMexico(dato4);
				//$('#robo_importe_factura_1').val('');
				$('#daños_material_importe_factura_4').val(dato4);
				$('#robo_importe_factura_4').val(dato4);
				$('#error_daños_material_importe_factura_4').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
		$('#error_daños_material_importe_factura_4').slideUp('fast');
		return true;
	}
}

function verificar_daños_material_importe_factura_5()
{
	var dato5=$('#daños_material_importe_factura_5').val();
	dato5=dato5.replace(/,/g, "");
	if($('#daños_opcion5_selec option:selected').val()=='V.FACTURA')
	{
		if(dato5=='' ||dato5==0)
		{
			$('#robo_importe_factura_5').val('');

			$('#error_daños_material_importe_factura_5').slideDown('fast');
			$('#error_daños_material_importe_factura_5').html('¡Debes ingresar una cantidad!');
			
			$('#daños_material_importe_factura_5').focus();
			return false;
		}
		else
		{

			if((dato5.split('.').length-1)>1)
			{
				$('#error_daños_material_importe_factura_5').slideDown('fast');
				$('#error_daños_material_importe_factura_5').html('¡Ingresa un número valido!');
				$('#daños_material_importe_factura_5').focus();
				return false;
			}
			else
			{
				dato5=formatoMexico(dato5);
				//$('#robo_importe_factura_1').val('');
				$('#daños_material_importe_factura_5').val(dato5);
				$('#robo_importe_factura_5').val(dato5);
				$('#error_daños_material_importe_factura_5').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
		$('#error_daños_material_importe_factura_5').slideUp('fast');
		return true;
	}
}

function verificar_robo_importe_factura_1()
{
	//console.log("hola");
	var dato5=$('#robo_importe_factura_1').val();
	dato5=dato5.replace(/,/g, "");
	if($('#robo_opcion1_selec option:selected').val()=='V.FACTURA')
	{
		if(dato5=='' ||dato5==0)
		{
			$('#error_robo_importe_factura_1').slideDown('fast');
			$('#error_robo_importe_factura_1').html('¡Debes ingresar una cantidad!');
			
			$('#robo_importe_factura_1').focus();
			return false;
		}
		else
		{
			//console.log(dato5);
			if((dato5.split('.').length-1)>1)
			{
				$('#error_robo_importe_factura_1').slideDown('fast');
				$('#error_robo_importe_factura_1').html('¡Ingresa un número valido!');
				$('#robo_importe_factura_1').focus();
				return false;
			}
			else
			{
				//dato5=formatoMexico(dato5);
				//$('#robo_importe_factura_1').val('');
				dato5=formatoMexico(dato5);
				$('#robo_importe_factura_1').val(dato5);
				$('#error_robo_importe_factura_1').slideUp('fast');
				return true;
			}
		}	
		/*else if (parseInt(dato5)<0)
		{
			$('#error_robo_importe_factura_1').slideDown('fast');
			$('#error_robo_importe_factura_1').html('¡No esta permitido números negativos!');
			$('#robo_importe_factura_1').focus();
			return false;
		}
		else
		{
			$('#error_robo_importe_factura_1').slideUp('fast');
			return true;
		}*/
	}
	else
	{
		$('#error_robo_importe_factura_1').slideUp('fast');
		return true;
	}
}

function verificar_robo_importe_factura_2()
{
	var dato5=$('#robo_importe_factura_2').val();
	dato5=dato5.replace(/,/g, "");
	if($('#robo_opcion2_selec option:selected').val()=='V.FACTURA')
	{
		if(dato5=='' ||dato5==0)
		{
			$('#error_robo_importe_factura_2').slideDown('fast');
			$('#error_robo_importe_factura_2').html('¡Debes ingresar una cantidad!');
			
			$('#robo_importe_factura_2').focus();
			return false;
		}
		else
		{
			if((dato5.split('.').length-1)>1)
			{
				$('#error_robo_importe_factura_2').slideDown('fast');
				$('#error_robo_importe_factura_2').html('¡Ingresa un número valido!');
				$('#robo_importe_factura_1').focus();
				return false;
			}
			else
			{
				dato5=formatoMexico(dato5);
				//$('#robo_importe_factura_1').val('');
				$('#robo_importe_factura_2').val(dato5);
				$('#error_robo_importe_factura_2').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
		$('#error_robo_importe_factura_2').slideUp('fast');
			return true;
	}
}


function verificar_robo_importe_factura_3()
{
	var dato5=$('#robo_importe_factura_3').val();
	dato5=dato5.replace(/,/g, "");
	if($('#robo_opcion3_selec option:selected').val()=='V.FACTURA')
	{
		if(dato5=='' ||dato5==0)
		{
			$('#error_robo_importe_factura_3').slideDown('fast');
			$('#error_robo_importe_factura_3').html('¡Debes ingresar una cantidad!');
			
			$('#robo_importe_factura_3').focus();
			return false;
		}
		else
		{

			if((dato5.split('.').length-1)>1)
			{
				$('#error_robo_importe_factura_3').slideDown('fast');
				$('#error_robo_importe_factura_3').html('¡Ingresa un número valido!');
				$('#robo_importe_factura_3').focus();
				return false;
			}
			else
			{
				dato5=formatoMexico(dato5);
				//$('#robo_importe_factura_1').val('');
				$('#robo_importe_factura_3').val(dato5);
				$('#error_robo_importe_factura_3').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
		$('#error_robo_importe_factura_3').slideUp('fast');
			return true;
	}
}

function verificar_robo_importe_factura_4()
{
	var dato5=$('#robo_importe_factura_4').val();
	dato5=dato5.replace(/,/g, "");
	if($('#robo_opcion4_selec option:selected').val()=='V.FACTURA')
	{
	
		if(dato5=='' ||dato5==0)
		{
			$('#error_robo_importe_factura_4').slideDown('fast');
			$('#error_robo_importe_factura_4').html('¡Debes ingresar una cantidad!');
			
			$('#robo_importe_factura_4').focus();
			return false;
		}
		else
		{
			if((dato5.split('.').length-1)>1)
			{
				$('#error_robo_importe_factura_4').slideDown('fast');
				$('#error_robo_importe_factura_4').html('¡Ingresa un número valido!');
				$('#robo_importe_factura_4').focus();
				return false;
			}
			else
			{
				dato5=formatoMexico(dato5);
				//$('#robo_importe_factura_1').val('');
				$('#robo_importe_factura_4').val(dato5);
				$('#error_robo_importe_factura_4').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
			$('#error_robo_importe_factura_4').slideUp('fast');
			return true;
	}
}

function verificar_robo_importe_factura_5()
{
	var dato5=$('#robo_importe_factura_5').val();
	dato5=dato5.replace(/,/g, "");
	if($('#robo_opcion5_selec option:selected').val()=='V.FACTURA')
	{
		if(dato5=='' ||dato5==0)
		{
			$('#error_robo_importe_factura_5').slideDown('fast');
			$('#error_robo_importe_factura_5').html('¡Debes ingresar una cantidad!');
			
			$('#robo_importe_factura_5').focus();
			return false;
		}
		else
		{
			if((dato5.split('.').length-1)>1)
			{
				$('#error_robo_importe_factura_5').slideDown('fast');
				$('#error_robo_importe_factura_5').html('¡Ingresa un número valido!');
				$('#robo_importe_factura_5').focus();
				return false;
			}
			else
			{
				dato5=formatoMexico(dato5);
				//$('#robo_importe_factura_1').val('');
				$('#robo_importe_factura_5').val(dato5);
				$('#error_robo_importe_factura_5').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
			$('#error_robo_importe_factura_5').slideUp('fast');
			return true;
	}
}


function verificar_deducible_opcion1()
{
	if($('#deducible_opcion1').is(':enabled')===true)
	{
		var deducible_opcion1=$('#deducible_opcion1').val();
		if(deducible_opcion1=='na')
		{
			$('#error_deducible_opcion1').slideDown('fast');
			$('#error_deducible_opcion1').html('¡Selecciona un deducible!');
			
			$('#deducible_opcion1').focus();
			return false;
		}
		else
		{
			$('#error_deducible_opcion1').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_deducible_opcion1').slideUp('fast');
			return true;
	}
}

function verificar_deducible_opcion2()
{
	if($('#deducible_opcion2').is(':enabled')===true)
	{
		var deducible_opcion2=$('#deducible_opcion2').val();
		if(deducible_opcion2=='na')
		{
			$('#error_deducible_opcion2').slideDown('fast');
			$('#error_deducible_opcion2').html('¡Selecciona un deducible!');
			
			$('#deducible_opcion2').focus();
			return false;
		}
		else
		{
			$('#error_deducible_opcion2').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_deducible_opcion2').slideUp('fast');
			return true;	
	}
}
function verificar_deducible_opcion3()
{
	if($('#deducible_opcion3').is(':enabled')===true)
	{
		var deducible_opcion3=$('#deducible_opcion3').val();
		if(deducible_opcion3=='na')
		{
			$('#error_deducible_opcion3').slideDown('fast');
			$('#error_deducible_opcion3').html('¡Selecciona un deducible!');
			
			$('#deducible_opcion3').focus();
			return false;
		}
		else
		{
			$('#error_deducible_opcion3').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_deducible_opcion3').slideUp('fast');
			return true;
	}
}

function verificar_deducible_opcion4()
{
	if($('#deducible_opcion4').is(':enabled')===true)
	{
		var deducible_opcion4=$('#deducible_opcion4').val();
		if(deducible_opcion4=='na')
		{
			$('#error_deducible_opcion4').slideDown('fast');
			$('#error_deducible_opcion4').html('¡Selecciona un deducible!');
			
			$('#deducible_opcion4').focus();
			return false;
		}
		else
		{
			$('#error_deducible_opcion4').slideUp('fast');
			return true;
		}
	}
	else
	{
			$('#error_deducible_opcion4').slideUp('fast');
			return true;
	}
}
function verificar_deducible_opcion5()
{
	if($('#deducible_opcion5').is(':enabled')===true)
	{
		var deducible_opcion5=$('#deducible_opcion5').val();
		if(deducible_opcion5=='na')
		{
			$('#error_deducible_opcion5').slideDown('fast');
			$('#error_deducible_opcion5').html('¡Selecciona un deducible!');
			
			$('#deducible_opcion5').focus();
			return false;
		}
		else
		{
			$('#error_deducible_opcion5').slideUp('fast');
			return true;
		}
	}
	else
	{
			$('#error_deducible_opcion5').slideUp('fast');
			return true;
	}
}

function verificar_cristales_opcion1_selec()
{
	if($('#cristales_opcion1_selec').is(':enabled')===true)
	{
		var opcion=$('#cristales_opcion1_selec').val();
		if(opcion==0)
		{
			$('#error_cristales_opcion1_selec').slideDown('fast');
			$('#error_cristales_opcion1_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#cristales_opcion1_selec').focus();
			return false;
		}
		else
		{
			$('#error_cristales_opcion1_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_cristales_opcion1_selec').slideUp('fast');
			return true;
	}
}
function verificar_cristales_opcion2_selec()
{
	if($('#cristales_opcion2_selec').is(':enabled')===true)
	{
		var opcion=$('#cristales_opcion2_selec').val();
		if(opcion==0)
		{
			$('#error_cristales_opcion2_selec').slideDown('fast');
			$('#error_cristales_opcion2_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#cristales_opcion2_selec').focus();
			return false;
		}
		else
		{
			$('#error_cristales_opcion2_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_cristales_opcion2_selec').slideUp('fast');
		return true;
	}
}
function verificar_cristales_opcion3_selec()
{
	if($('#cristales_opcion3_selec').is(':enabled')===true)
	{
		var opcion=$('#cristales_opcion3_selec').val();
		if(opcion==0)
		{
			$('#error_cristales_opcion3_selec').slideDown('fast');
			$('#error_cristales_opcion3_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#cristales_opcion3_selec').focus();
			return false;
		}
		else
		{
			$('#error_cristales_opcion3_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_cristales_opcion3_selec').slideUp('fast');
			return true;
	}
}
function verificar_cristales_opcion4_selec()
{
	if($('#cristales_opcion4_selec').is(':enabled')===true)
	{
		var opcion=$('#cristales_opcion4_selec').val();
		if(opcion==0)
		{
			$('#error_cristales_opcion4_selec').slideDown('fast');
			$('#error_cristales_opcion4_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#cristales_opcion4_selec').focus();
			return false;
		}
		else
		{
			$('#error_cristales_opcion4_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_cristales_opcion4_selec').slideUp('fast');
			return true;
	}
}
function verificar_cristales_opcion5_selec()
{
	if($('#cristales_opcion5_selec').is(':enabled')===true)
	{
		var opcion=$('#cristales_opcion5_selec').val();
		if(opcion==0)
		{
			$('#error_cristales_opcion5_selec').slideDown('fast');
			$('#error_cristales_opcion5_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#cristales_opcion5_selec').focus();
			return false;
		}
		else
		{
			$('#error_cristales_opcion5_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_cristales_opcion5_selec').slideUp('fast');
			return true;
	}
}

function verificar_deducible_rt_opcion1()
{
	if($('#deducible_rt1').is(':enabled')===true)
	{	
		var deducible_rt1=$('#deducible_rt1').val();
		if(deducible_rt1=='na')
		{
			$('#error_deducible_rt1').slideDown('fast');
			$('#error_deducible_rt1').html('¡Selecciona un deducible!');
			
			$('#deducible_rt1').focus();
			return false;
		}
		else
		{
			$('#error_deducible_rt1').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_deducible_rt1').slideUp('fast');
			return true;
	}
}
function verificar_deducible_rt_opcion2()
{
	if($('#deducible_rt2').is(':enabled')===true)
	{
		var deducible_rt1=$('#deducible_rt2').val();
		if(deducible_rt1=='na')
		{
			$('#error_deducible_rt2').slideDown('fast');
			$('#error_deducible_rt2').html('¡Selecciona un deducible!');
			
			$('#deducible_rt2').focus();
			return false;
		}
		else
		{
			$('#error_deducible_rt2').slideUp('fast');
			return true;
		}
	}
	else
	{
			$('#error_deducible_rt2').slideUp('fast');
			return true;
	}
}
function verificar_deducible_rt_opcion3()
{
	if($('#deducible_rt3').is(':enabled')===true)
	{
		var deducible_rt1=$('#deducible_rt3').val();
		if(deducible_rt1=='na')
		{
			$('#error_deducible_rt3').slideDown('fast');
			$('#error_deducible_rt3').html('¡Debes ingresar un deducible!');
			
			$('#deducible_rt3').focus();
			return false;
		}
		else
		{
			$('#error_deducible_rt3').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_deducible_rt3').slideUp('fast');
		return true;
	}
}
function verificar_deducible_rt_opcion4()
{
	if($('#deducible_rt4').is(':enabled')===true)
	{
		var deducible_rt4=$('#deducible_rt4').val();
		if(deducible_rt4=='na')
		{
			$('#error_deducible_rt4').slideDown('fast');
			$('#error_deducible_rt4').html('¡Selecciona un deducible!');
			
			$('#deducible_rt4').focus();
			return false;
		}
		else
		{
			$('#error_deducible_rt4').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_deducible_rt4').slideUp('fast');
			return true;
	}
}
function verificar_deducible_rt_opcion5()
{
	if($('#deducible_rt5').is(':enabled')===true)
	{
		var deducible_rt5=$('#deducible_rt5').val();
		if(deducible_rt5=='na')
		{
			$('#error_deducible_rt5').slideDown('fast');
			$('#error_deducible_rt5').html('¡Debes ingresar un deducible!');
			
			$('#deducible_rt5').focus();
			return false;
		}
		else
		{
			$('#error_deducible_rt5').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_deducible_rt5').slideUp('fast');
			return true;
	}
}

function verificar_robo_opcion1_selec()
{
	if($('#robo_opcion1_selec').is(':enabled')===true)
	{
		var opcion=$('#robo_opcion1_selec').val();
		if(opcion==0)
		{
			$('#error_robo_opcion1_selec').slideDown('fast');
			$('#error_robo_opcion1_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#robo_opcion1_selec').focus();
			return false;
		}
		else
		{
			if(opcion=='V.FACTURA')
			{
				$('#robo_importe_factura_1').prop('disabled',false);
			}
			else
			{
				$('#robo_importe_factura_1').prop('disabled',true);
			}
			$('#error_robo_opcion1_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_robo_opcion1_selec').slideUp('fast');
			return true;
	}
}
function verificar_robo_opcion2_selec()
{
	if($('#robo_opcion2_selec').is(':enabled')===true)
	{
		var opcion=$('#robo_opcion2_selec').val();
		if(opcion==0)
		{
			$('#error_robo_opcion2_selec').slideDown('fast');
			$('#error_robo_opcion2_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#robo_opcion2_selec').focus();
			return false;
		}
		else
		{
			if(opcion=='V.FACTURA')
			{
				$('#robo_importe_factura_2').prop('disabled',false);
			}
			else
			{
				$('#robo_importe_factura_2').prop('disabled',true);
			}
			$('#error_robo_opcion2_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_robo_opcion2_selec').slideUp('fast');
			return true;
	}
}
function verificar_robo_opcion3_selec()
{
	if($('#robo_opcion3_selec').is(':enabled')===true)
	{
		var opcion=$('#robo_opcion3_selec').val();
		if(opcion==0)
		{
			$('#error_robo_opcion3_selec').slideDown('fast');
			$('#error_robo_opcion3_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#robo_opcion3_selec').focus();
			return false;
		}
		else
		{
			if(opcion=='V.FACTURA')
			{
				$('#robo_importe_factura_3').prop('disabled',false);
			}
			else
			{
				$('#robo_importe_factura_3').prop('disabled',true);
			}
			$('#error_robo_opcion3_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_robo_opcion3_selec').slideUp('fast');
			return true;
	}
}
function verificar_robo_opcion4_selec()
{
	if($('#robo_opcion4_selec').is(':enabled')===true)
	{
		var opcion=$('#robo_opcion4_selec').val();
		if(opcion==0)
		{
			$('#error_robo_opcion4_selec').slideDown('fast');
			$('#error_robo_opcion4_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#robo_opcion4_selec').focus();
			return false;
		}
		else
		{
			if(opcion=='V.FACTURA')
			{
				$('#robo_importe_factura_4').prop('disabled',false);
			}
			else
			{
				$('#robo_importe_factura_4').prop('disabled',true);
			}

			$('#error_robo_opcion4_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_robo_opcion4_selec').slideUp('fast');
			return true;
	}
}
function verificar_robo_opcion5_selec()
{
	if($('#robo_opcion5_selec').is(':enabled')===true)
	{
		var opcion=$('#robo_opcion5_selec').val();
		if(opcion==0)
		{
			$('#error_robo_opcion5_selec').slideDown('fast');
			$('#error_robo_opcion5_selec').html('¡Debes seleccionar un tipo de cobertura!');
			
			$('#robo_opcion5_selec').focus();
			return false;
		}
		else
		{
			if(opcion=='V.FACTURA')
			{
				$('#robo_importe_factura_5').prop('disabled',false);
			}
			else
			{
				$('#robo_importe_factura_5').prop('disabled',true);
			}
			$('#error_robo_opcion5_selec').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_robo_opcion5_selec').slideUp('fast');
		return true;
	}
}

function verificar_daños_tercero_opcion_1()
{
	var daños_tercero_opcion_1=$('#daños_tercero_opcion_1').val();
	daños_tercero_opcion_1=daños_tercero_opcion_1.replace(/,/g, "");
	if(daños_tercero_opcion_1=='' ||daños_tercero_opcion_1==0)
	{
		$('#error_daños_tercero_opcion_1').slideDown('fast');
		$('#error_daños_tercero_opcion_1').html('¡Debes ingresar una cantidad!');
		
		$('#daños_tercero_opcion_1').focus();
		return false;
	}
	else
		{
			if((daños_tercero_opcion_1.split('.').length-1)>1)
			{
				$('#error_daños_tercero_opcion_1').slideDown('fast');
				$('#error_daños_tercero_opcion_1').html('¡Ingresa un número valido!');
				$('#daños_tercero_opcion_1').focus();
				return false;
			}
			else
			{
				daños_tercero_opcion_1=formatoMexico(daños_tercero_opcion_1);
				//$('#robo_importe_factura_1').val('');
				$('#daños_tercero_opcion_1').val(daños_tercero_opcion_1);
				$('#error_daños_tercero_opcion_1').slideUp('fast');
				return true;
			}
		}
	/*else if (parseInt(daños_tercero_opcion_1)<0)
	{
		$('#error_daños_tercero_opcion_1').slideDown('fast');
		$('#error_daños_tercero_opcion_1').html('¡No esta permitido números negativos!');
		$('#daños_tercero_opcion_1').focus();
		return false;
	}
	else
	{
		$('#error_daños_tercero_opcion_1').slideUp('fast');
		return true;
	}*/
}

function verificar_daños_tercero_opcion_2()
{
	var daños_tercero_opcion_2=$('#daños_tercero_opcion_2').val();
	daños_tercero_opcion_2=daños_tercero_opcion_2.replace(/,/g, "");
	if(daños_tercero_opcion_2=='' ||daños_tercero_opcion_2==0)
	{
		$('#error_daños_tercero_opcion_2').slideDown('fast');
		$('#error_daños_tercero_opcion_2').html('¡Debes ingresar una cantidad!');
		
		$('#daños_tercero_opcion_2').focus();
		return false;
	}
	else
		{
			if((daños_tercero_opcion_2.split('.').length-1)>1)
			{
				$('#error_daños_tercero_opcion_2').slideDown('fast');
				$('#error_daños_tercero_opcion_2').html('¡Ingresa un número valido!');
				$('#daños_tercero_opcion_2').focus();
				return false;
			}
			else
			{
				daños_tercero_opcion_2=formatoMexico(daños_tercero_opcion_2);
				//$('#robo_importe_factura_1').val('');
				$('#daños_tercero_opcion_2').val(daños_tercero_opcion_2);
				$('#error_daños_tercero_opcion_2').slideUp('fast');
				return true;
			}
		}
	/*else if (parseInt(daños_tercero_opcion_2)<0)
	{
		$('#error_daños_tercero_opcion_2').slideDown('fast');
		$('#error_daños_tercero_opcion_2').html('¡No esta permitido números negativos!');
		$('#daños_tercero_opcion_2').focus();
		return false;
	}
	else
	{
		$('#error_daños_tercero_opcion_2').slideUp('fast');
		return true;
	}*/
}
function verificar_daños_tercero_opcion_3()
{
	var daños_tercero_opcion_3=$('#daños_tercero_opcion_3').val();
		daños_tercero_opcion_3=daños_tercero_opcion_3.replace(/,/g, "");

	if(daños_tercero_opcion_3=='' ||daños_tercero_opcion_3==0)
	{
		$('#error_daños_tercero_opcion_3').slideDown('fast');
		$('#error_daños_tercero_opcion_3').html('¡Debes ingresar una cantidad!');
		
		$('#daños_tercero_opcion_3').focus();
		return false;
	}
	else
		{
			if((daños_tercero_opcion_3.split('.').length-1)>1)
			{
				$('#error_daños_tercero_opcion_3').slideDown('fast');
				$('#error_daños_tercero_opcion_3').html('¡Ingresa un número valido!');
				$('#daños_tercero_opcion_3').focus();
				return false;
			}
			else
			{
				daños_tercero_opcion_3=formatoMexico(daños_tercero_opcion_3);
				//$('#robo_importe_factura_1').val('');
				$('#daños_tercero_opcion_3').val(daños_tercero_opcion_3);
				$('#error_daños_tercero_opcion_3').slideUp('fast');
				return true;
			}
		}
}
function verificar_daños_tercero_opcion_4()
{
	var daños_tercero_opcion_4=$('#daños_tercero_opcion_4').val();
			daños_tercero_opcion_4=daños_tercero_opcion_4.replace(/,/g, "");

	if(daños_tercero_opcion_4=='' ||daños_tercero_opcion_4==0 )
	{
		$('#error_daños_tercero_opcion_4').slideDown('fast');
		$('#error_daños_tercero_opcion_4').html('¡Debes ingresar una cantidad!');
		
		$('#daños_tercero_opcion_4').focus();
		return false;
	}
	else
		{
			if((daños_tercero_opcion_4.split('.').length-1)>1)
			{
				$('#error_daños_tercero_opcion_4').slideDown('fast');
				$('#error_daños_tercero_opcion_4').html('¡Ingresa un número valido!');
				$('#daños_tercero_opcion_4').focus();
				return false;
			}
			else
			{
				daños_tercero_opcion_4=formatoMexico(daños_tercero_opcion_4);
				//$('#robo_importe_factura_1').val('');
				$('#daños_tercero_opcion_4').val(daños_tercero_opcion_4);
				$('#error_daños_tercero_opcion_4').slideUp('fast');
				return true;
			}
		}
}
function verificar_daños_tercero_opcion_5()
{
	var daños_tercero_opcion_5=$('#daños_tercero_opcion_5').val();
				daños_tercero_opcion_5=daños_tercero_opcion_5.replace(/,/g, "");

	if(daños_tercero_opcion_5=='' ||daños_tercero_opcion_5==0)
	{
		$('#error_daños_tercero_opcion_5').slideDown('fast');
		$('#error_daños_tercero_opcion_5').html('¡Debes ingresar una cantidad!');
		
		$('#daños_tercero_opcion_5').focus();
		return false;
	}
	else
		{
			if((daños_tercero_opcion_5.split('.').length-1)>1)
			{
				$('#error_daños_tercero_opcion_5').slideDown('fast');
				$('#error_daños_tercero_opcion_5').html('¡Ingresa un número valido!');
				$('#daños_tercero_opcion_5').focus();
				return false;
			}
			else
			{
				daños_tercero_opcion_5=formatoMexico(daños_tercero_opcion_5);
				//$('#robo_importe_factura_1').val('');
				$('#daños_tercero_opcion_5').val(daños_tercero_opcion_5);
				$('#error_daños_tercero_opcion_5').slideUp('fast');
				return true;
			}
		}
}


function verificar_deducible_de_rc1()
{
	var deducible_de_rc_opcion1=$('#deducible_de_rc_opcion1').val();
					deducible_de_rc_opcion1=deducible_de_rc_opcion1.replace(/,/g, "");


	if(deducible_de_rc_opcion1=='' || deducible_de_rc_opcion1=="e")
	{
		$('#error_deducible_de_rc_opcion1').slideDown('fast');
		$('#error_deducible_de_rc_opcion1').html('¡Debes ingresar una cantidad!');
		
		$('#deducible_de_rc_opcion1').focus();
		return false;
	}
	else if((deducible_de_rc_opcion1.split('.').length-1)>1)
	{
		$('#error_deducible_de_rc_opcion1').slideDown('fast');
		$('#error_deducible_de_rc_opcion1').html('¡Ingresa un número valido!');
		$('#deducible_de_rc_opcion1').focus();
		return false;
	}
			 /*if (parseInt(deducible_de_rc_opcion1)<0)
	{
		$('#error_deducible_de_rc_opcion1').slideDown('fast');
		$('#error_deducible_de_rc_opcion1').html('¡No esta permitido números negativos!');
		$('#deducible_de_rc_opcion1').focus();
		return false;
	}*/
	else
	{
		deducible_de_rc_opcion1=formatoMexico(deducible_de_rc_opcion1);
		$('#deducible_de_rc_opcion1').val(deducible_de_rc_opcion1);
		$('#error_deducible_de_rc_opcion1').slideUp('fast');
		return true;
	}
}
function verificar_deducible_de_rc2()
{
	var deducible_de_rc_opcion2=$('#deducible_de_rc_opcion2').val();
		deducible_de_rc_opcion2=deducible_de_rc_opcion2.replace(/,/g, "");
	if(deducible_de_rc_opcion2=='' || deducible_de_rc_opcion2=="e")
	{
		$('#error_deducible_de_rc_opcion2').slideDown('fast');
		$('#error_deducible_de_rc_opcion2').html('¡Debes ingresar una cantidad!');
		
		$('#deducible_de_rc_opcion2').focus();
		return false;
	}
	else if((deducible_de_rc_opcion2.split('.').length-1)>1)
	{
		$('#error_deducible_de_rc_opcion2').slideDown('fast');
		$('#error_deducible_de_rc_opcion2').html('¡Ingresa un número valido!');
		$('#deducible_de_rc_opcion2').focus();
		return false;
	}
	else
	{
		deducible_de_rc_opcion2=formatoMexico(deducible_de_rc_opcion2);
		$('#deducible_de_rc_opcion2').val(deducible_de_rc_opcion2);
		$('#error_deducible_de_rc_opcion2').slideUp('fast');
		return true;
	}
}

function verificar_deducible_de_rc3()
{
	var deducible_de_rc_opcion3=$('#deducible_de_rc_opcion3').val();
			deducible_de_rc_opcion3=deducible_de_rc_opcion3.replace(/,/g, "");

	if(deducible_de_rc_opcion3=='' || deducible_de_rc_opcion3=="e")
	{
		$('#error_deducible_de_rc_opcion3').slideDown('fast');
		$('#error_deducible_de_rc_opcion3').html('¡Debes ingresar una cantidad!');
		
		$('#deducible_de_rc_opcion3').focus();
		return false;
	}
	else if((deducible_de_rc_opcion3.split('.').length-1)>1)
	{
		$('#error_deducible_de_rc_opcion3').slideDown('fast');
		$('#error_deducible_de_rc_opcion3').html('¡Ingresa un número valido!');
		$('#deducible_de_rc_opcion3').focus();
		return false;
	}
	else
	{
		deducible_de_rc_opcion3=formatoMexico(deducible_de_rc_opcion3);
		$('#deducible_de_rc_opcion3').val(deducible_de_rc_opcion3);
		$('#error_deducible_de_rc_opcion3').slideUp('fast');
		return true;
	}
}

function verificar_deducible_de_rc4()
{
	var deducible_de_rc_opcion4=$('#deducible_de_rc_opcion4').val();
				deducible_de_rc_opcion4=deducible_de_rc_opcion4.replace(/,/g, "");

	if(deducible_de_rc_opcion4=='' || deducible_de_rc_opcion4=="e")
	{
		$('#error_deducible_de_rc_opcion4').slideDown('fast');
		$('#error_deducible_de_rc_opcion4').html('¡Debes ingresar una cantidad!');
		
		$('#deducible_de_rc_opcion4').focus();
		return false;
	}
	else if((deducible_de_rc_opcion4.split('.').length-1)>1)
	{
		$('#error_deducible_de_rc_opcion4').slideDown('fast');
		$('#error_deducible_de_rc_opcion4').html('¡Ingresa un número valido!');
		$('#deducible_de_rc_opcion4').focus();
		return false;
	}
	else
	{
		deducible_de_rc_opcion4=formatoMexico(deducible_de_rc_opcion4);
		$('#deducible_de_rc_opcion4').val(deducible_de_rc_opcion4);
		$('#error_deducible_de_rc_opcion4').slideUp('fast');
		return true;
	}
}

function verificar_deducible_de_rc5()
{
	var deducible_de_rc_opcion5=$('#deducible_de_rc_opcion5').val();
	deducible_de_rc_opcion5=deducible_de_rc_opcion5.replace(/,/g, "");
	if(deducible_de_rc_opcion5==''  || deducible_de_rc_opcion5=="e")
	{
		$('#error_deducible_de_rc_opcion5').slideDown('fast');
		$('#error_deducible_de_rc_opcion5').html('¡Debes ingresar una cantidad!');
		
		$('#deducible_de_rc_opcion5').focus();
		return false;
	}
	else if((deducible_de_rc_opcion5.split('.').length-1)>1)
	{
		$('#error_deducible_de_rc_opcion5').slideDown('fast');
		$('#error_deducible_de_rc_opcion5').html('¡Ingresa un número valido!');
		$('#deducible_de_rc_opcion5').focus();
		return false;
	}
	else
	{
		deducible_de_rc_opcion5=formatoMexico(deducible_de_rc_opcion5);
		$('#deducible_de_rc_opcion5').val(deducible_de_rc_opcion5);
		$('#error_deducible_de_rc_opcion5').slideUp('fast');
		return true;
	}
}

function verificar_proteccion_opcion1_selec()
{
	var opcion=$('#proteccion_opcion1_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion1_selec').slideDown('fast');
		$('#error_proteccion_opcion1_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion1_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion1_selec').slideUp('fast');
		return true;
	}
}
function verificar_proteccion_opcion2_selec()
{
	var opcion=$('#proteccion_opcion2_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion2_selec').slideDown('fast');
		$('#error_proteccion_opcion2_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion2_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion2_selec').slideUp('fast');
		return true;
	}
}
function verificar_proteccion_opcion3_selec()
{
	var opcion=$('#proteccion_opcion3_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion3_selec').slideDown('fast');
		$('#error_proteccion_opcion3_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion3_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion3_selec').slideUp('fast');
		return true;
	}
}
function verificar_proteccion_opcion4_selec()
{
	var opcion=$('#proteccion_opcion4_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion4_selec').slideDown('fast');
		$('#error_proteccion_opcion4_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion4_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion4_selec').slideUp('fast');
		return true;
	}
}
function verificar_proteccion_opcion5_selec()
{
	var opcion=$('#proteccion_opcion5_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion5_selec').slideDown('fast');
		$('#error_proteccion_opcion5_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion5_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion5_selec').slideUp('fast');
		return true;
	}
}



function verificar_gastos_medicos_opcion_1()
{
	var gastos_medicos_opcion_1=$('#gastos_medicos_opcion_1').val();
	gastos_medicos_opcion_1=gastos_medicos_opcion_1.replace(/,/g, "");

	if(gastos_medicos_opcion_1=='' ||gastos_medicos_opcion_1==0 || gastos_medicos_opcion_1=="e")
	{
		$('#error_gastos_medicos_opcion_1').slideDown('fast');
		$('#error_gastos_medicos_opcion_1').html('¡Debes ingresar una cantidad!');
		
		$('#gastos_medicos_opcion_1').focus();
		return false;
	}
	else if((gastos_medicos_opcion_1.split('.').length-1)>1)
	{
		$('#error_gastos_medicos_opcion_1').slideDown('fast');
		$('#error_gastos_medicos_opcion_1').html('¡Ingresa un número valido!');
		$('#gastos_medicos_opcion_1').focus();
		return false;
	}
	else
	{
		gastos_medicos_opcion_1=formatoMexico(gastos_medicos_opcion_1);
		$('#gastos_medicos_opcion_1').val(gastos_medicos_opcion_1);
		$('#error_gastos_medicos_opcion_1').slideUp('fast');
		return true;
	}
}

function verificar_gastos_medicos_opcion_2()
{
	var gastos_medicos_opcion_2=$('#gastos_medicos_opcion_2').val();
		gastos_medicos_opcion_2=gastos_medicos_opcion_2.replace(/,/g, "");


	if(gastos_medicos_opcion_2=='' ||gastos_medicos_opcion_2==0 || gastos_medicos_opcion_2=="e")
	{
		$('#error_gastos_medicos_opcion_2').slideDown('fast');
		$('#error_gastos_medicos_opcion_2').html('¡Debes ingresar una cantidad!');
		
		$('#gastos_medicos_opcion_2').focus();
		return false;
	}
	else  if((gastos_medicos_opcion_2.split('.').length-1)>1)
	{
		$('#error_gastos_medicos_opcion_2').slideDown('fast');
		$('#error_gastos_medicos_opcion_2').html('¡Ingresa un número valido!');
		$('#gastos_medicos_opcion_2').focus();
		return false;
	}
	else
	{
		gastos_medicos_opcion_2=formatoMexico(gastos_medicos_opcion_2);
		$('#gastos_medicos_opcion_2').val(gastos_medicos_opcion_2);
		$('#error_gastos_medicos_opcion_2').slideUp('fast');
		return true;
	}
}

function verificar_gastos_medicos_opcion_3()
{
	var gastos_medicos_opcion_3=$('#gastos_medicos_opcion_3').val();
		gastos_medicos_opcion_3=gastos_medicos_opcion_3.replace(/,/g, "");
	if(gastos_medicos_opcion_3=='' ||gastos_medicos_opcion_3==0 || gastos_medicos_opcion_3=="e")
	{
		$('#error_gastos_medicos_opcion_3').slideDown('fast');
		$('#error_gastos_medicos_opcion_3').html('¡Debes ingresar una cantidad!');
		
		$('#gastos_medicos_opcion_3').focus();
		return false;
	}
	else  if((gastos_medicos_opcion_3.split('.').length-1)>1)
	{
		$('#error_gastos_medicos_opcion_3').slideDown('fast');
		$('#error_gastos_medicos_opcion_3').html('¡Ingresa un número valido!');
		$('#gastos_medicos_opcion_3').focus();
		return false;
	}
	else
	{
		gastos_medicos_opcion_3=formatoMexico(gastos_medicos_opcion_3);
		$('#gastos_medicos_opcion_3').val(gastos_medicos_opcion_3);
		$('#error_gastos_medicos_opcion_3').slideUp('fast');
		return true;
	}
}

function verificar_gastos_medicos_opcion_4()
{
	var gastos_medicos_opcion_4=$('#gastos_medicos_opcion_4').val();
	gastos_medicos_opcion_4=gastos_medicos_opcion_4.replace(/,/g, "");
	if(gastos_medicos_opcion_4=='' ||gastos_medicos_opcion_4==0 || gastos_medicos_opcion_4=="e")
	{
		$('#error_gastos_medicos_opcion_4').slideDown('fast');
		$('#error_gastos_medicos_opcion_4').html('¡Debes ingresar una cantidad!');
		
		$('#gastos_medicos_opcion_4').focus();
		return false;
	}
	else if((gastos_medicos_opcion_4.split('.').length-1)>1)
	{
		$('#error_gastos_medicos_opcion_4').slideDown('fast');
		$('#error_gastos_medicos_opcion_4').html('¡Ingresa un número valido!');
		$('#gastos_medicos_opcion_4').focus();
		return false;
	}
	else
	{
		gastos_medicos_opcion_4=formatoMexico(gastos_medicos_opcion_4);
		$('#gastos_medicos_opcion_4').val(gastos_medicos_opcion_4);
		$('#error_gastos_medicos_opcion_4').slideUp('fast');
		return true;
	}
}
function verificar_gastos_medicos_opcion_5()
{
	var gastos_medicos_opcion_5=$('#gastos_medicos_opcion_5').val();
	gastos_medicos_opcion_5=gastos_medicos_opcion_5.replace(/,/g, "");
	if(gastos_medicos_opcion_5=='' ||gastos_medicos_opcion_5==0 || gastos_medicos_opcion_5=="e")
	{
		$('#error_gastos_medicos_opcion_5').slideDown('fast');
		$('#error_gastos_medicos_opcion_5').html('¡Debes ingresar una cantidad!');
		
		$('#gastos_medicos_opcion_5').focus();
		return false;
	}
	else  if((gastos_medicos_opcion_5.split('.').length-1)>1)
	{
		$('#error_gastos_medicos_opcion_5').slideDown('fast');
		$('#error_gastos_medicos_opcion_5').html('¡Ingresa un número valido!');
		$('#gastos_medicos_opcion_5').focus();
		return false;
	}
	else
	{
		gastos_medicos_opcion_5=formatoMexico(gastos_medicos_opcion_5);
		$('#gastos_medicos_opcion_5').val(gastos_medicos_opcion_5);
		$('#error_gastos_medicos_opcion_5').slideUp('fast');
		return true;
	}
}

function verificar_accidente_conducir_opcion_1()
{
	var accidente_conducir_opcion_1=$('#accidente_conducir_opcion_1').val();
	accidente_conducir_opcion_1=accidente_conducir_opcion_1.replace(/,/g, "");
	if(accidente_conducir_opcion_1=='' ||accidente_conducir_opcion_1==0 || accidente_conducir_opcion_1=="e")
	{
		$('#error_accidente_conducir_opcion_1').slideDown('fast');
		$('#error_accidente_conducir_opcion_1').html('¡Debes ingresar una cantidad!');
		
		$('#accidente_conducir_opcion_1').focus();
		return false;
	}
	else if((accidente_conducir_opcion_1.split('.').length-1)>1)
	{
		$('#error_accidente_conducir_opcion_1').slideDown('fast');
		$('#error_accidente_conducir_opcion_1').html('¡Ingresa un número valido!');
		$('#accidente_conducir_opcion_1').focus();
		return false;
	}
	else
	{
		accidente_conducir_opcion_1=formatoMexico(accidente_conducir_opcion_1);
		$('#accidente_conducir_opcion_1').val(accidente_conducir_opcion_1);
		$('#error_accidente_conducir_opcion_1').slideUp('fast');
		return true;
	}
}
function verificar_accidente_conducir_opcion_2()
{
	var accidente_conducir_opcion_2=$('#accidente_conducir_opcion_2').val();
	accidente_conducir_opcion_2=accidente_conducir_opcion_2.replace(/,/g, "");
	if(accidente_conducir_opcion_2=='' ||accidente_conducir_opcion_2==0 || accidente_conducir_opcion_2=="e")
	{
		$('#error_accidente_conducir_opcion_2').slideDown('fast');
		$('#error_accidente_conducir_opcion_2').html('¡Debes ingresar una cantidad!');
		
		$('#accidente_conducir_opcion_2').focus();
		return false;
	}
	else  if((accidente_conducir_opcion_2.split('.').length-1)>1)
	{
		$('#error_accidente_conducir_opcion_2').slideDown('fast');
		$('#error_accidente_conducir_opcion_2').html('¡Ingresa un número valido!');
		$('#accidente_conducir_opcion_2').focus();
		return false;
	}
	else
	{
		accidente_conducir_opcion_2=formatoMexico(accidente_conducir_opcion_2);
		$('#accidente_conducir_opcion_2').val(accidente_conducir_opcion_2);
		$('#error_accidente_conducir_opcion_2').slideUp('fast');
		return true;
	}
}
function verificar_accidente_conducir_opcion_3()
{
	var accidente_conducir_opcion_3=$('#accidente_conducir_opcion_3').val();
	accidente_conducir_opcion_3=accidente_conducir_opcion_3.replace(/,/g, "");
	if(accidente_conducir_opcion_3=='' ||accidente_conducir_opcion_3==0 || accidente_conducir_opcion_3=="e")
	{
		$('#error_accidente_conducir_opcion_3').slideDown('fast');
		$('#error_accidente_conducir_opcion_3').html('¡Debes ingresar una cantidad!');
		
		$('#accidente_conducir_opcion_3').focus();
		return false;
	}
	else if((accidente_conducir_opcion_3.split('.').length-1)>1)
	{
		$('#error_accidente_conducir_opcion_3').slideDown('fast');
		$('#error_accidente_conducir_opcion_3').html('¡Ingresa un número valido!');
		$('#accidente_conducir_opcion_3').focus();
		return false;
	}
	else
	{
		accidente_conducir_opcion_3=formatoMexico(accidente_conducir_opcion_3);
		$('#accidente_conducir_opcion_3').val(accidente_conducir_opcion_3);
		$('#error_accidente_conducir_opcion_3').slideUp('fast');
		return true;
	}
}
function verificar_accidente_conducir_opcion_4()
{
	var accidente_conducir_opcion_4=$('#accidente_conducir_opcion_4').val();
	accidente_conducir_opcion_4=accidente_conducir_opcion_4.replace(/,/g, "");
	if(accidente_conducir_opcion_4=='' ||accidente_conducir_opcion_4==0 || accidente_conducir_opcion_4=="e")
	{
		$('#error_accidente_conducir_opcion_4').slideDown('fast');
		$('#error_accidente_conducir_opcion_4').html('¡Debes ingresar una cantidad!');
		
		$('#accidente_conducir_opcion_4').focus();
		return false;
	}
	else if((accidente_conducir_opcion_4.split('.').length-1)>1)
	{
		$('#error_accidente_conducir_opcion_4').slideDown('fast');
		$('#error_accidente_conducir_opcion_4').html('¡Ingresa un número valido!');
		$('#accidente_conducir_opcion_4').focus();
		return false;
	}
	else
	{
		accidente_conducir_opcion_4=formatoMexico(accidente_conducir_opcion_4);
		$('#accidente_conducir_opcion_4').val(accidente_conducir_opcion_4);
		$('#error_accidente_conducir_opcion_4').slideUp('fast');
		return true;
	}
}
function verificar_accidente_conducir_opcion_5()
{
	var accidente_conducir_opcion_5=$('#accidente_conducir_opcion_5').val();
	accidente_conducir_opcion_5=accidente_conducir_opcion_5.replace(/,/g, "");
	if(accidente_conducir_opcion_5=='' ||accidente_conducir_opcion_5==0 || accidente_conducir_opcion_5=="e")
	{
		$('#error_accidente_conducir_opcion_5').slideDown('fast');
		$('#error_accidente_conducir_opcion_5').html('¡Debes ingresar una cantidad!');
		
		$('#accidente_conducir_opcion_5').focus();
		return false;
	}
	else if((accidente_conducir_opcion_5.split('.').length-1)>1)
	{
		$('#error_accidente_conducir_opcion_5').slideDown('fast');
		$('#error_accidente_conducir_opcion_5').html('¡Ingresa un número valido!');
		$('#accidente_conducir_opcion_5').focus();
		return false;
	}
	else
	{
		accidente_conducir_opcion_5=formatoMexico(accidente_conducir_opcion_5);
		$('#accidente_conducir_opcion_5').val(accidente_conducir_opcion_5);
		$('#error_accidente_conducir_opcion_5').slideUp('fast');
		return true;
	}
}
function verificar_adaptaciones_opcion_1()
{
	var adaptaciones_opcion_1=$('#adaptaciones_opcion_1').val();
		adaptaciones_opcion_1=adaptaciones_opcion_1.replace(/,/g, "");
	if(adaptaciones_opcion_1==''  || adaptaciones_opcion_1=="e")
	{
		$('#error_adaptaciones_opcion_1').slideDown('fast');
		$('#error_adaptaciones_opcion_1').html('¡Debes ingresar una cantidad!');
		
		$('#adaptaciones_opcion_1').focus();
		return false;
	}
	else if((adaptaciones_opcion_1.split('.').length-1)>1)
	{
		$('#error_adaptaciones_opcion_1').slideDown('fast');
		$('#error_adaptaciones_opcion_1').html('¡Ingresa un número valido!');
		$('#adaptaciones_opcion_1').focus();
		return false;
	}
	else
	{
		adaptaciones_opcion_1=formatoMexico(adaptaciones_opcion_1);
		$('#adaptaciones_opcion_1').val(adaptaciones_opcion_1);
		$('#error_adaptaciones_opcion_1').slideUp('fast');
		return true;
	}
}
function verificar_adaptaciones_opcion_2()
{
	var adaptaciones_opcion_2=$('#adaptaciones_opcion_2').val();
		adaptaciones_opcion_2=adaptaciones_opcion_2.replace(/,/g, "");

	if(adaptaciones_opcion_2==''  || adaptaciones_opcion_2=="e")
	{
		$('#error_adaptaciones_opcion_2').slideDown('fast');
		$('#error_adaptaciones_opcion_2').html('¡Debes ingresar una cantidad!');
		
		$('#adaptaciones_opcion_2').focus();
		return false;
	}
	else if((adaptaciones_opcion_2.split('.').length-1)>1)
	{
		$('#error_adaptaciones_opcion_2').slideDown('fast');
		$('#error_adaptaciones_opcion_2').html('¡Ingresa un número valido!');
		$('#adaptaciones_opcion_2').focus();
		return false;
	}
	else
	{
		adaptaciones_opcion_2=formatoMexico(adaptaciones_opcion_2);
		$('#adaptaciones_opcion_2').val(adaptaciones_opcion_2);
		$('#error_adaptaciones_opcion_2').slideUp('fast');
		return true;
	}
}
function verificar_adaptaciones_opcion_3()
{
	var adaptaciones_opcion_3=$('#adaptaciones_opcion_3').val();
		adaptaciones_opcion_3=adaptaciones_opcion_3.replace(/,/g, "");
	if(adaptaciones_opcion_3==''  || adaptaciones_opcion_3=="e")
	{
		$('#error_adaptaciones_opcion_3').slideDown('fast');
		$('#error_adaptaciones_opcion_3').html('¡Debes ingresar una cantidad!');
		
		$('#adaptaciones_opcion_3').focus();
		return false;
	}
	else if((adaptaciones_opcion_3.split('.').length-1)>1)
	{
		$('#error_adaptaciones_opcion_3').slideDown('fast');
		$('#error_adaptaciones_opcion_3').html('¡Ingresa un número valido!');
		$('#adaptaciones_opcion_3').focus();
		return false;
	}
	else
	{
		adaptaciones_opcion_3=formatoMexico(adaptaciones_opcion_3);
		$('#adaptaciones_opcion_3').val(adaptaciones_opcion_3);
		$('#error_adaptaciones_opcion_3').slideUp('fast');
		return true;
	}
}
function verificar_adaptaciones_opcion_4()
{
	var adaptaciones_opcion_4=$('#adaptaciones_opcion_4').val();
			adaptaciones_opcion_4=adaptaciones_opcion_4.replace(/,/g, "");
	if(adaptaciones_opcion_4=='' || adaptaciones_opcion_4=="e")
	{
		$('#error_adaptaciones_opcion_4').slideDown('fast');
		$('#error_adaptaciones_opcion_4').html('¡Debes ingresar una cantidad!');
		
		$('#adaptaciones_opcion_4').focus();
		return false;
	}
	else if((adaptaciones_opcion_4.split('.').length-1)>1)
	{
		$('#error_adaptaciones_opcion_4').slideDown('fast');
		$('#error_adaptaciones_opcion_4').html('¡Ingresa un número valido!');
		$('#adaptaciones_opcion_4').focus();
		return false;
	}
	else
	{
		adaptaciones_opcion_4=formatoMexico(adaptaciones_opcion_4);
		$('#adaptaciones_opcion_4').val(adaptaciones_opcion_4);
		$('#error_adaptaciones_opcion_4').slideUp('fast');
		return true;
	}
}
function verificar_adaptaciones_opcion_5()
{
	var adaptaciones_opcion_5=$('#adaptaciones_opcion_5').val();
				adaptaciones_opcion_5=adaptaciones_opcion_5.replace(/,/g, "");
	if(adaptaciones_opcion_5==''  || adaptaciones_opcion_5=="e")
	{
		$('#error_adaptaciones_opcion_5').slideDown('fast');
		$('#error_adaptaciones_opcion_5').html('¡Debes ingresar una cantidad!');
		
		$('#adaptaciones_opcion_5').focus();
		return false;
	}
	else if((adaptaciones_opcion_5.split('.').length-1)>1)
	{
		$('#error_adaptaciones_opcion_5').slideDown('fast');
		$('#error_adaptaciones_opcion_5').html('¡Ingresa un número valido!');
		$('#adaptaciones_opcion_5').focus();
		return false;
	}
	else
	{
		adaptaciones_opcion_5=formatoMexico(adaptaciones_opcion_5);
		$('#adaptaciones_opcion_5').val(adaptaciones_opcion_5);
		$('#error_adaptaciones_opcion_5').slideUp('fast');
		return true;
	}
}
function verificar_fallecimiento_opcion_1()
{
	var fallecimiento_opcion_1=$('#fallecimiento_opcion_1').val();
	fallecimiento_opcion_1=fallecimiento_opcion_1.replace(/,/g, "");
	if(fallecimiento_opcion_1=='' ||fallecimiento_opcion_1==0)
	{
		$('#error_fallecimiento_opcion_1').slideDown('fast');
		$('#error_fallecimiento_opcion_1').html('¡Debes ingresar una cantidad!');
		
		$('#fallecimiento_opcion_1').focus();
		return false;
	}

	else
		{
			if((fallecimiento_opcion_1.split('.').length-1)>1)
			{
				$('#error_fallecimiento_opcion_1').slideDown('fast');
				$('#error_fallecimiento_opcion_1').html('¡Ingresa un número valido!');
				$('#fallecimiento_opcion_1').focus();
				return false;
			}
			else
			{
				fallecimiento_opcion_1=formatoMexico(fallecimiento_opcion_1);
				//$('#robo_importe_factura_1').val('');
				$('#fallecimiento_opcion_1').val(fallecimiento_opcion_1);
				$('#error_fallecimiento_opcion_1').slideUp('fast');
				return true;
			}
		}
		/*
	else if (parseInt(fallecimiento_opcion_1)<0)
	{
		$('#error_fallecimiento_opcion_1').slideDown('fast');
		$('#error_fallecimiento_opcion_1').html('¡No esta permitido números negativos!');
		$('#fallecimiento_opcion_1').focus();
		return false;
	}
	else
	{
		$('#error_fallecimiento_opcion_1').slideUp('fast');
		return true;
	}*/
}
function verificar_fallecimiento_opcion_2()
{
	var fallecimiento_opcion_2=$('#fallecimiento_opcion_2').val();
	fallecimiento_opcion_2=fallecimiento_opcion_2.replace(/,/g, "");

	if(fallecimiento_opcion_2=='' ||fallecimiento_opcion_2==0)
	{
		$('#error_fallecimiento_opcion_2').slideDown('fast');
		$('#error_fallecimiento_opcion_2').html('¡Debes ingresar una cantidad!');
		
		$('#fallecimiento_opcion_2').focus();
		return false;
	}
	else
		{
			if((fallecimiento_opcion_2.split('.').length-1)>1)
			{
				$('#error_fallecimiento_opcion_2').slideDown('fast');
				$('#error_fallecimiento_opcion_2').html('¡Ingresa un número valido!');
				$('#fallecimiento_opcion_2').focus();
				return false;
			}
			else
			{
				fallecimiento_opcion_2=formatoMexico(fallecimiento_opcion_2);
				//$('#robo_importe_factura_1').val('');
				$('#fallecimiento_opcion_2').val(fallecimiento_opcion_2);
				$('#error_fallecimiento_opcion_2').slideUp('fast');
				return true;
			}
		}
		/*
	else if (parseInt(fallecimiento_opcion_2)<0)
	{
		$('#error_fallecimiento_opcion_2').slideDown('fast');
		$('#error_fallecimiento_opcion_2').html('¡No esta permitido números negativos!');
		$('#fallecimiento_opcion_2').focus();
		return false;
	}
	else
	{
		$('#error_fallecimiento_opcion_2').slideUp('fast');
		return true;
	}*/
}
function verificar_fallecimiento_opcion_3()
{
	var fallecimiento_opcion_3=$('#fallecimiento_opcion_3').val();
	fallecimiento_opcion_3=fallecimiento_opcion_3.replace(/,/g, "");
	if(fallecimiento_opcion_3=='' ||fallecimiento_opcion_3==0)
	{
		$('#error_fallecimiento_opcion_3').slideDown('fast');
		$('#error_fallecimiento_opcion_3').html('¡Debes ingresar una cantidad!');
		
		$('#fallecimiento_opcion_3').focus();
		return false;
	}
	else
		{
			if((fallecimiento_opcion_3.split('.').length-1)>1)
			{
				$('#error_fallecimiento_opcion_3').slideDown('fast');
				$('#error_fallecimiento_opcion_3').html('¡Ingresa un número valido!');
				$('#fallecimiento_opcion_3').focus();
				return false;
			}
			else
			{
				fallecimiento_opcion_3=formatoMexico(fallecimiento_opcion_3);
				//$('#robo_importe_factura_1').val('');
				$('#fallecimiento_opcion_3').val(fallecimiento_opcion_3);
				$('#error_fallecimiento_opcion_3').slideUp('fast');
				return true;
			}
		}
		/*
	else if (parseInt(fallecimiento_opcion_3)<0)
	{
		$('#error_fallecimiento_opcion_3').slideDown('fast');
		$('#error_fallecimiento_opcion_3').html('¡No esta permitido números negativos!');
		$('#fallecimiento_opcion_3').focus();
		return false;
	}
	else
	{
		$('#error_fallecimiento_opcion_3').slideUp('fast');
		return true;
	}*/
}
function verificar_fallecimiento_opcion_4()
{
	var fallecimiento_opcion_4=$('#fallecimiento_opcion_4').val();
		fallecimiento_opcion_4=fallecimiento_opcion_4.replace(/,/g, "");

	if(fallecimiento_opcion_4=='' ||fallecimiento_opcion_4==0 || fallecimiento_opcion_4=="e")
	{
		$('#error_fallecimiento_opcion_4').slideDown('fast');
		$('#error_fallecimiento_opcion_4').html('¡Debes ingresar una cantidad!');
		
		$('#fallecimiento_opcion_4').focus();
		return false;
	}
	else
		{
			if((fallecimiento_opcion_4.split('.').length-1)>1)
			{
				$('#error_fallecimiento_opcion_4').slideDown('fast');
				$('#error_fallecimiento_opcion_4').html('¡Ingresa un número valido!');
				$('#fallecimiento_opcion_4').focus();
				return false;
			}
			else
			{
				fallecimiento_opcion_4=formatoMexico(fallecimiento_opcion_4);
				//$('#robo_importe_factura_1').val('');
				$('#fallecimiento_opcion_4').val(fallecimiento_opcion_4);
				$('#error_fallecimiento_opcion_4').slideUp('fast');
				return true;
			}
		}

	/*else if (parseInt(fallecimiento_opcion_4)<0)
	{
		$('#error_fallecimiento_opcion_4').slideDown('fast');
		$('#error_fallecimiento_opcion_4').html('¡No esta permitido números negativos!');
		$('#fallecimiento_opcion_4').focus();
		return false;
	}
	else
	{
		$('#error_fallecimiento_opcion_4').slideUp('fast');
		return true;
	}*/
}
function verificar_fallecimiento_opcion_5()
{
	var fallecimiento_opcion_5=$('#fallecimiento_opcion_5').val();
			fallecimiento_opcion_5=fallecimiento_opcion_5.replace(/,/g, "");

	if(fallecimiento_opcion_5=='' ||fallecimiento_opcion_5==0)
	{
		$('#error_fallecimiento_opcion_5').slideDown('fast');
		$('#error_fallecimiento_opcion_5').html('¡Debes ingresar una cantidad!');
		
		$('#fallecimiento_opcion_5').focus();
		return false;
	}
	else
		{
			if((fallecimiento_opcion_5.split('.').length-1)>1)
			{
				$('#error_fallecimiento_opcion_5').slideDown('fast');
				$('#error_fallecimiento_opcion_5').html('¡Ingresa un número valido!');
				$('#fallecimiento_opcion_5').focus();
				return false;
			}
			else
			{
				fallecimiento_opcion_5=formatoMexico(fallecimiento_opcion_5);
				//$('#robo_importe_factura_1').val('');
				$('#fallecimiento_opcion_5').val(fallecimiento_opcion_5);
				$('#error_fallecimiento_opcion_5').slideUp('fast');
				return true;
			}
		}/*
	else if (parseInt(fallecimiento_opcion_5)<0)
	{
		$('#error_fallecimiento_opcion_5').slideDown('fast');
		$('#error_fallecimiento_opcion_5').html('¡No esta permitido números negativos!');
		$('#fallecimiento_opcion_5').focus();
		return false;
	}
	else
	{
		$('#error_fallecimiento_opcion_5').slideUp('fast');
		return true;
	}*/
}

function verificar_asistencia_vial_opcion1_selec()
{
	var opcion=$('#asistencia_vial_opcion1_selec').val();
	if(opcion==0)
	{
		$('#error_asistencia_vial_opcion1_selec').slideDown('fast');
		$('#error_asistencia_vial_opcion1_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#asistencia_vial_opcion1_selec').focus();
		return false;
	}
	else
	{
		$('#error_asistencia_vial_opcion1_selec').slideUp('fast');
		return true;
	}
}
function verificar_asistencia_vial_opcion2_selec()
{
	var opcion=$('#asistencia_vial_opcion2_selec').val();
	if(opcion==0)
	{
		$('#error_asistencia_vial_opcion2_selec').slideDown('fast');
		$('#error_asistencia_vial_opcion2_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#asistencia_vial_opcion2_selec').focus();
		return false;
	}
	else
	{
		$('#error_asistencia_vial_opcion2_selec').slideUp('fast');
		return true;
	}
}
function verificar_asistencia_vial_opcion3_selec()
{
	var opcion=$('#asistencia_vial_opcion3_selec').val();
	if(opcion==0)
	{
		$('#error_asistencia_vial_opcion3_selec').slideDown('fast');
		$('#error_asistencia_vial_opcion3_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#asistencia_vial_opcion3_selec').focus();
		return false;
	}
	else
	{
		$('#error_asistencia_vial_opcion3_selec').slideUp('fast');
		return true;
	}
}
function verificar_asistencia_vial_opcion4_selec()
{
	var opcion=$('#asistencia_vial_opcion4_selec').val();
	if(opcion==0)
	{
		$('#error_asistencia_vial_opcion4_selec').slideDown('fast');
		$('#error_asistencia_vial_opcion4_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#asistencia_vial_opcion4_selec').focus();
		return false;
	}
	else
	{
		$('#error_asistencia_vial_opcion4_selec').slideUp('fast');
		return true;
	}
}
function verificar_asistencia_vial_opcion5_selec()
{
	var opcion=$('#asistencia_vial_opcion5_selec').val();
	if(opcion==0)
	{
		$('#error_asistencia_vial_opcion5_selec').slideDown('fast');
		$('#error_asistencia_vial_opcion5_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#asistencia_vial_opcion5_selec').focus();
		return false;
	}
	else
	{
		$('#error_asistencia_vial_opcion5_selec').slideUp('fast');
		return true;
	}
}


function verificar_proteccion_opcion1_selec()
{
	var opcion=$('#proteccion_opcion1_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion1_selec').slideDown('fast');
		$('#error_proteccion_opcion1_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion1_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion1_selec').slideUp('fast');
		return true;
	}
}
function verificar_proteccion_opcion2_selec()
{
	var opcion=$('#proteccion_opcion2_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion2_selec').slideDown('fast');
		$('#error_proteccion_opcion2_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion2_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion2_selec').slideUp('fast');
		return true;
	}
}
function verificar_proteccion_opcion3_selec()
{
	var opcion=$('#proteccion_opcion3_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion3_selec').slideDown('fast');
		$('#error_proteccion_opcion3_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion3_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion3_selec').slideUp('fast');
		return true;
	}
}
function verificar_proteccion_opcion4_selec()
{
	var opcion=$('#proteccion_opcion4_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion4_selec').slideDown('fast');
		$('#error_proteccion_opcion4_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion4_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion4_selec').slideUp('fast');
		return true;
	}
}
function verificar_proteccion_opcion5_selec()
{
	var opcion=$('#proteccion_opcion5_selec').val();
	if(opcion==0)
	{
		$('#error_proteccion_opcion5_selec').slideDown('fast');
		$('#error_proteccion_opcion5_selec').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#proteccion_opcion5_selec').focus();
		return false;
	}
	else
	{
		$('#error_proteccion_opcion5_selec').slideUp('fast');
		return true;
	}
}

function verificar_daños_carga_opcion_selec_1()
{
	var opcion=$('#daños_carga_opcion_selec_1').val();
	if(opcion==0)
	{
		$('#error_daños_carga_opcion_selec_1').slideDown('fast');
		$('#error_daños_carga_opcion_selec_1').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#daños_carga_opcion_selec_1').focus();
		return false;
	}
	else
	{
		$('#error_daños_carga_opcion_selec_1').slideUp('fast');
		return true;
	}
}
function verificar_daños_carga_opcion_selec_2()
{
	var opcion=$('#daños_carga_opcion_selec_2').val();
	if(opcion==0)
	{
		$('#error_daños_carga_opcion_selec_2').slideDown('fast');
		$('#error_daños_carga_opcion_selec_2').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#daños_carga_opcion_selec_2').focus();
		return false;
	}
	else
	{
		$('#error_daños_carga_opcion_selec_2').slideUp('fast');
		return true;
	}
}
function verificar_daños_carga_opcion_selec_3()
{
	var opcion=$('#daños_carga_opcion_selec_3').val();
	if(opcion==0)
	{
		$('#error_daños_carga_opcion_selec_3').slideDown('fast');
		$('#error_daños_carga_opcion_selec_3').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#daños_carga_opcion_selec_3').focus();
		return false;
	}
	else
	{
		$('#error_daños_carga_opcion_selec_3').slideUp('fast');
		return true;
	}
}
function verificar_daños_carga_opcion_selec_4()
{
	var opcion=$('#daños_carga_opcion_selec_4').val();
	if(opcion==0)
	{
		$('#error_daños_carga_opcion_selec_4').slideDown('fast');
		$('#error_daños_carga_opcion_selec_4').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#daños_carga_opcion_selec_4').focus();
		return false;
	}
	else
	{
		$('#error_daños_carga_opcion_selec_4').slideUp('fast');
		return true;
	}
}
function verificar_daños_carga_opcion_selec_5()
{
	var opcion=$('#daños_carga_opcion_selec_5').val();
	if(opcion==0)
	{
		$('#error_daños_carga_opcion_selec_5').slideDown('fast');
		$('#error_daños_carga_opcion_selec_5').html('¡Debes seleccionar un tipo de cobertura!');
		
		$('#daños_carga_opcion_selec_5').focus();
		return false;
	}
	else
	{
		$('#error_daños_carga_opcion_selec_5').slideUp('fast');
		return true;
	}
}

function verificar_extension_rc_opcion1()
{
	var opcion=$('#extension_rc_opcion1').val();
	if(opcion==0)
	{
		$('#error_extension_rc_opcion1').slideDown('fast');
		$('#error_extension_rc_opcion1').html('¡Debes seleccionar un tipo de extension!');
		
		$('#extension_rc_opcion1').focus();
		return false;
	}
	else
	{
		$('#error_extension_rc_opcion1').slideUp('fast');
		return true;
	}
}

function verificar_extension_rc_opcion2()
{
	var opcion=$('#extension_rc_opcion2').val();
	if(opcion==0)
	{
		$('#error_extension_rc_opcion2').slideDown('fast');
		$('#error_extension_rc_opcion2').html('¡Debes seleccionar un tipo de extension!');
		
		$('#extension_rc_opcion2').focus();
		return false;
	}
	else
	{
		$('#error_extension_rc_opcion2').slideUp('fast');
		return true;
	}
}

function verificar_extension_rc_opcion3()
{
	var opcion=$('#extension_rc_opcion3').val();
	if(opcion==0)
	{
		$('#error_extension_rc_opcion3').slideDown('fast');
		$('#error_extension_rc_opcion3').html('¡Debes seleccionar un tipo de extension!');
		
		$('#extension_rc_opcion3').focus();
		return false;
	}
	else
	{
		$('#error_extension_rc_opcion3').slideUp('fast');
		return true;
	}
}

function verificar_extension_rc_opcion4()
{
	var opcion=$('#extension_rc_opcion4').val();
	if(opcion==0)
	{
		$('#error_extension_rc_opcion4').slideDown('fast');
		$('#error_extension_rc_opcion4').html('¡Debes seleccionar un tipo de extension!');
		
		$('#extension_rc_opcion4').focus();
		return false;
	}
	else
	{
		$('#error_extension_rc_opcion4').slideUp('fast');
		return true;
	}
}


function verificar_extension_rc_opcion5()
{
	var opcion=$('#extension_rc_opcion5').val();
	if(opcion==0)
	{
		$('#error_extension_rc_opcion5').slideDown('fast');
		$('#error_extension_rc_opcion5').html('¡Debes seleccionar un tipo de extension!');
		
		$('#extension_rc_opcion5').focus();
		return false;
	}
	else
	{
		$('#error_extension_rc_opcion5').slideUp('fast');
		return true;
	}
}



function verificar_cobertura_opcion_1()
{
	var opcion=$('#cobertura_opcion_1').val();
	if(opcion!='')
	{
		var cantidad_opciones =$('#cantidad_aseguradoras').val();
		var resultados=cantidad_opciones;
		var o=1;
		for (var i = 0; i < cantidad_opciones; i++) 
		{
			var valor_opcion =$('#cobertura_opcion_'+(o)+'_select').val();
			if(valor_opcion==0)
			{
				$('#error_cobertura_opcion_'+o+'_select').html('¡Debes seleccionar un tipo de cobertura!');
				$('#error_cobertura_opcion_'+o+'_select').slideDown('fast');
				resultados--;
			}
			o++;
			
		}

		if(resultados==cantidad_opciones)
		{
				$('#error_cobertura_opcion_1').slideUp('fast');
			return true; 
		}
		else
		{
			return false;
		}
		
	}
	else
	{

		var cantidad_opciones2 =$('#cantidad_aseguradoras').val();
		var resultados2=0;
		var m=1;
		for (var j = 0; j < cantidad_opciones2; j++) 
		{
			var valor_opcion2 =$('#cobertura_opcion_'+(m)+'_select').val();
			if(valor_opcion2!=0)
			{
				resultados2++;
			}
			m++;
		}
		if(resultados2>0)
		{

			$('#error_cobertura_opcion_1').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_1').slideDown('fast');
			return false;
		}
		else
		{
			
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;
		}
	}
}


function verificar_cobertura_opcion_2()
{
	var opcion=$('#cobertura_opcion_2').val();
	if(opcion!='')
	{
		var cantidad_opciones =$('#cantidad_aseguradoras').val();
		var resultados=cantidad_opciones;
		var o=1;
		for (var i = 0; i < cantidad_opciones; i++) 
		{
			var valor_opcion =$('#cobertura_opcion_2_'+(o)+'_select').val();
			if(valor_opcion==0)
			{
				$('#error_cobertura_opcion_2_'+o+'_select').html('¡Debes seleccionar un tipo de cobertura!');
				$('#error_cobertura_opcion_2_'+o+'_select').slideDown('fast');
				resultados--;
			}
			o++;
			
		}

		if(resultados==cantidad_opciones)
		{
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true; 
		}
		else
		{
			return false;
		}
		
	}
	else
	{

		var cantidad_opciones2 =$('#cantidad_aseguradoras').val();
		var resultados2=0;
		var m=1;
		for (var j = 0; j < cantidad_opciones2; j++) 
		{
			var valor_opcion2 =$('#cobertura_opcion_2_'+(m)+'_select').val();
			if(valor_opcion2!=0)
			{
				resultados2++;
			}
			m++;
		}
		if(resultados2>0)
		{

			$('#error_cobertura_opcion_2').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_2').slideDown('fast');
			return false;
		}
		else
		{
			
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;
		}
	}
}


function verificar_cobertura_opcion_1_select()
{
	var opcion=$('#cobertura_opcion_1_select').val();
	var cobertura_opcion_1 = $('#cobertura_opcion_1').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_1=='')
		{
			$('#error_cobertura_opcion_1').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_1').slideDown('fast');
			$('#cobertura_opcion_1').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_1_select').slideUp('fast');
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_1!='')
		{
			$('#error_cobertura_opcion_1_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_1_select').slideDown('fast');
			$('#cobertura_opcion_1_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;	
		}
	}
}

function verificar_cobertura_opcion_2_select()
{
	var opcion=$('#cobertura_opcion_2_select').val();
	var cobertura_opcion_2 = $('#cobertura_opcion_1').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_2=='')
		{
			$('#error_cobertura_opcion_1').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_1').slideDown('fast');
			$('#cobertura_opcion_1').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2_select').slideUp('fast');
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_2!='')
		{
			$('#error_cobertura_opcion_2_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_2_select').slideDown('fast');
			$('#cobertura_opcion_2_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;	
		}
	}
}

function verificar_cobertura_opcion_3_select()
{
	var opcion=$('#cobertura_opcion_3_select').val();
	var cobertura_opcion_3 = $('#cobertura_opcion_1').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_3=='')
		{
			$('#error_cobertura_opcion_1').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_1').slideDown('fast');
			$('#cobertura_opcion_1').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_3_select').slideUp('fast');
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_3!='')
		{
			$('#error_cobertura_opcion_3_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_3_select').slideDown('fast');
			$('#cobertura_opcion_3_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;	
		}
	}
}


function verificar_cobertura_opcion_4_select()
{
	var opcion=$('#cobertura_opcion_4_select').val();
	var cobertura_opcion_4 = $('#cobertura_opcion_1').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_4=='')
		{
			$('#error_cobertura_opcion_1').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_1').slideDown('fast');
			$('#cobertura_opcion_1').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_4_select').slideUp('fast');
			$('#error_cobertura_opcion_4').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_4!='')
		{
			$('#error_cobertura_opcion_4_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_4_select').slideDown('fast');
			$('#cobertura_opcion_4_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;	
		}
	}
}

function verificar_cobertura_opcion_5_select()
{
	var opcion=$('#cobertura_opcion_5_select').val();
	var cobertura_opcion_5 = $('#cobertura_opcion_1').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_5=='')
		{
			$('#error_cobertura_opcion_1').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_1').slideDown('fast');
			$('#cobertura_opcion_1').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_5_select').slideUp('fast');
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_5!='')
		{
			$('#error_cobertura_opcion_5_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_5_select').slideDown('fast');
			$('#cobertura_opcion_5_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_1').slideUp('fast');
			return true;	
		}
	}
}


function verificar_cobertura_opcion_2_1_select()
{
	var opcion=$('#cobertura_opcion_2_1_select').val();
	var cobertura_opcion_5 = $('#cobertura_opcion_2').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_5=='')
		{
			$('#error_cobertura_opcion_2').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_2').slideDown('fast');
			$('#cobertura_opcion_2').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2_1_select').slideUp('fast');
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_5!='')
		{
			$('#error_cobertura_opcion_2_1_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_2_1_select').slideDown('fast');
			$('#cobertura_opcion_2_1_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
}

function verificar_cobertura_opcion_2_2_select()
{
	var opcion=$('#cobertura_opcion_2_2_select').val();
	var cobertura_opcion_5 = $('#cobertura_opcion_2').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_5=='')
		{
			$('#error_cobertura_opcion_2').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_2').slideDown('fast');
			$('#cobertura_opcion_2').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2_2_select').slideUp('fast');
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_5!='')
		{
			$('#error_cobertura_opcion_2_2_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_2_2_select').slideDown('fast');
			$('#cobertura_opcion_2_1_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
}


function verificar_cobertura_opcion_2_3_select()
{
	var opcion=$('#cobertura_opcion_2_3_select').val();
	var cobertura_opcion_5 = $('#cobertura_opcion_2').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_5=='')
		{
			$('#error_cobertura_opcion_2').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_2').slideDown('fast');
			$('#cobertura_opcion_2').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2_3_select').slideUp('fast');
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_5!='')
		{
			$('#error_cobertura_opcion_2_3_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_2_3_select').slideDown('fast');
			$('#cobertura_opcion_2_1_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
}


function verificar_cobertura_opcion_2_4_select()
{
	var opcion=$('#cobertura_opcion_2_4_select').val();
	var cobertura_opcion_5 = $('#cobertura_opcion_2').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_5=='')
		{
			$('#error_cobertura_opcion_2').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_2').slideDown('fast');
			$('#cobertura_opcion_2').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2_4_select').slideUp('fast');
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_5!='')
		{
			$('#error_cobertura_opcion_2_4_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_2_4_select').slideDown('fast');
			$('#cobertura_opcion_2_1_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
}

function verificar_cobertura_opcion_2_5_select()
{
	var opcion=$('#cobertura_opcion_2_5_select').val();
	var cobertura_opcion_5 = $('#cobertura_opcion_2').val();

	if(opcion!=0)
	{
		
		if(cobertura_opcion_5=='')
		{
			$('#error_cobertura_opcion_2').html('¡Debes ingresar nombre de cobertura!');
			$('#error_cobertura_opcion_2').slideDown('fast');
			$('#cobertura_opcion_2').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2_5_select').slideUp('fast');
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
	else
	{
		if(cobertura_opcion_5!='')
		{
			$('#error_cobertura_opcion_2_5_select').html('¡Debes seleccionar un tipo de cobertura!');
			$('#error_cobertura_opcion_2_5_select').slideDown('fast');
			$('#cobertura_opcion_2_1_select').focus();
			return false;
		}
		else
		{
			$('#error_cobertura_opcion_2').slideUp('fast');
			return true;	
		}
	}
}

function verificar_forma_de_pago()
{
	var opcion=$('#forma_de_pago').val();
	if(opcion==0)
	{
		$('#error_forma_de_pago').slideDown('fast');
		$('#error_forma_de_pago').html('¡Debes seleccionar forma de pago!');
		
		$('#forma_de_pago').focus();
		return false;
	}
	else
	{
		$('#error_forma_de_pago').slideUp('fast');
		return true;
	}
}

function verificar_cantidad_prima_neta_opcion1()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion1=$('#cantidad_prima_neta_opcion1').val();
		var cantidad_total_anual_opcion_1 = $('#cantidad_total_anual_opcion_1').val();
		cantidad_total_anual_opcion_1=cantidad_total_anual_opcion_1.replace(/,/g, "");
		cantidad_prima_neta_opcion1=cantidad_prima_neta_opcion1.replace(/,/g, "");


		if(cantidad_prima_neta_opcion1=='' ||cantidad_prima_neta_opcion1==0)
		{
			$('#error_cantidad_prima_neta_opcion1').slideDown('fast');
			$('#error_cantidad_prima_neta_opcion1').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_prima_neta_opcion1').focus();
			return false;
		}
		else if((cantidad_prima_neta_opcion1.split('.').length-1)>1)
			{
				$('#error_cantidad_prima_neta_opcion1').slideDown('fast');
				$('#error_cantidad_prima_neta_opcion1').html('¡Ingresa un número valido!');
				$('#cantidad_prima_neta_opcion1').focus();
				return false;
			}
		else if ((parseFloat(cantidad_total_anual_opcion_1)!='' && parseFloat(cantidad_total_anual_opcion_1)!=null && parseFloat(cantidad_total_anual_opcion_1)!='e' && parseFloat(cantidad_total_anual_opcion_1)>=0))
		{
			if(parseFloat(cantidad_prima_neta_opcion1)>=parseFloat(cantidad_total_anual_opcion_1))
			{
				$('#error_cantidad_prima_neta_opcion1').slideDown('fast');
				$('#error_cantidad_prima_neta_opcion1').html('¡Prima neta debe ser menor que prima total anual!');
				$('#cantidad_prima_neta_opcion1').focus();
				return false;
			}
			else
			{
				cantidad_prima_neta_opcion1=formatoMexico(cantidad_prima_neta_opcion1);
				//$('#robo_importe_factura_1').val('');
				$('#cantidad_prima_neta_opcion1').val(cantidad_prima_neta_opcion1);
				$('#error_cantidad_prima_neta_opcion1').slideUp('fast');
				return true;
			}
			
		}
		else
		{
			$('#error_cantidad_prima_neta_opcion1').slideUp('fast');
			return true;
		}
	}
	else
	{
		return false;
	}
	
}
function verificar_cantidad_prima_neta_opcion2()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion1=$('#cantidad_prima_neta_opcion2').val();
		var cantidad_total_anual_opcion_1 = $('#cantidad_total_anual_opcion_2').val();
		cantidad_total_anual_opcion_1=cantidad_total_anual_opcion_1.replace(/,/g, "");
		cantidad_prima_neta_opcion1=cantidad_prima_neta_opcion1.replace(/,/g, "");

		if(cantidad_prima_neta_opcion1=='' ||cantidad_prima_neta_opcion1==0 || cantidad_prima_neta_opcion1=="e")
		{
			$('#error_cantidad_prima_neta_opcion2').slideDown('fast');
			$('#error_cantidad_prima_neta_opcion2').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_prima_neta_opcion2').focus();
			return false;
		}
		else  if((cantidad_prima_neta_opcion1.split('.').length-1)>1)
			{
				$('#error_cantidad_prima_neta_opcion2').slideDown('fast');
				$('#error_cantidad_prima_neta_opcion2').html('¡Ingresa un número valido!');
				$('#cantidad_prima_neta_opcion2').focus();
				return false;
			}
		else if ((parseFloat(cantidad_total_anual_opcion_1)!='' && parseFloat(cantidad_total_anual_opcion_1)!=null && parseFloat(cantidad_total_anual_opcion_1)!='e' && parseFloat(cantidad_total_anual_opcion_1)>=0))
		{
			if(parseFloat(cantidad_prima_neta_opcion1)>=parseFloat(cantidad_total_anual_opcion_1))
			{
				$('#error_cantidad_prima_neta_opcion2').slideDown('fast');
				$('#error_cantidad_prima_neta_opcion2').html('¡Prima neta debe ser menor que prima total anual!');
				$('#cantidad_prima_neta_opcion2').focus();
				return false;
			}
			else
			{
				cantidad_prima_neta_opcion1=formatoMexico(cantidad_prima_neta_opcion1);
				//$('#robo_importe_factura_1').val('');
				$('#cantidad_prima_neta_opcion2').val(cantidad_prima_neta_opcion1);
				$('#error_cantidad_prima_neta_opcion2').slideUp('fast');
				return true;
			}
			
		}
		else
		{
			$('#error_cantidad_prima_neta_opcion2').slideUp('fast');
			return true;
		}
	}
	else
	{
		return false;
	}
	
}
function verificar_cantidad_prima_neta_opcion3()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion1=$('#cantidad_prima_neta_opcion3').val();
		var cantidad_total_anual_opcion_1 = $('#cantidad_total_anual_opcion_3').val();

		cantidad_total_anual_opcion_1=cantidad_total_anual_opcion_1.replace(/,/g, "");
		cantidad_prima_neta_opcion1=cantidad_prima_neta_opcion1.replace(/,/g, "");

		if(cantidad_prima_neta_opcion1=='' ||cantidad_prima_neta_opcion1==0 || cantidad_prima_neta_opcion1=="e")
		{
			$('#error_cantidad_prima_neta_opcion3').slideDown('fast');
			$('#error_cantidad_prima_neta_opcion3').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_prima_neta_opcion3').focus();
			return false;
		}
		
		else  if((cantidad_prima_neta_opcion1.split('.').length-1)>1)
			{
				$('#error_cantidad_prima_neta_opcion3').slideDown('fast');
				$('#error_cantidad_prima_neta_opcion3').html('¡Ingresa un número valido!');
				$('#cantidad_prima_neta_opcion3').focus();
				return false;
			}
		else if ((parseFloat(cantidad_total_anual_opcion_1)!='' && parseFloat(cantidad_total_anual_opcion_1)!=null && parseFloat(cantidad_total_anual_opcion_1)!='e' && parseFloat(cantidad_total_anual_opcion_1)>=0))
		{
			if(parseFloat(cantidad_prima_neta_opcion1)>=parseFloat(cantidad_total_anual_opcion_1))
			{
				$('#error_cantidad_prima_neta_opcion3').slideDown('fast');
				$('#error_cantidad_prima_neta_opcion3').html('¡Prima neta debe ser menor que prima total anual!');
				$('#cantidad_prima_neta_opcion3').focus();
				return false;
			}
			else
			{
				cantidad_prima_neta_opcion1=formatoMexico(cantidad_prima_neta_opcion1);
				//$('#robo_importe_factura_1').val('');
				$('#cantidad_prima_neta_opcion3').val(cantidad_prima_neta_opcion1);
				$('#error_cantidad_prima_neta_opcion3').slideUp('fast');
				return true;
			}
			
		}
		else
		{
			$('#error_cantidad_prima_neta_opcion3').slideUp('fast');
			return true;
		}
	}
	else
	{
		return false;
	}
	
}

function verificar_cantidad_prima_neta_opcion4()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion1=$('#cantidad_prima_neta_opcion4').val();
		var cantidad_total_anual_opcion_1 = $('#cantidad_total_anual_opcion_4').val();

		cantidad_total_anual_opcion_1=cantidad_total_anual_opcion_1.replace(/,/g, "");
		cantidad_prima_neta_opcion1=cantidad_prima_neta_opcion1.replace(/,/g, "");

		if(cantidad_prima_neta_opcion1=='' ||cantidad_prima_neta_opcion1==0 || cantidad_prima_neta_opcion1=="e")
		{
			$('#error_cantidad_prima_neta_opcion4').slideDown('fast');
			$('#error_cantidad_prima_neta_opcion4').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_prima_neta_opcion4').focus();
			return false;
		}
		else  if((cantidad_prima_neta_opcion1.split('.').length-1)>1)
			{
				$('#error_cantidad_prima_neta_opcion4').slideDown('fast');
				$('#error_cantidad_prima_neta_opcion4').html('¡Ingresa un número valido!');
				$('#cantidad_prima_neta_opcion4').focus();
				return false;
			}
		else if ((parseFloat(cantidad_total_anual_opcion_1)!='' && parseFloat(cantidad_total_anual_opcion_1)!=null && parseFloat(cantidad_total_anual_opcion_1)!='e' && parseFloat(cantidad_total_anual_opcion_1)>=0))
		{
			if(parseFloat(cantidad_prima_neta_opcion1)>=parseFloat(cantidad_total_anual_opcion_1))
			{
				$('#error_cantidad_prima_neta_opcion4').slideDown('fast');
				$('#error_cantidad_prima_neta_opcion4').html('¡Prima neta debe ser menor que prima total anual!');
				$('#cantidad_prima_neta_opcion4').focus();
				return false;
			}
			else
			{
				cantidad_prima_neta_opcion1=formatoMexico(cantidad_prima_neta_opcion1);
				//$('#robo_importe_factura_1').val('');
				$('#cantidad_prima_neta_opcion4').val(cantidad_prima_neta_opcion1);
				$('#error_cantidad_prima_neta_opcion4').slideUp('fast');
				return true;
			}
			
		}
		else
		{
			$('#error_cantidad_prima_neta_opcion4').slideUp('fast');
			return true;
		}
	}
	else
	{
		return false;
	}
	
}

function verificar_cantidad_prima_neta_opcion5()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion1=$('#cantidad_prima_neta_opcion5').val();
		var cantidad_total_anual_opcion_1 = $('#cantidad_total_anual_opcion_5').val();

	cantidad_total_anual_opcion_1=cantidad_total_anual_opcion_1.replace(/,/g, "");
		cantidad_prima_neta_opcion1=cantidad_prima_neta_opcion1.replace(/,/g, "");

		if(cantidad_prima_neta_opcion1=='' ||cantidad_prima_neta_opcion1==0 || cantidad_prima_neta_opcion1=="e")
		{
			$('#error_cantidad_prima_neta_opcion5').slideDown('fast');
			$('#error_cantidad_prima_neta_opcion5').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_prima_neta_opcion5').focus();
			return false;
		}
		else if((cantidad_prima_neta_opcion1.split('.').length-1)>1)
		{
			$('#error_cantidad_prima_neta_opcion5').slideDown('fast');
			$('#error_cantidad_prima_neta_opcion5').html('¡Ingresa un número valido!');
			$('#cantidad_prima_neta_opcion5').focus();
			return false;
		}
		else if ((parseFloat(cantidad_total_anual_opcion_1)!='' && parseFloat(cantidad_total_anual_opcion_1)!=null && parseFloat(cantidad_total_anual_opcion_1)!='e' && parseFloat(cantidad_total_anual_opcion_1)>=0))
		{
			if(parseFloat(cantidad_prima_neta_opcion1)>=parseFloat(cantidad_total_anual_opcion_1))
			{
				$('#error_cantidad_prima_neta_opcion5').slideDown('fast');
				$('#error_cantidad_prima_neta_opcion5').html('¡Prima neta debe ser menor que prima total anual!');
				$('#cantidad_prima_neta_opcion5').focus();
				return false;
			}
			else
			{
				cantidad_prima_neta_opcion1=formatoMexico(cantidad_prima_neta_opcion1);
				//$('#robo_importe_factura_1').val('');
				$('#cantidad_prima_neta_opcion5').val(cantidad_prima_neta_opcion1);
				$('#error_cantidad_prima_neta_opcion5').slideUp('fast');
				return true;
			}
			
		}
		else
		{
			$('#error_cantidad_prima_neta_opcion5').slideUp('fast');
			return true;
		}
	}
	else
	{
		return false;
	}
	
}

function verificar_primer_pago_opcion_1()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var primer_pago =$('#primer_pago_opcion_1').val();
		var pago=$('#forma_de_pago').val();
		var prima_total_anual=$('#cantidad_total_anual_opcion_1').val();
		prima_total_anual=prima_total_anual.replace(/,/g, "");
		primer_pago=primer_pago.replace(/,/g, "");
		

		if(pago=='ANUAL')
		{
			if(parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)>0)
			{
				prima_total_anual=formatoMexico(prima_total_anual);
				$('#primer_pago_opcion_1').val(prima_total_anual);
				$('#subsecuente_opcion1').val(0);
				return true;
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#error_primer_pago_opcion_1').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_1').focus();
					return false;
				}
				/*if(parseFloat(primer_pago)<0)
				{
					$('#error_primer_pago_opcion_1').html('¡No estan permitidos números negativos!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;
				}*/
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_1').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					//$('#robo_importe_factura_1').val('');
					$('#primer_pago_opcion_1').val(primer_pago);
					$('#error_primer_pago_opcion_1').slideUp('fast');
					return true;
				}	
			}
			
		}
		else if (pago=='SEMESTRAL')
		{


			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#error_primer_pago_opcion_1').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_1').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_1').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_1').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=formatoMexico(total_final);
					
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_1').val(primer_pago);
					
					$('#subsecuente_opcion1').val(total_final);
					$('#error_primer_pago_opcion_1').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#error_primer_pago_opcion_1').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_1').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_1').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					//$('#robo_importe_factura_1').val('');
					$('#primer_pago_opcion_1').val(primer_pago);
					$('#error_primer_pago_opcion_1').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='TRIMESTRAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#error_primer_pago_opcion_1').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_1').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_1').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_1').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);

				primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_1').val(primer_pago);


					$('#subsecuente_opcion1').val(total_final);
					$('#error_primer_pago_opcion_1').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#error_primer_pago_opcion_1').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_1').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_1').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					//$('#robo_importe_factura_1').val('');
					$('#primer_pago_opcion_1').val(primer_pago);
					$('#error_primer_pago_opcion_1').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='MENSUAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#error_primer_pago_opcion_1').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_1').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_1').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_1').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else
				{
					

					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);

					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_1').val(primer_pago);

					$('#subsecuente_opcion1').val(total_final);
					$('#error_primer_pago_opcion_1').slideUp('fast');
					return true;


				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#error_primer_pago_opcion_1').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_1').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_1').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_1').slideDown('fast');
					$('#primer_pago_opcion_1').focus();
					return false;	
				}
				else
				{
					
					primer_pago=formatoMexico(primer_pago);
					//$('#robo_importe_factura_1').val('');
					$('#primer_pago_opcion_1').val(primer_pago);
					$('#error_primer_pago_opcion_1').slideUp('fast');
					return true;
				}	
			}
		}
	}
	else
	{
		return false;
	}
}


function verificar_primer_pago_opcion_3()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var primer_pago =$('#primer_pago_opcion_3').val();
		var pago=$('#forma_de_pago').val();
		var prima_total_anual=$('#cantidad_total_anual_opcion_3').val();

		prima_total_anual=prima_total_anual.replace(/,/g, "");
		primer_pago=primer_pago.replace(/,/g, "");
		


		if(pago=='ANUAL')
		{
			if(parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)>0)
			{
				prima_total_anual=formatoMexico(prima_total_anual);
				$('#primer_pago_opcion_3').val(prima_total_anual);
				$('#subsecuente_opcion3').val(0);
				return true;
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#error_primer_pago_opcion_3').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_3').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_3').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_3').val(primer_pago);
					$('#error_primer_pago_opcion_3').slideUp('fast');
					return true;
				}	
			}
			
		}
		else if (pago=='SEMESTRAL')
		{


			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#error_primer_pago_opcion_3').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_3').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_3').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_3').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=formatoMexico(total_final);
				primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_3').val(primer_pago);


					$('#subsecuente_opcion3').val(total_final);
					$('#error_primer_pago_opcion_3').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#error_primer_pago_opcion_3').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_3').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_3').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_3').val(primer_pago);
					$('#error_primer_pago_opcion_3').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='TRIMESTRAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#error_primer_pago_opcion_3').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_3').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_3').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_3').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_3').val(primer_pago);
					
					$('#subsecuente_opcion3').val(total_final);
					$('#error_primer_pago_opcion_3').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#error_primer_pago_opcion_3').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_3').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_3').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_3').val(primer_pago);
					$('#error_primer_pago_opcion_3').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='MENSUAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#error_primer_pago_opcion_3').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_3').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_3').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_3').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_3').val(primer_pago);


					$('#subsecuente_opcion3').val(total_final);
					$('#error_primer_pago_opcion_3').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#error_primer_pago_opcion_3').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_3').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_3').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_3').slideDown('fast');
					$('#primer_pago_opcion_3').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_3').val(primer_pago);
					$('#error_primer_pago_opcion_3').slideUp('fast');
					return true;
				}	
			}
		}
	}
	else
	{
		return false;
	}
}

function verificar_primer_pago_opcion_2()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var primer_pago =$('#primer_pago_opcion_2').val();
		var pago=$('#forma_de_pago').val();
		var prima_total_anual=$('#cantidad_total_anual_opcion_2').val();

		prima_total_anual=prima_total_anual.replace(/,/g, "");
		primer_pago=primer_pago.replace(/,/g, "");
		


		if(pago=='ANUAL')
		{
			if(parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)>0)
			{
				prima_total_anual=formatoMexico(prima_total_anual);
				$('#primer_pago_opcion_2').val(prima_total_anual);
				$('#subsecuente_opcion2').val(0);
				return true;
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#error_primer_pago_opcion_2').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_2').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_2').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_2').val(primer_pago);
					$('#error_primer_pago_opcion_2').slideUp('fast');
					return true;
				}	
			}
			
		}
		else if (pago=='SEMESTRAL')
		{


			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#error_primer_pago_opcion_2').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_2').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_2').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_2').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=formatoMexico(total_final);
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_2').val(primer_pago);


					$('#subsecuente_opcion2').val(total_final);
					$('#error_primer_pago_opcion_2').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#error_primer_pago_opcion_2').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_2').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_2').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_2').val(primer_pago);
					$('#error_primer_pago_opcion_2').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='TRIMESTRAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#error_primer_pago_opcion_2').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_2').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_2').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_2').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_2').val(primer_pago);
					
					$('#subsecuente_opcion2').val(total_final);
					$('#error_primer_pago_opcion_2').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#error_primer_pago_opcion_2').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_2').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_2').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_2').val(primer_pago);
					$('#error_primer_pago_opcion_2').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='MENSUAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#error_primer_pago_opcion_2').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_2').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_2').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_2').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_2').val(primer_pago);


					$('#subsecuente_opcion2').val(total_final);
					$('#error_primer_pago_opcion_2').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#error_primer_pago_opcion_2').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_2').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_2').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_2').slideDown('fast');
					$('#primer_pago_opcion_2').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_2').val(primer_pago);
					$('#error_primer_pago_opcion_2').slideUp('fast');
					return true;
				}	
			}
		}
	}
	else
	{
		return false;
	}
}


function verificar_primer_pago_opcion_4()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var primer_pago =$('#primer_pago_opcion_4').val();
		var pago=$('#forma_de_pago').val();
		var prima_total_anual=$('#cantidad_total_anual_opcion_4').val();

		prima_total_anual=prima_total_anual.replace(/,/g, "");
		primer_pago=primer_pago.replace(/,/g, "");
		


		if(pago=='ANUAL')
		{
			if(parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)>0)
			{
				prima_total_anual=formatoMexico(prima_total_anual);
				$('#primer_pago_opcion_4').val(prima_total_anual);
				$('#subsecuente_opcion4').val(0);
				return true;
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#error_primer_pago_opcion_4').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_4').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_4').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_4').val(primer_pago);
					$('#error_primer_pago_opcion_4').slideUp('fast');
					return true;
				}	
			}
			
		}
		else if (pago=='SEMESTRAL')
		{


			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#error_primer_pago_opcion_4').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_4').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_4').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_4').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=formatoMexico(total_final);


					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_4').val(primer_pago);


					$('#subsecuente_opcion4').val(total_final);
					$('#error_primer_pago_opcion_4').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#error_primer_pago_opcion_4').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_4').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_4').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_4').val(primer_pago);
					$('#error_primer_pago_opcion_4').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='TRIMESTRAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#error_primer_pago_opcion_4').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_4').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_4').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_4').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);

					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_4').val(primer_pago);
					
					$('#subsecuente_opcion4').val(total_final);
					$('#error_primer_pago_opcion_4').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#error_primer_pago_opcion_4').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_4').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_4').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_4').val(primer_pago);
					$('#error_primer_pago_opcion_4').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='MENSUAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#error_primer_pago_opcion_4').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_4').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_4').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_4').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_4').val(primer_pago);


					$('#subsecuente_opcion4').val(total_final);
					$('#error_primer_pago_opcion_4').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#error_primer_pago_opcion_4').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_4').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_4').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_4').slideDown('fast');
					$('#primer_pago_opcion_4').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_4').val(primer_pago);
					$('#error_primer_pago_opcion_4').slideUp('fast');
					return true;
				}	
			}
		}
	}
	else
	{
		return false;
	}
}


function verificar_primer_pago_opcion_5()
{
	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var primer_pago =$('#primer_pago_opcion_5').val();
		var pago=$('#forma_de_pago').val();
		var prima_total_anual=$('#cantidad_total_anual_opcion_5').val();

		prima_total_anual=prima_total_anual.replace(/,/g, "");
		primer_pago=primer_pago.replace(/,/g, "");
		


		if(pago=='ANUAL')
		{
			if(parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)>0)
			{
				prima_total_anual=formatoMexico(prima_total_anual);
				$('#primer_pago_opcion_5').val(prima_total_anual);
				$('#subsecuente_opcion5').val(0);
				return true;
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#error_primer_pago_opcion_5').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_5').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_5').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_5').val(primer_pago);
					$('#error_primer_pago_opcion_5').slideUp('fast');
					return true;
				}	
			}
			
		}
		else if (pago=='SEMESTRAL')
		{


			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#error_primer_pago_opcion_5').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_5').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_5').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_5').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=formatoMexico(total_final);

					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_5').val(primer_pago);


					$('#subsecuente_opcion5').val(total_final);
					$('#error_primer_pago_opcion_5').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#error_primer_pago_opcion_5').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_5').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_5').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_5').val(primer_pago);
					$('#error_primer_pago_opcion_5').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='TRIMESTRAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#error_primer_pago_opcion_5').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_5').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_5').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_5').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);

					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_5').val(primer_pago);
					
					$('#subsecuente_opcion5').val(total_final);
					$('#error_primer_pago_opcion_5').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#error_primer_pago_opcion_5').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_5').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_5').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_5').val(primer_pago);
					$('#error_primer_pago_opcion_5').slideUp('fast');
					return true;
				}	
			}
		}
		else if (pago=='MENSUAL')
		{
			if((parseFloat(prima_total_anual)!='' && parseFloat(prima_total_anual)!=null && parseFloat(prima_total_anual)!='e' && parseFloat(prima_total_anual)>=0))
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#error_primer_pago_opcion_5').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_5').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_5').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
				{
					$('#error_primer_pago_opcion_5').html('¡Primer pago debe ser menor a prima total anual!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else
				{
					total_anual_decimal= new Decimal(prima_total_anual);
					total_final=total_anual_decimal.minus(primer_pago);
					total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_5').val(primer_pago);


					$('#subsecuente_opcion5').val(total_final);
					$('#error_primer_pago_opcion_5').slideUp('fast');
					return true;
				}
				
			}
			else
			{
				 if((primer_pago.split('.').length-1)>1)
				{
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#error_primer_pago_opcion_5').html('¡Ingresa un número valido!');
					$('#primer_pago_opcion_5').focus();
					return false;
				}
				else if(primer_pago=='e'||primer_pago=='' ||parseFloat(primer_pago)=='' || parseFloat(primer_pago)==null)
				{
					$('#error_primer_pago_opcion_5').html('¡Debes ingresar una cantidad!');
					$('#error_primer_pago_opcion_5').slideDown('fast');
					$('#primer_pago_opcion_5').focus();
					return false;	
				}
				else
				{
					primer_pago=formatoMexico(primer_pago);
					$('#primer_pago_opcion_5').val(primer_pago);
					$('#error_primer_pago_opcion_5').slideUp('fast');
					return true;
				}	
			}
		}
	}
	else
	{
		return false;
	}
}


function verificar_cantidad_total_anual_opcion_1()
{

	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion1=$('#cantidad_prima_neta_opcion1').val();
		var cantidad_total_anual_opcion_1=$('#cantidad_total_anual_opcion_1').val();
		var primer_pago_1= $('#primer_pago_opcion_1').val();

		primer_pago_1=primer_pago_1.replace(/,/g, "");
		cantidad_total_anual_opcion_1=cantidad_total_anual_opcion_1.replace(/,/g, "");
		cantidad_prima_neta_opcion1=cantidad_prima_neta_opcion1.replace(/,/g, "");
		if(cantidad_total_anual_opcion_1=='' ||cantidad_total_anual_opcion_1==0 || cantidad_total_anual_opcion_1=="e")
		{
			$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_1').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_total_anual_opcion_1').focus();
			return false;
		}
		else  if((cantidad_total_anual_opcion_1.split('.').length-1)>1)
		{
			$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_1').html('¡Ingresa un número valido!');
			$('#cantidad_total_anual_opcion_1').focus();
			return false;
		}
		/*else if(parseFloat(cantidad_prima_neta_opcion1)>=parseFloat(cantidad_total_anual_opcion_1))//if ((parseFloat(cantidad_prima_neta_opcion1)!='' && parseFloat(cantidad_prima_neta_opcion1)!=null && parseFloat(cantidad_prima_neta_opcion1)!='e' && parseFloat(cantidad_prima_neta_opcion1)>=0))
		{
			
				$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
				$('#error_cantidad_total_anual_opcion_1').html('¡Prima total anual debe ser mayor que prima neta!');
				$('#cantidad_total_anual_opcion_1').focus();
				return false;
		
			
		}*/
		else
		{
			var pago=$('#forma_de_pago').val();
			var id_aseguradora = $('#empresas_opcion1').val();

			if(pago=='ANUAL')
			{
				//$('#primer_pago_opcion_1').val(cantidad_total_anual_opcion_1);
				$('#error_cantidad_total_anual_opcion_1').slideUp('fast');
				$('#error_primer_pago_opcion_1').slideUp('fast');
				
				
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion1();
				}
				else
				{
						$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{
							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_1
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
								$('#cantidad_total_anual_opcion_1').focus();
								return false;
							}
							else
							{
								if(parseFloat(data)<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion1').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_1').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
									$('#cantidad_total_anual_opcion_1').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									cantidad_total_anual_opcion_1=formatoMexico(cantidad_total_anual_opcion_1);
									$('#cantidad_prima_neta_opcion1').val(prima_neta);
									$('#cantidad_total_anual_opcion_1').val(cantidad_total_anual_opcion_1);
									$('#primer_pago_opcion_1').val(cantidad_total_anual_opcion_1);
									$('#subsecuente_opcion1').val(0);
									return true;

								}
								
							}
							
						}

					});
				}
				
			}
			else if (pago=='SEMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion1();
				}
				else
				{
						if((parseFloat(primer_pago_1)!='' && parseFloat(primer_pago_1)!=null && parseFloat(primer_pago_1)!='e' && parseFloat(primer_pago_1)>=0))
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_1
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
									$('#cantidad_total_anual_opcion_1').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
									$('#cantidad_total_anual_opcion_1').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion1').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_1').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
										$('#cantidad_total_anual_opcion_1').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion1').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_1);
										total_final=total_anual_decimal.minus(primer_pago_1);
										total_final=formatoMexico(total_final);
										cantidad_total_anual_opcion_1=formatoMexico(cantidad_total_anual_opcion_1);
										$('#cantidad_total_anual_opcion_1').val(cantidad_total_anual_opcion_1);
										$('#subsecuente_opcion1').val(total_final);
										$('#error_cantidad_total_anual_opcion_1').slideUp('fast');
										return true;
									}
									
								}
							}

						});
						
					}
					else
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_1
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
									$('#cantidad_total_anual_opcion_1').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
									$('#cantidad_total_anual_opcion_1').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion1').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_1').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
										$('#cantidad_total_anual_opcion_1').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion1').val(prima_neta);
										cantidad_total_anual_opcion_1=formatoMexico(cantidad_total_anual_opcion_1);
										$('#cantidad_total_anual_opcion_1').val(cantidad_total_anual_opcion_1);
										$('#error_cantidad_total_anual_opcion_1').slideUp('fast');
										return true;
									}
									
								}
							}

						});
					}
				}

				
			}
			else if (pago=='TRIMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion1();
				}
				else
				{
						if((parseFloat(primer_pago_1)!='' && parseFloat(primer_pago_1)!=null && parseFloat(primer_pago_1)!='e' && parseFloat(primer_pago_1)>=0))
						{

							$.ajax({
								url:'metodos/cotizacion_metodos.php',
								type:'post',
								data:{

									metodos_sacar_prima_neta:1,
									id_aseguradora:id_aseguradora,
									forma_pago:pago,
									prima_anual_neta:cantidad_total_anual_opcion_1
								},
								success:function(data)
								{
									if(data.trim()=='no_derecho')
									{
										$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar derecho de poliza para procesar la información!');
										$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
										$('#cantidad_total_anual_opcion_1').focus();
										return false;
									}
									else if(data.trim()=='no_recargo')
									{
										$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
										$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
										$('#cantidad_total_anual_opcion_1').focus();
										return false;
									}
									else
									{
										if(data<0)
										{
											var prima_neta = formatoMexico(data);
											$('#cantidad_prima_neta_opcion1').val(prima_neta);
											$('#error_cantidad_total_anual_opcion_1').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
											$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
											$('#cantidad_total_anual_opcion_1').focus();
											return false;
										}
										else
										{
											var prima_neta = formatoMexico(data);
											$('#cantidad_prima_neta_opcion1').val(prima_neta);
											total_anual_decimal= new Decimal(cantidad_total_anual_opcion_1);
											total_final=total_anual_decimal.minus(primer_pago_1);
											total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
											total_final=formatoMexico(total_final);

											cantidad_total_anual_opcion_1=formatoMexico(cantidad_total_anual_opcion_1);
											$('#cantidad_total_anual_opcion_1').val(cantidad_total_anual_opcion_1);

											$('#subsecuente_opcion1').val(total_final);
											$('#error_cantidad_total_anual_opcion_1').slideUp('fast');
											return true;
										}
										
									}
								}

							});
							

						}
						else
						{
							$.ajax({
								url:'metodos/cotizacion_metodos.php',
								type:'post',
								data:{

									metodos_sacar_prima_neta:1,
									id_aseguradora:id_aseguradora,
									forma_pago:pago,
									prima_anual_neta:cantidad_total_anual_opcion_1
								},
								success:function(data)
								{
									if(data.trim()=='no_derecho')
									{
										$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar derecho de poliza para procesar la información!');
										$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
										$('#cantidad_total_anual_opcion_1').focus();
										return false;
									}
									else if(data.trim()=='no_recargo')
									{
										$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
										$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
										$('#cantidad_total_anual_opcion_1').focus();
										return false;
									}
									else
									{
										if(data<0)
										{
											var prima_neta = formatoMexico(data);
											$('#cantidad_prima_neta_opcion1').val(prima_neta);
											$('#error_cantidad_total_anual_opcion_1').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
											$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
											$('#cantidad_total_anual_opcion_1').focus();
											return false;
										}
										else
										{
											var prima_neta = formatoMexico(data);
											$('#cantidad_prima_neta_opcion1').val(prima_neta);
											

											cantidad_total_anual_opcion_1=formatoMexico(cantidad_total_anual_opcion_1);
											$('#cantidad_total_anual_opcion_1').val(cantidad_total_anual_opcion_1);

											$('#error_cantidad_total_anual_opcion_1').slideUp('fast');
											return true;
										}
										
									}
								}

							});

						}
				}

				
			}
			else if (pago=='MENSUAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion1();
				}
				else
				{
						if((parseFloat(primer_pago_1)!='' && parseFloat(primer_pago_1)!=null && parseFloat(primer_pago_1)!='e' && parseFloat(primer_pago_1)>=0))
					{


						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_1
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
									$('#cantidad_total_anual_opcion_1').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
									$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
									$('#cantidad_total_anual_opcion_1').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion1').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_1').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
										$('#cantidad_total_anual_opcion_1').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion1').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_1);
										total_final=total_anual_decimal.minus(primer_pago_1);
										total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
										total_final=formatoMexico(total_final);
										cantidad_total_anual_opcion_1=formatoMexico(cantidad_total_anual_opcion_1);
										$('#cantidad_total_anual_opcion_1').val(cantidad_total_anual_opcion_1);

										primer_pago_1=formatoMexico(primer_pago_1);
										$('#primer_pago_1').val(primer_pago_1);

										$('#subsecuente_opcion1').val(total_final);
										$('#error_cantidad_total_anual_opcion_1').slideUp('fast');
										return true;
									}
									
								}
							}

						});


							 
					}
					else
					{

						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_1
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
									$('#cantidad_total_anual_opcion_1').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_1').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
									$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
									$('#cantidad_total_anual_opcion_1').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion1').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_1').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_1').slideDown('fast');
										$('#cantidad_total_anual_opcion_1').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion1').val(prima_neta);
										
										cantidad_total_anual_opcion_1=formatoMexico(cantidad_total_anual_opcion_1);
										$('#cantidad_total_anual_opcion_1').val(cantidad_total_anual_opcion_1);

										primer_pago_1=formatoMexico(primer_pago_1);
										$('#primer_pago_1').val(primer_pago_1);

										$('#error_cantidad_total_anual_opcion_1').slideUp('fast');
										return true;
									}
									
								}
							}

						});

						
					}
				}

				
			}
		}
	}
	else
	{
		return false;
	}
}



function verificar_cantidad_total_anual_opcion_2()
{

	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion2=$('#cantidad_prima_neta_opcion2').val();
		var cantidad_total_anual_opcion_2=$('#cantidad_total_anual_opcion_2').val();
		var primer_pago_2= $('#primer_pago_opcion_2').val();
		var pago=$('#forma_de_pago').val();
		var id_aseguradora = $('#empresas_opcion2').val();

		primer_pago_2=primer_pago_2.replace(/,/g, "");
		cantidad_total_anual_opcion_2=cantidad_total_anual_opcion_2.replace(/,/g, "");
		cantidad_prima_neta_opcion2=cantidad_prima_neta_opcion2.replace(/,/g, "");
		if(cantidad_total_anual_opcion_2=='' ||cantidad_total_anual_opcion_2==0 || cantidad_total_anual_opcion_2=="e")
		{
			$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_2').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_total_anual_opcion_2').focus();
			return false;
		}
		else  if((cantidad_total_anual_opcion_2.split('.').length-1)>1)
		{
			$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_2').html('¡Ingresa un número valido!');
			$('#cantidad_total_anual_opcion_2').focus();
			return false;
		}
		/*else if(parseFloat(cantidad_prima_neta_opcion2)>=parseFloat(cantidad_total_anual_opcion_2))//if ((parseFloat(cantidad_prima_neta_opcion2)!='' && parseFloat(cantidad_prima_neta_opcion2)!=null && parseFloat(cantidad_prima_neta_opcion2)!='e' && parseFloat(cantidad_prima_neta_opcion2)>=0))
		{
			
				$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
				$('#error_cantidad_total_anual_opcion_2').html('¡Prima total anual debe ser mayor que prima neta!');
				$('#cantidad_total_anual_opcion_2').focus();
				return false;
		
			
		}*/
		else
		{
			

			if(pago=='ANUAL')
			{
				//$('#primer_pago_opcion_2').val(cantidad_total_anual_opcion_2);
				$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
				$('#error_primer_pago_opcion_2').slideUp('fast');
				
				
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion2();
				}
				else
				{
						$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{
							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_2
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
								$('#cantidad_total_anual_opcion_2').focus();
								return false;
							}
							else
							{
								if(parseFloat(data)<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion2').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_2').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									cantidad_total_anual_opcion_2=formatoMexico(cantidad_total_anual_opcion_2);
									$('#cantidad_prima_neta_opcion2').val(prima_neta);
									$('#cantidad_total_anual_opcion_2').val(cantidad_total_anual_opcion_2);
									$('#primer_pago_opcion_2').val(cantidad_total_anual_opcion_2);
									$('#subsecuente_opcion2').val(0);
									return true;

								}
								
							}
							
						}

					});
				}
				
			}
			else if (pago=='SEMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion2();
				}
				else
				{
					if((parseFloat(primer_pago_2)!='' && parseFloat(primer_pago_2)!=null && parseFloat(primer_pago_2)!='e' && parseFloat(primer_pago_2)>=0))
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_2
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion2').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_2').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
										$('#cantidad_total_anual_opcion_2').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion2').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_2);
										total_final=total_anual_decimal.minus(primer_pago_2);
										total_final=formatoMexico(total_final);
										cantidad_total_anual_opcion_2=formatoMexico(cantidad_total_anual_opcion_2);
										$('#cantidad_total_anual_opcion_2').val(cantidad_total_anual_opcion_2);
										$('#subsecuente_opcion2').val(total_final);
										$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
										return true;
									}
									
								}
							}

						});
						
					}
					else
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_2
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion2').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_2').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
										$('#cantidad_total_anual_opcion_2').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion2').val(prima_neta);
										cantidad_total_anual_opcion_2=formatoMexico(cantidad_total_anual_opcion_2);
										$('#cantidad_total_anual_opcion_2').val(cantidad_total_anual_opcion_2);
										$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
										return true;
									}
									
								}
							}

						});
					}
				}
				
			}
			else if (pago=='TRIMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion2();
				}
				else
				{
					if((parseFloat(primer_pago_2)!='' && parseFloat(primer_pago_2)!=null && parseFloat(primer_pago_2)!='e' && parseFloat(primer_pago_2)>=0))
					{

						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_2
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion2').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_2').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
										$('#cantidad_total_anual_opcion_2').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion2').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_2);
										total_final=total_anual_decimal.minus(primer_pago_2);
										total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
										total_final=formatoMexico(total_final);

										cantidad_total_anual_opcion_2=formatoMexico(cantidad_total_anual_opcion_2);
										$('#cantidad_total_anual_opcion_2').val(cantidad_total_anual_opcion_2);

										$('#subsecuente_opcion2').val(total_final);
										$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
										return true;
									}
									
								}
							}

						});
						

					}
					else
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_2
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion2').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_2').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
										$('#cantidad_total_anual_opcion_2').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion2').val(prima_neta);
										

										cantidad_total_anual_opcion_2=formatoMexico(cantidad_total_anual_opcion_2);
										$('#cantidad_total_anual_opcion_2').val(cantidad_total_anual_opcion_2);

										$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
										return true;
									}
									
								}
							}

						});
					}
				}

				
			}
			else if (pago=='MENSUAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion2();
				}
				else
				{
						if((parseFloat(primer_pago_2)!='' && parseFloat(primer_pago_2)!=null && parseFloat(primer_pago_2)!='e' && parseFloat(primer_pago_2)>=0))
					{


					$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{

							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_2
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
								$('#cantidad_total_anual_opcion_2').focus();
								return false;
							}
							else if(data.trim()=='no_recargo')
							{
								$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
								$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
								$('#cantidad_total_anual_opcion_2').focus();
								return false;
							}
							else
							{
								if(data<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion2').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_2').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion2').val(prima_neta);
									total_anual_decimal= new Decimal(cantidad_total_anual_opcion_2);
									total_final=total_anual_decimal.minus(primer_pago_2);
									total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
									total_final=formatoMexico(total_final);
									cantidad_total_anual_opcion_2=formatoMexico(cantidad_total_anual_opcion_2);
									$('#cantidad_total_anual_opcion_2').val(cantidad_total_anual_opcion_2);

									primer_pago_2=formatoMexico(primer_pago_2);
									$('#primer_pago_2').val(primer_pago_2);

									$('#subsecuente_opcion2').val(total_final);
									$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
									return true;
								}
								
							}
						}

					});


						 
					}
					else
					{

					$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{

							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_2
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
								$('#cantidad_total_anual_opcion_2').focus();
								return false;
							}
							else if(data.trim()=='no_recargo')
							{
								$('#error_cantidad_total_anual_opcion_2').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
								$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
								$('#cantidad_total_anual_opcion_2').focus();
								return false;
							}
							else
							{
								if(data<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion2').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_2').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
									$('#cantidad_total_anual_opcion_2').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion2').val(prima_neta);
									
									cantidad_total_anual_opcion_2=formatoMexico(cantidad_total_anual_opcion_2);
									$('#cantidad_total_anual_opcion_2').val(cantidad_total_anual_opcion_2);

									primer_pago_2=formatoMexico(primer_pago_2);
									$('#primer_pago_2').val(primer_pago_2);

									$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
									return true;
								}
								
							}
						}

					});


					}
				}

				
			}
		}
	}
	else
	{
		return false;
	}
}

/*
function verificar_cantidad_total_anual_opcion_2()
{

	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion1=$('#cantidad_prima_neta_opcion2').val();
		var cantidad_total_anual_opcion_1=$('#cantidad_total_anual_opcion_2').val();
		var primer_pago_1= $('#primer_pago_opcion_2').val();

		if(cantidad_total_anual_opcion_1=='' ||cantidad_total_anual_opcion_1==0 || cantidad_total_anual_opcion_1=="e")
		{
			$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_2').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_total_anual_opcion_2').focus();
			return false;
		}
		else  if((primer_pago.split('.').length-1)>1)
				{
					$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
					$('#error_cantidad_total_anual_opcion_2').html('¡Ingresa un número valido!');
					$('#cantidad_total_anual_opcion_2').focus();
					return false;
				}
		else if(parseFloat(cantidad_prima_neta_opcion1)>=parseFloat(cantidad_total_anual_opcion_1))//if ((parseFloat(cantidad_prima_neta_opcion1)!='' && parseFloat(cantidad_prima_neta_opcion1)!=null && parseFloat(cantidad_prima_neta_opcion1)!='e' && parseFloat(cantidad_prima_neta_opcion1)>=0))
		{
			
			
				$('#error_cantidad_total_anual_opcion_2').slideDown('fast');
				$('#error_cantidad_total_anual_opcion_2').html('¡Prima total anual debe ser mayor que prima neta!');
				$('#cantidad_total_anual_opcion_2').focus();
				return false;
			
		}
		else
		{
			var pago=$('#forma_de_pago').val();
			if(pago=='ANUAL')
			{
				//$('#primer_pago_opcion_1').val(cantidad_total_anual_opcion_1);
				$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
				$('#error_primer_pago_opcion_2').slideUp('fast');
				$('#primer_pago_opcion_2').val(cantidad_total_anual_opcion_1);
				$('#subsecuente_opcion2').val(0);
				return true;
				
			}
			else if (pago=='SEMESTRAL')
			{
				if((parseFloat(primer_pago_1)!='' && parseFloat(primer_pago_1)!=null && parseFloat(primer_pago_1)!='e' && parseFloat(primer_pago_1)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_1);
					total_final=total_anual_decimal.minus(primer_pago_1);
					$('#subsecuente_opcion2').val(total_final);
					$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
					return true;
				}
				else
				{
					$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
					return true;
				}
			}
			else if (pago=='TRIMESTRAL')
			{
				if((parseFloat(primer_pago_1)!='' && parseFloat(primer_pago_1)!=null && parseFloat(primer_pago_1)!='e' && parseFloat(primer_pago_1)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_1);
					total_final=total_anual_decimal.minus(primer_pago_1);
					$('#subsecuente_opcion2').val((total_final.dividedBy(3)).toNumber().toFixed(2));
					$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
					return true;

				}
				else
				{
					$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
					return true;
				}
			}
			else if (pago=='MENSUAL')
			{
				if((parseFloat(primer_pago_1)!='' && parseFloat(primer_pago_1)!=null && parseFloat(primer_pago_1)!='e' && parseFloat(primer_pago_1)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_1);
					total_final=total_anual_decimal.minus(primer_pago_1);
					$('#subsecuente_opcion2').val((total_final.dividedBy(11)).toNumber().toFixed(2));
					$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
					return true;	 
				}
				else
				{
					$('#error_cantidad_total_anual_opcion_2').slideUp('fast');
					return true;
				}
			}
		}
	}
	else
	{
		return false;
	}
}*/

/*
function verificar_cantidad_total_anual_opcion_3()
{

	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion3=$('#cantidad_prima_neta_opcion3').val();
		var cantidad_total_anual_opcion_3=$('#cantidad_total_anual_opcion_3').val();
		var primer_pago_3= $('#primer_pago_opcion_3').val();

		primer_pago_3=primer_pago_3.replace(/,/g, "");
		cantidad_total_anual_opcion_3=cantidad_total_anual_opcion_3.replace(/,/g, "");
		cantidad_prima_neta_opcion3=cantidad_prima_neta_opcion3.replace(/,/g, "");

		if(cantidad_total_anual_opcion_3=='' ||cantidad_total_anual_opcion_3==0 || cantidad_total_anual_opcion_3=="e")
		{
			$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_3').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_total_anual_opcion_3').focus();
			return false;
		}
		else  if((cantidad_total_anual_opcion_3.split('.').length-1)>1)
				{
					$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
					$('#error_cantidad_total_anual_opcion_3').html('¡Ingresa un número valido!');
					$('#cantidad_total_anual_opcion_3').focus();
					return false;
				}
		else if(parseFloat(cantidad_prima_neta_opcion3)>=parseFloat(cantidad_total_anual_opcion_3))//if ((parseFloat(cantidad_prima_neta_opcion3)!='' && parseFloat(cantidad_prima_neta_opcion3)!=null && parseFloat(cantidad_prima_neta_opcion3)!='e' && parseFloat(cantidad_prima_neta_opcion3)>=0))
		{
			
				$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
				$('#error_cantidad_total_anual_opcion_3').html('¡Prima total anual debe ser mayor que prima neta!');
				$('#cantidad_total_anual_opcion_3').focus();
				return false;
		
			
		}
		else
		{
			var pago=$('#forma_de_pago').val();


			if(pago=='ANUAL')
			{
				//$('#primer_pago_opcion_3').val(cantidad_total_anual_opcion_3);
				$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
				$('#error_primer_pago_opcion_3').slideUp('fast');
				cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
				$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);
				$('#primer_pago_opcion_3').val(cantidad_total_anual_opcion_3);
				$('#subsecuente_opcion3').val(0);
				return true;
				
			}
			else if (pago=='SEMESTRAL')
			{
				if((parseFloat(primer_pago_3)!='' && parseFloat(primer_pago_3)!=null && parseFloat(primer_pago_3)!='e' && parseFloat(primer_pago_3)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_3);
					total_final=total_anual_decimal.minus(primer_pago_3);
					total_final=formatoMexico(total_final);
					
					cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
					$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);
					
					$('#subsecuente_opcion3').val(total_final);
					$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
					return true;
				}
				else
				{
					$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
					return true;
				}
			}
			else if (pago=='TRIMESTRAL')
			{
				if((parseFloat(primer_pago_3)!='' && parseFloat(primer_pago_3)!=null && parseFloat(primer_pago_3)!='e' && parseFloat(primer_pago_3)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_3);
					total_final=total_anual_decimal.minus(primer_pago_3);
					total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);

					cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
					$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);

					$('#subsecuente_opcion3').val(total_final);
					$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
					return true;

				}
				else
				{
					$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
					return true;
				}
			}
			else if (pago=='MENSUAL')
			{
				if((parseFloat(primer_pago_3)!='' && parseFloat(primer_pago_3)!=null && parseFloat(primer_pago_3)!='e' && parseFloat(primer_pago_3)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_3);
					total_final=total_anual_decimal.minus(primer_pago_3);
					total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
					
					cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
					$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);

					primer_pago_3=formatoMexico(primer_pago_3);
					$('#primer_pago_3').val(primer_pago_3);

					$('#subsecuente_opcion3').val(total_final);
					$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
					return true;	 
				}
				else
				{

					$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
					return true;
				}
			}
		}
	}
	else
	{
		return false;
	}
}*/


function verificar_cantidad_total_anual_opcion_3()
{

	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion3=$('#cantidad_prima_neta_opcion3').val();
		var cantidad_total_anual_opcion_3=$('#cantidad_total_anual_opcion_3').val();
		var primer_pago_3= $('#primer_pago_opcion_3').val();
		var pago=$('#forma_de_pago').val();
		var id_aseguradora = $('#empresas_opcion3').val();

		primer_pago_3=primer_pago_3.replace(/,/g, "");
		cantidad_total_anual_opcion_3=cantidad_total_anual_opcion_3.replace(/,/g, "");
		cantidad_prima_neta_opcion3=cantidad_prima_neta_opcion3.replace(/,/g, "");
		if(cantidad_total_anual_opcion_3=='' ||cantidad_total_anual_opcion_3==0 || cantidad_total_anual_opcion_3=="e")
		{
			$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_3').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_total_anual_opcion_3').focus();
			return false;
		}
		else  if((cantidad_total_anual_opcion_3.split('.').length-1)>1)
		{
			$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_3').html('¡Ingresa un número valido!');
			$('#cantidad_total_anual_opcion_3').focus();
			return false;
		}
		/*else if(parseFloat(cantidad_prima_neta_opcion3)>=parseFloat(cantidad_total_anual_opcion_3))//if ((parseFloat(cantidad_prima_neta_opcion3)!='' && parseFloat(cantidad_prima_neta_opcion3)!=null && parseFloat(cantidad_prima_neta_opcion3)!='e' && parseFloat(cantidad_prima_neta_opcion3)>=0))
		{
			
				$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
				$('#error_cantidad_total_anual_opcion_3').html('¡Prima total anual debe ser mayor que prima neta!');
				$('#cantidad_total_anual_opcion_3').focus();
				return false;
		
			
		}*/
		else
		{
			

			if(pago=='ANUAL')
			{
				//$('#primer_pago_opcion_3').val(cantidad_total_anual_opcion_3);
				$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
				$('#error_primer_pago_opcion_3').slideUp('fast');
				
				
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion3();
				}
				else
				{
						$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{
							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_3
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
								$('#cantidad_total_anual_opcion_3').focus();
								return false;
							}
							else
							{
								if(parseFloat(data)<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion3').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_3').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
									$('#cantidad_prima_neta_opcion3').val(prima_neta);
									$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);
									$('#primer_pago_opcion_3').val(cantidad_total_anual_opcion_3);
									$('#subsecuente_opcion3').val(0);
									return true;

								}
								
							}
							
						}

					});
				}
				
			}
			else if (pago=='SEMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion3();
				}
				else
				{
					if((parseFloat(primer_pago_3)!='' && parseFloat(primer_pago_3)!=null && parseFloat(primer_pago_3)!='e' && parseFloat(primer_pago_3)>=0))
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_3
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion3').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_3').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
										$('#cantidad_total_anual_opcion_3').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion3').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_3);
										total_final=total_anual_decimal.minus(primer_pago_3);
										total_final=formatoMexico(total_final);
										cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
										$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);
										$('#subsecuente_opcion3').val(total_final);
										$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
										return true;
									}
									
								}
							}

						});
						
					}
					else
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_3
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion3').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_3').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
										$('#cantidad_total_anual_opcion_3').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion3').val(prima_neta);
										cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
										$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);
										$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
										return true;
									}
									
								}
							}

						});
					}
				}
				
			}
			else if (pago=='TRIMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion3();
				}
				else
				{
					if((parseFloat(primer_pago_3)!='' && parseFloat(primer_pago_3)!=null && parseFloat(primer_pago_3)!='e' && parseFloat(primer_pago_3)>=0))
					{

						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_3
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion3').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_3').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
										$('#cantidad_total_anual_opcion_3').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion3').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_3);
										total_final=total_anual_decimal.minus(primer_pago_3);
										total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
										total_final=formatoMexico(total_final);

										cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
										$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);

										$('#subsecuente_opcion3').val(total_final);
										$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
										return true;
									}
									
								}
							}

						});
						

					}
					else
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_3
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion3').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_3').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
										$('#cantidad_total_anual_opcion_3').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion3').val(prima_neta);
										

										cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
										$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);

										$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
										return true;
									}
									
								}
							}

						});
					}
				}

				
			}
			else if (pago=='MENSUAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion3();
				}
				else
				{
						if((parseFloat(primer_pago_3)!='' && parseFloat(primer_pago_3)!=null && parseFloat(primer_pago_3)!='e' && parseFloat(primer_pago_3)>=0))
					{


					$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{

							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_3
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
								$('#cantidad_total_anual_opcion_3').focus();
								return false;
							}
							else if(data.trim()=='no_recargo')
							{
								$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
								$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
								$('#cantidad_total_anual_opcion_3').focus();
								return false;
							}
							else
							{
								if(data<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion3').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_3').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion3').val(prima_neta);
									total_anual_decimal= new Decimal(cantidad_total_anual_opcion_3);
									total_final=total_anual_decimal.minus(primer_pago_3);
									total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
									total_final=formatoMexico(total_final);
									cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
									$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);

									primer_pago_3=formatoMexico(primer_pago_3);
									$('#primer_pago_3').val(primer_pago_3);

									$('#subsecuente_opcion3').val(total_final);
									$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
									return true;
								}
								
							}
						}

					});


						 
					}
					else
					{

					$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{

							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_3
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
								$('#cantidad_total_anual_opcion_3').focus();
								return false;
							}
							else if(data.trim()=='no_recargo')
							{
								$('#error_cantidad_total_anual_opcion_3').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
								$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
								$('#cantidad_total_anual_opcion_3').focus();
								return false;
							}
							else
							{
								if(data<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion3').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_3').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_3').slideDown('fast');
									$('#cantidad_total_anual_opcion_3').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion3').val(prima_neta);
									
									cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_3);
									$('#cantidad_total_anual_opcion_3').val(cantidad_total_anual_opcion_3);

									primer_pago_3=formatoMexico(primer_pago_3);
									$('#primer_pago_3').val(primer_pago_3);

									$('#error_cantidad_total_anual_opcion_3').slideUp('fast');
									return true;
								}
								
							}
						}

					});


					}
				}

				
			}
		}
	}
	else
	{
		return false;
	}
}

/*
function verificar_cantidad_total_anual_opcion_4()
{

	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion4=$('#cantidad_prima_neta_opcion4').val();
		var cantidad_total_anual_opcion_4=$('#cantidad_total_anual_opcion_4').val();
		var primer_pago_4= $('#primer_pago_opcion_4').val();

		primer_pago_4=primer_pago_4.replace(/,/g, "");
		cantidad_total_anual_opcion_4=cantidad_total_anual_opcion_4.replace(/,/g, "");
		cantidad_prima_neta_opcion4=cantidad_prima_neta_opcion4.replace(/,/g, "");

		if(cantidad_total_anual_opcion_4=='' ||cantidad_total_anual_opcion_4==0 || cantidad_total_anual_opcion_4=="e")
		{
			$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_4').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_total_anual_opcion_4').focus();
			return false;
		}
		else  if((cantidad_total_anual_opcion_4.split('.').length-1)>1)
				{
					$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
					$('#error_cantidad_total_anual_opcion_4').html('¡Ingresa un número valido!');
					$('#cantidad_total_anual_opcion_4').focus();
					return false;
				}
		else if(parseFloat(cantidad_prima_neta_opcion4)>=parseFloat(cantidad_total_anual_opcion_4))//if ((parseFloat(cantidad_prima_neta_opcion4)!='' && parseFloat(cantidad_prima_neta_opcion4)!=null && parseFloat(cantidad_prima_neta_opcion4)!='e' && parseFloat(cantidad_prima_neta_opcion4)>=0))
		{
			
				$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
				$('#error_cantidad_total_anual_opcion_4').html('¡Prima total anual debe ser mayor que prima neta!');
				$('#cantidad_total_anual_opcion_4').focus();
				return false;
		
			
		}
		else
		{
			var pago=$('#forma_de_pago').val();


			if(pago=='ANUAL')
			{
				//$('#primer_pago_opcion_4').val(cantidad_total_anual_opcion_4);
				$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
				$('#error_primer_pago_opcion_4').slideUp('fast');
				cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
				$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);
				$('#primer_pago_opcion_4').val(cantidad_total_anual_opcion_4);
				$('#subsecuente_opcion4').val(0);
				return true;
				
			}
			else if (pago=='SEMESTRAL')
			{
				if((parseFloat(primer_pago_4)!='' && parseFloat(primer_pago_4)!=null && parseFloat(primer_pago_4)!='e' && parseFloat(primer_pago_4)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_4);
					total_final=total_anual_decimal.minus(primer_pago_4);
					total_final=formatoMexico(total_final);
					
					cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
					$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);
					
					$('#subsecuente_opcion4').val(total_final);
					$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
					return true;
				}
				else
				{
					$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
					return true;
				}
			}
			else if (pago=='TRIMESTRAL')
			{
				if((parseFloat(primer_pago_4)!='' && parseFloat(primer_pago_4)!=null && parseFloat(primer_pago_4)!='e' && parseFloat(primer_pago_4)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_4);
					total_final=total_anual_decimal.minus(primer_pago_4);
					total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);

					cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
					$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);

					$('#subsecuente_opcion4').val(total_final);
					$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
					return true;

				}
				else
				{
					$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
					return true;
				}
			}
			else if (pago=='MENSUAL')
			{
				if((parseFloat(primer_pago_4)!='' && parseFloat(primer_pago_4)!=null && parseFloat(primer_pago_4)!='e' && parseFloat(primer_pago_4)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_4);
					total_final=total_anual_decimal.minus(primer_pago_4);
					total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
					
					cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
					$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);

					primer_pago_4=formatoMexico(primer_pago_4);
					$('#primer_pago_4').val(primer_pago_4);

					$('#subsecuente_opcion4').val(total_final);
					$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
					return true;	 
				}
				else
				{

					$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
					return true;
				}
			}
		}
	}
	else
	{
		return false;
	}
}*/
function verificar_cantidad_total_anual_opcion_5()
{

	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion5=$('#cantidad_prima_neta_opcion5').val();
		var cantidad_total_anual_opcion_5=$('#cantidad_total_anual_opcion_5').val();
		var primer_pago_5= $('#primer_pago_opcion_5').val();
		var pago=$('#forma_de_pago').val();
		var id_aseguradora = $('#empresas_opcion5').val();

		primer_pago_5=primer_pago_5.replace(/,/g, "");
		cantidad_total_anual_opcion_5=cantidad_total_anual_opcion_5.replace(/,/g, "");
		cantidad_prima_neta_opcion5=cantidad_prima_neta_opcion5.replace(/,/g, "");
		if(cantidad_total_anual_opcion_5=='' ||cantidad_total_anual_opcion_5==0 || cantidad_total_anual_opcion_5=="e")
		{
			$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_5').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_total_anual_opcion_5').focus();
			return false;
		}
		else  if((cantidad_total_anual_opcion_5.split('.').length-1)>1)
		{
			$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_5').html('¡Ingresa un número valido!');
			$('#cantidad_total_anual_opcion_5').focus();
			return false;
		}
		/*else if(parseFloat(cantidad_prima_neta_opcion5)>=parseFloat(cantidad_total_anual_opcion_5))//if ((parseFloat(cantidad_prima_neta_opcion5)!='' && parseFloat(cantidad_prima_neta_opcion5)!=null && parseFloat(cantidad_prima_neta_opcion5)!='e' && parseFloat(cantidad_prima_neta_opcion5)>=0))
		{
			
				$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
				$('#error_cantidad_total_anual_opcion_5').html('¡Prima total anual debe ser mayor que prima neta!');
				$('#cantidad_total_anual_opcion_5').focus();
				return false;
		
			
		}*/
		else
		{
			

			if(pago=='ANUAL')
			{
				//$('#primer_pago_opcion_5').val(cantidad_total_anual_opcion_5);
				$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
				$('#error_primer_pago_opcion_5').slideUp('fast');
				
				
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion5();
				}
				else
				{
						$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{
							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_5
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
								$('#cantidad_total_anual_opcion_5').focus();
								return false;
							}
							else
							{
								if(parseFloat(data)<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion5').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_5').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
									$('#cantidad_prima_neta_opcion5').val(prima_neta);
									$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);
									$('#primer_pago_opcion_5').val(cantidad_total_anual_opcion_5);
									$('#subsecuente_opcion5').val(0);
									return true;

								}
								
							}
							
						}

					});
				}
				
			}
			else if (pago=='SEMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion5();
				}
				else
				{
					if((parseFloat(primer_pago_5)!='' && parseFloat(primer_pago_5)!=null && parseFloat(primer_pago_5)!='e' && parseFloat(primer_pago_5)>=0))
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_5
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion5').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_5').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
										$('#cantidad_total_anual_opcion_5').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion5').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_5);
										total_final=total_anual_decimal.minus(primer_pago_5);
										total_final=formatoMexico(total_final);
										cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
										$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);
										$('#subsecuente_opcion5').val(total_final);
										$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
										return true;
									}
									
								}
							}

						});
						
					}
					else
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_5
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion5').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_5').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
										$('#cantidad_total_anual_opcion_5').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion5').val(prima_neta);
										cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
										$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);
										$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
										return true;
									}
									
								}
							}

						});
					}
				}
				
			}
			else if (pago=='TRIMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion5();
				}
				else
				{
					if((parseFloat(primer_pago_5)!='' && parseFloat(primer_pago_5)!=null && parseFloat(primer_pago_5)!='e' && parseFloat(primer_pago_5)>=0))
					{

						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_5
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion5').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_5').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
										$('#cantidad_total_anual_opcion_5').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion5').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_5);
										total_final=total_anual_decimal.minus(primer_pago_5);
										total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
										total_final=formatoMexico(total_final);

										cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_5);
										$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);

										$('#subsecuente_opcion5').val(total_final);
										$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
										return true;
									}
									
								}
							}

						});
						

					}
					else
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_5
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion5').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_5').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
										$('#cantidad_total_anual_opcion_5').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion5').val(prima_neta);
										

										cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
										$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);

										$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
										return true;
									}
									
								}
							}

						});
					}
				}

				
			}
			else if (pago=='MENSUAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion5();
				}
				else
				{
						if((parseFloat(primer_pago_5)!='' && parseFloat(primer_pago_5)!=null && parseFloat(primer_pago_5)!='e' && parseFloat(primer_pago_5)>=0))
					{


					$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{

							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_5
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
								$('#cantidad_total_anual_opcion_5').focus();
								return false;
							}
							else if(data.trim()=='no_recargo')
							{
								$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
								$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
								$('#cantidad_total_anual_opcion_5').focus();
								return false;
							}
							else
							{
								if(data<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion5').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_5').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion5').val(prima_neta);
									total_anual_decimal= new Decimal(cantidad_total_anual_opcion_5);
									total_final=total_anual_decimal.minus(primer_pago_5);
									total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
									total_final=formatoMexico(total_final);
									cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
									$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);

									primer_pago_5=formatoMexico(primer_pago_5);
									$('#primer_pago_5').val(primer_pago_5);

									$('#subsecuente_opcion5').val(total_final);
									$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
									return true;
								}
								
							}
						}

					});


						 
					}
					else
					{

					$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{

							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_5
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
								$('#cantidad_total_anual_opcion_5').focus();
								return false;
							}
							else if(data.trim()=='no_recargo')
							{
								$('#error_cantidad_total_anual_opcion_5').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
								$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
								$('#cantidad_total_anual_opcion_5').focus();
								return false;
							}
							else
							{
								if(data<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion5').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_5').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
									$('#cantidad_total_anual_opcion_5').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion5').val(prima_neta);
									
									cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
									$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);

									primer_pago_5=formatoMexico(primer_pago_5);
									$('#primer_pago_5').val(primer_pago_5);

									$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
									return true;
								}
								
							}
						}

					});


					}
				}

				
			}
		}
	}
	else
	{
		return false;
	}
}


/*
function verificar_cantidad_total_anual_opcion_5()
{

	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion5=$('#cantidad_prima_neta_opcion5').val();
		var cantidad_total_anual_opcion_5=$('#cantidad_total_anual_opcion_5').val();
		var primer_pago_5= $('#primer_pago_opcion_5').val();

		primer_pago_5=primer_pago_5.replace(/,/g, "");
		cantidad_total_anual_opcion_5=cantidad_total_anual_opcion_5.replace(/,/g, "");
		cantidad_prima_neta_opcion5=cantidad_prima_neta_opcion5.replace(/,/g, "");

		if(cantidad_total_anual_opcion_5=='' ||cantidad_total_anual_opcion_5==0 || cantidad_total_anual_opcion_5=="e")
		{
			$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_5').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_total_anual_opcion_5').focus();
			return false;
		}
		else  if((cantidad_total_anual_opcion_5.split('.').length-1)>1)
				{
					$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
					$('#error_cantidad_total_anual_opcion_5').html('¡Ingresa un número valido!');
					$('#cantidad_total_anual_opcion_5').focus();
					return false;
				}
		else if(parseFloat(cantidad_prima_neta_opcion5)>=parseFloat(cantidad_total_anual_opcion_5))//if ((parseFloat(cantidad_prima_neta_opcion5)!='' && parseFloat(cantidad_prima_neta_opcion5)!=null && parseFloat(cantidad_prima_neta_opcion5)!='e' && parseFloat(cantidad_prima_neta_opcion5)>=0))
		{
			
				$('#error_cantidad_total_anual_opcion_5').slideDown('fast');
				$('#error_cantidad_total_anual_opcion_5').html('¡Prima total anual debe ser mayor que prima neta!');
				$('#cantidad_total_anual_opcion_5').focus();
				return false;
		
			
		}
		else
		{
			var pago=$('#forma_de_pago').val();


			if(pago=='ANUAL')
			{
				//$('#primer_pago_opcion_5').val(cantidad_total_anual_opcion_5);
				$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
				$('#error_primer_pago_opcion_5').slideUp('fast');
				cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
				$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);
				$('#primer_pago_opcion_5').val(cantidad_total_anual_opcion_5);
				$('#subsecuente_opcion5').val(0);
				return true;
				
			}
			else if (pago=='SEMESTRAL')
			{
				if((parseFloat(primer_pago_5)!='' && parseFloat(primer_pago_5)!=null && parseFloat(primer_pago_5)!='e' && parseFloat(primer_pago_5)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_5);
					total_final=total_anual_decimal.minus(primer_pago_5);
					total_final=formatoMexico(total_final);
					
					cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
					$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);
					
					$('#subsecuente_opcion5').val(total_final);
					$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
					return true;
				}
				else
				{
					$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
					return true;
				}
			}
			else if (pago=='TRIMESTRAL')
			{
				if((parseFloat(primer_pago_5)!='' && parseFloat(primer_pago_5)!=null && parseFloat(primer_pago_5)!='e' && parseFloat(primer_pago_5)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_5);
					total_final=total_anual_decimal.minus(primer_pago_5);
					total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
					total_final=formatoMexico(total_final);

					cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
					$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);

					$('#subsecuente_opcion5').val(total_final);
					$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
					return true;

				}
				else
				{
					$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
					return true;
				}
			}
			else if (pago=='MENSUAL')
			{
				if((parseFloat(primer_pago_5)!='' && parseFloat(primer_pago_5)!=null && parseFloat(primer_pago_5)!='e' && parseFloat(primer_pago_5)>=0))
				{
					total_anual_decimal= new Decimal(cantidad_total_anual_opcion_5);
					total_final=total_anual_decimal.minus(primer_pago_5);
					total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
					
					cantidad_total_anual_opcion_5=formatoMexico(cantidad_total_anual_opcion_5);
					$('#cantidad_total_anual_opcion_5').val(cantidad_total_anual_opcion_5);

					primer_pago_5=formatoMexico(primer_pago_5);
					$('#primer_pago_5').val(primer_pago_5);

					$('#subsecuente_opcion5').val(total_final);
					$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
					return true;	 
				}
				else
				{

					$('#error_cantidad_total_anual_opcion_5').slideUp('fast');
					return true;
				}
			}
		}
	}
	else
	{
		return false;
	}
}
*/

function verificar_cantidad_total_anual_opcion_4()
{

	var resultado=verificar_forma_de_pago();
	if(resultado==true)
	{
		var cantidad_prima_neta_opcion4=$('#cantidad_prima_neta_opcion4').val();
		var cantidad_total_anual_opcion_4=$('#cantidad_total_anual_opcion_4').val();
		var primer_pago_4= $('#primer_pago_opcion_4').val();
		var pago=$('#forma_de_pago').val();
		var id_aseguradora = $('#empresas_opcion4').val();

		primer_pago_4=primer_pago_4.replace(/,/g, "");
		cantidad_total_anual_opcion_4=cantidad_total_anual_opcion_4.replace(/,/g, "");
		cantidad_prima_neta_opcion4=cantidad_prima_neta_opcion4.replace(/,/g, "");
		if(cantidad_total_anual_opcion_4=='' ||cantidad_total_anual_opcion_4==0 || cantidad_total_anual_opcion_4=="e")
		{
			$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_4').html('¡Debes ingresar una cantidad!');
			
			$('#cantidad_total_anual_opcion_4').focus();
			return false;
		}
		else  if((cantidad_total_anual_opcion_4.split('.').length-1)>1)
		{
			$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
			$('#error_cantidad_total_anual_opcion_4').html('¡Ingresa un número valido!');
			$('#cantidad_total_anual_opcion_4').focus();
			return false;
		}
		/*else if(parseFloat(cantidad_prima_neta_opcion4)>=parseFloat(cantidad_total_anual_opcion_4))//if ((parseFloat(cantidad_prima_neta_opcion4)!='' && parseFloat(cantidad_prima_neta_opcion4)!=null && parseFloat(cantidad_prima_neta_opcion4)!='e' && parseFloat(cantidad_prima_neta_opcion4)>=0))
		{
			
				$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
				$('#error_cantidad_total_anual_opcion_4').html('¡Prima total anual debe ser mayor que prima neta!');
				$('#cantidad_total_anual_opcion_4').focus();
				return false;
		
			
		}*/
		else
		{
			

			if(pago=='ANUAL')
			{
				//$('#primer_pago_opcion_4').val(cantidad_total_anual_opcion_4);
				$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
				$('#error_primer_pago_opcion_4').slideUp('fast');
				
				
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion4();
				}
				else
				{
						$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{
							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_4
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
								$('#cantidad_total_anual_opcion_4').focus();
								return false;
							}
							else
							{
								if(parseFloat(data)<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion4').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_4').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
									$('#cantidad_prima_neta_opcion4').val(prima_neta);
									$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);
									$('#primer_pago_opcion_4').val(cantidad_total_anual_opcion_4);
									$('#subsecuente_opcion4').val(0);
									return true;

								}
								
							}
							
						}

					});
				}
				
			}
			else if (pago=='SEMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion4();
				}
				else
				{
					if((parseFloat(primer_pago_4)!='' && parseFloat(primer_pago_4)!=null && parseFloat(primer_pago_4)!='e' && parseFloat(primer_pago_4)>=0))
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_4
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion4').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_4').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
										$('#cantidad_total_anual_opcion_4').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion4').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_4);
										total_final=total_anual_decimal.minus(primer_pago_4);
										total_final=formatoMexico(total_final);
										cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
										$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);
										$('#subsecuente_opcion4').val(total_final);
										$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
										return true;
									}
									
								}
							}

						});
						
					}
					else
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_4
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago semestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion4').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_4').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
										$('#cantidad_total_anual_opcion_4').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion4').val(prima_neta);
										cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
										$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);
										$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
										return true;
									}
									
								}
							}

						});
					}
				}
				
			}
			else if (pago=='TRIMESTRAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion4();
				}
				else
				{
					if((parseFloat(primer_pago_4)!='' && parseFloat(primer_pago_4)!=null && parseFloat(primer_pago_4)!='e' && parseFloat(primer_pago_4)>=0))
					{

						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_4
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion4').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_4').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
										$('#cantidad_total_anual_opcion_4').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion4').val(prima_neta);
										total_anual_decimal= new Decimal(cantidad_total_anual_opcion_4);
										total_final=total_anual_decimal.minus(primer_pago_4);
										total_final=(total_final.dividedBy(3)).toNumber().toFixed(2);
										total_final=formatoMexico(total_final);

										cantidad_total_anual_opcion_3=formatoMexico(cantidad_total_anual_opcion_4);
										$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);

										$('#subsecuente_opcion4').val(total_final);
										$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
										return true;
									}
									
								}
							}

						});
						

					}
					else
					{
						$.ajax({
							url:'metodos/cotizacion_metodos.php',
							type:'post',
							data:{

								metodos_sacar_prima_neta:1,
								id_aseguradora:id_aseguradora,
								forma_pago:pago,
								prima_anual_neta:cantidad_total_anual_opcion_4
							},
							success:function(data)
							{
								if(data.trim()=='no_derecho')
								{
									$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar derecho de poliza para procesar la información!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else if(data.trim()=='no_recargo')
								{
									$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago trimestral para procesar la información!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else
								{
									if(data<0)
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion4').val(prima_neta);
										$('#error_cantidad_total_anual_opcion_4').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
										$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
										$('#cantidad_total_anual_opcion_4').focus();
										return false;
									}
									else
									{
										var prima_neta = formatoMexico(data);
										$('#cantidad_prima_neta_opcion4').val(prima_neta);
										

										cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
										$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);

										$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
										return true;
									}
									
								}
							}

						});
					}
				}

				
			}
			else if (pago=='MENSUAL')
			{
				if(id_aseguradora=='0')
				{
					verificar_empresas_opcion4();
				}
				else
				{
						if((parseFloat(primer_pago_4)!='' && parseFloat(primer_pago_4)!=null && parseFloat(primer_pago_4)!='e' && parseFloat(primer_pago_4)>=0))
					{


					$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{

							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_4
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
								$('#cantidad_total_anual_opcion_4').focus();
								return false;
							}
							else if(data.trim()=='no_recargo')
							{
								$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
								$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
								$('#cantidad_total_anual_opcion_4').focus();
								return false;
							}
							else
							{
								if(data<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion4').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_4').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion4').val(prima_neta);
									total_anual_decimal= new Decimal(cantidad_total_anual_opcion_4);
									total_final=total_anual_decimal.minus(primer_pago_4);
									total_final=(total_final.dividedBy(11)).toNumber().toFixed(2);
									total_final=formatoMexico(total_final);
									cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
									$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);

									primer_pago_4=formatoMexico(primer_pago_4);
									$('#primer_pago_4').val(primer_pago_4);

									$('#subsecuente_opcion4').val(total_final);
									$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
									return true;
								}
								
							}
						}

					});


						 
					}
					else
					{

					$.ajax({
						url:'metodos/cotizacion_metodos.php',
						type:'post',
						data:{

							metodos_sacar_prima_neta:1,
							id_aseguradora:id_aseguradora,
							forma_pago:pago,
							prima_anual_neta:cantidad_total_anual_opcion_4
						},
						success:function(data)
						{
							if(data.trim()=='no_derecho')
							{
								$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar derecho de poliza para procesar la información!');
								$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
								$('#cantidad_total_anual_opcion_4').focus();
								return false;
							}
							else if(data.trim()=='no_recargo')
							{
								$('#error_cantidad_total_anual_opcion_4').html('¡Debes registrar recargo por cargo fraccionado en la forma de pago mensual para procesar la información!');
								$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
								$('#cantidad_total_anual_opcion_4').focus();
								return false;
							}
							else
							{
								if(data<0)
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion4').val(prima_neta);
									$('#error_cantidad_total_anual_opcion_4').html('¡El precio de la prima total anual, no puedo producir prima neta negativa!');
									$('#error_cantidad_total_anual_opcion_4').slideDown('fast');
									$('#cantidad_total_anual_opcion_4').focus();
									return false;
								}
								else
								{
									var prima_neta = formatoMexico(data);
									$('#cantidad_prima_neta_opcion4').val(prima_neta);
									
									cantidad_total_anual_opcion_4=formatoMexico(cantidad_total_anual_opcion_4);
									$('#cantidad_total_anual_opcion_4').val(cantidad_total_anual_opcion_4);

									primer_pago_4=formatoMexico(primer_pago_4);
									$('#primer_pago_4').val(primer_pago_4);

									$('#error_cantidad_total_anual_opcion_4').slideUp('fast');
									return true;
								}
								
							}
						}

					});


					}
				}

				
			}
		}
	}
	else
	{
		return false;
	}
}

function verificar_marca()
{
	var nombre=$('#marca').val();
	if(nombre=='')
	{
		$('#error_marca').slideDown('fast');
		$('#error_marca').html('¡Debes ingresar una marca!');
		
		$('#marca').focus();
		return false;
	}
	else 
	{
		if(!/^([a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n])*$/.test(nombre))
		{
			$('#error_marca').slideDown('fast');
			$('#error_marca').html('¡Solo esta permitido letras!');
			$('#marca').focus();
			return false;	
		}
		else
		{
			$('#error_marca').slideUp('fast');
			return true;
		}
	}
}

function verificar_descripcion()
{
	var nombre=$('#descripcion').val();
	if(nombre=='')
	{
		$('#error_descripcion').slideDown('fast');
		$('#error_descripcion').html('¡Debes ingresar una descripcion!');
		
		$('#descripcion').focus();
		return false;
	}
	else 
	{
		if(!/^([a-zA-Z0-9 áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\.\,\-\.])*$/.test(nombre))
		{
			$('#error_descripcion').slideDown('fast');
			$('#error_descripcion').html('¡Solo esta permitido letras!');
			$('#descripcion').focus();
			return false;	
		}
		else
		{
			$('#error_descripcion').slideUp('fast');
			return true;
		}
	}
}

function verificar_modelo()
{
	var modelo=$('#modelo').val();
	if(modelo=='' ||modelo==0 || modelo=="e")
	{
		$('#error_modelo').slideDown('fast');
		$('#error_modelo').html('¡Debes ingresar un modelo!');
		
		$('#modelo').focus();
		return false;
	}
	else if (parseInt(modelo)<0)
	{
		$('#error_modelo').slideDown('fast');
		$('#error_modelo').html('¡No esta permitido números negativos!');
		$('#modelo').focus();
		return false;
	}
	else
	{
		$('#error_modelo').slideUp('fast');
		return true;
	}
}

function verificar_uso_de_unidad()
{
	var nombre=$('#uso_de_unidad').val();
	if(nombre=='')
	{
		$('#error_uso_de_unidad').slideDown('fast');
		$('#error_uso_de_unidad').html('¡Debes ingresar uso de la unidad!');
		
		$('#uso_de_unidad').focus();
		return false;
	}
	else 
	{
		if(!/^([a-zA-Z0-9 áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\,\-\.])*$/.test(nombre))
		{
			$('#error_uso_de_unidad').slideDown('fast');
			$('#error_uso_de_unidad').html('¡Solo esta permitido letras!');
			$('#uso_de_unidad').focus();
			return false;	
		}
		else
		{
			$('#error_uso_de_unidad').slideUp('fast');
			return true;
		}
	}
}

function verificar_tipo_auto()
{
	var opcion=$('#tipo_auto').val();
	if(opcion==0)
	{
		$('#error_tipo_auto').slideDown('fast');
		$('#error_tipo_auto').html('¡Debes seleccionar un tipo de auto!');
		
		$('#tipo_auto').focus();
		return false;
	}
	else
	{
		$('#error_tipo_auto').slideUp('fast');
		return true;
	}
}

function verificar_carga()
{
	if($('#contenedor_descripcion_de_la_carga').is(':visible')===true)
	{
		var opcion=$('#carga').val();
		if(opcion==0)
		{
			$('#error_carga').slideDown('fast');
			$('#error_carga').html('¡Debes seleccionar un tipo de carga!');
			
			$('#carga').focus();
			return false;
		}
		else if ($('#carga').is(':visible')===true)
		{
			$('#error_carga').slideUp('fast');
			return true;
		}	
	}
	else
	{
		$('#error_carga').slideUp('fast');
			return true;
	}
	
}

function verificar_compañia_actual()
{
	if($('#contenedor_formacion_poliza_renovar').is(':visible')===true)
	{
		var nombre=$('#compañia_actual').val();

		if(nombre=='')
		{
			$('#error_compañia_actual').slideDown('fast');
			$('#error_compañia_actual').html('¡Debes ingresar un nombre de compañia actual!');
			
			$('#compañia_actual').focus();
			return false;
		}
		else 
		{
			if(!/^([0-9a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\*\+\.\,\-])*$/.test(nombre))
			{
				$('#error_compañia_actual').slideDown('fast');
				$('#error_compañia_actual').html('¡No estan permitidos caracteres especiales!');
				$('#compañia_actual').focus();
				return false;	
			}
			else
			{
				$('#error_compañia_actual').slideUp('fast');
				return true;
			}
		}	
	}
	else
	{
		$('#error_compañia_actual').slideUp('fast');
				return true;
	}
	
}
function verificar_fecha_vigencia()
{
	if($('#contenedor_formacion_poliza_renovar').is(':visible')===true)
	{
		var fecha_vigencia = $('#fecha_vigencia').val();
		if(fecha_vigencia==null  || fecha_vigencia=='')
		{
			$('#error_fecha_vigencia').html('¡Debes ingresar una fecha de vigencia!');
			$('#error_fecha_vigencia').slideDown('fast');
			$('#fecha_vigencia').focus();
			return false;
		}
		else
		{
			$('#error_fecha_vigencia').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_fecha_vigencia').slideUp('fast');
				return true;
	}
}

function verificar_poliza_a_renovar()
{
	if($('#contenedor_formacion_poliza_renovar').is(':visible')===true)
	{
		var poliza_a_renovar=$('#poliza_a_renovar').val();
		if(poliza_a_renovar=='' ||poliza_a_renovar==0 || poliza_a_renovar=="e")
		{
			$('#error_poliza_a_renovar').slideDown('fast');
			$('#error_poliza_a_renovar').html('¡Debes ingresar un poliza_a_renovar!');
			
			$('#poliza_a_renovar').focus();
			return false;
		}
		else if (parseInt(poliza_a_renovar)<0)
		{
			$('#error_poliza_a_renovar').slideDown('fast');
			$('#error_poliza_a_renovar').html('¡No esta permitido números negativos!');
			$('#poliza_a_renovar').focus();
			return false;
		}
		else
		{
			$('#error_poliza_a_renovar').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_compañia_actual').slideUp('fast');
				return true;
	}
}


function verificar_prima_año()
{
	if($('#contenedor_formacion_poliza_renovar').is(':visible')===true)
	{
		var prima_año=$('#prima_año').val();
		prima_año=prima_año.replace(/,/g, "");
		if(prima_año=='' ||prima_año==0 || prima_año=="e")
		{
			$('#error_prima_año').slideDown('fast');
			$('#error_prima_año').html('¡Debes ingresar prima del año anterior!');
			
			$('#prima_año').focus();
			return false;
		}
		else if((prima_año.split('.').length-1)>1)
				{
					$('#error_prima_año').slideDown('fast');
					$('#error_prima_año').html('¡Ingresa un número valido!');
					$('#prima_año').focus();
					return false;
				}
		else
		{
			prima_año=formatoMexico(prima_año);
			$('#prima_año').val(prima_año);
			$('#error_prima_año').slideUp('fast');
			return true;
		}
	}
	else
	{
		$('#error_compañia_actual').slideUp('fast');
				return true;
	}
}


function verificar_cantidad_aseguradoras()
{
	var opcion=$('#cantidad_aseguradoras').val();
	if(opcion==0)
	{
		$('#error_cantidad_aseguradoras').slideDown('fast');
		$('#error_cantidad_aseguradoras').html('¡Debes seleccionar la cantidad de aseguradoras!');
		
		$('#cantidad_aseguradoras').focus();
		return false;
	}
	else
	{
		$('#error_cantidad_aseguradoras').slideUp('fast');
		return true;
	}
}
function verificar_paquete()
{
	var opcion=$('#paquete').val();
	if(opcion==0)
	{
		$('#error_paquete').slideDown('fast');
		$('#error_paquete').html('¡Debes seleccionar el paquete!');
		
		$('#paquete').focus();
		return false;
	}
	else
	{
		$('#error_paquete').slideUp('fast');
		return true;
	}
}
function verificando_contactos()
{
	var opcion=$('#contactos').val();
	if(opcion==0)
	{
		$('#error_contactos').slideDown('fast');
		$('#error_contactos').html('¡Debes seleccionar el contactos!');
		
		$('#contactos').focus();
		return false;
	}
	else
	{
		$('#error_contactos').slideUp('fast');
		return true;
	}
}


function verificar_nombre_contacto()
{
	var nombre=$('#nombre_contacto').val();
	if(nombre=='')
	{
		$('#error_nombre_contacto').slideDown('fast');
		$('#error_nombre_contacto').html('¡Debes ingresar un nombre!');
		
		$('#nombre_contacto').focus();
		return false;
	}
	else 
	{
		if(!/^([a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n])*$/.test(nombre))
		{
			$('#error_nombre_contacto').slideDown('fast');
			$('#error_nombre_contacto').html('¡Solo esta permitido letras!');
			$('#nombre_contacto').focus();
			return false;	
		}
		else
		{
			$('#error_nombre_contacto').slideUp('fast');
			return true;
		}
	}
}

function verificar_telefono_contacto()
{
	var nombre=$('#telefono_contacto').val();
	if(nombre=='')
	{
		$('#error_telefono_contacto').slideDown('fast');
		$('#error_telefono_contacto').html('¡Debes ingresar un telefono de contacto!');
		
		$('#telefono_contacto').focus();
		return false;
	}
	else 
	{
		if(!/^([0-9])*$/.test(nombre))
		{
			$('#error_telefono_contacto').slideDown('fast');
			$('#error_telefono_contacto').html('¡Solo esta permitido letras!');
			$('#telefono_contacto').focus();
			return false;	
		}
		else
		{
			$('#error_telefono_contacto').slideUp('fast');
			return true;
		}
	}
}



function verficando_correo()
{
	var email = $('#correo_contacto').val();
	 if(email!="")
	{
		if (/^([a-zA-Z0-9_ñ\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email)) 
		{
			$('#error_correo_contacto').html("¡El formato de email no es válido. Intenta de nuevo!");
			$('#error_correo_contacto').slideUp('fast');

			var resultado="";
			/*$.ajax({
				url:'metodos/alta_usuarios_metodos.php',
				type:'post',
				dataType:'json',
				  async: false,
				data:{correo:email},	
				success:function(data)
				{
					 
					if(data.mensaje_correo==1)
					{
						$('#error_correo_contacto').html('¡El correo ya se encuentra registrado!');
						$('#error_correo_contacto').slideDown('fast');
						return resultado = false;
					}
					else
					{
						$('#error_correo_contacto').slideUp('fast');
						return resultado = true;
					}
				
				}
			});*/
		//return resultado;
			return true;
		}
		else
		{
			
			$('#error_correo_contacto').html("¡El formato de email no es válido. Intenta de nuevo!");
			$('#error_correo_contacto').slideDown('fast');	
			return false;
		}
	}
	else
	{
			$('#error_correo_contacto').slideUp('fast');	
			return true;
	}
}

function verificar_tipo_contacto()
{
	var tipo_contacto =$('#tipo_contacto').val();
	if(tipo_contacto=='0')
	{
		$('#error_tipo_contacto').html('¡Debes seleccionar un tipo de contacto!');
		$('#error_tipo_contacto').slideDown('fast');
		$('#tipo_contacto').focus();
		return false;
	}
	else
	{
		$('#error_tipo_contacto').slideUp('fast');
		return true;
	}
}

function verificar_hora_solicitada()
{
	if($('#hora_solicitada').is(':enabled')===true)
	{
		if($('#hora_solicitada').val()=='' || $('#hora_solicitada').val()==null)
		{
			$('#error_hora_solicitada').html('¡Debes ingresar la hora de solicitud de la nueva cotización!');
			$('#error_hora_solicitada').slideDown('fast');
			$('#hora_solicitada').focus();
			return false;
		}
		else
		{

			var ingresada =moment($('#hora_solicitada').val(), 'HH:mm:ss');
			var now = moment();
			if(ingresada.isBefore(now)==true)
			{
				$('#error_hora_solicitada').slideUp('fast');
				return true;
			}
			else
			{
				$('#error_hora_solicitada').html('¡La hora solicitada no puede ser mayor a la hora actual!');
				$('#error_hora_solicitada').slideDown('fast');
				$('#hora_solicitada').focus();
				return false;
		
			}
			
			
		}
	}
	else
	{
		$('#error_hora_solicitada').slideUp('fast');
		return true;
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