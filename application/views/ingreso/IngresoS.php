<script type="text/javascript">
/**
 * Campos
 */
var evento = 'I';
var table;
var tblIngresoDetalle;
var subTotalDetalle=0.00;
var ivaDetalle=0.00;
var totalDetalle=0.00;
var iva = 12;
var ivaCal=0.12;

$(document).ready(function() {

inicio();
iniciarTablaPrincipal();
iniciarTablaIngresoDetalle();
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
    table = $('#tbl_ingreso').DataTable({
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
            "url": "<?php echo site_url('IngresoC/lista_json')?>",
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

    $('#inpProCodigo').val('');	
	$('#inpNombrePro').val('');
	$('#selTipoComprobante').val(0);
    $('#inpNumIngreso').val('');
    $('#inpImpIngreso').val('');
    $('#inpImpIngreso').prop('disabled', true);
    $("#inpNumIngreso").prop('disabled', true);
    $("#divAgregarArt").show();
    $("#idAvisoCombo").show();


    $("#txtProveedor").hide();
    $("#txtTipoComprobante").hide();
    $("#txtNumero").hide();

    $('#btnCancelar').text('Cancelar');
    

    $('#inpProCodigo').show();
    $('#inpNombrePro').show();
    $('#selTipoComprobante').show();
    $('#inpNumIngreso').show();
    $('#divImpuesto').show();
    $('#divBotonProveedor').show();

    $('#inpArtCodigo').val('');
    $('#inpNombreArt').val('');
    $('#inpCantInDet').val('');
    $('#inpPreComInDet').val('');
    $('#inpPreVenInDet').val('');

    $('#btnCancelarIngDetalle').hide();
	$('#btnEliminarIngDetalle').hide();
	$('#btnAgregarIngDetalle').show();

    document.getElementById("ingresoSubtotal").innerHTML = '$ 0.00';
    document.getElementById("ingresoIva").innerHTML = '$ 0.00';
    document.getElementById("ingresoTotal").innerHTML = '$ 0.00';

    $('txtProveedor').text('');
    $('txtTipoComprobante').text('');
    $('txtNumero').text('');



	tblIngresoDetalle.clear().draw();	
}


function guardar()
{
    $('#btnGuardar').text('guardando...'); 
    $('#btnGuardar').attr('disabled',true); 
    
    /* Ingreso Detalle*/
    var arregloIngDetalle = new Array();
    var datosIngDetalle = tblIngresoDetalle.rows().data();
    
    for (var int = 0; int < datosIngDetalle.length; int++) {
     	 var fila = tblIngresoDetalle.row(int).data();
     	 var item = {codigoArticulo: fila['codigoArticulo'],nombreArticulo: fila['nombreArticulo'], cantidadArticulo: fila['cantidadArticulo'],precioCompraArticulo: fila['precioCompraArticulo'],precioVentaArticulo: fila['precioVentaArticulo'],subtotalArticulo: fila['subtotalArticulo'],  idIngDet: tblIngresoDetalle.row(int).id()}  	
          arregloIngDetalle.push(item);
     	 
   }
  	
    if(evento == 'N') {
        url = "<?php echo site_url('IngresoC/ajax_guardar')?>";
    } else if(evento == 'E'){
        url = "<?php echo site_url('IngresoC/ajax_modificar')?>";
    }
    $.ajax({
    	
        url : url,
        type: "POST",
        data: {      
            id : $('#codigoIngreso').val(),
        	inpProCodigo: $('#inpProCodigo').val(), 
        	inpNombrePro: $('#inpNombrePro').val(),
        	selTipoComprobante: $('#selTipoComprobante').val(),
            inpNumIngreso: $('#inpNumIngreso').val(),
            inpImpIngreso: $('#inpImpIngreso').val(),
            inpTotal: totalDetalle,
            listaIngresoDetalle: JSON.stringify(arregloIngDetalle)
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
            }else if (data.ingresoDet) {
				alertaWarn('Debe ingresar al menos un Articulo para este Ingreso');
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
    $("#inpNumIngreso").prop('disabled', false);	
    $("#divAgregarArt").hide();
    $("#idAvisoCombo").hide();
    $('#inpProCodigo').hide();
    $('#inpNombrePro').hide();
    $('#selTipoComprobante').hide();
    $('#inpNumIngreso').hide();
    $('#divImpuesto').hide();
    $('#divBotonProveedor').hide();
    $('#btnGuardar').hide();
    $("#txtProveedor").show();
    $("#txtTipoComprobante").show();
    $("#txtNumero").show();
    
    $('#btnCancelar').text('Atras');
}

function editar(codigo){
	nuevo();
	evento = 'E';
	cambiarEstados();

    $.ajax({
        url : "<?php echo site_url('IngresoC/ajax_editar/')?>/" + codigo,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('#txtProveedor').text(data[0].PER_NOMBRE+' - '+data[0].PER_NUM_DOCUMENTO);
            $('#txtTipoComprobante').text(data[0].ING_TIPO_COMPROBANTE==1?'Factura':'Nota de Venta');
            $('#selTipoComprobante').val(data[0].ING_TIPO_COMPROBANTE);
            
            if ($("#selTipoComprobante").val()==1) {
                $("#ingresoIva").show();
                $("#txtIva").show();
            }else if ($("#selTipoComprobante").val()==2) {
                $("#ingresoIva").hide();
                $("#txtIva").hide();
            }

            $('#txtNumero').text(data[0].ING_NUMERO_COMPROBANTE);

            var arregloIngresoDetalle = data[1];
            for (var int = 0; int < arregloIngresoDetalle.length; int++) {
                
                agregarATablaIngresoDetalle(arregloIngresoDetalle[int].IND_CODIGO, arregloIngresoDetalle[int].ART_CODIGO, arregloIngresoDetalle[int].ART_NOMBRE, arregloIngresoDetalle[int].IND_CANTIDAD,arregloIngresoDetalle[int].IND_PRECIO_COMPRA,arregloIngresoDetalle[int].IND_PRECIO_VENTA,parseFloat(parseFloat(arregloIngresoDetalle[int].IND_CANTIDAD).toFixed(2)*parseFloat(arregloIngresoDetalle[int].IND_PRECIO_COMPRA).toFixed(2)).toFixed(2));
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
    if(confirm('Desea Anular el Ingreso?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('IngresoC/ajax_eliminar')?>/"+id,
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



function iniciarTablaIngresoDetalle() {

tblIngresoDetalle = $('#tbl_ingreso_detalle').DataTable({paging: false, searching: false, select: true,
    "columns": [
        { "data": "codigoArticulo" },
        { "data": "nombreArticulo" },
        { "data": "cantidadArticulo" },
        { "data": "precioCompraArticulo" },
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



 $('#tbl_ingreso_detalle tbody').on('click', 'tr', function () {
     var data = tblIngresoDetalle.row( this ).data();
         $('#inpArtCodigo').val(data['codigoArticulo']);
         $('#inpNombreArt').val(data['nombreArticulo']);
         $('#inpCantInDet').val(data['cantidadArticulo']);
         $('#inpPreComInDet').val(data['precioCompraArticulo']);
         $('#inpPreVenInDet').val(data['precioVentaArticulo']);
         $("#inpCantInDet").prop('disabled', false);
         $("#inpPreComInDet").prop('disabled', false);
         $("#inpPreVenInDet").prop('disabled', false);
          $('#btnAgregarIcoDetalle').removeClass( "glyphicon glyphicon-plus" ).addClass("glyphicon glyphicon-pencil");
          document.getElementById("btnAgregarIngDetalle").title='Editar';
          $('#btnEliminarIngDetalle').show();
          $('#btnCancelarIngDetalle').show();	
          $("#btnAgregarIngDetalle").prop('disabled', false);	
 } );
 

}

//AGREGAR Articulo al combo_detalle
function agregarArticulo(){

    var codigoArticulo = $('#inpArtCodigo').val();
	var nombreArticulo = $('#inpNombreArt').val();
	var cantidadArticulo = $('#inpCantInDet').val();
    var precioCompraArticulo = parseFloat($('#inpPreComInDet').val()).toFixed(2);
    var precioVentaArticulo = parseFloat($('#inpPreVenInDet').val()).toFixed(2);
    var subtotalArticulo = (parseFloat($('#inpCantInDet').val())*parseFloat($('#inpPreComInDet').val())).toFixed(2);

    if ($("#selTipoComprobante").val()!=0) {
        if(document.getElementById("btnAgregarIngDetalle").title=='Agregar'){
        if ($('#inpCantInDet').val()!=''&&$('#inpPreComInDet').val()!=''&&$('#inpPreVenInDet').val()!='') {
                agregarATablaIngresoDetalle('0' ,codigoArticulo,nombreArticulo,cantidadArticulo,precioCompraArticulo,precioVentaArticulo,subtotalArticulo);
                limpiarProveedorDetalle();
        } else {
            alertaWarn('Debe ingresar una Cantidad para el Articulo Seleccionado.');
        }
        }else if (document.getElementById("btnAgregarIngDetalle").title=='Editar'){
            if ($('#inpCantInDet').val()!=''&&$('#inpPreComInDet').val()!=''&&$('#inpPreVenInDet').val()!='') {

                            datos = {
                                "DT_RowId": tblIngresoDetalle.row('.selected').id(),
                                "codigoArticulo" : codigoArticulo,
                                "nombreArticulo" : nombreArticulo,
                                "cantidadArticulo" : cantidadArticulo,
                                "precioCompraArticulo" : precioCompraArticulo,
                                "precioVentaArticulo" : precioVentaArticulo,
                                "subtotalArticulo":subtotalArticulo,
                                }
                    
                            tblIngresoDetalle.row('.selected').data(datos);
                            tblIngresoDetalle.rows('.selected').deselect();
                            tblIngresoDetalle.draw();
                            limpiarProveedorDetalle();
                
            } else {
                alertaWarn('Debe ingresar la cantidad');
            }
            
            
        }
        actualizarSubTotalDetalle();
    } else {
        alertaWarn('Debe Seleccionar un Tipo de Comprobante para agregar el articulo');
    }

}

/**
 * Metodo eliminar articulo
 */

 function eliminarArticulo(){
    if(tblIngresoDetalle.row('.selected').id() != 0){
        
        if(confirm('Desea eliminar el registro del articulo?'))
        {  
            $.ajax({
                url : "<?php echo site_url('IngresoC/ajax_eliminar_detalle_ingreso')?>/"+tblIngresoDetalle.row('.selected').id(),
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status) //if success close modal and reload ajax table
                    {  
                        tblIngresoDetalle.row('.selected').remove().draw( false );
                        limpiarProveedorDetalle();
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

        tblIngresoDetalle.row('.selected').remove().draw( false );
        limpiarProveedorDetalle();
    }
    actualizarSubTotalDetalle();
}

function actualizarSubTotalDetalle(){
    subTotalDetalle = 0.00;
    var arregloIngresoDetalle = new Array();
    var datosIngDetalle = tblIngresoDetalle.rows().data();

    for (var int = 0; int < datosIngDetalle.length; int++) {
   	 	var fila = tblIngresoDetalle.row(int).data();
   	 	var item = {subtotalArticulo: fila['subtotalArticulo'] }  	
   	 	arregloIngresoDetalle.push(item);
            subTotalDetalle+=parseFloat(arregloIngresoDetalle[int].subtotalArticulo);
        document.getElementById("ingresoSubtotal").innerHTML = '$ '+subTotalDetalle.toFixed(2);
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

    document.getElementById("ingresoIva").innerHTML = '$ '+ivaDetalle.toFixed(2);
    document.getElementById("ingresoTotal").innerHTML = '$ '+totalDetalle.toFixed(2);

}
function agregarATablaIngresoDetalle(codigoIngresoDetalle, codigoArticulo, nombreArticulo, cantidadArticulo,precioCompraArticulo,precioVentaArticulo,subtotalArticulo){
	
    tblIngresoDetalle.row.add( {
			"DT_RowId": codigoIngresoDetalle,
			"codigoArticulo" : codigoArticulo,
	    	"nombreArticulo" : nombreArticulo,
	    	"cantidadArticulo" : cantidadArticulo,
            "precioCompraArticulo": precioCompraArticulo,
            "precioVentaArticulo": precioVentaArticulo,
            "subtotalArticulo":subtotalArticulo,
		} ).draw();
}


function limpiarProveedorDetalle(){
			$('#inpArtCodigo').val('');
            $('#inpNombreArt').val('');
            $('#inpCantInDet').val('');
            $('#inpPreComInDet').val('');
            $('#inpPreVenInDet').val('');
            $('#btnCancelarIngDetalle').hide();
	        $('#btnEliminarIngDetalle').hide();
            $('#btnAgregarIcoDetalle').removeClass( "glyphicon glyphicon-pencil" ).addClass("glyphicon glyphicon-plus");
          document.getElementById("btnAgregarIngDetalle").title='Agregar';
          if (tblIngresoDetalle.rows().count() == 0) {
            document.getElementById("ingresoSubtotal").innerHTML = '$ 0.00';
          }
    }
    

//Función de Autocompletado del proveedor
$(function() { 				          
    $("#inpNombrePro").autocomplete({
    	 source: function( request, response ) {
    		 $.ajax({
    		        url : "<?php echo site_url('ProveedorC/autocompletar')?>/"+request.term, 
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
         	document.getElementById("inpProCodigo").value = ui.item.data.datos.PER_CODIGO;
         	document.getElementById("inpNombrePro").value = ui.item.data.datos.PER_NOMBRE;
           },
         change: function (event, ui) {
             if (ui.item == null || ui.item == undefined) {
            	 $('[name="inpProCodigo"]').val("");
                 $('[name="inpNombrePro"]').val("");		
                                                                         
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
    		        url : "<?php echo site_url('ArticuloC/autocompletar')?>/"+request.term, 
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


function seleccionTipoComprobante()
{	
	
	if ($("#selTipoComprobante").val()==1) {
        $("#inpNumIngreso").prop('disabled', false);
		$("#inpImpIngreso").val(parseFloat(iva).toFixed(2));
        $("#ingresoIva").show();
        $("#txtIva").show();
        actualizarIvaYTotal();
	}else if ($("#selTipoComprobante").val()==2) {
        $("#inpNumIngreso").prop('disabled', false);
		$("#inpImpIngreso").val(parseFloat(0).toFixed(2));
        $("#ingresoIva").hide();
        $("#txtIva").hide();
        actualizarIvaYTotal();
	}else if ($("#selTipoComprobante").prop('selected', true)) {
		$("#inpImpIngreso").val('');
        $("#inpNumIngreso").prop('disabled', true);
	}

}

function recargar()
{
    table.ajax.reload(null,false); //Recarga la tabla con ajax


}


//para proveedor

/**
  * Metodos para formulario Agregar Proveedor
  */
  function nuevoProveedor()
 	{
 	    
 	    $('#dlg_formulario_proveedor').modal('show'); // Muestra la ventana emergente
 	    $('.modal-title').text('Agregar Proveedor'); // Asigna un titulo a la ventana emergente
         $('#codigoProveedor').val('');
        $('#inpNombreProveedor').val('');
        $('#inpDireccionProveedor').val('');
        $('#selTipoIdeProveedor').val(0);
        $('#inpIdentProveedor').val('');
        $('#inpTelfProveedor').val('');
        $('#inpEmailProveedor').val('');
        $("#inpIdentProveedor").prop('disabled', true);
        $("#selTipoIdeProveedor").prop('disabled', false);
 	}
function guardarProveedor()
{
    $('#btnGuardar').text('guardando...'); 
    $('#btnGuardar').attr('disabled',true); 

        url = "<?php echo site_url('ProveedorC/ajax_guardar')?>";
   

    
    $.ajax({
        url : url,
        type: "POST",
        data: {            
        	inpNombreProveedor: $('#inpNombreProveedor').val(), 
            inpDireccionProveedor: $('#inpDireccionProveedor').val(),
            selTipoIdeProveedor: $('#selTipoIdeProveedor').val(), 
            inpIdentProveedor: $('#inpIdentProveedor').val(),
            inpTelfProveedor: $('#inpTelfProveedor').val(), 
        	inpEmailProveedor: $('#inpEmailProveedor').val(),
            id : $('#codigoProveedor').val(),
            },
        dataType: "JSON",
        success: function(data)
        {  
            if(data.status) 
            {              
                alertaInfo('Guardado / Modificado con éxito');	
 	            	$('#dlg_formulario_proveedor').modal('hide');	
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
 				        	$('#selTipoIdeProveedor').append('<option value="'+(int+1)+'">'+(data[int])+'</option>');
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
	
	if ($("#selTipoIdeProveedor").val()==1) {
		$("#inpIdentProveedor").prop('disabled', false);
		$("#inpIdentProveedor").mask('9999999999');
	}else if ($("#selTipoIdeProveedor").val()==2) {
		$("#inpIdentProveedor").prop('disabled', false);
		$("#inpIdentProveedor").mask('9999999999999');
	}else if ($("#selTipoIdeProveedor").prop('selected', true)) {
		$("#inpIdentProveedor").prop('disabled', true);
	}
	$('#inpIdentProveedor').val('');
}

//articulo
function nuevoArticulo()
 	{
 	    
         $('#dlg_formulario_articulo1').modal('show'); // Muestra la ventana emergente
 	    $('.modal-title').text('Agregar Articulo'); // Asigna un titulo a la ventana emergente
         $('#inpNombreArt1').val('');
        $('#inpCategoria').val('');
        $('#inpCatCodigo').val('');
        $('#inpNombreCat').val('');
        $('#inpSerialArt').val('');
        $('#inpDescripcion1').val('');
        $('#codigoArticulo1').val('');
     }
     
     //Función de Autocompletado de la categoria
$(function() { 				          
    $("#inpNombreCat").autocomplete({
    	 source: function( request, response ) {
    		 $.ajax({
    		        url : "<?php echo site_url('CategoriaC/autocompletar')?>/"+request.term, 
    		        type: "GET",
    		        dataType: "JSON",            		        
    		        success: function(data)
    		        {     
    		        	response( $.map( data, function(item) {
                            return {
                                label: item.datos.CAT_NOMBRE,                                                                           	
                                value: item.datos.CAT_NOMBRE,
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
         	document.getElementById("inpCatCodigo").value = ui.item.data.datos.CAT_CODIGO;
         	document.getElementById("inpNombreCat").value = ui.item.data.datos.CAT_NOMBRE;
           },
         change: function (event, ui) {
             if (ui.item == null || ui.item == undefined) {
            	 $('[name="inpCatCodigo"]').val("");
                 $('[name="inpNombreCat"]').val("");		
                                                                         
             }},                       
      
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" + item.label + "</a>" )
        .appendTo( ul );
    };
});


function guardarArticulo()
{
   

    
        url = "<?php echo site_url('ArticuloC/ajax_guardar')?>";

    
    $.ajax({
        url : url,
        type: "POST",
        data: {            
            inpNombreArt: $('#inpNombreArt1').val(), 
            inpCatCodigo: $('#inpCatCodigo').val(), 
            inpSerialArt: $('#inpSerialArt').val(), 
            inpStockArt: 0, 
            inpPrecioCompraArt: 0, 
            inpPrecioVentaArt: 0, 
            inpDescripcion: $('#inpDescripcion1').val(),
            id : $('#codigoArticulo1').val(),
            },
        dataType: "JSON",
        success: function(data)
        {  
            if(data.status) 
            {              
            	alertaInfo('Guardado / Modificado con éxito');	
 	            	$('#dlg_formulario_articulo1').modal('hide');	
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

</script>


<!-- Dialogo Proveedor -->
<!-- Bootstrap modal -->
<div class="modal fade" id="dlg_formulario_proveedor" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin-top: 150px;">
            <div class="modal-header">
                <button type="button"  class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Agregar Proveedor</h3>
            </div>
         <div class="panel-body">  
         <div class="form-group row">
         <input type="hidden" value="" id="codigoProveedor" />
				<div class="col-xs-12 col-md-2">
					<p>
						<label for="inpNombreProveedor">Nombre*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreProveedor">
				</div>
                <div class="col-xs-12 col-md-2">
					<p>
						<label for="inpDireccionProveedor">Dirección*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpDireccionProveedor">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-xs-12 col-md-2">
					<p>
						<label for="selTipoIdeProveedor">Tipo de Ident*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<select name="selTipoIdeProveedor" id="selTipoIdeProveedor" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" onchange="seleccionMascaraIdentificacion()">
						<option value="0" id="sel" selected="selected">Seleccione...</option>
					</select>
				</div>
                <div class="col-xs-12 col-md-2">
					<p>
						<label for="inpIdentProveedor">Identificación*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpIdentProveedor">
				</div>
			</div>
            <div class="form-group row">
				<div class="col-xs-12 col-md-2">
					<p>
						<label for="inpTelfProveedor">Teléfono*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpTelfProveedor">
				</div>
                <div class="col-xs-12 col-md-2">
					<p>
						<label for="inpEmailProveedor">Email*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpEmailProveedor">
				</div>
			</div>
           
           
            <div class="modal-footer">
                <button type="button" id="btnGuardarCategoria" onclick="guardarProveedor()" class="btn btn-info">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


<!-- Dialogo Articulo -->
<!-- Bootstrap modal -->
<div class="modal fade" id="dlg_formulario_articulo1" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin-top: 150px;">
            <div class="modal-header">
                <button type="button"  class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Agregar Articulo</h3>
            </div>
        
            <div class="panel-body">  
            <div class="form-group row">
                    <input type="hidden" value="" id="codigoArticulo1" />
                    <div class="col-xs-12 col-md-1">
                        <p>
                            <label for="inpNombreArt1">Nombre Articulo*:</label>
                        </p>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreArt1">
                    </div>
                    <div class="col-xs-12 col-md-1">
                        <p>
                            <label for="inpCategoria">Categoria:</label>
                        </p>
                    </div>
                    <div class="col-xs-12 col-md-4 ui-front" >
                        <input type="hidden"  id="inpCatCodigo" name="inpCatCodigo" />
                        <input id="inpNombreCat" name="inpNombreCat"
                                class="form-control"
                                placeholder="Ingrese el nombre de la categoria"
                                value="" required="required">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-md-1">
                        <p>
                            <label for="inpSerialArt">Codigo*:</label>
                        </p>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpSerialArt">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-md-1">
                        <p>
                            <label for="inpDescripcion1">Descripción*:</label>
                        </p>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <textarea class="form-control" id="inpDescripcion1" rows="2"
                            style="height: 65px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnGuardarArticulo" onclick="guardarArticulo()" class="btn btn-info">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
