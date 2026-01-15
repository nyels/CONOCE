$(document).ready(inicio);
function inicio()
{
	$('#fecha_inicial_nueva').blur(verificar_fecha_inicial);
	$('#fecha_final_nueva').blur(verificar_fecha_final);


	$('#fecha_inicial_renovacion').blur(verificar_fecha_inicial_renovacion);
	$('#fecha_final_renovacion').blur(verificar_fecha_final_renovacion);

	$('#opciones_Cotizadas').blur(verificar_select_opcion_cotizadas);
	$('#numero_poliza').blur(verificar_numero_poliza);
	$('#inicio_vigencia').blur(verificar_fecha_inicio_vigencia);
	$('#select_opcion_no_concretar').blur(verificar_select_motivos);
}

window.addEventListener("load", function() 
{
var inicio_vigencia = document.querySelector('#inicio_vigencia');
  	inicio_vigencia.addEventListener("keypress", todas_las_teclas, false);
});

function todas_las_teclas(e)
{
  var key = window.event ? e.which : e.keyCode;
  if (key >0)
  {
    e.preventDefault();
  }
}

$(document).on('click','.cotizacion',function(e){

$('#contenedor_genetal_tabla_contizaciones_renovaciones').slideUp('fast');
$('#contenedor_general_si_no_concreta').slideUp('fast');
$('#contenedor_formulario_no_concreta').slideUp('fast');



$('#contenedor_formulario_cotizacion').slideDown('fast');


});

$(document).on('click','.prospecto',function(e){
	$.ajax({
		url:'metodos/cotizacion_metodos.php',
		type:'post',
		data:{prospecto:this.value},
		success:function(data)
		{
			$('#titulo_mostrar_datos').html('DATOS PROSPECTO');
			$('#cuerpo_modal_mostrar_datos').html(data);
			$('#modal_mostrar_datos').modal({show:true});
		}
	});
});

$(document).on('click','.opciones',function(e){
	$.ajax({
		url:'metodos/cotizacion_metodos.php',
		type:'post',
		data:{opciones:this.value},
		success:function(data)
		{
			$('#titulo_mostrar_datos').html('OPCIONES COTIZADAS');
			$('#cuerpo_modal_mostrar_datos').html(data);
			$('#modal_mostrar_datos').modal({show:true});
		}
	});
});

$(document).on('click','.contacto',function(e){
	$.ajax({
		url:'metodos/cotizacion_metodos.php',
		type:'post',
		data:{contacto:this.value},
		success:function(data)
		{
			$('#titulo_mostrar_datos').html('DATOS CONTACTO');
			$('#cuerpo_modal_mostrar_datos').html(data);
			$('#modal_mostrar_datos').modal({show:true});
		}
	});
});




//ver tabla de cotizaciones
$(document).on('click','.lista_cotizaciones',function(e){
$('#contenedor_general_si_no_concreta').slideUp('fast');
$('#contenedor_formulario_cotizacion').slideUp('fast');
$('#contenedor_genetal_tabla_contizaciones_renovaciones').slideDown('fast');

$('#contenedor_tabla_renovaciones').slideUp('fast');
$('#contenedor_tabla_nuevas_cotizaciones').slideDown('fast');


var fecha=hoyFecha();
$('#tabla_lista_cotizaciones').DataTable({
  initComplete: function () {
            this.api().columns('.select-filter').every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
		order:[[1,'desc']],
			"destroy":true,
			"pageLength": 20,
			 "language": 
			 {
		      "emptyTable": "No se encontraron datos"
	    }, 
		dom:'Blfrtip',
              buttons:[

                {

                extend:'excelHtml5',
                className:'btn-success',
                 //autoFilter: true,
                title:'Lista de cotizaciones nuevas',
                 sheetName: 'COTIZACIONES NUEVAS',
                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
                exportOptions: 
                {
                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
                  }
                }],
		    ajax:
			{
				url:'metodos/cotizacion_metodos.php',
				type:"post",
				data:function(d)
				{
					d.cotizaciones_nuevas=1;
				}		
			},
			 "columnDefs": [
		        {"className": "dt-center", "targets": "_all"}
		      ],
			"columns":
			[
				{"data":"realizo"},
				{"data":"fecha_alta"},
				{"data":"hora_solicitada"},
				{"data":"contacto"},
				{"data":"tipo_contacto"},
				{"data":"prospecto"},
				{"data":"descripcion"},
				{"data":"cantidad_opciones_cotizadas"},
				{"data":"objetivo_minimo_concretar"},
				{"data":"paquete_solicitado"},
				{"data":"forma_de_pago"},
				{"data":"prima_neta"},
				{"data":"prima_total_anual"},
				{"data":"hora_envio"},
				{"data":"tiempo_respuesta"},
				{"data":"estatus_cotizacion"},
				{"data":"pdf"},
				{"data":"concretar"},
				{"data":"aseguradora_concretada"},
				{"data":"prima_neta_concretada"},
				{"data":"prima_total_anual_concretada"},
				{"data":"numero_poliza"},
				{"data":"inicio_vigencia"},
				{"data":"primer_pago"},
				{"data":"motivos"},
			]
	});


});


//muestra lista de renovaciones
$(document).on('click','.lista_renovaciones',function(e){
$('#contenedor_general_si_no_concreta').slideUp('fast');
$('#contenedor_formulario_cotizacion').slideUp('fast');
$('#contenedor_genetal_tabla_contizaciones_renovaciones').slideDown('fast');
$('#contenedor_tabla_nuevas_cotizaciones').slideUp('fast');
$('#contenedor_tabla_renovaciones').slideDown('fast');


var fecha=hoyFecha();
$('#tabla_lista_renovaciones').DataTable({
  initComplete: function () {
            this.api().columns('.select-filter').every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
		order:[[1,'desc']],
			"destroy":true,
			"pageLength": 20,
			 "language": 
			 {
		      "emptyTable": "No se encontraron datos"
	    }, 
		dom:'Blfrtip',
              buttons:[

                {

                extend:'excelHtml5',
                className:'btn-success',
                 //autoFilter: true,
                title:'Lista de renovaciones',
                 sheetName: 'RENOVACIONES',
                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
                exportOptions: 
                {
                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
                  }
                }],
		    ajax:
			{
				url:'metodos/cotizacion_metodos.php',
				type:"post",
				data:function(d)
				{
					d.cotizaciones_renovaciones=1;
				}		
			},
			 "columnDefs": [
		        {"className": "dt-center", "targets": "_all"}
		      ],
			"columns":
			[
				{"data":"realizo"},
				{"data":"fecha_alta"},
				{"data":"fecha_vencimiento"},
				{"data":"aseguradora_actual"},
				{"data":"poliza_renovar"},
				{"data":"contacto"},
				{"data":"tipo_contacto"},
				{"data":"prospecto"},
				{"data":"descripcion"},
				{"data":"cantidad_opciones_cotizadas"},
				{"data":"objetivo_minimo_concretar"},
				{"data":"paquete_solicitado"},
				{"data":"forma_de_pago"},
				{"data":"prima_neta"},
				{"data":"prima_total_anual"},
				{"data":"fecha_envio_cotizacion_renovacion"},
				{"data":"tiempo_respuesta"},
				{"data":"estatus_cotizacion"},
				{"data":"pdf"},
				{"data":"concretar"},
				{"data":"aseguradora_concretada"},
				{"data":"prima_neta_concretada"},
				{"data":"prima_total_anual_concretada"},
				{"data":"numero_poliza"},
				{"data":"inicio_vigencia"},
				{"data":"primer_pago"},
				{"data":"fecha_envio_poliza"},
				{"data":"motivos"},
			]
	});


});

function verificar_fecha_inicial()
{
	var fecha_inicial=$('#fecha_inicial_nueva').val();
	
	var fecha_final=$('#fecha_final_nueva').val();

	var fecha=hoyFecha();
	if(fecha_inicial!='' && fecha_final!='')
	{

		$('#tabla_lista_cotizaciones').DataTable({

  initComplete: function () {
            this.api().columns('.select-filter').every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
				order:[[1,'desc']],
					"destroy":true,
					"pageLength": 20,
					 "language": 
					 {
				      "emptyTable": "No se encontraron datos"
			    }, 
				dom:'Blrtip',
		              buttons:[

		                {

		                extend:'excelHtml5',
		                className:'btn-success',
		                 //autoFilter: true,
		                title:'Lista de cotizaciones nuevas del '+fecha_inicial+' al '+fecha_final,
		                 sheetName: 'COTIZACIONES NUEVAS',
		                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
		                exportOptions: 
		                {
		                    columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
		                  }
		                }],
				    ajax:
					{
						url:'metodos/cotizacion_metodos.php',
						type:"post",
						data:function(d)
						{
							d.cotizaciones_nuevas=1;
							d.fecha_inicial_nueva=fecha_inicial;
							d.fecha_final_nueva=fecha_final;
							
						}		
					},
					 "columnDefs": [
				        {"className": "dt-center", "targets": "_all"}
				      ],
					"columns":
					[
					{"data":"realizo"},
						{"data":"fecha_alta"},
						{"data":"hora_solicitada"},
						{"data":"contacto"},
						{"data":"tipo_contacto"},
						{"data":"prospecto"},
						{"data":"descripcion"},
						{"data":"cantidad_opciones_cotizadas"},
						{"data":"objetivo_minimo_concretar"},
						{"data":"paquete_solicitado"},
						{"data":"forma_de_pago"},
						{"data":"prima_neta"},
						{"data":"prima_total_anual"},
						{"data":"hora_envio"},
						{"data":"tiempo_respuesta"},
						{"data":"estatus_cotizacion"},
						{"data":"pdf"},
						{"data":"concretar"},
						{"data":"aseguradora_concretada"},
						{"data":"prima_neta_concretada"},
						{"data":"prima_total_anual_concretada"},
						{"data":"numero_poliza"},
						{"data":"inicio_vigencia"},
						{"data":"primer_pago"},
						{"data":"motivos"},
					]
			});
	}
	else
	{

	}

}

function verificar_fecha_inicial_renovacion()
{
	var fecha_inicial=$('#fecha_inicial_renovacion').val();
	
	var fecha_final=$('#fecha_final_renovacion').val();

	var fecha=hoyFecha();
	if(fecha_inicial!='' && fecha_final!='')
	{
		$('#tabla_lista_renovaciones').DataTable({
		  initComplete: function () {
		            this.api().columns('.select-filter').every( function () {
		                var column = this;
		                var select = $('<select><option value=""></option></select>')
		                    .appendTo( $(column.header()).empty() )
		                    .on( 'change', function () {
		                        var val = $.fn.dataTable.util.escapeRegex(
		                            $(this).val()
		                        );
		 
		                        column
		                            .search( val ? '^'+val+'$' : '', true, false )
		                            .draw();
		                    } );
		 
		                column.data().unique().sort().each( function ( d, j ) {
		                    select.append( '<option value="'+d+'">'+d+'</option>' )
		                } );
		            } );
		        },
			order:[[1,'desc']],
					"destroy":true,
					"pageLength": 20,
					 "language": 
					 {
				      "emptyTable": "No se encontraron datos"
			    }, 
				dom:'Blfrtip',
		              buttons:[

		                {

		                extend:'excelHtml5',
		                className:'btn-success',
		                 //autoFilter: true,
		                  title:'Lista de renovaciones del '+fecha_inicial+' al '+fecha_final,
		                 sheetName: 'RENOVACIONES',
		                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
		                exportOptions: 
		                {
		                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
		                  }
		                }],
				    ajax:
					{
						url:'metodos/cotizacion_metodos.php',
						type:"post",
						data:function(d)
						{
							d.cotizaciones_renovaciones=1;
							d.fecha_inicial_nueva=fecha_inicial;
							d.fecha_final_nueva=fecha_final;
						}		
					},
					 "columnDefs": [
				        {"className": "dt-center", "targets": "_all"}
				      ],
					"columns":
					[
					{"data":"realizo"},
						{"data":"fecha_alta"},
						{"data":"fecha_vencimiento"},
						{"data":"aseguradora_actual"},
						{"data":"poliza_renovar"},
						{"data":"contacto"},
						{"data":"tipo_contacto"},
						{"data":"prospecto"},
						{"data":"descripcion"},
						{"data":"cantidad_opciones_cotizadas"},
						{"data":"objetivo_minimo_concretar"},
						{"data":"paquete_solicitado"},
						{"data":"forma_de_pago"},
						{"data":"prima_neta"},
						{"data":"prima_total_anual"},
						{"data":"fecha_envio_cotizacion_renovacion"},
						{"data":"tiempo_respuesta"},
						{"data":"estatus_cotizacion"},
						{"data":"pdf"},
						{"data":"concretar"},
						{"data":"aseguradora_concretada"},
						{"data":"prima_neta_concretada"},
						{"data":"prima_total_anual_concretada"},
						{"data":"numero_poliza"},
						{"data":"inicio_vigencia"},
						{"data":"primer_pago"},
						{"data":"fecha_envio_poliza"},
						{"data":"motivos"},
					]
			});

	}
	else
	{

	}

}


function verificar_fecha_final ()
{
	var fecha_inicial=$('#fecha_inicial_nueva').val();
	var fecha_final=$('#fecha_final_nueva').val();

	if(fecha_inicial!='' && fecha_final!='')
	{

		$('#tabla_lista_cotizaciones').DataTable({

  initComplete: function () {
            this.api().columns('.select-filter').every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
				order:[[1,'desc']],
					"destroy":true,
					"pageLength": 20,
					 "language": 
					 {
				      "emptyTable": "No se encontraron datos"
			    }, 
				dom:'Bfrtip',
		              buttons:[

		                {

		                extend:'excelHtml5',
		                className:'btn-success',
		                 //autoFilter: true,
		                title:'Lista de cotizaciones nuevas del '+fecha_inicial+' al'+fecha_final,
		                 sheetName: 'COTIZACIONES NUEVAS',
		                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
		                exportOptions: 
		                {
		                   columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
		                  }
		                }],
				    ajax:
					{
						url:'metodos/cotizacion_metodos.php',
						type:"post",
						data:function(d)
						{
							d.cotizaciones_nuevas=1;
							d.fecha_inicial_nueva=fecha_inicial;
							d.fecha_final_nueva=fecha_final;
							
						}		
					},
					 "columnDefs": [
				        {"className": "dt-center", "targets": "_all"}
				      ],
					"columns":
					[
					{"data":"realizo"},
						{"data":"fecha_alta"},
						{"data":"hora_solicitada"},
						{"data":"contacto"},
						{"data":"tipo_contacto"},
						{"data":"prospecto"},
						{"data":"descripcion"},
						{"data":"cantidad_opciones_cotizadas"},
						{"data":"objetivo_minimo_concretar"},
						{"data":"paquete_solicitado"},
						{"data":"forma_de_pago"},
						{"data":"prima_neta"},
						{"data":"prima_total_anual"},
						{"data":"hora_envio"},
						{"data":"tiempo_respuesta"},
						{"data":"estatus_cotizacion"},
						{"data":"pdf"},
						{"data":"concretar"},
						{"data":"aseguradora_concretada"},
						{"data":"prima_neta_concretada"},
						{"data":"prima_total_anual_concretada"},
						{"data":"numero_poliza"},
						{"data":"inicio_vigencia"},
						{"data":"primer_pago"},
						{"data":"motivos"},
					]
			});
	}
	else
	{

	}

}


function verificar_fecha_final_renovacion ()
{
	var fecha_inicial=$('#fecha_inicial_renovacion').val();
	var fecha_final=$('#fecha_final_renovacion').val();
		
	if(fecha_inicial!='' && fecha_final!='')
	{

		$('#tabla_lista_renovaciones').DataTable({
		  initComplete: function () {
		            this.api().columns('.select-filter').every( function () {
		                var column = this;
		                var select = $('<select><option value=""></option></select>')
		                    .appendTo( $(column.header()).empty() )
		                    .on( 'change', function () {
		                        var val = $.fn.dataTable.util.escapeRegex(
		                            $(this).val()
		                        );
		 
		                        column
		                            .search( val ? '^'+val+'$' : '', true, false )
		                            .draw();
		                    } );
		 
		                column.data().unique().sort().each( function ( d, j ) {
		                    select.append( '<option value="'+d+'">'+d+'</option>' )
		                } );
		            } );
		        },
				order:[[1,'desc']],
					"destroy":true,
					"pageLength": 20,
					 "language": 
					 {
				      "emptyTable": "No se encontraron datos"
			    }, 
				dom:'Blfrtip',
		              buttons:[

		                {

		                extend:'excelHtml5',
		                className:'btn-success',
		                 //autoFilter: true,
		                  title:'Lista de renovaciones del '+fecha_inicial+' al '+fecha_final,
		                 sheetName: 'RENOVACIONES',
		                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
		                exportOptions: 
		                {
		                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
		                  }
		                }],
				    ajax:
					{
						url:'metodos/cotizacion_metodos.php',
						type:"post",
						data:function(d)
						{
							d.cotizaciones_renovaciones=1;
							d.fecha_inicial_nueva=fecha_inicial;
							d.fecha_final_nueva=fecha_final;
						}		
					},
					 "columnDefs": [
				        {"className": "dt-center", "targets": "_all"}
				      ],
					"columns":
					[
					{"data":"realizo"},
						{"data":"fecha_alta"},
						{"data":"fecha_vencimiento"},
						{"data":"aseguradora_actual"},
						{"data":"poliza_renovar"},
						{"data":"contacto"},
						{"data":"tipo_contacto"},
						{"data":"prospecto"},
						{"data":"descripcion"},
						{"data":"cantidad_opciones_cotizadas"},
						{"data":"objetivo_minimo_concretar"},
						{"data":"paquete_solicitado"},
						{"data":"forma_de_pago"},
						{"data":"prima_neta"},
						{"data":"prima_total_anual"},
						{"data":"fecha_envio_cotizacion_renovacion"},
						{"data":"tiempo_respuesta"},
						{"data":"estatus_cotizacion"},
						{"data":"pdf"},
						{"data":"concretar"},
						{"data":"aseguradora_concretada"},
						{"data":"prima_neta_concretada"},
						{"data":"prima_total_anual_concretada"},
						{"data":"numero_poliza"},
						{"data":"inicio_vigencia"},
						{"data":"primer_pago"},
						{"data":"fecha_envio_poliza"},
						{"data":"motivos"},
					]
			});
	}
	else
	{

	}

}


$(document).on('click','.si_concretar',function(e){
document.getElementById('formulario_concretar').reset();
$('#alerta_error_concretar').slideUp('fast');
$('#alerta_correcta_concretar').slideUp('fast');

$('#contenedor_general_si_no_concreta').slideDown('fast');
$('#contenedor_formulario_concreta').slideDown('fast');
$('#contenedor_formulario_no_concreta').slideUp('fast');

var datos =$(this).val();


prospecto_concretar=$(this).parents("tr").find("td").eq(4).text();
descripcion_vechiculo=$(this).parents("tr").find("td").eq(5).text();


$('#prospecto_concretar').val(prospecto_concretar);
$('#descripcion_vehiculo_concretar').val(descripcion_vechiculo);
$('#guardar_concretar').val(datos);

datos = datos.split('*');

switch(datos[1])
{
	case'RENOVACION':
	$('#inicio_vigencia').val($(this).parents("tr").find("td").eq(1).text());
	break;
}		

	$.ajax({
		url:'metodos/cotizacion_metodos.php',
		type:'post',
		data:{concretar_buscando_datos:datos},
		success:function(data)
		{
			$('#opciones_Cotizadas').html(data);
		}
	});

});

$(document).on('change','#opciones_Cotizadas',function(e){
prima_anual=$(this).val();
prima_anual=prima_anual.split('*');
$('#prima_anual_concretar').val('$'+prima_anual[1]);

});

$(document).on('click','#guardar_concretar',function(e){

var datos = $(this).val();
datos = datos.split('*');

$('#alerta_error_concretar').slideUp('fast');
$('#alerta_correcta_concretar').slideUp('fast');
var parametros = new FormData(document.getElementById('formulario_concretar'));
	parametros.append('guardar_concretar',$(this).val());
	//parametros.forEach((valor,key)=>console.log('valor: '+valor+' key'+key));
		$.ajax({
			url:'metodos/cotizacion_metodos.php',
			type:'post',
			data:parametros,
			contentType:false,
			processData:false,
			success:function(data)
			{
				if(data.trim()=='1')
				{
					document.getElementById('formulario_concretar').reset();
					$('#alerta_correcta_concretar').slideDown('fast');
					
					var fecha=hoyFecha();
					switch(datos[1])
					{
						case'NUEVA':
						$('#tabla_lista_cotizaciones').DataTable({
						  initComplete: function () {
						            this.api().columns('.select-filter').every( function () {
						                var column = this;
						                var select = $('<select><option value=""></option></select>')
						                    .appendTo( $(column.header()).empty() )
						                    .on( 'change', function () {
						                        var val = $.fn.dataTable.util.escapeRegex(
						                            $(this).val()
						                        );
						 
						                        column
						                            .search( val ? '^'+val+'$' : '', true, false )
						                            .draw();
						                    } );
						 
						                column.data().unique().sort().each( function ( d, j ) {
						                    select.append( '<option value="'+d+'">'+d+'</option>' )
						                } );
						            } );
						        },
								order:[[1,'desc']],
									"destroy":true,
									"pageLength": 20,
									 "language": 
									 {
								      "emptyTable": "No se encontraron datos"
							    }, 
								dom:'Blfrtip',
						              buttons:[

						                {

						                extend:'excelHtml5',
						                className:'btn-success',
						                 //autoFilter: true,
						                title:'Lista de cotizaciones nuevas',
						                 sheetName: 'COTIZACIONES NUEVAS',
						                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
						                exportOptions: 
						                {
						                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
						                  }
						                }],
								    ajax:
									{
										url:'metodos/cotizacion_metodos.php',
										type:"post",
										data:function(d)
										{
											d.cotizaciones_nuevas=1;
										}		
									},
									 "columnDefs": [
								        {"className": "dt-center", "targets": "_all"}
								      ],
									"columns":
									[
									{"data":"realizo"},
										{"data":"fecha_alta"},
										{"data":"hora_solicitada"},
										{"data":"contacto"},
										{"data":"tipo_contacto"},
										{"data":"prospecto"},
										{"data":"descripcion"},
										{"data":"cantidad_opciones_cotizadas"},
										{"data":"objetivo_minimo_concretar"},
										{"data":"paquete_solicitado"},
										{"data":"forma_de_pago"},
										{"data":"prima_neta"},
										{"data":"prima_total_anual"},
										{"data":"hora_envio"},
										{"data":"tiempo_respuesta"},
										{"data":"estatus_cotizacion"},
										{"data":"pdf"},
										{"data":"concretar"},
										{"data":"aseguradora_concretada"},
										{"data":"prima_neta_concretada"},
										{"data":"prima_total_anual_concretada"},
										{"data":"numero_poliza"},
										{"data":"inicio_vigencia"},
										{"data":"primer_pago"},
										{"data":"motivos"},
									]
							});
						break;
						case 'RENOVACION':

						$('#tabla_lista_renovaciones').DataTable({
						  initComplete: function () {
						            this.api().columns('.select-filter').every( function () {
						                var column = this;
						                var select = $('<select><option value=""></option></select>')
						                    .appendTo( $(column.header()).empty() )
						                    .on( 'change', function () {
						                        var val = $.fn.dataTable.util.escapeRegex(
						                            $(this).val()
						                        );
						 
						                        column
						                            .search( val ? '^'+val+'$' : '', true, false )
						                            .draw();
						                    } );
						 
						                column.data().unique().sort().each( function ( d, j ) {
						                    select.append( '<option value="'+d+'">'+d+'</option>' )
						                } );
						            } );
						        },
								order:[[1,'desc']],
									"destroy":true,
									"pageLength": 20,
									 "language": 
									 {
								      "emptyTable": "No se encontraron datos"
							    }, 
								dom:'Blfrtip',
						              buttons:[

						                {

						                extend:'excelHtml5',
						                className:'btn-success',
						                 //autoFilter: true,
						                title:'Lista de renovaciones',
						                 sheetName: 'RENOVACIONES',
						                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
						                exportOptions: 
						                {
						                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
						                  }
						                }],
								    ajax:
									{
										url:'metodos/cotizacion_metodos.php',
										type:"post",
										data:function(d)
										{
											d.cotizaciones_renovaciones=1;
										}		
									},
									 "columnDefs": [
								        {"className": "dt-center", "targets": "_all"}
								      ],
									"columns":
									[
									{"data":"realizo"},
										{"data":"fecha_alta"},
										{"data":"fecha_vencimiento"},
										{"data":"aseguradora_actual"},
										{"data":"poliza_renovar"},
										{"data":"contacto"},
										{"data":"tipo_contacto"},
										{"data":"prospecto"},
										{"data":"descripcion"},
										{"data":"cantidad_opciones_cotizadas"},
										{"data":"objetivo_minimo_concretar"},
										{"data":"paquete_solicitado"},
										{"data":"forma_de_pago"},
										{"data":"prima_neta"},
										{"data":"prima_total_anual"},
										{"data":"fecha_envio_cotizacion_renovacion"},
										{"data":"tiempo_respuesta"},
										{"data":"estatus_cotizacion"},
										{"data":"pdf"},
										{"data":"concretar"},
										{"data":"aseguradora_concretada"},
										{"data":"prima_neta_concretada"},
										{"data":"prima_total_anual_concretada"},
										{"data":"numero_poliza"},
										{"data":"inicio_vigencia"},
										{"data":"primer_pago"},
										{"data":"fecha_envio_poliza"},
										{"data":"motivos"},
									]
							});
						break;

					}
					
				}
				else
				{
					$('#alerta_error_concretar').slideDown('fast');
				}
			}
		});
});

$(document).on('click','.no_concretar',function(e){
document.getElementById('formulario_concretar').reset();
$('#alerta_error_concretar').slideUp('fast');
$('#alerta_correcta_concretar').slideUp('fast');

$('#contenedor_general_si_no_concreta').slideDown('fast');
$('#contenedor_formulario_concreta').slideUp('fast');
$('#contenedor_formulario_no_concreta').slideDown('fast');

var datos =$(this).val();
$('#guardar_no_concretar').val(datos);

});


$(document).on('click','#guardar_no_concretar',function(e){

datos = $(this).val();

$('#alerta_error_concretar').slideUp('fast');
$('#alerta_correcta_concretar').slideUp('fast');
var parametros = new FormData(document.getElementById('formulario_no_concreta'));
	parametros.append('guardar_no_concretar',$(this).val());
	//parametros.forEach((valor,key)=>console.log('valor: '+valor+' key'+key));
		$.ajax({
			url:'metodos/cotizacion_metodos.php',
			type:'post',
			data:parametros,
			contentType:false,
			processData:false,
			success:function(data)
			{
				if(data.trim()=='1')
				{
					document.getElementById('formulario_no_concreta').reset();
					$('#alerta_correcta_concretar').slideDown('fast');
					
					var fecha=hoyFecha();

					switch(datos[0])
					{
						case'NUEVA':
								$('#tabla_lista_cotizaciones').DataTable({
								  initComplete: function () {
								            this.api().columns('.select-filter').every( function () {
								                var column = this;
								                var select = $('<select><option value=""></option></select>')
								                    .appendTo( $(column.header()).empty() )
								                    .on( 'change', function () {
								                        var val = $.fn.dataTable.util.escapeRegex(
								                            $(this).val()
								                        );
								 
								                        column
								                            .search( val ? '^'+val+'$' : '', true, false )
								                            .draw();
								                    } );
								 
								                column.data().unique().sort().each( function ( d, j ) {
								                    select.append( '<option value="'+d+'">'+d+'</option>' )
								                } );
								            } );
								        },
										order:[[1,'desc']],
											"destroy":true,
											"pageLength": 20,
											 "language": 
											 {
										      "emptyTable": "No se encontraron datos"
									    }, 
										dom:'Blfrtip',
								              buttons:[

								                {

								                extend:'excelHtml5',
								                className:'btn-success',
								                 //autoFilter: true,
								                title:'Lista de cotizaciones nuevas',
								                 sheetName: 'COTIZACIONES NUEVAS',
								                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
								                exportOptions: 
								                {
								                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
								                  }
								                }],
										    ajax:
											{
												url:'metodos/cotizacion_metodos.php',
												type:"post",
												data:function(d)
												{
													d.cotizaciones_nuevas=1;
												}		
											},
											 "columnDefs": [
										        {"className": "dt-center", "targets": "_all"}
										      ],
											"columns":
											[
											{"data":"realizo"},
												{"data":"fecha_alta"},
												{"data":"hora_solicitada"},
												{"data":"contacto"},
												{"data":"tipo_contacto"},
												{"data":"prospecto"},
												{"data":"descripcion"},
												{"data":"cantidad_opciones_cotizadas"},
												{"data":"objetivo_minimo_concretar"},
												{"data":"paquete_solicitado"},
												{"data":"forma_de_pago"},
												{"data":"prima_neta"},
												{"data":"prima_total_anual"},
												{"data":"hora_envio"},
												{"data":"tiempo_respuesta"},
												{"data":"estatus_cotizacion"},
												{"data":"pdf"},
												{"data":"concretar"},
												{"data":"aseguradora_concretada"},
												{"data":"prima_neta_concretada"},
												{"data":"prima_total_anual_concretada"},
												{"data":"numero_poliza"},
												{"data":"inicio_vigencia"},
												{"data":"primer_pago"},
												{"data":"motivos"},
											]
									});
						break;
						case 'RENOVACION':

								$('#tabla_lista_renovaciones').DataTable({
								  initComplete: function () {
								            this.api().columns('.select-filter').every( function () {
								                var column = this;
								                var select = $('<select><option value=""></option></select>')
								                    .appendTo( $(column.header()).empty() )
								                    .on( 'change', function () {
								                        var val = $.fn.dataTable.util.escapeRegex(
								                            $(this).val()
								                        );
								 
								                        column
								                            .search( val ? '^'+val+'$' : '', true, false )
								                            .draw();
								                    } );
								 
								                column.data().unique().sort().each( function ( d, j ) {
								                    select.append( '<option value="'+d+'">'+d+'</option>' )
								                } );
								            } );
								        },
										order:[[1,'desc']],
											"destroy":true,
											"pageLength": 20,
											 "language": 
											 {
										      "emptyTable": "No se encontraron datos"
									    }, 
										dom:'Blfrtip',
								              buttons:[

								                {

								                extend:'excelHtml5',
								                className:'btn-success',
								                 //autoFilter: true,
								                title:'Lista de renovaciones',
								                 sheetName: 'RENOVACIONES',
								                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
								                exportOptions: 
								                {
								                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
								                  }
								                }],
										    ajax:
											{
												url:'metodos/cotizacion_metodos.php',
												type:"post",
												data:function(d)
												{
													d.cotizaciones_renovaciones=1;
												}		
											},
											 "columnDefs": [
										        {"className": "dt-center", "targets": "_all"}
										      ],
											"columns":
											[
											{"data":"realizo"},
												{"data":"fecha_alta"},
												{"data":"fecha_vencimiento"},
												{"data":"aseguradora_actual"},
												{"data":"poliza_renovar"},
												{"data":"contacto"},
												{"data":"tipo_contacto"},
												{"data":"prospecto"},
												{"data":"descripcion"},
												{"data":"cantidad_opciones_cotizadas"},
												{"data":"objetivo_minimo_concretar"},
												{"data":"paquete_solicitado"},
												{"data":"forma_de_pago"},
												{"data":"prima_neta"},
												{"data":"prima_total_anual"},
												{"data":"fecha_envio_cotizacion_renovacion"},
												{"data":"tiempo_respuesta"},
												{"data":"estatus_cotizacion"},
												{"data":"pdf"},
												{"data":"concretar"},
												{"data":"aseguradora_concretada"},
												{"data":"prima_neta_concretada"},
												{"data":"prima_total_anual_concretada"},
												{"data":"numero_poliza"},
												{"data":"inicio_vigencia"},
												{"data":"primer_pago"},
												{"data":"fecha_envio_poliza"},
												{"data":"motivos"},
											]
									});
						break;
					}
					
				}
				else
				{
					$('#alerta_error_concretar').slideDown('fast');
				}
			}
		});
});

//dar fecha envio cotizacion renovacion
$(document).on('click','.fecha_envio_cot_reno',function(e){
var id_tabla =$(this).val();
		$.ajax({
			url:'metodos/cotizacion_metodos.php',
			type:'post',
			data:{alta_fecha_envio_renovacion:id_tabla},
			success:function(data)
			{
				if(data.trim()=='1')
				{
					
					$('#tabla_lista_renovaciones').DataTable({
					  initComplete: function () {
					            this.api().columns('.select-filter').every( function () {
					                var column = this;
					                var select = $('<select><option value=""></option></select>')
					                    .appendTo( $(column.header()).empty() )
					                    .on( 'change', function () {
					                        var val = $.fn.dataTable.util.escapeRegex(
					                            $(this).val()
					                        );
					 
					                        column
					                            .search( val ? '^'+val+'$' : '', true, false )
					                            .draw();
					                    } );
					 
					                column.data().unique().sort().each( function ( d, j ) {
					                    select.append( '<option value="'+d+'">'+d+'</option>' )
					                } );
					            } );
					        },
							order:[[1,'desc']],
								"destroy":true,
								"pageLength": 20,
								 "language": 
								 {
							      "emptyTable": "No se encontraron datos"
						    }, 
							dom:'Blfrtip',
					              buttons:[

					                {

					                extend:'excelHtml5',
					                className:'btn-success',
					                 //autoFilter: true,
					                title:'Lista de renovaciones',
					                 sheetName: 'RENOVACIONES',
					                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
					                exportOptions: 
					                {
					                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
					                  }
					                }],
							    ajax:
								{
									url:'metodos/cotizacion_metodos.php',
									type:"post",
									data:function(d)
									{
										d.cotizaciones_renovaciones=1;
									}		
								},
								 "columnDefs": [
							        {"className": "dt-center", "targets": "_all"}
							      ],
								"columns":
								[
								{"data":"realizo"},
									{"data":"fecha_alta"},
									{"data":"fecha_vencimiento"},
									{"data":"aseguradora_actual"},
									{"data":"poliza_renovar"},
									{"data":"contacto"},
									{"data":"tipo_contacto"},
									{"data":"prospecto"},
									{"data":"descripcion"},
									{"data":"cantidad_opciones_cotizadas"},
									{"data":"objetivo_minimo_concretar"},
									{"data":"paquete_solicitado"},
									{"data":"forma_de_pago"},
									{"data":"prima_neta"},
									{"data":"prima_total_anual"},
									{"data":"fecha_envio_cotizacion_renovacion"},
									{"data":"tiempo_respuesta"},
									{"data":"estatus_cotizacion"},
									{"data":"pdf"},
									{"data":"concretar"},
									{"data":"aseguradora_concretada"},
									{"data":"prima_neta_concretada"},
									{"data":"prima_total_anual_concretada"},
									{"data":"numero_poliza"},
									{"data":"inicio_vigencia"},
									{"data":"primer_pago"},
									{"data":"fecha_envio_poliza"},
									{"data":"motivos"},
								]
						});
				}
				else
				{

				}
			}
		});
});

//dar fecha envio poliza renovacion
$(document).on('click','.fecha_envio_poliza_reno',function(e){
var id_tabla =$(this).val();
		$.ajax({
			url:'metodos/cotizacion_metodos.php',
			type:'post',
			data:{alta_fecha_envio_poliza_renovacion:id_tabla},
			success:function(data)
			{
				if(data.trim()=='1')
				{
					
					$('#tabla_lista_renovaciones').DataTable({
					  initComplete: function () {
					            this.api().columns('.select-filter').every( function () {
					                var column = this;
					                var select = $('<select><option value=""></option></select>')
					                    .appendTo( $(column.header()).empty() )
					                    .on( 'change', function () {
					                        var val = $.fn.dataTable.util.escapeRegex(
					                            $(this).val()
					                        );
					 
					                        column
					                            .search( val ? '^'+val+'$' : '', true, false )
					                            .draw();
					                    } );
					 
					                column.data().unique().sort().each( function ( d, j ) {
					                    select.append( '<option value="'+d+'">'+d+'</option>' )
					                } );
					            } );
					        },
						order:[[1,'desc']],
								"destroy":true,
								"pageLength": 20,
								 "language": 
								 {
							      "emptyTable": "No se encontraron datos"
						    }, 
							dom:'Blfrtip',
					              buttons:[

					                {

					                extend:'excelHtml5',
					                className:'btn-success',
					                 //autoFilter: true,
					                title:'Lista de renovaciones',
					                 sheetName: 'RENOVACIONES',
					                text:'<i class="icon-file-spreadsheet position-left"></i> EXPORTAR EXCEL',
					                exportOptions: 
					                {
					                     columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21,22]
					                  }
					                }],
							    ajax:
								{
									url:'metodos/cotizacion_metodos.php',
									type:"post",
									data:function(d)
									{
										d.cotizaciones_renovaciones=1;
									}		
								},
								 "columnDefs": [
							        {"className": "dt-center", "targets": "_all"}
							      ],
								"columns":
								[
								{"data":"realizo"},
									{"data":"fecha_alta"},
									{"data":"fecha_vencimiento"},
									{"data":"aseguradora_actual"},
									{"data":"poliza_renovar"},
									{"data":"contacto"},
									{"data":"tipo_contacto"},
									{"data":"prospecto"},
									{"data":"descripcion"},
									{"data":"cantidad_opciones_cotizadas"},
									{"data":"objetivo_minimo_concretar"},
									{"data":"paquete_solicitado"},
									{"data":"forma_de_pago"},
									{"data":"prima_neta"},
									{"data":"prima_total_anual"},
									{"data":"fecha_envio_cotizacion_renovacion"},
									{"data":"tiempo_respuesta"},
									{"data":"estatus_cotizacion"},
									{"data":"pdf"},
									{"data":"concretar"},
									{"data":"aseguradora_concretada"},
									{"data":"prima_neta_concretada"},
									{"data":"prima_total_anual_concretada"},
									{"data":"numero_poliza"},
									{"data":"inicio_vigencia"},
									{"data":"primer_pago"},
									{"data":"fecha_envio_poliza"},
									{"data":"motivos"},
								]
						});
				}
				else
				{

				}
			}
		});
});


function verificar_select_opcion_cotizadas()
{
	var valor=$('#opciones_Cotizadas').val();
	if(valor==0)
	{
		$('#error_opciones_Cotizadas').html('¡Debes seleccionar una opcion cotizada!');
		$('#error_opciones_Cotizadas').slideDown('fast');
		$('#opciones_Cotizadas').focus();
		return false;

	}
	else
	{
		$('#error_opciones_Cotizadas').slideUp('fast');
		return true;
	}
}

function verificar_select_motivos()
{
	var valor=$('#select_opcion_no_concretar').val();
	if(valor==0)
	{
		$('#error_select_opcion_no_concretar').html('¡Debes seleccionar un motivo!');
		$('#error_select_opcion_no_concretar').slideDown('fast');
		$('#select_opcion_no_concretar').focus();
		return false;

	}
	else
	{
		$('#error_select_opcion_no_concretar').slideUp('fast');
		return true;
	}
}
function verificar_numero_poliza()
{
	var valor=$('#numero_poliza').val();
	if(valor=='')
	{
		$('#error_numero_poliza').html('¡Debes ingresar un número de poliza!');
		$('#error_numero_poliza').slideDown('fast');
		$('#numero_poliza').focus();
		return false;
	}
	else
	{
		if(!/^([a-zA-Z0-9 áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\-\.])*$/.test(valor))
		{
			$('#error_numero_poliza').html('¡No se aceptan caracteres especiales!');
			$('#error_numero_poliza').slideDown('fast');
			$('#numero_poliza').focus();
			return false;
		}
		else
		{
			$('#error_numero_poliza').slideUp('fast');
			return true;	
		}
	}
}


function verificar_fecha_inicio_vigencia()
{
	var valor=$('#inicio_vigencia').val();
	if(valor=='')
	{
		$('#error_inicio_vigencia').html('¡Debes ingresar una fecha de vigencia!');
		$('#error_inicio_vigencia').slideDown('fast');
		$('#inicio_vigencia').focus();
		return false;
	}
	else
	{
		valor=moment(valor,'DD-MM-YYYY');
		if(valor.isValid()==false)
		{
			$('#error_inicio_vigencia').html('¡Debes ingresar una fecha valida D/M/A!');
			$('#error_inicio_vigencia').slideDown('fast');
			$('#inicio_vigencia').focus();
			return false;
		}
		else
		{
			$('#error_inicio_vigencia').slideUp('fast');
			return true;

		}		
	}
}






function hoyFecha(){
    var hoy = new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1;
        var yyyy = hoy.getFullYear();
        
        dd = addZero(dd);
        mm = addZero(mm);
 
        return dd+'/'+mm+'/'+yyyy;
}

function addZero(i) {
    if (i < 10) {
        i = '0' + i;
    }
    return i;
}
