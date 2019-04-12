
            
            <br>
            <div class="form-group row" id="frmComboDetalle1">
                <div class="col-xs-12 col-md-1">
                    <p>
                        <label for="cboArticulo">Articulo*:</label>
                    </p>
                </div>
                <div class="col-xs-12 col-md-3">
					<input type="hidden"  id="inpArtCodigo" name="inpArtCodigo" />
					<input id="inpNombreArt" name="inpNombreArt"
							class="form-control"
							placeholder="Ingrese el nombre del articulo"
							value="" required="required">
				</div>

                <div class="col-xs-12 col-md-1">
                    <p>
                        <label for="inpStockArti">Stock:</label>
                    </p>
                </div>
                <div class="col-xs-12 col-md-1">
					<input disabled="true" type="letter" pattern="[a-zA-Z]+" class="form-control" id="inpStockArti">
				</div>
                
            </div>

            <div class="form-group row" id="frmComboDetalle2">
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpCantidadArticulo">Cantidad*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-2">
					<input type="number" pattern="\d+" class="form-control" id="inpCantidadArticulo">
                </div>
                <div class="col-xs-12 col-md-1">
					<p>
						<label for="inpPrecioVentaArticulo">Precio Venta*:</label>
					</p>
				</div>
				<div class="col-xs-12 col-md-2">
					<input type="number" pattern="\d+" class="form-control" id="inpPrecioVentaArticulo">
				</div>

                <div class="col-xs-12 col-md-4">
                    <button id="btnAgregarArticulo" type="button" class="btn btn-info"
                        onclick="agregarArticulo()">Agregar</button>
                        <button id="btnEliminarArticulo" type="button" class="btn btn-danger"
                    onclick="eliminarArticulo()">Eliminar</button>
                    <button id="btnCancelarArticulo" type="button" class="btn btn-warning"
                        onclick="limpiarCombo()">Cancelar</button>
                </div>
            </div>
