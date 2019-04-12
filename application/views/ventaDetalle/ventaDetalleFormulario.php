<div class="form-group row" id="divAgregarArt">
                <div class="col-lg-3 col-sm-4 col-md-4 col-xs-12">
                    <div class="form-group">
                        <label for="cboArticulo">Articulo*:</label>
                        <input type="hidden"  id="inpArtCodigo" name="inpArtCodigo" />
					    <input id="inpNombreArt" name="inpNombreArt"
							class="form-control"
							placeholder="Ingrese el nombre del articulo"
							value="" required="required">
                    </div>
                </div>

                <div class="col-lg-2 col-sm-4 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="inpCantVeDet">Cantidad:</label>
                        <input type="number" pattern="[0-9]+" class="form-control" id="inpCantVeDet" min="1" pattern="^[0-9]+">
                    </div>
                </div>

                <div class="col-lg-1 col-sm-4 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="inpStockArti">Stock:</label>
                        <input type="text" pattern="[0-9]+" class="form-control" id="inpStockArti" min="1" pattern="^[0-9]+" disabled>
                    </div>
                </div>


                <div class="col-lg-2 col-sm-4 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="inpPreVenVeDet">Precio Venta:</label>
                        <input type="number" pattern="[0-9]+" class="form-control" id="inpPreVenVeDet" min="1" pattern="^[0-9]+">
                    </div>
                </div>


                <div class="col-lg-3 col-sm-4 col-md-2 col-xs-12">
                    <div class="form-group">
                    <label for="botones" style="padding-bottom: 13px;"></label>
                    <div>
                         <button id="btnAgregarVenDetalle" type="button" class="btn btn-sm btn-info" onclick="agregarArticulo()" title="Agregar">
                            <i id ="btnAgregarIcoDetalle" class="glyphicon glyphicon-plus"></i>
                        </button>
                        <button id="btnEliminarVenDetalle" type="button" class="btn btn-sm btn-danger" onclick="eliminarArticulo()"  title="Eliminar">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
                        <button id="btnCancelarVenDetalle" type="button" class="btn btn-sm btn-warning" onclick="limpiarClienteDetalle()" title="Cancelar">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>
                    </div>
                </div>
    </div>

</div>