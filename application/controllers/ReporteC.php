<?php
class ReporteC extends CI_Controller {
	function __construct() {
		parent::__construct ();
		if (!$this->session->userdata ( 'codigo' )) {
			redirect('CerrarSesion');
		}
	}
	
	
	public function index() {
		
	}
	
	public function reporteVenta()
	{
		$this->load->view('reportes/reporteVentas');
	}
	public function reporteStock()
	{
		$this->load->view('reportes/reporteStock');
	}	
	public function reporteStockPorComprar()
	{
		$this->load->view('reportes/reporteStockPorComprar');
	}

	public function reportePrecios(){
		$this->load->view('reportes/reportePrecios');
	}


	
	
}