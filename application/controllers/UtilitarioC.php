<?php 
class UtilitarioC extends CI_Controller{
 /**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('UtilitarioM');
		$this->load->library('form_validation');
		if (!$this->session->userdata ( 'codigo' )) {
			redirect('CerrarSesion');
		}
    }

    /***
	 * Metodo encargado de obtener el tipo de identificacion (Cédula o Ruc)
	 */
	public function ajax_obtener_tipo_identificacion()
	{
		$listaTipoIdentificacion=$this->UtilitarioM->listaTipoIdentificacion();
		echo json_encode($listaTipoIdentificacion);
	}


	/***
	 * Metodo encargado de obtener el tipo de rol de usuario (Administrador o usuario)
	 */
	public function ajax_obtener_tipo_usuario()
	{
		$listaTipoUsuario=$this->UtilitarioM->listaTipoUsuario();
		echo json_encode($listaTipoUsuario);
	}

	

	/***
	 * Metodo encargado de obtener el tipo de comprobante (Factura o Nota de venta)
	 */
	public function ajax_obtener_tipo_comprobante()
	{
		$listaTipoComprobante=$this->UtilitarioM->listaTipoComprobante();
		echo json_encode($listaTipoComprobante);
	}

}
?>