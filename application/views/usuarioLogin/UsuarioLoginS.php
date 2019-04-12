<script>	

	<?php if(form_error('username')) {?>
	alertaError(<?php echo form_error('username');  ?>);
	<?php }?>
	
	<?php if(form_error('password')) {?>
	alertaError(<?php echo form_error('password');?>);
	<?php }?>

	<?php if(!$success) {?>
	alertaError('Usuario o Contraseña Incorrecta');
	<?php }?>


	function login()
	{
	   // $('#btnGuardar').text('guardando...'); //Cambiamos el texto del botón Guardar
	    //$('#btnGuardar').attr('disabled',true); //Se desabilita el boton
	    var url;
	 
	   
	        url = "<?php echo site_url('UsuarioLoginC/validar_ingreso')?>";
	   
	 
	    // ajax adding data to database
	    $.ajax({
	        url : url,
	        type: "POST",
	        data: $('#form').serialize(),
	        dataType: "JSON",
	        success: function(data)
	        {
	 
	            if(data.status) //if success close modal and reload ajax table
	            {
	                  alertaInfo('Bienvenido');	
	            }
	            else
	            {
	                for (var i = 0; i < data.inputerror.length; i++)
	                {
	                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
	                  //  $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
	                    alertaError(data.error_string[i]);	
	                 }
	            }
	            //$('#btnGuardar').text('Guardar'); //change button text
	            //$('#btnGuardar').attr('disabled',false); //set button enable
	 
	 
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	        	alertaError('Error guardando o actualizando');
	           // $('#btnGuardar').text('Guardar'); //change button text
	            //$('#btnGuardar').attr('disabled',false); //set button enable 
	        }
	    });
	}
		
</script>