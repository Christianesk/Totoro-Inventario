<?php
class ComboC extends CI_Controller{
    /**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
        $this->load->model('ArticuloM');
        $this->load->model('ComboDetalleM');
		$this->load->model('UtilitarioM');
		$this->load->library('form_validation');
		if (!$this->session->userdata ( 'codigo' )) {
			redirect('CerrarSesion');
		}
    } 
    
    /***
	 * Metodo encargado de iniciar la vista de los combos
	 */
	public function index() {
		$this->layout_sin_datos('combo/ComboV', 'combo/ComboS');
    }

    /***
	 * Metodo encargado de obtener una lista de combo
	 */
	public function lista_json()
	{
		$lista = $this->ArticuloM->lista();
		$datos = array();
		$no = $_POST['start'];
		foreach ($lista as $combo) {
            if ($combo->ART_TIPO==UtilitarioM::COMBO && $combo->ART_ESTADO!=0) {

                $listaComboDetalle = $this->ComboDetalleM->obtener_por_codigo_combo($combo->ART_CODIGO);
            
                $comboDetalleCadena = '';

                foreach ($listaComboDetalle as $comboDetalle) {
                    $comboDetalleCadena .= $comboDetalle -> ART_NOMBRE .' - Cant: '.$comboDetalle -> COD_CANTIDAD. '<br>';
                }
                
                $no++;
                    
                $row = array();
                $row[] = $no;
                $row[] = $combo->ART_NOMBRE;
                $row[] = $comboDetalleCadena;
                $row[] = $combo->ART_DESCRIPCION;
                $row[] = $combo->ART_STOCK;
                $row[] = $combo->ART_COMBO_PVP;
                $row[] = $this->UtilitarioM->estado($combo->ART_ESTADO);
        
                //add html for action
                $row[] = '<a class="btn btn-sm btn-primary" href="#" title="Editar" onclick="editar('."'".$combo->ART_CODIGO."'".')"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a class="btn btn-sm btn-danger" href="#" title="Hapus" onclick="eliminar('."'".$combo->ART_CODIGO."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
        
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


    public function ajax_guardar()
	{
	
		$listaComboDetalle = json_decode ( $this->input->post ( 'listaComboDetalle' ) );
        
		$this->_validar();
		$dataCombo = array(
            'CAT_CODIGO' => UtilitarioM::CATEGORIA_COMBO,
            'ART_SERIAL' => strtoupper ($this->input->post ( 'inpSerialCom' )),
            'ART_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreCombo' )),
            'ART_STOCK' => $this->input->post ( 'inpStockCom' ),
            'ART_DESCRIPCION' => strtoupper ($this->input->post ( 'inpDescripcionCombo' )),
            'ART_TIPO' => UtilitarioM::COMBO,
            'ART_COMBO_PVP' => $this->input->post ( 'inpPrecioVentaCombo' ),
            'ART_USU_CODIGO_CREACION' => $this->session->userdata ( 'codigo' ),
            'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
            'ART_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual (),
            'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
            'ART_ESTADO' => UtilitarioM::ACTIVO         
		);
		$insertCombo = $this->ArticuloM->guardar($dataCombo);
	
		foreach ( $listaComboDetalle as $combo ) {
				
			$dataComboDetalle=array(
					'COD_TAB_ART' => $insertCombo,
					'COD_ART_CODIGO' => $combo->codigoArticulo,
					'COD_CANTIDAD' => $combo->cantidadArticulo,
					'COD_ART_STOCK' => $this->input->post ( 'inpStockCom' ),
					'COD_CANTIDAD_POR_STOCK' => $this->input->post ( 'inpStockCom' )*$combo->cantidadArticulo,
					'COD_ESTADO' => UtilitarioM::ACTIVO
			);
				
            $this->ComboDetalleM->guardar($dataComboDetalle);
			
			$stockActualArticulo=$this->ArticuloM->obtener_por_codigo($combo->codigoArticulo);
				$dataArticulo = array (
					'ART_STOCK' => $stockActualArticulo->ART_STOCK-($this->input->post ( 'inpStockCom' )*$combo->cantidadArticulo),
					'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
				);
				$this->ArticuloM->modificar ( array ('ART_CODIGO' => $combo->codigoArticulo), $dataArticulo );

           
				
		}

		echo json_encode(array("status" => TRUE));
    }
    

    public function ajax_modificar(){
		$listaComboDetalle = json_decode ( $this->input->post ( 'listaComboDetalle' ) );
		
		$this->_validar();
		
		$data = array(
            'ART_SERIAL' => strtoupper ($this->input->post ( 'inpSerialCom' )),
            'ART_NOMBRE' => strtoupper ($this->input->post ( 'inpNombreCombo' )),
            'ART_STOCK' => $this->input->post ( 'inpStockCom' ),
            'ART_DESCRIPCION' => strtoupper ($this->input->post ( 'inpDescripcionCombo' )),
            'ART_COMBO_PVP' => $this->input->post ( 'inpPrecioVentaCombo' ),
            'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
            'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual ()       
		);
		$this->ArticuloM->modificar(array('ART_CODIGO' => $this->input->post('id')), $data);
	
		/*foreach ( $listaComboDetalle as $combo ) {
				
	
			if($combo->idCom==0) {
				$dataComboDetalle=array(
					'COD_TAB_ART' => $this->input->post('id'),
					'COD_ART_CODIGO' => $combo->codigoArticulo,
					'COD_CANTIDAD' => $combo->cantidadArticulo,
					'COD_ESTADO' => UtilitarioM::ACTIVO
                );
                    
                $this->ComboDetalleM->guardar($dataComboDetalle);

			}else {

				$dataComboDetalleModificar=array(
                    'COD_TAB_ART' => $this->input->post('id'),
					'COD_ART_CODIGO' => $combo->codigoArticulo,
					'COD_CANTIDAD' => $combo->cantidadArticulo,
				);
                $this->ComboDetalleM->modificar(array('COD_CODIGO' => $combo->idCom), $dataComboDetalleModificar);
			}
	
		}*/
		

		echo json_encode(array("status" => TRUE));
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
		
        $this->form_validation->set_rules ( 'inpNombreCombo', 'Nombre del Combo', 'trim|required' );
        $this->form_validation->set_rules ( 'inpStockCom', 'Stock', 'trim|required' );
        $this->form_validation->set_rules ( 'inpSerialCom', 'Codigo ', 'trim|required' );
        $this->form_validation->set_rules ( 'inpPrecioVentaCombo', 'Precio de venta', 'trim|required' );
        $this->form_validation->set_rules ( 'inpDescripcionCombo', 'Descripción', 'trim|required' );
        
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;

			
			if (form_error ( 'inpNombreCombo' )) {
				$data ['inputerror'] [] = 'inpNombreCombo';
				$data ['error_string'] [] = form_error ( 'inpNombreCombo' );
            }

            if (form_error ( 'inpStockCom' )) {
				$data ['inputerror'] [] = 'inpStockCom';
				$data ['error_string'] [] = form_error ( 'inpStockCom' );
            }

            if (form_error ( 'inpSerialCom' )) {
				$data ['inputerror'] [] = 'inpSerialCom';
				$data ['error_string'] [] = form_error ( 'inpSerialCom' );
            }
            
            if (form_error ( 'inpPrecioVentaCombo' )) {
				$data ['inputerror'] [] = 'inpPrecioVentaCombo';
				$data ['error_string'] [] = form_error ( 'inpPrecioVentaCombo' );
            }
            
            if (form_error ( 'inpDescripcionCombo' )) {
				$data ['inputerror'] [] = 'inpDescripcionCombo';
				$data ['error_string'] [] = form_error ( 'inpDescripcionCombo' );
            }
            
		}
		
		if ($data ['status'] === FALSE) {
			echo json_encode ( $data );
			exit ();
		}
    }

    public function ajax_editar($codigo) {
		$data = array(
				$this->ArticuloM->obtener_por_codigo($codigo),
				$this->ComboDetalleM->obtener_por_codigo_combo($codigo)
		);
		
		
		echo json_encode($data);
    }
    

    public function ajax_eliminar_articulo($elemento)
	{
		$dataComboDetalle=array(
            'COD_ESTADO' => 0
        );
			
		$this->ComboDetalleM->modificar(array('COD_CODIGO' => $elemento), $dataComboDetalle);
		echo json_encode(array("status" => TRUE));
	}


    /***
	 * Metodo encargado de mostrar la vista del modulo de combo y cargar scripts
	 * @param  $contenido - direccion de la vista del modulo
	 * @param  $script - direccion del script del modulo
	 */
	public function layout_sin_datos($contenido,$script){

		$this->load->view('plantillaAdmin/inicio');
        $this->load->view($contenido);
		$this->load->view('plantillaAdmin/fin');
		$this->load->view($script);
    }
    

    public function ajax_eliminar($id)
	{	if ($this->ArticuloM->dependencia_combo ( $id )) {
			echo json_encode ( array ("status" => FALSE ) );
        } 
        else 
        {
            $data = array(
                'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
                'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
                'ART_ESTADO'=>UtilitarioM::INACTIVO      
            );
			$this->ArticuloM->modificar(array('ART_CODIGO' => $id), $data);
			
			$listaComboDetalle= $this->ComboDetalleM->obtener_lista_por_codigo_combo($id);

			foreach ( $listaComboDetalle as $comboDetalle ) {
				
				$stockActualArticulo=$this->ArticuloM->obtener_por_codigo($comboDetalle->COD_ART_CODIGO);
					$dataArticulo = array (
						'ART_STOCK' => $stockActualArticulo->ART_STOCK+($comboDetalle->COD_ART_STOCK*$comboDetalle->COD_CANTIDAD),
						'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
						'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
					);
					$this->ArticuloM->modificar ( array ('ART_CODIGO' => $comboDetalle->COD_ART_CODIGO), $dataArticulo );
	
			   
					
			}
                
            $dataComboDetalle=array(
                'COD_ESTADO' => UtilitarioM::INACTIVO
            );
                
            $this->ComboDetalleM->modificar(array('COD_TAB_ART' => $id), $dataComboDetalle);
        
            
            echo json_encode(array("status" => TRUE));}

	}
}
?>