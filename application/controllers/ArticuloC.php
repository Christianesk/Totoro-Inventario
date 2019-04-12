<?php
class ArticuloC extends CI_Controller{
    /**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('ArticuloM');
		$this->load->model('ComboDetalleM');
		$this->load->model('UsuarioM');
		$this->load->model('UtilitarioM');
		$this->load->library('form_validation');
		if (!$this->session->userdata ( 'codigo' )) {
			redirect('CerrarSesion');
		}
    }

    /***
	 * Metodo encargado de iniciar la vista de los articulos
	 */
	public function index() {
		$this->layout_sin_datos( 'articulo/ArticuloV', 'articulo/ArticuloS');
    }

    /***
	 * Metodo encargado de obtener una lista de articulos
	 */
	public function lista_json()
	{
		$lista = $this->ArticuloM->lista();
		$datos = array();
		$no = $_POST['start'];
		foreach ($lista as $articulo) {

			$cantidadArticuloEnCombos=$this->ComboDetalleM->obtener_cantidad_articulo_en_combos($articulo->ART_CODIGO);
			
			if ($articulo->ART_TIPO==UtilitarioM::ARTICULO) {
				$no++;
				
				$row = array();
				$row[] = $no;
				$row[] = $articulo->ART_NOMBRE;
				$row[] = $articulo->ART_SERIAL;
				$row[] = $articulo->CAT_NOMBRE;
				if($articulo->ART_STOCK>6) {
					$row[] = $articulo->ART_STOCK;
				}else
				{
					$row [] = '<b style="color:red;" title="La fecha para la entrega de esta Orden ha vencido">'.$articulo->ART_STOCK.'<i class="glyphicon glyphicon-exclamation-sign" title="EL articulo esta proximo a terminarse"></i></b>';
				}
				
				/*$row[] = $cantidadArticuloEnCombos->cantidad==null?0:$cantidadArticuloEnCombos->cantidad;
				$row[] = $articulo->ART_STOCK+$cantidadArticuloEnCombos->cantidad;*/
				$row[] = $articulo->ART_PRECIO_COMPRA;
				$row[] = $articulo->ART_PRECIO_VENTA;
				$row[] = $articulo->ART_DESCRIPCION;
		
				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="#" title="Editar" onclick="editar('."'".$articulo->ART_CODIGO."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
					<a class="btn btn-sm btn-danger" href="#" title="Hapus" onclick="eliminar('."'".$articulo->ART_CODIGO."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
				$datos[] = $row;
			} 
			
			
		}
	
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->ArticuloM->tamanio_lista(),
				"recordsFiltered" => $this->ArticuloM->tamanio_filtro(),
				"data" => $datos,
		);
		//output to json format
		echo json_encode($output);
    }
    
    /***
	 * Metodo encargado de guardar una categoria
	 */
	public function ajax_guardar() {
		
		
		if ($this->ArticuloM->existencia($this->input->post ( 'inpSerialArt' ))) {
			$data = array (
                    'CAT_CODIGO' => $this->input->post ( 'inpCatCodigo' ),
                    'ART_SERIAL' => strtoupper ($this->input->post ( 'inpSerialArt' )),
                    'ART_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreArt' )),
                    'ART_STOCK' => $this->input->post ( 'inpStockArt' ),
					'ART_DESCRIPCION' => strtoupper ($this->input->post ( 'inpDescripcion' )),
					'ART_TIPO' => UtilitarioM::ARTICULO,
					'ART_PRECIO_COMPRA' => $this->input->post ( 'inpPrecioCompraArt' ),
					'ART_PRECIO_VENTA' => $this->input->post ( 'inpPrecioVentaArt' ),
					'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
					'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'ART_ESTADO' => UtilitarioM::ACTIVO
			);
			$this->ArticuloM->modificar ( array ('ART_SERIAL' => $this->input->post ( 'inpSerialArt' ) ), $data );
			
		}else 
		{
			$this->_validar ();
			$data = array (
                    'CAT_CODIGO' => $this->input->post ( 'inpCatCodigo' ),
                    'ART_SERIAL' => strtoupper ($this->input->post ( 'inpSerialArt' )),
                    'ART_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreArt' )),
                    'ART_STOCK' => $this->input->post ( 'inpStockArt' ),
					'ART_DESCRIPCION' => strtoupper ($this->input->post ( 'inpDescripcion' )),
					'ART_TIPO' => UtilitarioM::ARTICULO,
					'ART_PRECIO_COMPRA' => $this->input->post ( 'inpPrecioCompraArt' ),
					'ART_PRECIO_VENTA' => $this->input->post ( 'inpPrecioVentaArt' ),
					'ART_USU_CODIGO_CREACION' => $this->session->userdata ( 'codigo' ),
					'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
					'ART_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual (),
					'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'ART_ESTADO' => UtilitarioM::ACTIVO
			);
			$insert = $this->ArticuloM->guardar ( $data );
			
			
		}
		
		echo json_encode ( array ("status" => TRUE,"datos"=>$data) );
    }


    	/***
	 * Metodo encargado de guardar el articulo editado
	 */
	public function ajax_modificar() {
		$this->_validar_modificar();
		$data = array (
                'CAT_CODIGO' => $this->input->post ( 'inpCatCodigo' ),
                'ART_SERIAL' => strtoupper ($this->input->post ( 'inpSerialArt' )),
                'ART_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreArt' )),
				'ART_STOCK' => $this->input->post ( 'inpStockArt' ),
				'ART_PRECIO_COMPRA' => $this->input->post ( 'inpPrecioCompraArt' ),
				'ART_PRECIO_VENTA' => $this->input->post ( 'inpPrecioVentaArt' ),
				'ART_DESCRIPCION' => strtoupper ($this->input->post ( 'inpDescripcion' )),
				'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
				'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
		);
		$this->ArticuloM->modificar ( array ('ART_CODIGO' => $this->input->post ( 'id' ) ), $data );
		echo json_encode ( array ("status" => TRUE ) );
	}

    	/***
	 * Metodo encargado de obtener los datos a editar los datos del articulo
	 */
	public function ajax_editar($codigo) {
		$data = $this->ArticuloM->obtener_por_codigo($codigo);
		
		echo json_encode($data);
    }
    
    /***
	 * Metodo encargado de eliminar la categoria
	 */
	public function ajax_eliminar($id) {
		if($this->ArticuloM->dependencia_combodet($id) ){
			
			echo json_encode(array("status" => FALSE));
			
		}else if($this->ArticuloM->dependencia_ingreso($id)){
			echo json_encode(array("status" => FALSE));
		}else if( $this->ArticuloM->dependencia_venta($id)){
			echo json_encode(array("status" => FALSE));
		}else {
			$data = array (
				'ART_ESTADO' => UtilitarioM::INACTIVO,
				'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
				'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
		);
		$this->ArticuloM->modificar ( array ('ART_CODIGO' => $id ), $data );
		echo json_encode ( array ("status" => TRUE ,"combodet"=>$this->ArticuloM->dependencia_combodet($id),"ingreso"=>$this->ArticuloM->dependencia_ingreso($id),"venta"=>$this->ArticuloM->dependencia_venta($id)) );
		}
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
		$this->form_validation->set_rules ( 'inpSerialArt', 'Código', 'trim|required|is_unique[articulo.ART_SERIAL]' );
        $this->form_validation->set_rules ( 'inpNombreArt', 'Nombre Artículo', 'trim|required' );
		$this->form_validation->set_rules ( 'inpStockArt', 'Stock', 'trim|required' );
		$this->form_validation->set_rules ( 'inpPrecioCompraArt', 'Stock', 'trim|required' );
		$this->form_validation->set_rules ( 'inpPrecioVentaArt', 'Stock', 'trim|required' );
        $this->form_validation->set_rules ( 'inpCatCodigo', 'Categoria', 'trim|required' );
        $this->form_validation->set_rules ( 'inpDescripcion', 'Descripcion', 'trim|required' );
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;

			
			if (form_error ( 'inpSerialArt' )) {
				$data ['inputerror'] [] = 'inpSerialArt';
				$data ['error_string'] [] = form_error ( 'inpSerialArt' );
            }
            
            if (form_error ( 'inpNombreArt' )) {
				$data ['inputerror'] [] = 'inpNombreArt';
				$data ['error_string'] [] = form_error ( 'inpNombreArt' );
            }
            
            if (form_error ( 'inpStockArt' )) {
				$data ['inputerror'] [] = 'inpStockArt';
				$data ['error_string'] [] = form_error ( 'inpStockArt' );
			}
			
			if (form_error ( 'inpPrecioCompraArt' )) {
				$data ['inputerror'] [] = 'inpPrecioCompraArt';
				$data ['error_string'] [] = form_error ( 'inpPrecioCompraArt' );
			}
			
			if (form_error ( 'inpPrecioVentaArt' )) {
				$data ['inputerror'] [] = 'inpPrecioVentaArt';
				$data ['error_string'] [] = form_error ( 'inpPrecioVentaArt' );
            }
            
            if (form_error ( 'inpCatCodigo' )) {
				$data ['inputerror'] [] = 'inpCatCodigo';
				$data ['error_string'] [] = form_error ( 'inpCatCodigo' );
            }
            
			
			if (form_error ( 'inpDescripcion' )) {
				$data ['inputerror'] [] = 'inpDescripcion';
				$data ['error_string'] [] = form_error ( 'inpDescripcion' );
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
		$this->form_validation->set_rules ( 'inpSerialArt', 'Código', 'trim|required' );
        $this->form_validation->set_rules ( 'inpNombreArt', 'Nombre Artículo', 'trim|required' );
		$this->form_validation->set_rules ( 'inpStockArt', 'Stock', 'trim|required' );
		$this->form_validation->set_rules ( 'inpPrecioCompraArt', 'Stock', 'trim|required' );
		$this->form_validation->set_rules ( 'inpPrecioVentaArt', 'Stock', 'trim|required' );
        $this->form_validation->set_rules ( 'inpCatCodigo', 'Categoria', 'trim|required' );
        $this->form_validation->set_rules ( 'inpDescripcion', 'Descripcion', 'trim|required' );
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;

			
			if (form_error ( 'inpSerialArt' )) {
				$data ['inputerror'] [] = 'inpSerialArt';
				$data ['error_string'] [] = form_error ( 'inpSerialArt' );
            }
            
            if (form_error ( 'inpNombreArt' )) {
				$data ['inputerror'] [] = 'inpNombreArt';
				$data ['error_string'] [] = form_error ( 'inpNombreArt' );
            }
            
            if (form_error ( 'inpStockArt' )) {
				$data ['inputerror'] [] = 'inpStockArt';
				$data ['error_string'] [] = form_error ( 'inpStockArt' );
			}
			
			if (form_error ( 'inpPrecioCompraArt' )) {
				$data ['inputerror'] [] = 'inpPrecioCompraArt';
				$data ['error_string'] [] = form_error ( 'inpPrecioCompraArt' );
			}
			
			if (form_error ( 'inpPrecioVentaArt' )) {
				$data ['inputerror'] [] = 'inpPrecioVentaArt';
				$data ['error_string'] [] = form_error ( 'inpPrecioVentaArt' );
            }
            
            if (form_error ( 'inpCatCodigo' )) {
				$data ['inputerror'] [] = 'inpCatCodigo';
				$data ['error_string'] [] = form_error ( 'inpCatCodigo' );
            }
            
			
			if (form_error ( 'inpDescripcion' )) {
				$data ['inputerror'] [] = 'inpDescripcion';
				$data ['error_string'] [] = form_error ( 'inpDescripcion' );
			}
		}
		
		if ($data ['status'] === FALSE) {
			echo json_encode ( $data );
			exit ();
		}
	}


	/***
	 * Metodo encargado de autocompletar el articulo dependiendo del parametro de busqueda
	 * @param unknown $parametro_busqueda
	 */
	public function autocompletar($parametro_busqueda){
		$lista = $this->ArticuloM->autocompletar($parametro_busqueda);
		$datos = array();
	
		foreach ($lista as $articulo) {
			$datos[] =array('codigo' => $articulo->ART_CODIGO, 'valor' => $articulo->ART_NOMBRE, 'datos' => $articulo);
		}
	
		echo json_encode($datos);
	}

	/***
	 * Metodo encargado de autocompletar el articulo dependiendo del parametro de busqueda
	 * @param unknown $parametro_busqueda
	 */
	public function autocompletar_venta($parametro_busqueda){
		$lista = $this->ArticuloM->autocompletar_venta($parametro_busqueda);
		$datos = array();
	
		foreach ($lista as $articulo) {
			$datos[] =array('codigo' => $articulo->ART_CODIGO, 'valor' => $articulo->ART_NOMBRE, 'datos' => $articulo);
		}
	
		echo json_encode($datos);
	}

    /***
	 * Metodo encargado de mostrar la vista del modulo de articulos y cargar scripts
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
	 * Metodo encargado de obtener y validar el stock
	 * @param unknown $codigo Codigo ARTICULO
	 * @param unknown $cantidadIngresada VALOS INGRESADO
	 */
	public function ajax_validar_stock($codigo,$cantidadIngresada) {
		$data = $this->ArticuloM->obtener_stock_por_codigo_articulo( $codigo );

		if($cantidadIngresada<=$data->ART_STOCK&&$cantidadIngresada>0){
			echo json_encode ( array ("existeStock" => TRUE) );
		}else{
			echo json_encode ( array ("existeStock" => FALSE) );
		}
	}

	public function ajax_obtener_precio_venta($codigoArticulo){
		$data = $this->ArticuloM->obtener_precio_venta($codigoArticulo);
		echo json_encode($data);
	}
	

	public function ajax_obtener_stock_actual($codigo) {
		$data = $this->ArticuloM->obtener_stock_por_codigo_articulo( $codigo );
		echo json_encode ( $data);
	}
}
?>