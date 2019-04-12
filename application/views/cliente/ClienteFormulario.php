<br>
<div class="panel panel-info">

	<div class="panel-body">
		<form id="frmCliente">
			<input type="hidden" value="" id="codigoCliente" />
			<div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpNombreCliente">Nombre*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreCliente">
				</div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpDireccionCliente">Dirección*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpDireccionCliente">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="selTipoIdeCliente">Tipo de Ident*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<select name="selTipoIdeCliente" id="selTipoIdeCliente" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" onchange="seleccionMascaraIdentificacion()">
						<option value="0" id="sel" selected="selected">Seleccione...</option>
					</select>
				</div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpIdentCliente">Identificación*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpIdentCliente">
				</div>
			</div>
            <div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpTelfCliente">Teléfono*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpTelfCliente">
				</div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpEmailCliente">Email*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpEmailCliente">
				</div>
			</div>
		</form>
	</div>
</div>