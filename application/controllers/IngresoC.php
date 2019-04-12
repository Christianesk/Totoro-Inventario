<?php
class IngresoC extends CI_Controller{
/**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
        $this->load->model('IngresoM');
		$this->load->model('IngresoDetalleM');
		$this->load->model('ArticuloM');
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
		$this->layout_sin_datos('ingreso/IngresoV', 'ingreso/IngresoS');
    }

    /***
	 * Metodo encargado de obtener una lista de combo
	 */
	public function lista_json()
	{
		$lista = $this->IngresoM->lista();
		$datos = array();
		$no = $_POST['start'];
		foreach ($lista as $ingreso) {

          
                $no++;
                    
                $row = array();
                $row[] = $no;
                $row[] = $ingreso->ING_FECHA_HORA;
                $row[] = $ingreso->PER_NOMBRE;
                $row[] = $this->UtilitarioM->tipoComprobante($ingreso->ING_TIPO_COMPROBANTE).': '.$ingreso->ING_NUMERO_COMPROBANTE;
                $row[] = '$'.$ingreso->ING_IMPUESTO;
                $row[] = '$'.$ingreso->ING_TOTAL;
				/*$row[] = $this->UtilitarioM->estado($ingreso->ING_ESTADO);*/

                //add html for action
                $row[] = '<a class="btn btn-sm btn-primary" href="#" title="Ver Detalle" onclick="editar('."'".$ingreso->ING_CODIGO."'".')"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a class="btn btn-sm btn-danger" href="#" title="Hapus" onclick="eliminar('."'".$ingreso->ING_CODIGO."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
        
                $datos[] = $row;
 
		}
	
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->IngresoM->tamanio_lista(),
				"recordsFiltered" => $this->IngresoM->tamanio_filtro(),
				"data" => $datos,
		);
		//output to json format
		echo json_encode($output);
	}
	
	public function ajax_guardar()
	{

		$listaIngresoDetalle = json_decode ( $this->input->post ( 'listaIngresoDetalle' ));
		$tamanioLista=sizeof($listaIngresoDetalle);
		if ($listaIngresoDetalle!=null||$tamanioLista!=0) {
			$this->_validar();

			$data = array(
					'PER_CODIGO' => $this->input->post('inpProCodigo'),
					'ING_TIPO_COMPROBANTE' => $this->input->post('selTipoComprobante'),
					'ING_NUMERO_COMPROBANTE' => $this->input->post('inpNumIngreso'),
					'ING_FECHA_HORA' => $this->UtilitarioM->fecha_hora_actual(),
					'ING_IMPUESTO' => $this->input->post('inpImpIngreso'),
					'ING_TOTAL' => $this->input->post('inpTotal'),
					'ING_ESTADO_ING' => '',
					'ING_USU_CODIGO_CREACION' => $this->session->userdata('codigo'),
					'ING_USU_CODIGO_MODIFICACION' => $this->session->userdata('codigo'),
					'ING_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual() ,
					'ING_FECHA_MODIFICACION'  => $this->UtilitarioM->fecha_hora_actual(),
					'ING_ESTADO' => UtilitarioM::ACTIVO,
			);
			$insertIngreso = $this->IngresoM->guardar($data);
			
			foreach ( $listaIngresoDetalle as $ingresoDetalle ) {
			
				$dataIngresoDetalle=array(
						'ING_CODIGO' => $insertIngreso,
						'ART_CODIGO' => $ingresoDetalle->codigoArticulo,
						'IND_CANTIDAD'  => $ingresoDetalle->cantidadArticulo,
						'IND_PRECIO_COMPRA'  => $ingresoDetalle->precioCompraArticulo,
						'IND_PRECIO_VENTA'  => $ingresoDetalle->precioVentaArticulo,
						'IND_USU_CODIGO_CREACION' => $this->session->userdata('codigo'),
						'IND_USU_CODIGO_MODIFICACION' => $this->session->userdata('codigo'),
						'IND_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual() ,
						'IND_FECHA_MODIFICACION'  => $this->UtilitarioM->fecha_hora_actual(),
						'IND_ESTADO' => UtilitarioM::ACTIVO
				);
			
				$this->IngresoDetalleM->guardar($dataIngresoDetalle);


				$stockActualArticulo=$this->ArticuloM->obtener_por_codigo($ingresoDetalle->codigoArticulo);
				$dataArticulo = array (
					'ART_STOCK' => $stockActualArticulo->ART_STOCK+$ingresoDetalle->cantidadArticulo,
					'ART_PRECIO_COMPRA'  => $ingresoDetalle->precioCompraArticulo,
					'ART_PRECIO_VENTA'  => $ingresoDetalle->precioVentaArticulo,
					'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
				);
				$this->ArticuloM->modificar ( array ('ART_CODIGO' => $ingresoDetalle->codigoArticulo), $dataArticulo );
			
			}

			echo json_encode(array("status" => TRUE));
		}else {
			if ($listaIngresoDetalle!=null||$tamanioLista!=0) {
				echo json_encode(array("ingresoDet" => TRUE));
			}
			
		}
		
	}

	public function ajax_editar($codigo) {
		$data = array(
				$this->IngresoM->obtener_por_codigo($codigo),
				$this->IngresoDetalleM->obtener_por_codigo_ingreso($codigo)
		);
		
		
		echo json_encode($data);
	}

	public function ajax_obtener_precio_venta($codigoArticulo){
		$data = $this->IngresoDetalleM->obtener_ultimo_registro_por_codigo_articulo($codigoArticulo);
		echo json_encode($data);
	}

	public function ajax_eliminar($id)
	{
		
		$data = array(
			'ING_USU_CODIGO_MODIFICACION' => $this->session->userdata('codigo'),
			'ING_FECHA_MODIFICACION'  => $this->UtilitarioM->fecha_hora_actual(),
			'ING_ESTADO' => UtilitarioM::INACTIVO,
		);
		$this->IngresoM->modificar(array('ING_CODIGO' => $id), $data);
		
		$dataIngDet=array(
			'IND_USU_CODIGO_MODIFICACION' => $this->session->userdata('codigo'),
			'IND_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual() ,
			'IND_FECHA_MODIFICACION'  => $this->UtilitarioM->fecha_hora_actual(),
			'IND_ESTADO' => UtilitarioM::INACTIVO
		);	
		$this->IngresoDetalleM->modificar(array('ING_CODIGO' => $id), $dataIngDet);

		$listaIngresoDetalle =$this->IngresoDetalleM->obtener_por_codigo_ingreso($id);

        foreach ($listaIngresoDetalle as $ingresoDetalle) {
                $stockActualArticulo=$this->ArticuloM->obtener_por_codigo($ingresoDetalle->ART_CODIGO);
				$dataArticulo = array (
					'ART_STOCK' => $stockActualArticulo->ART_STOCK-$ingresoDetalle->IND_CANTIDAD,
					'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
				);
				$this->ArticuloM->modificar ( array ('ART_CODIGO' => $ingresoDetalle->ART_CODIGO), $dataArticulo );
        }

		echo json_encode(array("status" => TRUE));
	}




	private function _validar() {
		$data = array ();
		$data ['error_string'] = array ();
		$data ['inputerror'] = array ();
		$data ['status'] = TRUE;
		
		$this->form_validation->set_error_delimiters ( "", "" );
		$this->form_validation->set_rules ( 'inpProCodigo', 'Proveedor', 'trim|required' );
		$this->form_validation->set_rules ( 'inpNumIngreso', 'Número', 'trim|required' );
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;
			
			if (form_error ( 'inpProCodigo' )) {
				$data ['inputerror'] [] = 'inpProCodigo';
				$data ['error_string'] [] = form_error ( 'inpProCodigo' );
			}
			
			
			if (form_error ( 'inpNumIngreso' )) {
				$data ['inputerror'] [] = 'inpNumIngreso';
				$data ['error_string'] [] = form_error ( 'inpNumIngreso' );
			}
		}
		
		if ($data ['status'] === FALSE) {
			echo json_encode ( $data );
			exit ();
		}
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
	

	public function ajax_eliminar_detalle_ingreso($elemento)
	{
		$dataIngresoDetalle=array(
				'ING_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual(),
				'ING_ESTADO' => UtilitarioM::INACTIVO,
		);
			
		$this->IngresoDetalleM->modificar(array('VED_CODIGO' => $elemento), $dataIngresoDetalle);
		echo json_encode(array("status" => TRUE));
	}
}
?>