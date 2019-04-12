

<div class="row">
	<div class="col-lg-12 main-chart">

		<section class="content">
                  
			<div class="row">
				<div class="col-lg-3 col-xs-6">
          			<!-- small box -->
					<div class="small-box bg-aqua">
						<div class="inner">
						<h3 id="txtTotalCompras"></h3>

						<p>Compras</p>
						</div>
						<div class="icon">
						<i class="fa fa-shopping-bag"></i>
						</div>
						<a href="<?php echo site_url('Ingresos');?>" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-3 col-xs-6">
				<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
						<h3 id="txtTotalVentas"></h3>

						<p>Ventas</p>
						</div>
						<div class="icon">
						<i class="fa fa-shopping-cart"></i>
						</div>
						<a href="<?php echo site_url('Ventas');?>" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-3 col-xs-6">
          			<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
						<h3 id="txtTotalActual"></h3>

						<p>Caja Actual</p>
						</div>
						<div class="icon">
						<i class="fa fa-usd"></i>
						</div>
						<a href="#" class="small-box-footer"><i class="fa fa-usd"></i></a>
					</div>
				</div>

				<div class="col-lg-3 col-xs-6">
          			<!-- small box -->
					<div class="small-box bg-red">
						<div class="inner">
						<h3 id="txtTotalGan"></h3>

						<p>Ganacias</p>
						</div>
						<div class="icon">
						<i class="fa fa-usd"></i>
						</div>
						<a href="#" class="small-box-footer"><i class="fa fa-usd"></i></a>
					</div>
				</div>
				
			</div>

			<div class="row">
				<section class="col-md-6">
					<!-- AREA CHART -->
					<div class="box box-primary">
						<div class="box-header with-border">
						<h3 class="box-title">Compras - Meses</h3>

						<div class="box-tools pull-right">
							
						<div class="col-xs-12 col-md-12">
							<select name="selAnioCompras" id="selAnioCompras" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;"  onchange="seleccionAnioCompras()">
								
							</select>
						</div>
						
						</div>
						</div>
								<canvas id="chartCompras" width="400" height="200"></canvas>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</section>
				<section class="col-md-6">
					<!-- AREA CHART -->
					<div class="box box-info">
						<div class="box-header with-border">
						<h3 class="box-title">Ventas - Meses</h3>
						<div class="box-tools pull-right">
							
							<div class="col-xs-12 col-md-12">
								<select name="selAnioVentas" id="selAnioVentas" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" onchange="seleccionAnioVentas()">
								</select>
							</div>
						
						</div>
						
						</div>
								<canvas id="chartVentas" width="400" height="200"></canvas>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</section>
			</div>

			<div class="row">
				<section class="col-md-6">
					<!-- AREA CHART -->
					<div class="box box-danger">
						<div class="box-header with-border">
						<h3 class="box-title">Más Vendidos</h3>
						<div class="box-tools pull-right">
							
							<div class="col-xs-12 col-md-12">
								<select name="selAnioMas" id="selAnioMas" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" onchange="seleccionAnioMas()">
									
								</select>
							</div>
						
						</div>
						
						</div>
						<canvas id="chartMasVendidos" width="400" height="200"></canvas>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</section>
				<section class="col-md-6">
					<!-- AREA CHART -->
					<div class="box box-success">
						<div class="box-header with-border">
						<h3 class="box-title">Ventas - Diarias</h3>
						<div class="box-tools pull-right">
							<div class="col-xs-12 col-md-6" style="padding-left: 5px;">
								<select name="selAnioDia" id="selAnioDia" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" onchange="seleccionAnioDia()">
								
								</select>
								
							</div>

							<div class="col-xs-12 col-md-6">
								<select name="selMesDia" id="selMesDia" style="width: 100%; height: 34px; border-radius: 4px border: 1px solid #ccc;" onchange="seleccionMesDia()">
								<option value="0" id="sel" selected="selected">Todos</option>
								</select>
							</div>
						
						</div>
						
						</div>
								<canvas id="chartVentasDias" width="400" height="200"></canvas>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</section>
			</div>


		</section>
                      
                   
	</div><!-- /row -->	
</div><!-- /col-lg-9 END SECTION MIDDLE -->

