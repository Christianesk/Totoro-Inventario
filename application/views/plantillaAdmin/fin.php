            </section>
      </section>
</section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="assets/js/jquery.sparkline.js"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>
    
    <script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="assets/js/gritter-conf.js"></script>

    <!--script for this page-->
    <script src="assets/js/sparkline-chart.js"></script>    
    <script src="assets/js/zabuto_calendar.js"></script>	
    <script src="assets/js/notificacion/jquery.noty.packaged.min.js"></script>
    <script src="assets/js/notificacion/tipo_notificacion.js"></script>

    <!--agregados nuevos-->
    <script src="https://cdn.datatables.net/t/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,af-2.1.1,b-1.1.2,b-colvis-1.1.2,b-flash-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,fc-3.2.1,fh-3.1.1,kt-2.1.1,r-2.0.2,rr-1.1.1,sc-1.4.1,se-1.1.2/datatables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.maskedinput.min.js"></script>
    
    <script type="text/javascript" src="assets/js/moment.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="assets/js/improvements.js"></script>

    <script src="assets/js/buttons.bootstrap4.min.js"></script>
	
	
	<script type="application/javascript">
        $(document).ready(function () {
            $("#date-popover").popover({html: true, trigger: "manual"});
            $("#date-popover").hide();
            $("#date-popover").click(function (e) {
                $(this).hide();
            });
        
            $("#my-calendar").zabuto_calendar({
                action: function () {
                    return myDateFunction(this.id, false);
                },
                action_nav: function () {
                    return myNavFunction(this.id);
                },
                ajax: {
                    url: "show_data.php?action=1",
                    modal: true
                },
                legend: [
                    {type: "text", label: "Special event", badge: "00"},
                    {type: "block", label: "Regular event", }
                ]
            });
        });
        
        
        function myNavFunction(id) {
            $("#date-popover").hide();
            var nav = $("#" + id).data("navigation");
            var to = $("#" + id).data("to");
            console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
        }
    </script>

    <!-- Dialogo Rep Ventas -->
<!-- Bootstrap modal -->
<div class="modal fade" id="dlg_reporte_ventas" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin-top: 150px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Escoja las fechas para generar el reporte de ventas</h3>
            </div>
         <div class="panel-body">  
           <div class="form-group row">
			<div class="col-xs-12 col-md-2">
				<p>
					<label for="inpFechaInicioVenUno">Fecha Inicio *:</label>
				</p>
			</div>
			<div class="container">
				<div class="row">
			       <div class="col-sm-2" >
				        <div class="form-group">
				            <div class='input-group date' id='datetimepickerVen1'>
				                <input type='text' class="form-control" id="inpFechaInicioVenUno" data-date-format="YYYY-MM-DD" required="required" />
				                <span class="input-group-addon">
				                    <span class="fa fa-calendar">
				                    </span>
				                </span>
				            </div>
				        </div>
				    </div>
				    <div class="col-xs-12 col-md-2">
				<p>
					<label for="inpFechaFinalVenDos">Fecha Final *:</label>
				</p>
			</div>
			<div class="container">
				<div class="row">
			       <div class="col-sm-2" >
				        <div class="form-group">
				            <div class='input-group date' id='datetimepickerVen2'>
				                <input type='text' class="form-control" id="inpFechaFinalVenDos" data-date-format="YYYY-MM-DD" required="required" />
				                <span class="input-group-addon">
				                    <span class="fa fa-calendar">
				                    </span>
				                </span>
				            </div>
				        </div>
				    </div>
				    <div class="col-xs-12 col-md-4">
					<button id="btnGeneraReporteVenta" type="button" class="btn btn-info" title="Generar Pdf"
					 onclick="abrirReporteVentas()">Generar</button>
			</div>
			    </div>
			</div>
			    </div>
			</div>
		</div>
		
		
		</div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


  </body>
</html>
