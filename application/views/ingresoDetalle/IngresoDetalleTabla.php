

            <div class="table-responsive">
                <div id="idAvisoCombo"><?php $this->load->view('avisos/AvisoTabla');?></div>
                <br>
                <table id="tbl_ingreso_detalle"
                    class="table table-striped table-bordered table-condensed"
                    cellspacing="0" width="100%">
                    <thead style="background-color:#A9D0F5">
                        <tr>
                            <th>C Articulo</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tfoot>
                            <th style="border-color: white ; border-top-color: #ddd;"></th>
                            <th style="border-color: white ; border-top-color: #ddd;"></th>
                            <th style="border-color: white ; border-top-color: #ddd;"></th>
                            <th style="border-color: white ; border-top-color: #ddd;"></th>
                            <th style="border-color: white ; border-top-color: #ddd;">
                                <h5 style ="margin-top: 0px; margin-bottom: 0px;"><b>Subtotal:</b></h5><br>
                                <h5 style ="margin-top: 0px; margin-bottom: 0px;" id="txtIva"><b>Iva (12%):</b></h5><br>
                                <h5 style ="margin-top: 0px; margin-bottom: 0px;"><b>TOTAL:</b></h5>
                            </th>
                            <th>
                                <h5 style ="margin-top: 0px; margin-bottom: 0px;" id="ingresoSubtotal">$ 0.00</h5><br>
                                <h5 style ="margin-top: 0px; margin-bottom: 0px;" id="ingresoIva">$ 0.00</h5><br>
                                <h5 style ="margin-top: 0px; margin-bottom: 0px;" id="ingresoTotal">$ 0.00</h5>
                            </th>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
            
			