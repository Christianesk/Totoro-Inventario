if ($('#rolCodigo').val()==2) {
		$('#MnuAlmacen').hide();
		$('#MnuUsuario').hide();
	}

	function reporteVentas() {
		$('#dlg_reporte_ventas').modal('show');
		$fechaEmision = new Date();
		$('#inpFechaInicioVenUno').val($fechaEmision.getFullYear()+'-'+($fechaEmision.getMonth()+1)+'-'+$fechaEmision.getDate());
		$('#inpFechaFinalVenDos').val($fechaEmision.getFullYear()+'-'+($fechaEmision.getMonth()+1)+'-'+$fechaEmision.getDate());
	}

	 $(function () {
		$('#datetimepickerVen1').datetimepicker();
	    $('#datetimepickerVen2').datetimepicker({
	        useCurrent: true //Important! See issue #1075
	    });
	    $("#datetimepickerVen1").on("dp.change", function (e) {
	        $('#datetimepickerVen2').data("DateTimePicker").minDate(e.date);
	    });
	    $("#datetimepickerEVen2").on("dp.change", function (e) {
	        $('#datetimepickerVen1').data("DateTimePicker").maxDate(e.date);
	    });
	 });

	 function abrirReporteVentas(){
		$fechaEmision = new Date();
	        window.open("http://localhost/totoro/reporteVentas?fechaInicio="+'"'+$('#inpFechaInicioVenUno').val()+'"'+"&fechaFin="+'"'+$('#inpFechaFinalVenDos').val()+'"&fechaEmision='+'"'+$fechaEmision.getFullYear()+'-'+$fechaEmision.getMonth()+'-'+$fechaEmision.getDate()+'"', '_blank');
	 }

	 function reporteStock(){
		$fechaEmision = new Date();
	        window.open("http://localhost/totoro/reporteStock?fechaEmision="+'"'+$fechaEmision.getFullYear()+'-'+($fechaEmision.getMonth()+1)+'-'+$fechaEmision.getDate()+'"', '_blank');
	 }

	 function reporteStockPorComprar(){
		$fechaEmision = new Date();
	        window.open("http://localhost/totoro/reporteStockPorComprar?fechaEmision="+'"'+$fechaEmision.getFullYear()+'-'+($fechaEmision.getMonth()+1)+'-'+$fechaEmision.getDate()+'"', '_blank');
	 }

	 function reportePrecios(){
		$fechaEmision = new Date();
		window.open("http://localhost/totoro/reportePrecios?fechaEmision="+'"'+$fechaEmision.getFullYear()+'-'+($fechaEmision.getMonth()+1)+'-'+$fechaEmision.getDate()+'"', '_blank');
	 }