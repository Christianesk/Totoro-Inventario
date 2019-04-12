<script type="text/javascript">

/**
 * Campos
 */
var evento = 'I';
var table;
var tableComboDetalle;

$(document).ready(function() {

inicio();
iniciarTablaPrincipal();
iniciarTablaComboDetalle();


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
    table = $('#tbl_combo').DataTable({
    	"dom" : 'Bfrtip',
        "buttons" : [
        	{
                extend: 'excelHtml5',
                exportOptions: {
                	columns: [ 0, 1 ]
                }
            },
        	{
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1 ]
                }
            },
            'colvis',
            'pageLength'                  
              ],
 
        "processing": true, 
        "serverSide": true, 
        "order": [], 
 
        "ajax": {
            "url": "<?php echo site_url('ComboC/lista_json')?>",
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
	$('#inpNombreCombo').val('');
    $('#inpStockCom').val('');
    $('#inpSerialCom').val('');
    $('#inpPrecioVentaCombo').val('');
    $('#codigoCombo').val('')
    $('#inpDescripcionCombo').val('');
    $('#btnCancelarArticulo').hide();
	$('#btnEliminarArticulo').hide();
    $('#btnAgregarArticulo').show();
    
    $('#frmComboDetalle1').show();
    $('#frmComboDetalle2').show();
    $('#frmAvisoDetalle').show();

	tblComboDetalle.clear().draw();	
}

function guardar()
{
    $('#btnGuardar').text('guardando...'); 
    $('#btnGuardar').attr('disabled',true); 
	/**telefono*/
    var arregloComboDetalle = new Array();
    var datosComboDetalle = tblComboDetalle.rows().data();

    for (var int = 0; int < datosComboDetalle.length; int++) {
   	 	var fila = tblComboDetalle.row(int).data();
   	 	var item = {codigoArticulo: fila['codigoArticulo'], nombreArticulo: fila['nombreArticulo'], cantidadArticulo: fila['cantidadArticulo'],precioVentaArticulo: fila['precioVentaArticulo'], idCom: tblComboDetalle.row(int).id()  }  	
   	 	arregloComboDetalle.push(item);
    }
    if(evento == 'N') {
        url = "<?php echo site_url('ComboC/ajax_guardar')?>";
    } else if(evento == 'E'){
        url = "<?php echo site_url('ComboC/ajax_modificar')?>";
    }

    $.ajax({
        url : url,
        type: "POST",
        data: {            
        	inpNombreCombo: $('#inpNombreCombo').val(), 
            inpStockCom: $('#inpStockCom').val(), 
            inpSerialCom: $('#inpSerialCom').val(),
        	inpDescripcionCombo: $('#inpDescripcionCombo').val(),
            inpPrecioVentaCombo: $('#inpPrecioVentaCombo').val(),
            id : $('#codigoCombo').val(),
            listaComboDetalle: JSON.stringify(arregloComboDetalle)
            },
            
        dataType: "JSON",
        success: function(data)
        {  
            if(data.status) 
            {              
            	inicio();
            	recargar();
            	alertaInfo('Guardado / Modificado con éxito');	
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

function editar(codigo){
    nuevo();
    $('#frmComboDetalle1').hide();
    $('#frmComboDetalle2').hide();
    $('#idAvisoCombo').hide();
	evento = 'E';	
    $.ajax({
        url : "<?php echo site_url('ComboC/ajax_editar/')?>/" + codigo,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {


        	$('#codigoCombo').val(data[0].ART_CODIGO);
            $('#inpNombreCombo').val(data[0].ART_NOMBRE);
            $('#inpStockCom').val(data[0].ART_STOCK), 
            $('#inpSerialCom').val(data[0].ART_SERIAL),
            $('#inpDescripcionCombo').val(data[0].ART_DESCRIPCION);
            $('#inpPrecioVentaCombo').val(data[0].ART_COMBO_PVP);
      
            var arregloCodigo = data[1];
            for (var int = 0; int < arregloCodigo.length; int++) {
            	agregarATablaComboDetalle(arregloCodigo[int].COD_CODIGO, arregloCodigo[int].COD_ART_CODIGO, arregloCodigo[int].ART_NOMBRE,arregloCodigo[int].COD_CANTIDAD,arregloCodigo[int].COD_PRECIO_VENTA);
 			}  

        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });
	
}

function recargar()
{
    table.ajax.reload(null,false); //Recarga la tabla con ajax
}

function iniciarTablaComboDetalle() {

tblComboDetalle = $('#tbl_combo_detalle').DataTable({paging: false, searching: false, select: true,
    "columns": [

        { "data": "codigoArticulo" },
        { "data": "nombreArticulo" },
        { "data": "cantidadArticulo" },
        { "data": "precioVentaArticulo" },
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
    "processing":     "Procesando...",
    "lengthMenu":     "Mostrar _MENU_ registros",
    },
    paging: true,
    destroy:true,
    searching: true});

 $('#tbl_combo_detalle tbody').on('click', 'tr', function () {
     var data = tblComboDetalle.row( this ).data();
         $('#inpArtCodigo').val(data['codigoArticulo']);
         $('#inpNombreArt').val(data['nombreArticulo']);
         $('#inpCantidadArticulo').val(data['cantidadArticulo']);
         $('#inpPrecioVentaArticulo').val(data['precioVentaArticulo']);
         $("#inpCantidadArticulo").prop('disabled', false);
         $("#inpPrecioVentaArticulo").prop('disabled', false);
          $('#btnAgregarArticulo').text('Editar');
          $('#btnEliminarArticulo').show();
          $('#btnCancelarArticulo').show();	
          $("#btnAgregarArticulo").prop('disabled', false);	
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
             document.getElementById("inpStockArti").value = ui.item.data.datos.ART_STOCK;
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


//AGREGAR Articulo al combo_detalle
function agregarArticulo(){
    
    
    if($('#btnAgregarArticulo').text()=='Agregar'){
        if ($('#inpCantidadArticulo').val()!='' && $('#inpPrecioVentaArticulo').val()!='') {
            
            $.ajax({
                url : "<?php echo site_url('ArticuloC/ajax_validar_stock/')?>/" + $('#inpArtCodigo').val()+"/"+$('#inpCantidadArticulo').val(),
                type: "GET",
                dataType: "JSON",
                success: function(data)
                { 
                    if (data.existeStock) {
                        agregarATablaComboDetalle('0' ,$('#inpArtCodigo').val() ,$('#inpNombreArt').val() ,$('#inpCantidadArticulo').val(),$('#inpPrecioVentaArticulo').val());
                        limpiarCombo();
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
            if ($('#inpCantidadArticulo').val()=='') {
                alertaWarn('Debe ingresar una Cantidad para el Articulo Seleccionado.');
            } else if ($('#inpPrecioVentaArticulo').val()=='') {
                alertaWarn('Debe ingresar un precio de venta para el Articulo Seleccionado.');
            }
            
        }
    }else if ($('#btnAgregarArticulo').text()=='Editar'){
        if ($('#inpCantidadArticulo').val()!='' && $('#inpPrecioVentaArticulo').val()!='') {
            $.ajax({
                url : "<?php echo site_url('ArticuloC/ajax_validar_stock/')?>/" + $('#inpArtCodigo').val()+"/"+$('#inpCantidadArticulo').val(),
                type: "GET",
                dataType: "JSON",
                success: function(data)
                { 
                    
                    if (data.existeStock) {
                        datos = {
                            "DT_RowId": tblComboDetalle.row('.selected').id(),
                            "codigoArticulo" : $('#inpArtCodigo').val(),
                            "nombreArticulo" : $("#inpNombreArt").val(),
                            "cantidadArticulo" : $('#inpCantidadArticulo').val(),
                            "precioVentaArticulo" : $('#inpPrecioVentaArticulo').val()
                            }
                
                        tblComboDetalle.row('.selected').data(datos);
                        tblComboDetalle.rows('.selected').deselect();
                        tblComboDetalle.draw();
                        limpiarCombo();
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
            if ($('#inpCantidadArticulo').val()=='') {
                alertaWarn('Debe ingresar una Cantidad para el Articulo Seleccionado.');
            } else if ($('#inpPrecioVentaArticulo').val()=='') {
                alertaWarn('Debe ingresar un precio de venta para el Articulo Seleccionado.');
            }
        }
        
        
    }
}


function agregarATablaComboDetalle(codigoComboDetalle, codigoArticulo, nombreArticulo, cantidadArticulo,precioVentaArticulo){
	tblComboDetalle.row.add( {
			"DT_RowId": codigoComboDetalle,
			"codigoArticulo" : codigoArticulo,
	    	"nombreArticulo" : nombreArticulo,
            "cantidadArticulo" : cantidadArticulo,
            "precioVentaArticulo" : precioVentaArticulo,
		} ).draw();
}

function limpiarCombo(){
	$('#inpArtCodigo').val('');
    $('#inpNombreArt').val('');
    $('#inpStockArti').val('');
    $('#inpCantidadArticulo').val('');
    $('#inpPrecioVentaArticulo').val('');
	$('#btnAgregarArticulo').text('Agregar');
	$('#btnEliminarArticulo').hide();
	$('#btnCancelarArticulo').hide();		
}


function eliminar(id)
{
    if(confirm('Desea eliminar el registro?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('ComboC/ajax_eliminar')?>/"+id,
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


function eliminarArticulo(){
	if(tblComboDetalle.row('.selected').id() != 0){
		
		if(confirm('Desea eliminar el registro?'))
	    {  
	        $.ajax({
	            url : "<?php echo site_url('ComboC/ajax_eliminar_articulo')?>/"+tblComboDetalle.row('.selected').id(),
	            type: "POST",
	            dataType: "JSON",
	            success: function(data)
	            {
	            	if(data.status) //if success close modal and reload ajax table
	                {  
	            		tblComboDetalle.row('.selected').remove().draw( false );
	            		limpiarCombo();
	            	 	$('#btnAgregarArticulo').text('Agregar');
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
	
		tblComboDetalle.row('.selected').remove().draw( false );
		limpiarCombo();
 	$('#btnAgregarArticulo').text('Agregar');
	}
}

</script>

