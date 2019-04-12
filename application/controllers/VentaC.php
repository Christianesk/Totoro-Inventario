<?php
class VentaC extends CI_Controller{
/**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
        $this->load->model('VentaM');
		$this->load->model('VentaDetalleM');
		$this->load->model('IngresoDetalleM');
		$this->load->model('ComboDetalleM');
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
		$this->layout_sin_datos('venta/VentaV', 'venta/VentaS');
    }

    /***
	 * Metodo encargado de obtener una lista de combo
	 */
	public function lista_json()
	{
		$lista = $this->VentaM->lista();
		$datos = array();
		$no = $_POST['start'];
		foreach ($lista as $venta) {

          
                $no++;
                    
                $row = array();
                $row[] = $no;
                $row[] = $venta->VEN_FECHA_HORA;
                $row[] = $venta->PER_NOMBRE;
                $row[] = $this->UtilitarioM->tipoComprobante($venta->VEN_TIPO_COMPROBANTE).': '.$venta->VEN_NUM_COMPROBANTE;
                $row[] = '$'.$venta->VEN_IMPUESTO;
				$row[] = '$'.$venta->VEN_TOTAL;
				$row[] = '$'.$venta->VEN_GANANCIA;
				

                //add html for action
                $row[] = '<a class="btn btn-sm btn-primary" href="#" title="Ver Detalle" onclick="editar('."'".$venta->VEN_CODIGO."'".')"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a class="btn btn-sm btn-danger" href="#" title="Hapus" onclick="eliminar('."'".$venta->VEN_CODIGO."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
        
                $datos[] = $row;
 
		}
	
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->VentaM->tamanio_lista(),
				"recordsFiltered" => $this->VentaM->tamanio_filtro(),
				"data" => $datos,
		);
		//output to json format
		echo json_encode($output);
	}
	
	public function ajax_guardar()
	{

		$listaVentaDetalle = json_decode ( $this->input->post ( 'listaVentaDetalle' ));
		$tamanioLista=sizeof($listaVentaDetalle);
		if ($listaVentaDetalle!=null||$tamanioLista!=0) {
			$this->_validar();

			$data = array(
                    'PER_CODIGO' => $this->input->post('inpCliCodigo'),
                    'USU_CODIGO' => $this->session->userdata('codigo'),
					'VEN_TIPO_COMPROBANTE' => $this->input->post('selTipoComprobante'),
					'VEN_NUM_COMPROBANTE' => $this->input->post('inpNumVenta'),
					'VEN_FECHA_HORA' => $this->UtilitarioM->fecha_hora_actual(),
					'VEN_IMPUESTO' => $this->input->post('inpImpVenta'),
					'VEN_TOTAL' => $this->input->post('inpTotal'),
					'VEN_ESTADO_VENTA' => '',
					'VEN_USU_CODIGO_CREACION' => $this->session->userdata('codigo'),
					'VEN_USU_CODIGO_MODIFICACION' => $this->session->userdata('codigo'),
					'VEN_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual() ,
					'VEN_FECHA_MODIFICACION'  => $this->UtilitarioM->fecha_hora_actual(),
					'VEN_ESTADO' => UtilitarioM::ACTIVO,
			);
			$insertVenta = $this->VentaM->guardar($data);
			
			$ganaciaTotal=0;

			foreach ( $listaVentaDetalle as $ventaDetalle ) {
			
			$precioCompra= $this->ArticuloM->obtener_precio_compra($ventaDetalle->codigoArticulo);
			$gananciaPorArticulo=($ventaDetalle->precioVentaArticulo*$ventaDetalle->cantidadArticulo)-($precioCompra->ART_PRECIO_COMPRA*$ventaDetalle->cantidadArticulo);
			
				$dataVentaDetalle=array(
						'VEN_CODIGO' => $insertVenta,
						'ART_CODIGO' => $ventaDetalle->codigoArticulo,
						'VED_CANTIDAD'  => $ventaDetalle->cantidadArticulo,
						'VED_PRECIO_VENTA'  => $ventaDetalle->precioVentaArticulo,
						'VED_GAN_POR_ART'  => $gananciaPorArticulo,
						'VED_USU_CODIGO_CREACION' => $this->session->userdata('codigo'),
						'VED_USU_CODIGO_MODIFICACION' => $this->session->userdata('codigo'),
						'VED_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual() ,
						'VED_FECHA_MODIFICACION'  => $this->UtilitarioM->fecha_hora_actual(),
						'VED_ESTADO' => UtilitarioM::ACTIVO
				);
			
				$this->VentaDetalleM->guardar($dataVentaDetalle);

				$ganaciaTotal+=$gananciaPorArticulo;
				

				$stockActualArticulo=$this->ArticuloM->obtener_por_codigo($ventaDetalle->codigoArticulo);
				$dataArticulo = array (
					'ART_STOCK' => $stockActualArticulo->ART_STOCK-$ventaDetalle->cantidadArticulo,
					'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
				);
				$this->ArticuloM->modificar ( array ('ART_CODIGO' => $ventaDetalle->codigoArticulo), $dataArticulo );

				$listaComboDet =$this->ComboDetalleM->obtener_lista_por_codigo_combo($ventaDetalle->codigoArticulo);
				foreach ( $listaComboDet as $comboDetalle ) {
					$dataComDet = array (
						'COD_ART_STOCK' => $stockActualArticulo->ART_STOCK-$ventaDetalle->cantidadArticulo,
						'COD_CANTIDAD_POR_STOCK' => ($stockActualArticulo->ART_STOCK-$ventaDetalle->cantidadArticulo)*$comboDetalle->COD_CANTIDAD,
					);
					$this->ComboDetalleM->modificar ( array ('COD_CODIGO' => $comboDetalle->COD_CODIGO), $dataComDet );
				}
			}

			$dataVenta= array(
				'VEN_GANANCIA' => $ganaciaTotal,
			);
			$this->VentaM->modificar ( array ('VEN_CODIGO' => $insertVenta), $dataVenta );

			echo json_encode(array("status" => TRUE));
		}else {
			if ($listaVentaDetalle!=null||$tamanioLista!=0) {
				echo json_encode(array("ventaDet" => TRUE));
			}
			
		}
		
	}

	public function ajax_editar($codigo) {
		$data = array(
				$this->VentaM->obtener_por_codigo($codigo),
				$this->VentaDetalleM->obtener_por_codigo_venta($codigo)
		);
		
		
		echo json_encode($data);
	}

	public function ajax_eliminar($id)
	{
		
		$data = array(
			'VEN_USU_CODIGO_MODIFICACION' => $this->session->userdata('codigo'),
			'VEN_FECHA_MODIFICACION'  => $this->UtilitarioM->fecha_hora_actual(),
			'VEN_ESTADO' => UtilitarioM::INACTIVO,
		);
		$this->VentaM->modificar(array('VEN_CODIGO' => $id), $data);
		
		$dataVenDet=array(
			'VED_USU_CODIGO_MODIFICACION' => $this->session->userdata('codigo'),
			'VED_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual() ,
			'VED_FECHA_MODIFICACION'  => $this->UtilitarioM->fecha_hora_actual(),
			'VED_ESTADO' => UtilitarioM::INACTIVO
		);	
        $this->VentaDetalleM->modificar(array('VEN_CODIGO' => $id), $dataVenDet);
        

        $listaVentaDetalle =$this->VentaDetalleM->obtener_por_codigo_venta($id);

        foreach ($listaVentaDetalle as $ventaDetalle) {
                $stockActualArticulo=$this->ArticuloM->obtener_por_codigo($ventaDetalle->ART_CODIGO);
				$dataArticulo = array (
					'ART_STOCK' => $stockActualArticulo->ART_STOCK+$ventaDetalle->VED_CANTIDAD,
					'ART_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
					'ART_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ) 
				);
				$this->ArticuloM->modificar ( array ('ART_CODIGO' => $ventaDetalle->ART_CODIGO), $dataArticulo );
        }

		echo json_encode(array("status" => TRUE));
	}




	private function _validar() {
		$data = array ();
		$data ['error_string'] = array ();
		$data ['inputerror'] = array ();
		$data ['status'] = TRUE;
		
		$this->form_validation->set_error_delimiters ( "", "" );
		$this->form_validation->set_rules ( 'inpCliCodigo', 'Cliente', 'trim|required' );
		$this->form_validation->set_rules ( 'inpNumVenta', 'Número', 'trim|required' );
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;
			
			if (form_error ( 'inpCliCodigo' )) {
				$data ['inputerror'] [] = 'inpCliCodigo';
				$data ['error_string'] [] = form_error ( 'inpCliCodigo' );
			}
			
			
			if (form_error ( 'inpNumVenta' )) {
				$data ['inputerror'] [] = 'inpNumVenta';
				$data ['error_string'] [] = form_error ( 'inpNumVenta' );
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
	

	public function ajax_eliminar_detalle_venta($elemento)
	{
		$dataVentaDetalle=array(
				'VED_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual(),
				'VED_ESTADO' => UtilitarioM::INACTIVO,
		);
			
		$this->VentaDetalleM->modificar(array('VED_CODIGO' => $elemento), $dataVentaDetalle);
		echo json_encode(array("status" => TRUE));
	}
}
?>