<div class="panel panel-info">

	<div class="panel-body">
		<form id="frmVenta">
			<input type="hidden" value="" id="codigoVenta" />
            
			<div class="form-group row">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="form-group">
                        <label for="cboCliente"><b>Cliente*:</b></label>
                        <input type="hidden"  id="inpCliCodigo" name="inpCliCodigo" />
                        <input id="inpNombreCli" name="inpNombreCli"
                                class="form-control"
                                placeholder="Ingrese el nombre del cliente, cedula o ruc"
                                value="" required="required">
                        <div id="txtCliente"></div>
                    </div>
                </div>

                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="form-group" id="divBotonCliente">
                    <label for="inpbotonNuevoCli" style="margin-bottom: 20px !important;"></label>
                        <div>
                            <button class="btn btn-sm btn-info" onclick="nuevoCliente()" title="Nuevo Cliente"
								type="button">
								<i class="glyphicon glyphicon-plus"></i>
							</button>
                        </div>
                    </div>
                </div>


                
			</div>

            <div class="form-group row">
                <div class="col-lg-3 col-sm-4 col-md-4 col-xs-12">
                    <div class="form-group">
                        <label for="selTipoComprobante"><b>Tipo Comprobante*:</b></label>
                        <select name="selTipoComprobante" id="selTipoComprobante" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" onchange="seleccionTipoComprobante()">
						    <option value="0" id="sel" selected="selected">Seleccione...</option>
					    </select>
                        <div id="txtTipoComprobante"></div>
                    </div>
                </div>

                

                <div class="col-lg-3 col-sm-4 col-md-4 col-xs-12">
                    <div class="form-group">
                        <label for="inpNumVenta"><b>N° Comprobante*:</b></label>
                        <input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNumVenta">
                        <div id="txtNumero"></div>
                    </div>
                </div>
                <div class="col-lg-1 col-sm-4 col-md-2 col-xs-12" id="divImpuesto">
                    <div class="form-group">
                        <label for="inpImpVenta"><b>Impuesto(%):</b></label>
                        <input type="text" pattern="[0-9]+" class="form-control" id="inpImpVenta">
                    </div>
                </div>

			</div>
            
			
			
		</form>
        <div class="panel panel-primary">

            <div class="panel-body">

				<?php $this->load->view('ventaDetalle/VentaDetalleFormulario');?>
				<?php $this->load->view('ventaDetalle/VentaDetalleTabla');?>				

		</div>          
	    </div>       
	</div>
</div>