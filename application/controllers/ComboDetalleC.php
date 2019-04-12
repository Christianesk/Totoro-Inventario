<?php
class ComboC extends CI_Controller{
    /**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('ComboDetalleM');
		$this->load->model('UtilitarioM');
		$this->load->library('form_validation');
		if (!$this->session->userdata ( 'codigo' )) {
			redirect('CerrarSesion');
		}
    } 


    public function ajax_eliminar_articulo($codigo){
        $dataComboDetalle=array(
                'COD_ESTADO' => UtilitarioM::INACTIVO,
        );
            
        $this->ComboDetalleM->modificar(array('COD_CODIGO' => $codigo), $dataComboDetalle);
        echo json_encode(array("status" => TRUE));
    }

}
?>