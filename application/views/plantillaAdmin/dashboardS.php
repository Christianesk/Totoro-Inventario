<script type="text/javascript">

$(document).ready(function() {
    var fecha = new Date();
    var anio = fecha.getFullYear();
    var mes = fecha.getMonth();
    comprasMes(anio);
    ventasMes(anio);
    masVendidos(anio);
    obtenerMes(anio);
    ventasDia(anio,mes,1);
});


$(function (){
    $.ajax({
        url : "<?php echo site_url('DashboardC/ajax_obtener_totales')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        	$('#txtTotalCompras').text('$ '+((data[0].totalCompras==null)?'0.00':data[0].totalCompras));
            $('#txtTotalVentas').text('$ '+((data[1].totalVentas==null)?'0.00':data[1].totalVentas));
            $('#txtTotalActual').text('$ '+((data[2].totalActual==null)?'0.00':data[2].totalActual));
            $('#txtTotalGan').text('$ '+((data[3].totalGanancia==null)?'0.00':data[3].totalGanancia));
        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });
});


$(function (){
    $.ajax({
        url : "<?php echo site_url('DashboardC/ajax_obtener_anios')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
        	for (var int = 0; int < data.length; int++) {
                 $('#selAnioCompras').append('<option value="'+(data[int].año)+'">'+(data[int].año)+'</option>');
                 $('#selAnioVentas').append('<option value="'+(data[int].año)+'">'+(data[int].año)+'</option>');
                 $('#selAnioMas').append('<option value="'+(data[int].año)+'">'+(data[int].año)+'</option>');
                 $('#selAnioDia').append('<option value="'+(data[int].año)+'">'+(data[int].año)+'</option>');
 			}
        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });
});

$(function (){
    
});

function obtenerMes(anio){
    $.ajax({
        url : "<?php echo site_url('DashboardC/ajax_obtener_meses/')?>/"+anio,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
        	for (var int = 0; int < data.length; int++) {
                 $('#selMesDia').append('<option value="'+(data[int].mes)+'">'+(mesNombre(data[int].mes))+'</option>');
                 
 			}
        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });
}


function seleccionAnioCompras(){
    comprasMes($('#selAnioCompras').val());
}

function seleccionAnioVentas(){
    ventasMes($('#selAnioVentas').val());
}

function seleccionAnioMas(){
    masVendidos($('#selAnioMas').val());
}

function seleccionAnioDia(){
    $('#selMesDia').empty().append('<option value="0" id="sel" selected="selected">Todos</option>');
    obtenerMes($('#selAnioDia').val());
    ventasDia($('#selAnioDia').val(),$('#selMesDia').val(),1);
}

function seleccionMesDia(){
    ventasDia($('#selAnioDia').val(),$('#selMesDia').val(),$('#selMesDia').val()==0?1:2);
}


function comprasMes(anio){
    /**COMPRAS */
    var parMesesCompras;
    var parValoresCompras;

    var arregloMesesCompras= [];
    var arregloValoresCompras= [];

    $.ajax({
        url : "<?php echo site_url('DashboardC/ajax_obtener_compras_meses/')?>/"+anio,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            for (var int = 0; int < data.length; int++) {
                arregloMesesCompras.push( mesNombre(data[int].mes));  
                arregloValoresCompras.push(data[int].cantidad);         
            }
            parMesesCompras=arregloMesesCompras;
            parValoresCompras =arregloValoresCompras;
            var ctx =$("#chartCompras");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: parMesesCompras,
                    datasets: [{
                        label: "Compras",
                        fill:true,
                        lineTension:0.1,
                        backgroundColor: "rgba(75,192,192,0.4)",
                        borderColor: "rgba(75,192,192,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(75,192,192,1)",
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 10,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(75,192,192,1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 5,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: parValoresCompras,
                        spanGaps:false,
                    }]
                },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true,
                                    stepSize: 2,
                                }
                            }]
                        }
                    }
                });

        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });
}

function ventasMes(anio){
    

    /**VENTAS */
    var parMesesVentas;
    var parValoresVentas;

    var arregloMesesVentas= new Array();
    var arregloValoresVentass= new Array();

    $.ajax({
        url : "<?php echo site_url('DashboardC/ajax_obtener_ventas_meses/')?>/"+anio,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            for (var int = 0; int < data.length; int++) {
                arregloMesesVentas.push( mesNombre(data[int].mes));  
                arregloValoresVentass.push(parseInt(data[int].cantidad));             
            }
            

            parMesesVentas=arregloMesesVentas;
            parValoresVentas =arregloValoresVentass;
            var ctx =$("#chartVentas");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: parMesesVentas,
                    datasets: [{
                        label: "Ventas",
                        fill:true,
                        lineTension:0.1,
                        backgroundColor: "rgba(255, 159, 64, 0.2)",
                        borderColor: "rgba(255, 159, 64, 1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(255, 159, 64, 1)",
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 10,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(255, 159, 64, 1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 5,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: parValoresVentas,
                        spanGaps:false,
                    }]
                },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true,
                                    stepSize: 2,
                                }
                            }]
                        }
                    }
                });

        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });
}

function masVendidos(anio){
    /**MAS VENDIDOS */
    var parMesesVen;
    var parValoresVen;

    var arregloMesesVen= new Array();
    var arregloValoresVen= new Array();

    $.ajax({
        url : "<?php echo site_url('DashboardC/ajax_obtener_mas_vendidos/')?>/"+anio,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            for (var int = 0; int < data.length; int++) {
                arregloMesesVen.push( data[int].articulo);  
                arregloValoresVen.push(parseInt(data[int].cantidad));             
            }

            parMesesVen=arregloMesesVen;
            parValoresVen =arregloValoresVen;

            
            var ctx =$("#chartMasVendidos");
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: parMesesVen,
                    datasets: [{
                        label: "Mas Vendidos",
                        lineTension:0.1,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        
                        data: parValoresVen,
                    }]
                },
                options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
                });

        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });
}

function ventasDia(anio,mes,validacion){
    /**VENTAS POR DIAS */
    var parDias;
    var parValores;

    var arregloDias= new Array();
    var arregloValores= new Array();

    $.ajax({
        url : "<?php echo site_url('DashboardC/ajax_obtener_ventas_diarias/')?>/"+anio+'/'+mes+'/'+validacion,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            for (var int = 0; int < data.length; int++) {
                arregloDias.push(data[int].fecha);  
                arregloValores.push(parseInt(data[int].cantidad));             
            }
    
            parDias=arregloDias;
            parValores =arregloValores;
            var ctx =$("#chartVentasDias");
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: parDias,
                    datasets: [{
                        label: "Ventas por dia",
                        fill:true,
                        lineTension:0.1,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                        
                        data: parValores,
                        borderWidth:2,
                    }]
                },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true,
                                    stepSize: 2,
                                }
                            }]
                        }
                    }
                });

        },
        error: function (jqXHR, textStatus, errorThrown)
        { 
        	alertaError('No se han podido cargar los datos');
        }
    });

}


function mesNombre($mes)
	{
		$nombre = "";
		
		if ($mes == 1) {
			$nombre = "Ene";
		} else if ($mes == 2) {
			$nombre = "Feb";
		} else if ($mes == 3) {
			$nombre = "Mar";
		} else if ($mes == 4) {
			$nombre = "Abr";
		} else if ($mes == 5) {
			$nombre = "May";
		} else if ($mes == 6) {
			$nombre = "Jun";
		} else if ($mes == 7) {
			$nombre = "Jul";
		} else if ($mes == 8) {
			$nombre = "Ago";
		} else if ($mes == 9) {
			$nombre = "Sep";
		} else if ($mes == 10) {
			$nombre = "Oct";
		} else if ($mes == 11) {
			$nombre = "Nov";
		} else if ($mes == 12) {
			$nombre = "Dic";
		}
		
		return $nombre;
	}

</script>