$(document).ready(inicio);
function inicio()
{


}

$('#autos_admin').on('click',function(e){
$('#contenedor_cotizacion_autos').slideDown('fast');
});



$(document).on('click','.anular_concretar',function(e){
var datos = $(this).val();
	$('#confirmacion_anulacion').val(datos);
	$('#modal_anulacion').modal({show:true});
});

$(document).on('click','#confirmacion_anulacion',function(e){
	var datos = $(this).val();
	datos=datos.split('*');
$('#modal_anulacion').modal('hide');
	$.ajax({
		url:'metodos/cotizacion_metodos.php',
		type:'post',
		data:{anular_concretacion:datos},
		success:function(data)
		{

				if(data.trim()=='1*nueva')
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

				}
				else if(data.trim()=='0*nueva')
				{

				}
				else if(data.trim()=='1*renovacion')
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
				else if(data.trim()=='0*renovacion')
				{

				}
		}
	});
});	


