<div class="panel panel-info">

	<div class="panel-body">
		<form id="frmCombo">
			<input type="hidden" value="" id="codigoCombo" />
			<div class="form-group row">
                <br>
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpNombreCombo">Nombre*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreCombo">
				</div>
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpStockCom">Stock*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-2">
					<input type="number" pattern="\d+" class="form-control" id="inpStockCom">
				</div>
			</div>
			<div class="form-group row">
				
                
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpSerialCom">Codigo*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpSerialCom">
				</div>  
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpPrecioVentaCombo">Precio Venta Total*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-2">
					<input type="number" pattern="\d+" class="form-control" id="inpPrecioVentaCombo">
				</div>
			</div>
            <div class="form-group row">
                <div class="col-xs-12 col-md-1">
                        <p>
                            <label for="inpDescripcionCombo">Descripción*:</label>
                        </p>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <textarea class="form-control" id="inpDescripcionCombo" rows="2"
                            style="height: 65px;"></textarea>
		        </div>
				
            </div>
		</form>
        <ul class="nav nav-tabs">
			<li><a data-toggle="tab" href="#menuArticulos">Articulos</a></li>
		</ul>

		<div class="tab-content">
			<div id="menuArticulos" class="tab-pane fade">
				<br>

				<?php $this->load->view('comboDetalle/ComboDetalleFormulario');?>
				<?php $this->load->view('comboDetalle/ComboDetalleTabla');?>				

			</div>
		</div>          
	</div>
</div>