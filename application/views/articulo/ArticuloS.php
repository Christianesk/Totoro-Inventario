<script type="text/javascript">
/**
 * Campos
 */
var evento = 'I';
var table;

$(document).ready(function() {

inicio();
iniciarTablaPrincipal();


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
    table = $('#tbl_articulo').DataTable({
    	"dom" : 'Bfrtip',
        "buttons" : [
        	{
                extend: 'excelHtml5',
                exportOptions: {
                	columns: [ 0, 1,2,3 ,4,5,6]
                }
            },
        	{
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1,2,3 ,4,5,6]
                }
            },
            'colvis',
            'pageLength'                  
              ],
 
        "processing": true, 
        "serverSide": true, 
        "order": [], 
 
        "ajax": {
            "url": "<?php echo site_url('ArticuloC/lista_json')?>",
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
	$('#inpNombreArt').val('');
    $('#inpCategoria').val('');
    $('#inpCatCodigo').val('');
    $('#inpNombreCat').val('');
    $('#inpSerialArt').val('');
    $('#inpStockArt').val('');
    $('#inpPrecioCompraArt').val('');
    $('#inpPrecioVentaArt').val('');
	$('#inpDescripcion').val('');
	$('#codigoArticulo').val('');
	
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

function guardar()
{
    $('#btnGuardar').text('guardando...'); 
    $('#btnGuardar').attr('disabled',true); 

    if(evento == 'N') {
        url = "<?php echo site_url('ArticuloC/ajax_guardar')?>";
    } else if(evento == 'E'){
        url = "<?php echo site_url('ArticuloC/ajax_modificar')?>";
    }

    
    $.ajax({
        url : url,
        type: "POST",
        data: {            
            inpNombreArt: $('#inpNombreArt').val(), 
            inpCatCodigo: $('#inpCatCodigo').val(), 
            inpSerialArt: $('#inpSerialArt').val(), 
            inpStockArt: $('#inpStockArt').val(), 
            inpPrecioCompraArt: $('#inpPrecioCompraArt').val(), 
            inpPrecioVentaArt: $('#inpPrecioVentaArt').val(), 
            inpDescripcion: $('#inpDescripcion').val(),
            id : $('#codigoArticulo').val(),
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
	evento = 'E';	
  
    $.ajax({
        url : "<?php echo site_url('ArticuloC/ajax_editar/')?>/" + codigo,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        	$('#codigoArticulo').val(data.ART_CODIGO);
            $('#inpNombreArt').val(data.ART_NOMBRE);
            $('#inpCatCodigo').val(data.CAT_CODIGO);
            $('#inpNombreCat').val(data.CAT_NOMBRE);
            $('#inpSerialArt').val(data.ART_SERIAL);
            $('#inpStockArt').val(data.ART_STOCK);
            $('#inpPrecioCompraArt').val(data.ART_PRECIO_COMPRA);
            $('#inpPrecioVentaArt').val(data.ART_PRECIO_VENTA);
            $('#inpDescripcion').val(data.ART_DESCRIPCION);
             
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
        	alertaError('No se han podido cargar los datos');
        }
    });
	
}


function eliminar(id)
{
    if(confirm('Desea eliminar el registro?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('ArticuloC/ajax_eliminar')?>/"+id,
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

/**
  * Metodos para formulario Agregar Categoria
  */
  function nuevaCategoria()
 	{
 	    
 	    $('#dlg_formulario_categoria').modal('show'); // Muestra la ventana emergente
 	    $('.modal-title').text('Agregar Categoria'); // Asigna un titulo a la ventana emergente
 	   $('#inpNombre').val('');
 		$('#inpDescripcion').val('');
 		$('#codigoCategoria').val('');
 	}

function guardarCategoria()
{
    $('#btnGuardar').text('guardando...'); 
    $('#btnGuardar').attr('disabled',true); 

  
    url = "<?php echo site_url('CategoriaC/ajax_guardar')?>";
   

    
    $.ajax({
        url : url,
        type: "POST",
        data: {            
        	inpNombreCategoria: $('#inpNombreCategoria').val(), 
        	inpDescripcionCategoria: $('#inpDescripcionCategoria').val(),
            id : $('#codigoCategoria').val(),
            },
        dataType: "JSON",
        success: function(data)
        {  
            if(data.status) 
            {              
            	alertaInfo('Guardado / Modificado con éxito');	
 	            	$('#dlg_formulario_categoria').modal('hide');	
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

function recargar()
{
    table.ajax.reload(null,false); //Recarga la tabla con ajax
}

</script>

<!-- Dialogo Categoria -->
<!-- Bootstrap modal -->
<div class="modal fade" id="dlg_formulario_categoria" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin-top: 150px;">
            <div class="modal-header">
                <button type="button"  class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Agregar Categoria</h3>
            </div>
         <div class="panel-body">  
           <div class="form-group row">
                <input type="hidden" value="" id="codigoCategoria" />
                <div class="col-xs-12 col-md-2">
                    <p>
                        <label for="inpNombreCategoria">Nombre*:</label>
                    </p>
                </div>
                <div class="col-xs-12 col-md-5">
                        <input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreCategoria">
                </div>

			
		    </div>
            <div class="form-group row">
				<div class="col-xs-12 col-md-2">
					<p>
						<label for="inpDescripcionCategoria">Descripción*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-5">
			        <textarea class="form-control" id="inpDescripcionCategoria" rows="2"
				        style="height: 65px;"></textarea>
		        </div>
			</div>
           
           
            <div class="modal-footer">
                <button type="button" id="btnGuardarCategoria" onclick="guardarCategoria()" class="btn btn-info">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
