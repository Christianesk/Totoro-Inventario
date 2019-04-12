<script type="text/javascript">
/**
 * Campos
 */
var evento = 'I';
var table;

$(document).ready(function() {

inicio();
iniciarTablaPrincipal();
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
    table = $('#tbl_proveedor').DataTable({
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
            "url": "<?php echo site_url('ProveedorC/lista_json')?>",
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

function guardar()
{
    $('#btnGuardar').text('guardando...'); 
    $('#btnGuardar').attr('disabled',true); 

    if(evento == 'N') {
        url = "<?php echo site_url('ProveedorC/ajax_guardar')?>";
    } else if(evento == 'E'){
        url = "<?php echo site_url('ProveedorC/ajax_modificar')?>";
    }

    
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
    $("#inpIdentProveedor").prop('disabled', true);
    $("#selTipoIdeProveedor").prop('disabled', true);
    $.ajax({
        url : "<?php echo site_url('ProveedorC/ajax_editar/')?>/" + codigo,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('#codigoProveedor').val(data.PER_CODIGO);
            $('#inpNombreProveedor').val(data.PER_NOMBRE), 
            $('#inpDireccionProveedor').val(data.PER_DIRECCION),
            $('#selTipoIdeProveedor').val(data.PER_TIPO_DOCUMENTO), 
            $('#inpIdentProveedor').val(data.PER_NUM_DOCUMENTO),
            $('#inpTelfProveedor').val(data.PER_TELEFONO), 
        	$('#inpEmailProveedor').val(data.PER_EMAIL)
             
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
            url : "<?php echo site_url('ProveedorC/ajax_eliminar')?>/"+id,
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


function recargar()
{
    table.ajax.reload(null,false); //Recarga la tabla con ajax
}
</script>