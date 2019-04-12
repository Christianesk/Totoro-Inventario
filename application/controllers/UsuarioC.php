<?php
class UsuarioC extends CI_Controller{
/**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('UsuarioM');
		$this->load->model('UtilitarioM');
		$this->load->library('form_validation');
		if (!$this->session->userdata ( 'codigo' )) {
			redirect('CerrarSesion');
		}
    }
    
    /***
	 * Metodo encargado de iniciar la vista de los usuarios
	 */
	public function index() {
		$this->layout_sin_datos( 'usuario/UsuarioV', 'usuario/UsuarioS');
    }
    
    /***
	 * Metodo encargado de obtener una lista de usuarios
	 */
	public function lista_json()
	{
		$lista = $this->UsuarioM->lista();
		$datos = array();
		$no = $_POST['start'];
		foreach ($lista as $usuario) {

                $no++;
				
                $row = array();
                $row[] = $no;
                $row[] = $usuario->USU_NOMBRE_EMP.' '.$usuario->USU_APELLIDO_EMP;
                $row[] = $usuario->USU_NOMBRE;
                $row[] = $this->UtilitarioM->tipoUsuario($usuario->USU_TIPO);

        
                //add html for action
                $row[] = '<a class="btn btn-sm btn-primary" href="#" title="Editar" onclick="editar('."'".$usuario->USU_CODIGO."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
                    <a class="btn btn-sm btn-danger" href="#" title="Hapus" onclick="eliminar('."'".$usuario->USU_CODIGO."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
        
                $datos[] = $row;

            
			
			
		}
	
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->UsuarioM->tamanio_lista(),
				"recordsFiltered" => $this->UsuarioM->tamanio_filtro(),
				"data" => $datos,
		);
		//output to json format
		echo json_encode($output);
    }    


    /***
	 * Metodo encargado de guardar un usuario
	 */
	public function ajax_guardar() {
		
		if ($this->input->post ( 'inpContrasenaUsuario' )==$this->input->post ( 'inpVerfContraUsuario' )&&$this->input->post('selTipoIdeUsuario')!=0) {
            if ($this->UsuarioM->existencia($this->input->post ( 'inpNombreUsuario' ))) {
                $data = array (
                        'USU_NOMBRE_EMP' => strtoupper ($this->input->post ( 'inpNombreEmpUsuario' )),
                        'USU_APELLIDO_EMP' => strtoupper ($this->input->post ( 'inpApellidoEmpUsuario' )),
                        'USU_NOMBRE' => $this->input->post ( 'inpNombreUsuario' ),
                        'USU_CONTRASENA' => sha1($this->input->post ( 'inpContrasenaUsuario' )),
                        'USU_TIPO' => $this->input->post ( 'selTipoIdeUsuario' ),
                        'USU_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
                        'USU_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
                        'USU_ESTADO' => UtilitarioM::ACTIVO
                );
                $this->UsuarioM->modificar ( array ('USU_NOMBRE' => $this->input->post ( 'inpNombreUsuario' )), $data );
                echo json_encode ( array ("status" => TRUE) );
            }else 
            {
                $this->_validar ();
                $data = array (
                        'USU_NOMBRE_EMP' => strtoupper ($this->input->post ( 'inpNombreEmpUsuario' )),
                        'USU_APELLIDO_EMP' => strtoupper ($this->input->post ( 'inpApellidoEmpUsuario' )),
                        'USU_NOMBRE' => $this->input->post ( 'inpNombreUsuario' ),
                        'USU_CONTRASENA' => sha1($this->input->post ( 'inpContrasenaUsuario' )),
                        'USU_TIPO' => $this->input->post ( 'selTipoIdeUsuario' ),
                        'USU_USU_CODIGO_CREACION' => $this->session->userdata ( 'codigo' ),
                        'USU_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
                        'USU_FECHA_CREACION' => $this->UtilitarioM->fecha_hora_actual (),
                        'USU_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
                        'USU_ESTADO' => UtilitarioM::ACTIVO
                );
                $insert = $this->UsuarioM->guardar ( $data );
                
                echo json_encode ( array ("status" => TRUE) );
            }
        } else {
            if ($this->input->post('inpContrasenaUsuario')!=$this->input->post('inpVerfContraUsuario')&&$this->input->post('selTipoIdeUsuario')==0){
				echo json_encode ( array ("contrasena" => TRUE,"selError" => TRUE) );
			}elseif ($this->input->post('inpContrasenaUsuario')!=$this->input->post('inpVerfContraUsuario'))
			{
				echo json_encode ( array ("contrasena" => TRUE,"selError" => FALSE) );
			}elseif ($this->input->post('selTipoIdeUsuario')==0){
				echo json_encode ( array ("contrasena" => FALSE,"selError" => TRUE) );
			}
        }
        
		
		
		
    }


    /***
	 * Metodo encargado de guardar el usuario editado
	 */
	public function ajax_modificar() {
        if($this->input->post ( 'inpContrasenaUsuario' )==$this->input->post ( 'inpVerfContraUsuario' )&&$this->input->post('selTipoIdeUsuario')!=0){
            $this->_validar_modificar();
            $data = array (
                        'USU_NOMBRE_EMP' => strtoupper ($this->input->post ( 'inpNombreEmpUsuario' )),
                        'USU_APELLIDO_EMP' => strtoupper ($this->input->post ( 'inpApellidoEmpUsuario' )),
                        'USU_NOMBRE' => $this->input->post ( 'inpNombreUsuario' ),
                        'USU_CONTRASENA' => sha1($this->input->post ( 'inpContrasenaUsuario' )),
                        'USU_TIPO' => $this->input->post ( 'selTipoIdeUsuario' ),
                        'USU_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
                        'USU_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual ()
            );
            $this->UsuarioM->modificar ( array ('USU_CODIGO' => $this->input->post ( 'id' ) 
            ), $data );
            echo json_encode ( array ("status" => TRUE ) );
        } else {
            if ($this->input->post('inpContrasenaUsuario')!=$this->input->post('inpVerfContraUsuario')&&$this->input->post('tipoRol')==0){
                echo json_encode ( array ("contrasena" => TRUE,"selError" => TRUE) );
            }elseif ($this->input->post('inpContrasenaUsuario')!=$this->input->post('inpVerfContraUsuario'))
            {
                echo json_encode ( array ("contrasena" => TRUE,"selError" => FALSE) );
            }elseif ($this->input->post('selTipoIdeUsuario')==0){
                echo json_encode ( array ("contrasena" => FALSE,"selError" => TRUE) );
            }
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
		$this->form_validation->set_rules ( 'inpNombreUsuario', 'Nombre Usuario', 'trim|required|is_unique[usuario.USU_NOMBRE]' );
		$this->form_validation->set_rules ( 'inpNombreEmpUsuario', 'Nombre Empleado', 'trim|required' );
		$this->form_validation->set_rules ( 'inpApellidoEmpUsuario', 'Apellido Empleado', 'trim|required' );
        $this->form_validation->set_rules ( 'inpContrasenaUsuario', 'Contraseña', 'trim|required' );
        $this->form_validation->set_rules ( 'inpVerfContraUsuario', 'Verificación contraseña', 'trim|required' );
		//$this->form_validation->set_rules('inpEmail', 'Correo Electrónico', 'trim|required|valid_email|is_unique[CLIENTE.CLI_EMAIL]');
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;
			
			if (form_error ( 'inpNombreUsuario' )) {
				$data ['inputerror'] [] = 'inpNombreUsuario';
				$data ['error_string'] [] = form_error ( 'inpNombreUsuario' );
			}
			
			if (form_error ( 'inpNombreEmpUsuario' )) {
				$data ['inputerror'] [] = 'inpNombreEmpUsuario';
				$data ['error_string'] [] = form_error ( 'inpNombreEmpUsuario' );
			}
			
			if (form_error ( 'inpApellidoEmpUsuario' )) {
				$data ['inputerror'] [] = 'inpApellidoEmpUsuario';
				$data ['error_string'] [] = form_error ( 'inpApellidoEmpUsuario' );
			}
			
			if (form_error ( 'inpContrasenaUsuario' )) {
				$data ['inputerror'] [] = 'inpContrasenaUsuario';
				$data ['error_string'] [] = form_error ( 'inpContrasenaUsuario' );
            }
            
            if (form_error ( 'inpVerfContraUsuario' )) {
				$data ['inputerror'] [] = 'inpVerfContraUsuario';
				$data ['error_string'] [] = form_error ( 'inpVerfContraUsuario' );
			}

		}
		
		if ($data ['status'] === FALSE) {
			echo json_encode ( $data );
			exit ();
		}
    }


    /***
	 * Metodo encargado de validar el correcto ingreso de datos 
	 */
	private function _validar_modificar() {
		$data = array ();
		$data ['error_string'] = array ();
		$data ['inputerror'] = array ();
		$data ['status'] = TRUE;
		
		$this->form_validation->set_error_delimiters ( "", "" );
		$this->form_validation->set_rules ( 'inpNombreUsuario', 'Nombre Usuario', 'trim|required' );
		$this->form_validation->set_rules ( 'inpNombreEmpUsuario', 'Nombre Empleado', 'trim|required' );
		$this->form_validation->set_rules ( 'inpApellidoEmpUsuario', 'Apellido Empleado', 'trim|required' );
        $this->form_validation->set_rules ( 'inpContrasenaUsuario', 'Contraseña', 'trim|required' );
        $this->form_validation->set_rules ( 'inpVerfContraUsuario', 'Verificación contraseña', 'trim|required' );
		//$this->form_validation->set_rules('inpEmail', 'Correo Electrónico', 'trim|required|valid_email|is_unique[CLIENTE.CLI_EMAIL]');
		
		if ($this->form_validation->run ()) {
			$data ['status'] = TRUE;
		} else {
			$data ['status'] = FALSE;
			
			if (form_error ( 'inpNombreUsuario' )) {
				$data ['inputerror'] [] = 'inpNombreUsuario';
				$data ['error_string'] [] = form_error ( 'inpNombreUsuario' );
			}
			
			if (form_error ( 'inpNombreEmpUsuario' )) {
				$data ['inputerror'] [] = 'inpNombreEmpUsuario';
				$data ['error_string'] [] = form_error ( 'inpNombreEmpUsuario' );
			}
			
			if (form_error ( 'inpApellidoEmpUsuario' )) {
				$data ['inputerror'] [] = 'inpApellidoEmpUsuario';
				$data ['error_string'] [] = form_error ( 'inpApellidoEmpUsuario' );
			}
			
			if (form_error ( 'inpContrasenaUsuario' )) {
				$data ['inputerror'] [] = 'inpContrasenaUsuario';
				$data ['error_string'] [] = form_error ( 'inpContrasenaUsuario' );
            }
            
            if (form_error ( 'inpVerfContraUsuario' )) {
				$data ['inputerror'] [] = 'inpVerfContraUsuario';
				$data ['error_string'] [] = form_error ( 'inpVerfContraUsuario' );
			}

		}
		
		if ($data ['status'] === FALSE) {
			echo json_encode ( $data );
			exit ();
		}
    }

    /***
	 * Metodo encargado de obtener los datos a editar de datos de usuario seleccionados
	 * @param unknown $codigo Codigo de usuario seleccionado
	 */
	public function ajax_editar($codigo) {
		$data = $this->UsuarioM->obtener_por_codigo ( $codigo);
		echo json_encode ( $data );
    }
    
    /***
	 * Metodo encargado de eliminar el usuario
	 */
	public function ajax_eliminar($id) {
		if($this->UsuarioM->dependencia($id)){
			echo json_encode(array("status" => FALSE));
		}else {
		$data = array (

            'USU_USU_CODIGO_MODIFICACION' => $this->session->userdata ( 'codigo' ),
            'USU_FECHA_MODIFICACION' => $this->UtilitarioM->fecha_hora_actual (),
            'USU_ESTADO' => UtilitarioM::INACTIVO 
		);
		$this->UsuarioM->modificar ( array ('USU_CODIGO' => $id ), $data );
		echo json_encode ( array ("status" => TRUE ) );}
	}


    /***
	 * Metodo encargado de mostrar la vista del modulo de usuarios y cargar scripts
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