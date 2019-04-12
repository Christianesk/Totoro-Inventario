<?php
class DashboardC extends CI_Controller{
/**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('IngresoM');
		$this->load->model('VentaM');
		$this->load->model('UtilitarioM');
		if (!$this->session->userdata ( 'codigo' )) {
			redirect('CerrarSesion');
		}
    }
    
    /***
	 * Metodo encargado de iniciar la vista de los usuarios
	 */
	public function index() {
		$this->load->view('plantillaAdmin/inicio');
		$this->load->view('plantillaAdmin/dashboard');
		$this->load->view('plantillaAdmin/fin');
		$this->load->view('plantillaAdmin/dashboardS');
	}

	public function ajax_obtener_totales(){
		$data = array(
			$this->IngresoM->obtener_total_compras(),
			$this->VentaM->obtener_total_ventas(),
			$this->VentaM->obtener_total_actual($this->UtilitarioM->fecha_actual()),
			$this->VentaM->obtener_total_ganacias($this->UtilitarioM->fecha_actual())
		);
		echo json_encode($data);
	}

	public function ajax_obtener_compras_meses($anio){
		$data = $this->IngresoM->obtener_compras_por_meses($anio);
		echo json_encode($data);
	}
	

	public function ajax_obtener_ventas_meses($anio){
		$data = $this->VentaM->obtener_ventas_por_meses($anio);
		echo json_encode($data);
	}

	public function ajax_obtener_mas_vendidos($anio){
		$data = $this->VentaM->obtener_mas_vendidos($anio);
		echo json_encode($data);
	}

	
	public function ajax_obtener_ventas_diarias($anio,$mes,$validacion){
		$data = $this->VentaM->obtener_ventas_por_dia($anio,$mes,$validacion);
		echo json_encode($data);
	}

	public function ajax_obtener_anios(){
		$data = $this->VentaM->obtener_anios();
		echo json_encode($data);
	}
	public function ajax_obtener_meses($anio){
		$data = $this->VentaM->obtener_mes($anio);
		echo json_encode($data);
	}
	
	


	

}
?>