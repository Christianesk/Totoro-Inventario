<?php 
class CategoriaC extends CI_Controller{
    /**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('CategoriaM');
		$this->load->model('UsuarioM');
		$this->load->model('UtilitarioM');
		$this->load->library('form_validation');
		if (!$this->session->userdata ( 'codigo' )) {
			redirect('CerrarSesion');
		}
    }
    
    /***
	 * Metodo encargado de iniciar la vista de las categorias
	 */
	public function index() {
		$this->layout_sin_datos( 'categoria/CategoriaV', 'categoria/CategoriaS');
    }
    
    /***
	 * Metodo encargado de obtener una lista de categorias
	 */
	public function lista_json()
	{
		$lista = $this->CategoriaM->lista();
		$datos = array();
		$no = $_POST['start'];
		foreach ($lista as $categoria) {
			
			
			$no++;
				
			$row = array();
			$row[] = $no;
			$row[] = $categoria->CAT_NOMBRE;
			$row[] = $categoria->CAT_DESCRIPCION;
	
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="#" title="Editar" onclick="editar('."'".$categoria->CAT_CODIGO."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
                  <a class="btn btn-sm btn-danger" href="#" title="Hapus" onclick="eliminar('."'".$categoria->CAT_CODIGO."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
	
			$datos[] = $row;
		}
	
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->CategoriaM->tamanio_lista(),
				"recordsFiltered" => $this->CategoriaM->tamanio_filtro(),
				"data" => $datos,
		);
		//output to json format
		echo json_encode($output);
	}
	

	/***
	 * Metodo encargado de guardar una categoria
	 */
	public function ajax_guardar() {
		
		
		if ($this->CategoriaM->existencia($this->input->post ( 'inpNombreCategoria' ))) {
			$data = array (
					'CAT_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreCategoria' )),
					'CAT_DESCRIPCION' => strtoupper ($this->input->post ( 'inpDescripcionCategoria' )),
					'CAT_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
					'CAT_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'CAT_ESTADO' => UtilitarioM::ACTIVO
			);
			$this->CategoriaM->modificar ( array ('CAT_NOMBRE' => $this->input->post ( 'inpNombreCategoria' ) ), $data );
			
		}else 
		{
			$this->_validar ();
			$data = array (
					'CAT_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreCategoria' )),
					'CAT_DESCRIPCION' => strtoupper ($this->input->post ( 'inpDescripcionCategoria' )),
					'CAT_USU_CODIGO_CREACION' => $this->session->userdata ( 'codigo' ),
					'CAT_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
					'CAT_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual (),
					'CAT_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'CAT_ESTADO' => UtilitarioM::ACTIVO
			);
			$insert = $this->CategoriaM->guardar ( $data );
			
			
		}
		
		echo json_encode ( array ("status" => TRUE) );
	}

	/***
	 * Metodo encargado de obtener los datos a editar de datos de categoria seleccionados
	 * @param unknown $codigo Codigo de empleado seleccionado
	 */
	public function ajax_editar($codigo) {
		$data = $this->CategoriaM->obtener_por_codigo ( $codigo );
		echo json_encode ( $data );
	}


	/***
	 * Metodo encargado de guardar la categoria editado
	 */
	public function ajax_modificar() {
		$this->_validar_modificar();
		$data = array (
				'CAT_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreCategoria' )),
				'CAT_DESCRIPCION' => strtoupper ($this->input->post ( 'inpDescripcionCategoria' )),
				'CAT_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
				'CAT_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
		);
		$this->CategoriaM->modificar ( array (
				'CAT_CODIGO' => $this->input->post ( 'id' ) 
		), $data );
		echo json_encode ( array (
				"status" => TRUE 
		) );
	}

	/***
	 * Metodo encargado de eliminar la categoria
	 */
	public function ajax_eliminar($id) {
		if($this->CategoriaM->dependencia($id)){
			echo json_encode(array("status" => FALSE));
		}else {
		$data = array (
				'CAT_ESTADO' => UtilitarioM::INACTIVO,
				'CAT_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
				'CAT_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
		);
		$this->CategoriaM->modificar ( array ('CAT_CODIGO' => $id ), $data );
		echo json_encode ( array ("status" => TRUE ) );}
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
		$this->form_validation->set_rules ( 'inpNombreCategoria', 'Nombre', 'trim|required|is_unique[categoria.CAT_NOMBRE]' );
		$this->form_validation->set_rules ( 'inpDescripcionCategoria', 'Descripcion', 'trim|required' );
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;

			
			if (form_error ( 'inpNombreCategoria' )) {
				$data ['inputerror'] [] = 'inpNombreCategoria';
				$data ['error_string'] [] = form_error ( 'inpNombreCategoria' );
			}
			
			if (form_error ( 'inpDescripcionCategoria' )) {
				$data ['inputerror'] [] = 'inpDescripcionCategoria';
				$data ['error_string'] [] = form_error ( 'inpDescripcionCategoria' );
			}
		}
		
		if ($data ['status'] === FALSE) {
			echo json_encode ( $data );
			exit ();
		}
	}

	/***
	 * Metodo encargado de validar el correcto ingreso de datos modificados
	 */
	private function _validar_modificar() {
		$data = array ();
		$data ['error_string'] = array ();
		$data ['inputerror'] = array ();
		$data ['status'] = TRUE;
		
		$this->form_validation->set_error_delimiters ( "", "" );
		$this->form_validation->set_rules ( 'inpNombreCategoria', 'Nombre', 'trim|required' );
		$this->form_validation->set_rules ( 'inpDescripcionCategoria', 'Descripcion', 'trim|required' );
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;

			
			if (form_error ( 'inpNombreCategoria' )) {
				$data ['inputerror'] [] = 'inpNombreCategoria';
				$data ['error_string'] [] = form_error ( 'inpNombreCategoria' );
			}
			
			if (form_error ( 'inpDescripcionCategoria' )) {
				$data ['inputerror'] [] = 'inpDescripcionCategoria';
				$data ['error_string'] [] = form_error ( 'inpDescripcionCategoria' );
			}
		}
		
		if ($data ['status'] === FALSE) {
			echo json_encode ( $data );
			exit ();
		}
	}
    
    /***
	 * Metodo encargado de mostrar la vista del modulo de categorias y cargar scripts
	 * @param  $contenido - direccion de la vista del modulo
	 * @param  $script - direccion del script del modulo
	 */
	public function layout_sin_datos($contenido,$script){

		$this->load->view('plantillaAdmin/inicio');
        $this->load->view($contenido);
		$this->load->view('plantillaAdmin/fin');
		$this->load->view($script);
	}


	/***
	 * Metodo encargado de autocompletar LA CATEGORIA dependiendo del parametro de busqueda
	 * @param unknown $parametro_busqueda
	 */
	public function autocompletar($parametro_busqueda){
		$lista = $this->CategoriaM->autocompletar($parametro_busqueda);
		$datos = array();
		
		
		foreach ($lista as $categoria) {
			if ($categoria->CAT_CODIGO!=1) {
				$datos[] =array('codigo' => $categoria->CAT_CODIGO, 'valor' => $categoria->CAT_NOMBRE, 'datos' => $categoria);
			} 
			
		}
	
		echo json_encode($datos);
	}
}
?>