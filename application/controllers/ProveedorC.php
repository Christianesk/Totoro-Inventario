<?php 
class ProveedorC extends CI_Controller{
/**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('PersonaM');
		$this->load->model('UtilitarioM');
		$this->load->library('form_validation');
		if (!$this->session->userdata ( 'codigo' )) {
			redirect('CerrarSesion');
		}
    }
    
    /***
	 * Metodo encargado de iniciar la vista de los proveedores
	 */
	public function index() {
		$this->layout_sin_datos( 'proveedor/ProveedorV', 'proveedor/ProveedorS');
    }
    
    /***
	 * Metodo encargado de obtener una lista de proveedores
	 */
	public function lista_json()
	{
		$lista = $this->PersonaM->lista();
		$datos = array();
		$no = $_POST['start'];
		foreach ($lista as $persona) {
			
			
			if ($persona->PER_TIPO == UtilitarioM::TIPO_PROVEEDOR) {
                $no++;
				
                $row = array();
                $row[] = $no;
                $row[] = $persona->PER_NOMBRE;
                $row[] = $this->UtilitarioM->tipoDocumento($persona->PER_TIPO_DOCUMENTO);
                $row[] = $persona->PER_NUM_DOCUMENTO;
                $row[] = $persona->PER_TELEFONO;
                $row[] = $persona->PER_EMAIL;
        
                //add html for action
                $row[] = '<a class="btn btn-sm btn-primary" href="#" title="Editar" onclick="editar('."'".$persona->PER_CODIGO."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
                    <a class="btn btn-sm btn-danger" href="#" title="Hapus" onclick="eliminar('."'".$persona->PER_CODIGO."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
        
                $datos[] = $row;
            } 
		}
	
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->PersonaM->tamanio_lista(),
				"recordsFiltered" => $this->PersonaM->tamanio_filtro(),
				"data" => $datos,
		);
		//output to json format
		echo json_encode($output);
    }

    /***
	 * Metodo encargado de guardar un proveedor
	 */
	public function ajax_guardar() {
		
		
		if ($this->PersonaM->existencia($this->input->post ( 'inpIdentProveedor' ))) {
			$data = array (
					'PER_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreProveedor' )),
                    'PER_TIPO_DOCUMENTO' => $this->input->post ( 'selTipoIdeProveedor' ),
                    'PER_NUM_DOCUMENTO' => $this->input->post ( 'inpIdentProveedor' ),
                    'PER_DIRECCION' => strtoupper ($this->input->post ( 'inpDireccionProveedor' )),
                    'PER_TELEFONO' => $this->input->post ( 'inpTelfProveedor' ),
                    'PER_EMAIL' => $this->input->post ( 'inpEmailProveedor' ),
					'PER_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
					'PER_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'PER_ESTADO' => UtilitarioM::ACTIVO
			);
			$this->PersonaM->modificar ( array ('PER_NUM_DOCUMENTO' => $this->input->post ( 'inpIdentProveedor' )), $data );
			
		}else 
		{
			$this->_validar ();
			$data = array (
                    'PER_TIPO' => UtilitarioM::TIPO_PROVEEDOR,
                    'PER_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreProveedor' )),
                    'PER_TIPO_DOCUMENTO' => $this->input->post ( 'selTipoIdeProveedor' ),
                    'PER_NUM_DOCUMENTO' => $this->input->post ( 'inpIdentProveedor' ),
                    'PER_DIRECCION' => strtoupper ($this->input->post ( 'inpDireccionProveedor' )),
                    'PER_TELEFONO' => $this->input->post ( 'inpTelfProveedor' ),
                    'PER_EMAIL' => $this->input->post ( 'inpEmailProveedor' ),
					'PER_USU_CODIGO_CREACION' => $this->session->userdata ( 'codigo' ),
					'PER_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
					'PER_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual (),
					'PER_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'PER_ESTADO' => UtilitarioM::ACTIVO
			);
			$insert = $this->PersonaM->guardar ( $data );
			
			
		}
		
		echo json_encode ( array ("status" => TRUE) );
    }

    /***
	 * Metodo encargado de guardar el proveedor editado
	 */
	public function ajax_modificar() {
		$this->_validar_modificar();
		$data = array (
            'PER_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreProveedor' )),
            'PER_NUM_DOCUMENTO' => $this->input->post ( 'inpIdentProveedor' ),
            'PER_DIRECCION' => strtoupper ($this->input->post ( 'inpDireccionProveedor' )),
            'PER_TELEFONO' => $this->input->post ( 'inpTelfProveedor' ),
            'PER_EMAIL' => $this->input->post ( 'inpEmailProveedor' ),
            'PER_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
            'PER_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual ()
		);
		$this->PersonaM->modificar ( array (
				'PER_CODIGO' => $this->input->post ( 'id' ) 
		), $data );
		echo json_encode ( array (
				"status" => TRUE 
		) );
	}


    /***
	 * Metodo encargado de obtener los datos a editar de datos de PROVEEDOR seleccionados
	 * @param unknown $codigo Codigo de proveedor seleccionado
	 */
	public function ajax_editar($codigo) {
		$data = $this->PersonaM->obtener_por_codigo ( $codigo);
		echo json_encode ( $data );
	}
    

    /***
	 * Metodo encargado de validar el correcto ingreso de datos 
	 */
	private function _validar() {
		$data = array ();
		$data ['error_string'] = array ();
		$data ['inputerror'] = array ();
		$data ['status'] = TRUE;
		
		$this->form_validation->set_error_delimiters ( "", "" );
		$this->form_validation->set_rules ( 'inpIdentProveedor', 'Identificacion', 'trim|required|is_unique[persona.PER_NUM_DOCUMENTO]' );
		$this->form_validation->set_rules ( 'inpNombreProveedor', 'Nombre', 'trim|required' );
		$this->form_validation->set_rules ( 'inpTelfProveedor', 'Teléfono', 'trim|required' );
		$this->form_validation->set_rules ( 'inpEmailProveedor', 'Email', 'trim|required' );
		//$this->form_validation->set_rules('inpEmail', 'Correo Electrónico', 'trim|required|valid_email|is_unique[CLIENTE.CLI_EMAIL]');
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;
			
			if (form_error ( 'inpIdentProveedor' )) {
				$data ['inputerror'] [] = 'inpIdentProveedor';
				$data ['error_string'] [] = form_error ( 'inpIdentProveedor' );
			}
			
			if (form_error ( 'inpNombreProveedor' )) {
				$data ['inputerror'] [] = 'inpNombreProveedor';
				$data ['error_string'] [] = form_error ( 'inpNombreProveedor' );
			}
			
			if (form_error ( 'inpTelfProveedor' )) {
				$data ['inputerror'] [] = 'inpTelfProveedor';
				$data ['error_string'] [] = form_error ( 'inpTelfProveedor' );
			}
			
			if (form_error ( 'inpEmailProveedor' )) {
				$data ['inputerror'] [] = 'inpEmailProveedor';
				$data ['error_string'] [] = form_error ( 'inpEmailProveedor' );
			}

		}
		
		if ($data ['status'] === FALSE) {
			echo json_encode ( $data );
			exit ();
		}
    }
    

    private function _validar_modificar() {
		$data = array ();
		$data ['error_string'] = array ();
		$data ['inputerror'] = array ();
		$data ['status'] = TRUE;
		
		$this->form_validation->set_error_delimiters ( "", "" );
		$this->form_validation->set_rules ( 'inpIdentProveedor', 'Identificacion', 'trim|required' );
		$this->form_validation->set_rules ( 'inpNombreProveedor', 'Nombre', 'trim|required' );
		$this->form_validation->set_rules ( 'inpTelfProveedor', 'Teléfono', 'trim|required' );
		$this->form_validation->set_rules ( 'inpEmailProveedor', 'Email', 'trim|required' );
		//$this->form_validation->set_rules('inpEmail', 'Correo Electrónico', 'trim|required|valid_email|is_unique[CLIENTE.CLI_EMAIL]');
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;
			
			if (form_error ( 'inpIdentProveedor' )) {
				$data ['inputerror'] [] = 'inpIdentProveedor';
				$data ['error_string'] [] = form_error ( 'inpIdentProveedor' );
			}
			
			if (form_error ( 'inpNombreProveedor' )) {
				$data ['inputerror'] [] = 'inpNombreProveedor';
				$data ['error_string'] [] = form_error ( 'inpNombreProveedor' );
			}
			
			if (form_error ( 'inpTelfProveedor' )) {
				$data ['inputerror'] [] = 'inpTelfProveedor';
				$data ['error_string'] [] = form_error ( 'inpTelfProveedor' );
			}
			
			if (form_error ( 'inpEmailProveedor' )) {
				$data ['inputerror'] [] = 'inpEmailProveedor';
				$data ['error_string'] [] = form_error ( 'inpEmailProveedor' );
			}

		}
		
		if ($data ['status'] === FALSE) {
			echo json_encode ( $data );
			exit ();
		}
    }
    
    /***
	 * Metodo encargado de eliminar el proveedor
	 */
	public function ajax_eliminar($id) {
		if($this->PersonaM->dependencia_ingreso($id)){
			echo json_encode(array("status" => FALSE));
		}else {
		$data = array (
				'PER_ESTADO' => UtilitarioM::INACTIVO,
				'PER_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
                'PER_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual () 
		);
		$this->PersonaM->modificar ( array ('PER_CODIGO' => $id ), $data );
		echo json_encode ( array ("status" => TRUE ) );}
	}

	/***
	 * Metodo encargado de autocompletar el proveedor dependiendo del parametro de busqueda
	 * @param unknown $parametro_busqueda
	 */
	public function autocompletar($parametro_busqueda){
		$lista = $this->PersonaM->autocompletar_proveedor($parametro_busqueda);
		$datos = array();
	
		foreach ($lista as $persona) {
			$datos[] =array(
				'codigo' => $persona->PER_CODIGO, 
				'valor' => $persona->PER_NOMBRE, 
				'datos' => $persona);
		}
	
		echo json_encode($datos);
	}

    
    /***
	 * Metodo encargado de mostrar la vista del modulo de proveedores y cargar scripts
	 * @param  $contenido - direccion de la vista del modulo
	 * @param  $script - direccion del script del modulo
	 */
	public function layout_sin_datos($contenido,$script){

		$this->load->view('plantillaAdmin/inicio');
        $this->load->view($contenido);
		$this->load->view('plantillaAdmin/fin');
		$this->load->view($script);
	}
}
?>