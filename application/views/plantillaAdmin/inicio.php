<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Totoro - Inventario</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">    
    
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
         
    <script src="assets/js/Chart.min.js"></script>

    <!--agregados nuevos-->
   <link rel="stylesheet" href="assets/css/dataTables.bootstrap.css">
   <link href="assets/css/buttons.dataTables.min.css"	rel="stylesheet">
   <link rel="stylesheet" href="assets/css/jquery-ui.min.css">
   <link href="assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

   <link rel="stylesheet" href="assets/css/nuevos.css">
   <link rel="stylesheet" href="assets/css/font-awesome.min.css">
   <link rel="stylesheet" href="assets/css/morris.css">

   <link
	href="assets/css/buttons.bootstrap4.min.css"
	rel="stylesheet">
    <link rel="shortcut icon" href="assets/img/white_totoro.ico" type="image/x-icon">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" >

  <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.html" class="logo"><b>TOTORO - INVENTARIO</b></a>
            <!--logo end-->
            <div class="top-menu">
            	<ul class="nav pull-right top-menu">
                    <li><a class="logout" href="<?php echo site_url('CerrarSesion') ?>">Cerrar Sesión</a></li>
            	</ul>
            </div>
        </header>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              
              	  <p class="centered"><a href="#"><img src="assets/img/ui-sam.jpg" class="img-circle" width="60"></a></p>
              	  <h5 class="centered"><?php echo $this->session->userdata('nombreEmpleado'); ?></h5>
                  <h5 class="centered"><?php echo $this->session->userdata('rol'); ?></h5>
                  <input type="hidden"  id="rolCodigo" name="rolCodigo" value="<?php echo $this->session->userdata('tipoRol'); ?>" required="true">	
                  <li class="mt">
                      <a class="active" href="<?php echo site_url('Dashboard') ?>">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a href="javascript:;" id="MnuAlmacen">
                          <i class="fa fa-archive"></i>
                          <span>Almacén</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="<?php echo site_url('Categorias');?>">Categorias</a></li>
                          <li><a  href="<?php echo site_url('Articulos');?>">Articulos</a></li>
                          <!--<li><a  href="<?php echo site_url('Combos');?>">Combos</a></li>-->
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-shopping-bag"></i>
                          <span>Compras</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="<?php echo site_url('Ingresos');?>">Ingresos</a></li>
                          <li><a  href="<?php echo site_url('Proveedores');?>">Proveedores</a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-shopping-cart"></i>
                          <span>Ventas</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="<?php echo site_url('Ventas');?>">Ventas</a></li>
                          <li><a  href="<?php echo site_url('Clientes');?>">Clientes</a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="<?php echo site_url('Usuarios');?>" id="MnuUsuario">
                          <i class="fa fa-users"></i>
                          <span>Usuarios</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" id="MnuReportes">
                          <i class="fa fa-book"></i>
                          <span>Reportes</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="javascript:;" onclick="reporteVentas()"><i class="fa fa-file-pdf-o"></i><span>Ventas</span></a></li>
                          <li><a href="javascript:;"  onclick="reporteStock()"><i class="fa fa-file-pdf-o"></i><span>Stock Artículos</span></a></li>
                          <li><a href="javascript:;"  onclick="reporteStockPorComprar()"><i class="fa fa-file-pdf-o"></i><span>Artículos por Terminarse</span></a></li>
                          <li><a href="javascript:;"  onclick="reportePrecios()"><i class="fa fa-file-pdf-o"></i><span>Precios Artículos</span></a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="<?php echo site_url('Acerca');?>" >
                          <i class="fa fa-info-circle"></i>
                          <span>Acerca De</span>
                      </a>
                  </li>
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">	