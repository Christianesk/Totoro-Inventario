<div class="panel panel-info">

	<div class="panel-body">
		<form id="frmArticulo">
			<input type="hidden" value="" id="codigoArticulo" />
            <div class="form-group row">
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpNombreArt">Nombre Articulo*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
					<input type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpNombreArt">
				</div>
			    <div class="col-xs-12 col-md-1">
                    <p>
                        <label for="inpCategoria">Categoria:</label>
                    </p>
			    </div>
			    <div class="col-xs-12 col-md-4">
					<input type="hidden"  id="inpCatCodigo" name="inpCatCodigo" />
					<input id="inpNombreCat" name="inpNombreCat"
							class="form-control"
							placeholder="Ingrese el nombre de la categoria"
							value="" required="required">
				</div>
				<div class="col-xs-12 col-md-1">
			                <button class="btn btn-sm btn-info" onclick="nuevaCategoria()" title="Nueva Categoria"
								type="button">
								<i class="glyphicon glyphicon-plus"></i>
							</button>
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
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpStockArt">Stock*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-2">
					<input type="number" pattern="\d+" class="form-control" id="inpStockArt">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpPrecioCompraArt">Precio Compra*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-2" style="margin-right: 95px;">
					<input type="number" pattern="\d+" class="form-control" id="inpPrecioCompraArt">
				</div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpPrecioVentaArt">Precio Venta*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-2">
					<input type="number" pattern="\d+" class="form-control" id="inpPrecioVentaArt">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-xs-12 col-md-1">
					<p>
						<label for="inpDescripcion">Descripción*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-3">
			        <textarea class="form-control" id="inpDescripcion" rows="2"
				        style="height: 65px;"></textarea>
		        </div>
			</div>
		</form>
	</div>
</div>
