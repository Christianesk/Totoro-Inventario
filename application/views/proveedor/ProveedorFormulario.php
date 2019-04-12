<br>
<div class="panel panel-info">

	<div class="panel-body">
		<form id="frmProveedor">
			<input type="hidden" value="" id="codigoProveedor" />
			<div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpNombreProveedor">Nombre*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreProveedor">
				</div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpDireccionProveedor">Dirección*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpDireccionProveedor">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="selTipoIdeProveedor">Tipo de Ident*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<select name="selTipoIdeProveedor" id="selTipoIdeProveedor" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" onchange="seleccionMascaraIdentificacion()">
						<option value="0" id="sel" selected="selected">Seleccione...</option>
					</select>
				</div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpIdentProveedor">Identificación*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpIdentProveedor">
				</div>
			</div>
            <div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpTelfProveedor">Teléfono*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpTelfProveedor">
				</div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpEmailProveedor">Email*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpEmailProveedor">
				</div>
			</div>
		</form>
	</div>
</div>