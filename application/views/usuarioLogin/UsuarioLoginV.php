<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Christian Mena">
    <meta name="keyword" content="Inventario, Ventas,Sales,Inventory">

    <title>TOTORO - Inventario</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">



    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

	  <div id="login-page">
	  	<div class="container">
	  	
		      <form class="form-login" action="<?php echo site_url('Principal');?>" method="post">
		        <h2 class="form-login-heading">Inicio de Sesión</h2>
		        <div class="login-wrap">
		            <input type="text" class="form-control" placeholder="Usuario" autofocus name="username" value="<?php echo set_value("username"); ?>" >
		            <br>
		            <input type="password" class="form-control" placeholder="Contraseña" name="password" value="<?php echo set_value("password"); ?>" >
		            <label class="checkbox">
		                <span class="pull-right">
		                    
		
		                </span>
		            </label>
		            <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> Ingresar</button>
		            
		            
		
		        </div>
		
		      </form>	  	
	  	
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("assets/img/login-bg3.jpg", {speed: 500});
    </script>

<script src="assets/js/notificacion/tipo_notificacion.js"></script>
    <script type="text/javascript"
	src="assets/js/notificacion/jquery.noty.packaged.min.js"></script>


  </body>
</html>
