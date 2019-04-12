<br>
<div class="panel panel-info">

	<div class="panel-body">
		<form id="frmUsuario">
			<input type="hidden" value="" id="codigoUsuario" />
			<div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpNombreEmpUsuario">Nombre*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreEmpUsuario">
				</div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpApellidoEmpUsuario">Apellido*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpApellidoEmpUsuario">
				</div>
            </div>
            
            
			<div class="form-group row">
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpNombreUsuario">Nombre Usuario*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreUsuario">
				</div>
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="selTipoIdeUsuario">Rol*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<select name="selTipoIdeUsuario" id="selTipoIdeUsuario" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" >
						<option value="0" id="sel" selected="selected">Seleccione...</option>
					</select>
				</div>
            </div>
            
            <div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpContrasenaUsuario">Contraseña*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="password" pattern="[a-zA-Z]+" class="form-control" id="inpContrasenaUsuario">
				</div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpVerfContraUsuario">Verificación Contraseña*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="password" pattern="[a-zA-Z]+" class="form-control" id="inpVerfContraUsuario">
				</div>
			</div>
            
		</form>
	</div>
</div>