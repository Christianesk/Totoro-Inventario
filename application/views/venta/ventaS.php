<script type="text/javascript">
/**
 * Campos
 */
var evento = 'I';
var table;
var tblVentaDetalle;
var subTotalDetalle=0.00;
var ivaDetalle=0.00;
var totalDetalle=0.00;
var iva = 12;
var ivaCal=0.12;

$(document).ready(function() {

inicio();
iniciarTablaPrincipal();
iniciarTablaVentaDetalle();
cargarTipoComprobante();

cargarTipoIdentificacion();

$("input").change(function(){
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
});
$("textarea").change(function(){
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
});
$("select").change(function(){
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
});

});


function iniciarTablaPrincipal(){
	  //datatables
    table = $('#tbl_venta').DataTable({
    	"dom" : 'Bfrtip',
        "buttons" : [
        	{
                extend: 'excelHtml5',
                exportOptions: {
                	columns: [ 0, 1 ,2,3,4,5,6]
                }
            },
        	{
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1 ,2,3,4,5,6]
                }
            },
            'colvis',
            'pageLength'                  
              ],
 
        "processing": true, 
        "serverSide": true, 
        "order": [], 
 
        "ajax": {
            "url": "<?php echo site_url('VentaC/lista_json')?>",
            "type": "POST"
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
        "language": {
        	"buttons": {
                colvis: 'Mostrar/Ocultar',
                pageLength:"Mostrar %d registros"                
            },
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No existe registros",
            "info": "Mostrando _PAGE_ de _PAGES_ páginas",
            "infoEmpty": "No existe registros",
            "infoFiltered": "(Filtrado de un total de _MAX_ registros )",
            "loadingRecords": "Cargando...",
            "processing":     "Procesando...",
            "search":         "Buscar:",
            "lengthMenu":     "Mostrar _MENU_ registros",
            "paginate": {
                "first":      "Primero",
                "last":       "Ultimo",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
        }

        
        
 
    });

    table.buttons().container()
    .appendTo( '#table_wrapper .col-md-6:eq(0)' );
 
	
}

function inicio(){
	//Inicialización de Formularios DIV
	evento = 'I';
	$('#menu').show();
	$('#tabla').show();
	$('#formulario').hide();
	$('#btnNuevo').show();
	$('#btnGuardar').hide();
	$('#btnCancelar').hide();	


}

function nuevo()
{
	evento = 'N';	
	$('#tabla').hide();
	$('#formulario').show();
	$('#btnNuevo').hide();
	$('#btnGuardar').show();
	$('#btnCancelar').show();

    $('#inpCliCodigo').val(1);	
	$('#inpNombreCli').val('CONSUMIDOR FINAL - 9999999999');
	$('#selTipoComprobante').val(0);
    $('#inpNumVenta').val('');
    $('#inpImpVenta').val('');
    $('#inpImpVenta').prop('disabled', true);
    $("#inpNumVenta").prop('disabled', true);
    $("#divAgregarArt").show();
    $("#idAvisoCombo").show();


    $("#txtCliente").hide();
    $("#txtTipoComprobante").hide();
    $("#txtNumero").hide();

    $('#btnCancelar').text('Cancelar');
    

    $('#inpCliCodigo').show();
    $('#inpNombreCli').show();
    $('#selTipoComprobante').show();
    $('#inpNumVenta').show();
    $('#divImpuesto').show();
    $('#divBotonCliente').show();

    $('#inpArtCodigo').val('');
    $('#inpNombreArt').val('');
    $('#inpCantInDet').val('');
    $('#inpPreVenInDet').val('');

    $('#btnCancelarVenDetalle').hide();
	$('#btnEliminarVenDetalle').hide();
	$('#btnAgregarVenDetalle').show();

    
    $('#inpStockArti').val('');

    document.getElementById("ventaSubtotal").innerHTML = '$ 0.00';
    document.getElementById("ventaIva").innerHTML = '$ 0.00';
    document.getElementById("ventaTotal").innerHTML = '$ 0.00';

    $('txtCliente').text('');
    $('txtTipoComprobante').text('');
    $('txtNumero').text('');



	tblVentaDetalle.clear().draw();	
}

function guardar()
{
    $('#btnGuardar').text('guardando...'); 
    $('#btnGuardar').attr('disabled',true); 
    
    /* Venta Detalle*/
    var arregloVenDetalle = new Array();
    var datosVenDetalle = tblVentaDetalle.rows().data();
    
    for (var int = 0; int < datosVenDetalle.length; int++) {
     	 var fila = tblVentaDetalle.row(int).data();
     	 var item = {codigoArticulo: fila['codigoArticulo'],nombreArticulo: fila['nombreArticulo'], cantidadArticulo: fila['cantidadArticulo'],precioVentaArticulo: fila['precioVentaArticulo'],subtotalArticulo: fila['subtotalArticulo'],  idVenDet: tblVentaDetalle.row(int).id()}  	
          arregloVenDetalle.push(item);
     	 
   }
  	
    if(evento == 'N') {
        url = "<?php echo site_url('VentaC/ajax_guardar')?>";
    } else if(evento == 'E'){
        url = "<?php echo site_url('VentaC/ajax_modificar')?>";
    }
    $.ajax({
    	
        url : url,
        type: "POST",
        data: {      
            id : $('#codigoVenta').val(),
        	inpCliCodigo: $('#inpCliCodigo').val(), 
        	inpNombreCli: $('#inpNombreCli').val(),
        	selTipoComprobante: $('#selTipoComprobante').val(),
            inpNumVenta: $('#inpNumVenta').val(),
            inpImpVenta: $('#inpImpVenta').val(),
            inpTotal: totalDetalle,
            listaVentaDetalle: JSON.stringify(arregloVenDetalle)
            },
            
        dataType: "JSON",
        success: function(data)
        {  
            if(data.status) 
            { 
                console.log(data.status);             
            	inicio();
            	recargar();
            	alertaInfo('Guardado / Modificado con éxito');	
            }else if (data.ventaDet) {
				alertaWarn('Debe ingresar al menos un Articulo para esta venta');
			}
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    alertaError(data.error_string[i]);	
                }
            }
            $('#btnGuardar').text('Guardar'); 
            $('#btnGuardar').attr('disabled',false);   
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
        	alertaError('Error guardando o actualizando');
            $('#btnGuardar').text('Guardar'); 
            $('#btnGuardar').attr('disabled',false); 
        }
    });
}

function cambiarEstados(){
    $("#inpNumVenta").prop('disabled', false);	
    $("#divAgregarArt").hide();
    $("#idAvisoCombo").hide();
    $('#inpCliCodigo').hide();
    $('#inpNombreCli').hide();
    $('#selTipoComprobante').hide();
    $('#inpNumVenta').hide();
    $('#divImpuesto').hide();
    $('#divBotonCliente').hide();
    $('#btnGuardar').hide();
    $("#txtCliente").show();
    $("#txtTipoComprobante").show();
    $("#txtNumero").show();
    
    $('#btnCancelar').text('Atras');
}

function editar(codigo){
	nuevo();
	evento = 'E';
	cambiarEstados();

    $.ajax({
        url : "<?php echo site_url('VentaC/ajax_editar/')?>/" + codigo,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('#txtCliente').text(data[0].PER_NOMBRE+' - '+data[0].PER_NUM_DOCUMENTO);
            $('#txtTipoComprobante').text(data[0].VEN_TIPO_COMPROBANTE==1?'Factura':'Nota de Venta');
            $('#selTipoComprobante').val(data[0].VEN_TIPO_COMPROBANTE);

            if ($("#selTipoComprobante").val()==1) {
                $("#ventaIva").show();
                $("#txtIva").show();
            }else if ($("#selTipoComprobante").val()==2) {
                $("#ventaIva").hide();
                $("#txtIva").hide();
            }

            $('#txtNumero').text(data[0].VEN_NUM_COMPROBANTE);

            var arregloVentaDetalle = data[1];
            for (var int = 0; int < arregloVentaDetalle.length; int++) {

                var codigoVentaDetalle = arregloVentaDetalle[int].VED_CODIGO;
                var codigoArticulo = arregloVentaDetalle[int].ART_CODIGO;
                var nombreArticulo = arregloVentaDetalle[int].ART_NOMBRE;
                var cantidadArticulo = arregloVentaDetalle[int].VED_CANTIDAD;
                var precioVentaArticulo = arregloVentaDetalle[int].VED_PRECIO_VENTA;
                var subtotalArticulo = parseFloat(parseFloat(arregloVentaDetalle[int].VED_CANTIDAD).toFixed(2)*parseFloat(arregloVentaDetalle[int].VED_PRECIO_VENTA).toFixed(2)).toFixed(2);
                
                agregarATablaVentaDetalle(codigoVentaDetalle, codigoArticulo, nombreArticulo, cantidadArticulo,precioVentaArticulo,subtotalArticulo);
                //agregarATablaVentaDetalle(arregloVentaDetalle[int].VED_CODIGO, arregloVentaDetalle[int].ART_CODIGO, arregloVentaDetalle[int].ART_NOMBRE, arregloVentaDetalle[int].VED_CANTIDAD,arregloVentaDetalle[int].VED_PRECIO_VENTA,arregloVentaDetalle[int].VED_DESCUENTO,parseFloat(parseFloat(arregloVentaDetalle[int].VED_CANTIDAD).toFixed(2)*parseFloat(arregloVentaDetalle[int].VED_PRECIO_VENTA).toFixed(2)).toFixed(2));
                actualizarSubTotalDetalle();
 			}  
             

        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });
    
	
}

function eliminar(id)
{
    if(confirm('Desea Anular el Venta?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('VentaC/ajax_eliminar')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
            	if(data.status) //if success close modal and reload ajax table
                {  
                    recargar();
                }else{
                	 alertaError('No se puede eliminar ya que existen dependencias asociadas');	
                   
                 }             
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            	alertaError('Error eliminando');
            }
        });
 
    }
}



function iniciarTablaVentaDetalle() {

tblVentaDetalle = $('#tbl_venta_detalle').DataTable({paging: false, searching: false, select: true,
    "columns": [
        { "data": "codigoArticulo" },
        { "data": "nombreArticulo" },
        { "data": "cantidadArticulo" },
        { "data": "precioVentaArticulo" },
        { "data": "subtotalArticulo" },
    ],
    "columnDefs": [
        {
            "targets": [ 0],
            "visible": false,
        }
    ],

    
     "language": {
    "lengthMenu": "Mostrar _MENU_ registros por página",
    "zeroRecords": "No existe registros",
    "info": "Mostrando _PAGE_ de _PAGES_ páginas",
    "infoEmpty": "No existe registros",
    "loadingRecords": "Cargando...",
    "search":         "Buscar:",
    "processing":     "Procesando...",
    "lengthMenu":     "Mostrar _MENU_ registros",
    },
    paging: true,
    destroy:true});



 $('#tbl_venta_detalle tbody').on('click', 'tr', function () {
     var data = tblVentaDetalle.row( this ).data();
         $('#inpArtCodigo').val(data['codigoArticulo']);
         $('#inpNombreArt').val(data['nombreArticulo']);
         $('#inpCantVeDet').val(data['cantidadArticulo']);
         $('#inpPreVenVeDet').val(data['precioVentaArticulo']);
         $("#inpCantVeDet").prop('disabled', false);
         $("#inpPreVenVeDet").prop('disabled', false);
          $('#btnAgregarIcoDetalle').removeClass( "glyphicon glyphicon-plus" ).addClass("glyphicon glyphicon-pencil");
          document.getElementById("btnAgregarVenDetalle").title='Editar';
          $('#btnEliminarVenDetalle').show();
          $('#btnCancelarVenDetalle').show();	
          $("#btnAgregarVenDetalle").prop('disabled', false);
          obtenerStockActual($('#inpArtCodigo').val());	
 } );
 

}

function obtenerStockActual(codigoArticulo){
    $.ajax({
        url : "<?php echo site_url('ArticuloC/ajax_obtener_stock_actual/')?>/" + codigoArticulo,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        	$('#inpStockArti').val(data.ART_STOCK);
        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });
}

//AGREGAR Articulo al combo_detalle
function agregarArticulo(){

    var codigoArticulo = $('#inpArtCodigo').val();
	var nombreArticulo = $('#inpNombreArt').val();
	var cantidadArticulo = $('#inpCantVeDet').val();
    var precioVentaArticulo = parseFloat($('#inpPreVenVeDet').val()).toFixed(2);
    var subtotalArticulo = (parseFloat($('#inpCantVeDet').val())*parseFloat($('#inpPreVenVeDet').val())).toFixed(2);

    if ($("#selTipoComprobante").val()!=0) {
            if(document.getElementById("btnAgregarVenDetalle").title=='Agregar'){
            
            if ($('#inpCantVeDet').val()!=''&&$('#inpPreVenVeDet').val()!='') {
                $.ajax({
                    url : "<?php echo site_url('ArticuloC/ajax_validar_stock/')?>/" + $('#inpArtCodigo').val()+"/"+$('#inpCantVeDet').val(),
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    { 
                        if (data.existeStock) {
                            agregarATablaVentaDetalle('0' ,codigoArticulo,nombreArticulo,cantidadArticulo,precioVentaArticulo,subtotalArticulo);
                            limpiarClienteDetalle();
                            actualizarSubTotalDetalle();
                        } else  {
                            alertaWarn('la cantidad es mayor a la que existe en el stock.');
                            
                        }      
                        
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alertaError('No se han podido cargar los datos');
                    }
                });
            } else {
                alertaWarn('Debe ingresar una Cantidad para el Articulo Seleccionado.');
            }
        }else if (document.getElementById("btnAgregarVenDetalle").title=='Editar'){
            if ($('#inpCantVeDet').val()!=''&&$('#inpPreVenVeDet').val()!='') {
                $.ajax({
                    url : "<?php echo site_url('ArticuloC/ajax_validar_stock/')?>/" + $('#inpArtCodigo').val()+"/"+$('#inpCantVeDet').val(),
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    { 
                        
                        if (data.existeStock) {
                            datos = {
                                "DT_RowId": tblVentaDetalle.row('.selected').id(),
                                "codigoArticulo" : codigoArticulo,
                                "nombreArticulo" : nombreArticulo,
                                "cantidadArticulo" : cantidadArticulo,
                                "precioVentaArticulo" : precioVentaArticulo,
                                "subtotalArticulo":subtotalArticulo,
                                }
                    
                            tblVentaDetalle.row('.selected').data(datos);
                            tblVentaDetalle.rows('.selected').deselect();
                            tblVentaDetalle.draw();
                            limpiarClienteDetalle();
                            actualizarSubTotalDetalle();
                        } else {
                            alertaWarn('la cantidad es mayor a la que existe en el stock.');
                        }
                        
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alertaError('No se han podido cargar los datos');
                    }
                });
                
            } else {
                alertaWarn('Debe ingresar la cantidad');
            }
            
            
        }
    } else {
        alertaWarn('Debe Seleccionar un Tipo de Comprobante para agregar el articulo');
    }
    
    
}



/**
 * Metodo eliminar articulo
 */

 function eliminarArticulo(){

    if(tblVentaDetalle.row('.selected').id() != 0){
        
        if(confirm('Desea eliminar el registro del articulo?'))
        {  
            $.ajax({
                url : "<?php echo site_url('VentaC/ajax_eliminar_detalle_venta')?>/"+tblVentaDetalle.row('.selected').id(),
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status) //if success close modal and reload ajax table
                    {  
                        tblVentaDetalle.row('.selected').remove().draw( false );
                        limpiarClienteDetalle();
                    }else{
                        alertaError('No se puede eliminar ya que existen dependencias asociadas');	                   
                    }             
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alertaError('Error eliminando');
                }
            });	 
        }
    }else{

        tblVentaDetalle.row('.selected').remove().draw( false );
        limpiarClienteDetalle();
    }
    actualizarSubTotalDetalle();
}

function actualizarSubTotalDetalle(){
    subTotalDetalle = 0.00;
    var arregloVentaDetalle = new Array();
    var datosVenDetalle = tblVentaDetalle.rows().data();

    for (var int = 0; int < datosVenDetalle.length; int++) {
   	 	var fila = tblVentaDetalle.row(int).data();
   	 	var item = {subtotalArticulo: fila['subtotalArticulo'] }  	
   	 	arregloVentaDetalle.push(item);
            subTotalDetalle+=parseFloat(arregloVentaDetalle[int].subtotalArticulo);
        document.getElementById("ventaSubtotal").innerHTML = '$ '+subTotalDetalle.toFixed(2);
    }
    actualizarIvaYTotal();
}

function actualizarIvaYTotal(){

    if ($("#selTipoComprobante").val()==2) {
        ivaDetalle = 0.00;
    } else if($("#selTipoComprobante").val()==1) {
        ivaDetalle = subTotalDetalle*ivaCal;
    }
    
    totalDetalle = subTotalDetalle+ivaDetalle;

    document.getElementById("ventaIva").innerHTML = '$ '+ivaDetalle.toFixed(2);
    document.getElementById("ventaTotal").innerHTML = '$ '+totalDetalle.toFixed(2);

}
function agregarATablaVentaDetalle(codigoVentaDetalle, codigoArticulo, nombreArticulo, cantidadArticulo,precioVentaArticulo,subtotalArticulo){
	
    tblVentaDetalle.row.add( {
			"DT_RowId": codigoVentaDetalle,
			"codigoArticulo" : codigoArticulo,
	    	"nombreArticulo" : nombreArticulo,
	    	"cantidadArticulo" : cantidadArticulo,
            "precioVentaArticulo": precioVentaArticulo,
            "subtotalArticulo":subtotalArticulo,
		} ).draw();
}


function limpiarClienteDetalle(){
			$('#inpArtCodigo').val('');
            $('#inpNombreArt').val('');
            $('#inpCantVeDet').val('');
            $('#inpPreVenVeDet').val('');
            $('#inpStockArti').val('');
            $('#btnCancelarVenDetalle').hide();
	        $('#btnEliminarVenDetalle').hide();
            $('#btnAgregarIcoDetalle').removeClass( "glyphicon glyphicon-pencil" ).addClass("glyphicon glyphicon-plus");
          document.getElementById("btnAgregarVenDetalle").title='Agregar';
          if (tblVentaDetalle.rows().count() == 0) {
            document.getElementById("ventaSubtotal").innerHTML = '$ 0.00';
          }
	}

//Función de Autocompletado del cliente
$(function() { 				          
    $("#inpNombreCli").autocomplete({
    	 source: function( request, response ) {
    		 $.ajax({
    		        url : "<?php echo site_url('ClienteC/autocompletar')?>/"+request.term, 
    		        type: "GET",
    		        dataType: "JSON",            		        
    		        success: function(data)
    		        {     
    		        	response( $.map( data, function(item) {
                            return {
                                label: item.datos.PER_NUM_DOCUMENTO,                                                                           	
                                value: item.datos.PER_NOMBRE+" - "+item.datos.PER_NUM_DOCUMENTO,
                                data: item
                            }
                        }));              		 
    		        },
    		        error: function (jqXHR, textStatus, errorThrown)
    		        {
    		            alert('No se pudo traer los datos via Ajax');
    		        }
    		    });
        }, 
        select: function( event, ui ) {              
         	document.getElementById("inpCliCodigo").value = ui.item.data.datos.PER_CODIGO;
         	document.getElementById("inpNombreCli").value = ui.item.data.datos.PER_NOMBRE;
           },
         change: function (event, ui) {
             if (ui.item == null || ui.item == undefined) {
            	 $('[name="inpCliCodigo"]').val("");
                 $('[name="inpNombreCli"]').val("");		
                                                                         
             }},                       
      
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" + item.label + "<br>" + item.data.datos.PER_NOMBRE + "</a>" )
        .appendTo( ul );
    };
});

//CARGAR TIPO Comprobante
function cargarTipoComprobante(){
 			 $.ajax({
 			        url : "<?php echo site_url('UtilitarioC/ajax_obtener_tipo_comprobante/')?>/",
 			        type: "GET",
 			        dataType: "JSON",
 			        success: function(data)
 			        {
 				        for (var int = 0; int < data.length; int++) {
 				        	$('#selTipoComprobante').append('<option value="'+(int+1)+'">'+(data[int])+'</option>');
 						}
 			        	
 			        	
 			        },
 			        error: function (jqXHR, textStatus, errorThrown)
 			        {
 			        	alertaError('No se han podido cargar los datos');
 			        }
 			    });
}

//Función de Autocompletado del Articulo
$(function() { 				          
    $("#inpNombreArt").autocomplete({
    	 source: function( request, response ) {
    		 $.ajax({
    		        url : "<?php echo site_url('ArticuloC/autocompletar_venta')?>/"+request.term, 
    		        type: "GET",
    		        dataType: "JSON",            		        
    		        success: function(data)
    		        {     
    		        	response( $.map( data, function(item) {
                            return {
                                label: item.datos.ART_NOMBRE,                                                                           	
                                value: item.datos.ART_NOMBRE,
                                data: item
                            }
                        }));              		 
    		        },
    		        error: function (jqXHR, textStatus, errorThrown)
    		        {
    		            alert('No se pudo traer los datos via Ajax');
    		        }
    		    });
        }, 
        select: function( event, ui ) {              
         	document.getElementById("inpArtCodigo").value = ui.item.data.datos.ART_CODIGO;
         	document.getElementById("inpNombreArt").value = ui.item.data.datos.ART_NOMBRE;
             document.getElementById("inpStockArti").value = ui.item.data.datos.ART_STOCK;
             obtenerPrecioVenta(ui.item.data.datos.ART_CODIGO);
           },
         change: function (event, ui) {
             if (ui.item == null || ui.item == undefined) {
            	 $('[name="inpArtCodigo"]').val("");
                 $('[name="inpNombreArt"]').val("");		
                                                                         
             }},                       
      
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" + item.label + "</a>" )
        .appendTo( ul );
    };
});

function obtenerPrecioVenta(codigoArticulo){
    $.ajax({
                url : "<?php echo site_url('ArticuloC/ajax_obtener_precio_venta/')?>/" + codigoArticulo,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                { 
                    
                    $('#inpPreVenVeDet').val(data.ART_PRECIO_VENTA);
                    
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alertaError('No se han podido cargar los datos');
                }
            });
}

function seleccionTipoComprobante()
{	
	
	if ($("#selTipoComprobante").val()==1) {
        $("#inpNumVenta").prop('disabled', false);
		$("#inpImpVenta").val(parseFloat(iva).toFixed(2));
        $("#ventaIva").show();
        $("#txtIva").show();
        actualizarIvaYTotal();
	}else if ($("#selTipoComprobante").val()==2) {
        $("#inpNumVenta").prop('disabled', false);
		$("#inpImpVenta").val(parseFloat(0).toFixed(2));
        $("#ventaIva").hide();
        $("#txtIva").hide();
        actualizarIvaYTotal();
	}else if ($("#selTipoComprobante").prop('selected', true)) {
		$("#inpImpVenta").val('');
        $("#inpNumVenta").prop('disabled', true);
	}

}

function recargar()
{
    table.ajax.reload(null,false); //Recarga la tabla con ajax

}


//para cliente

/**
  * Metodos para formulario Agregar Cliente
  */
  function nuevoCliente()
 	{
 	    
 	    $('#dlg_formulario_cliente').modal('show'); // Muestra la ventana emergente
 	    $('.modal-title').text('Agregar Cliente'); // Asigna un titulo a la ventana emergente
         $('#codigoCliente').val('');
        $('#inpNombreCliente').val('');
        $('#inpDireccionCliente').val('');
        $('#selTipoIdeCliente').val(0);
        $('#inpIdentCliente').val('');
        $('#inpTelfCliente').val('');
        $('#inpEmailCliente').val('');
        $("#inpIdentCliente").prop('disabled', true);
        $("#selTipoIdeCliente").prop('disabled', false);
 	}
function guardarCliente()
{
    $('#btnGuardar').text('guardando...'); 
    $('#btnGuardar').attr('disabled',true); 

        url = "<?php echo site_url('ClienteC/ajax_guardar')?>";
   

    
    $.ajax({
        url : url,
        type: "POST",
        data: {            
        	inpNombreCliente: $('#inpNombreCliente').val(), 
            inpDireccionCliente: $('#inpDireccionCliente').val(),
            selTipoIdeCliente: $('#selTipoIdeCliente').val(), 
            inpIdentCliente: $('#inpIdentCliente').val(),
            inpTelfCliente: $('#inpTelfCliente').val(), 
        	inpEmailCliente: $('#inpEmailCliente').val(),
            id : $('#codigoCliente').val(),
            },
        dataType: "JSON",
        success: function(data)
        {  
            if(data.status) 
            {              
                alertaInfo('Guardado / Modificado con éxito');	
 	            	$('#dlg_formulario_cliente').modal('hide');	
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    alertaError(data.error_string[i]);	
                }
            }
            $('#btnGuardar').text('Guardar'); 
            $('#btnGuardar').attr('disabled',false);   
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
        	alertaError('Error guardando o actualizando');
            $('#btnGuardar').text('Guardar'); 
            $('#btnGuardar').attr('disabled',false); 
        }
    });
}

//CARGAR TIPO IDENTIFICACION
function cargarTipoIdentificacion(){
 			 $.ajax({
 			        url : "<?php echo site_url('UtilitarioC/ajax_obtener_tipo_identificacion/')?>/",
 			        type: "GET",
 			        dataType: "JSON",
 			        success: function(data)
 			        {
 				        for (var int = 0; int < data.length; int++) {
 				        	$('#selTipoIdeCliente').append('<option value="'+(int+1)+'">'+(data[int])+'</option>');
 						}
 			        	
 			        	
 			        },
 			        error: function (jqXHR, textStatus, errorThrown)
 			        {
 			        	alertaError('No se han podido cargar los datos');
 			        }
 			    });
}

function seleccionMascaraIdentificacion()
{	
	
	if ($("#selTipoIdeCliente").val()==1) {
		$("#inpIdentCliente").prop('disabled', false);
		$("#inpIdentCliente").mask('9999999999');
	}else if ($("#selTipoIdeCliente").val()==2) {
		$("#inpIdentCliente").prop('disabled', false);
		$("#inpIdentCliente").mask('9999999999999');
	}else if ($("#selTipoIdeCliente").prop('selected', true)) {
		$("#inpIdentCliente").prop('disabled', true);
	}
	$('#inpIdentCliente').val('');
}

</script>


<!-- Dialogo Cliente -->
<!-- Bootstrap modal -->
<div class="modal fade" id="dlg_formulario_cliente" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin-top: 150px;">
            <div class="modal-header">
                <button type="button"  class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Agregar Cliente</h3>
            </div>
         <div class="panel-body">  
         <div class="form-group row">
         <input type="hidden" value="" id="codigoCliente" />
				<div class="col-xs-12 col-md-2">
					<p>
						<label for="inpNombreCliente">Nombre*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreCliente">
				</div>
                <div class="col-xs-12 col-md-2">
					<p>
						<label for="inpDireccionCliente">Dirección*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpDireccionCliente">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-xs-12 col-md-2">
					<p>
						<label for="selTipoIdeCliente">Tipo de Ident*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<select name="selTipoIdeCliente" id="selTipoIdeCliente" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" onchange="seleccionMascaraIdentificacion()">
						<option value="0" id="sel" selected="selected">Seleccione...</option>
					</select>
				</div>
                <div class="col-xs-12 col-md-2">
					<p>
						<label for="inpIdentCliente">Identificación*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpIdentCliente">
				</div>
			</div>
            <div class="form-group row">
				<div class="col-xs-12 col-md-2">
					<p>
						<label for="inpTelfCliente">Teléfono*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpTelfCliente">
				</div>
                <div class="col-xs-12 col-md-2">
					<p>
						<label for="inpEmailCliente">Email*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpEmailCliente">
				</div>
			</div>
           
           
            <div class="modal-footer">
                <button type="button" id="btnGuardarCategoria" onclick="guardarCliente()" class="btn btn-info">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->