<?php
class AcercaC extends CI_Controller{
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
		$this->load->view('acerca/Acerca');
		$this->load->view('plantillaAdmin/fin');
	}
}
?>