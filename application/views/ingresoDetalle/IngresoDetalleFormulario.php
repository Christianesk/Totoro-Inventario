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
                <div class="col-lg-1 col-sm-4 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="botones" style="padding-bottom: 13px;"></label>
                        <div >
                            <button class="btn btn-sm btn-info" onclick="nuevoArticulo()" title="Crear Articulo" type="button">
                                        <i class="glyphicon glyphicon-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-sm-4 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="inpCantInDet">Cantidad:</label>
                        <input type="number" pattern="[0-9]+" class="form-control" id="inpCantInDet" min="1" pattern="^[0-9]+">
                    </div>
                </div>

                <div class="col-lg-2 col-sm-4 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="inpPreComInDet">Precio Compra:</label>
                        <input type="number" pattern="[0-9]+" class="form-control" id="inpPreComInDet" min="1" pattern="^[0-9]+">
                    </div>
                </div>

                <div class="col-lg-2 col-sm-4 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="inpPreVenInDet">Precio Venta:</label>
                        <input type="number" pattern="[0-9]+" class="form-control" id="inpPreVenInDet" min="1" pattern="^[0-9]+">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 col-md-2 col-xs-12">
                    <div class="form-group">
                    <label for="botones" style="padding-bottom: 13px;"></label>
                    <div>
                         <button id="btnAgregarIngDetalle" type="button" class="btn btn-sm btn-success" onclick="agregarArticulo()" title="Agregar">
                            <i id ="btnAgregarIcoDetalle" class="glyphicon glyphicon-plus"></i>
                        </button>
                        <button id="btnEliminarIngDetalle" type="button" class="btn btn-sm btn-danger" onclick="eliminarArticulo()"  title="Eliminar">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
                        <button id="btnCancelarIngDetalle" type="button" class="btn btn-sm btn-warning" onclick="limpiarProveedorDetalle()" title="Cancelar">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>
                    </div>
                </div>
    </div>

</div>