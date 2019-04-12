<?php
class UsuarioLoginC extends CI_Controller{
    /**
	 * Constructor para tener siempre cargado el modelo
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('UsuarioLoginM');
		$this->load->model('UtilitarioM');
		$this->load->library('form_validation');

	}

	/***
	 * Metodo encargado de iniciar la vista de loginPrincipal
	 */
	public function index() {
		$data = [ ];
		$data ['success'] = TRUE;
		$this->layout_con_datos ( 'usuarioLogin/UsuarioLoginV','usuarioLogin/UsuarioLoginS', $data );
		
	}
	
	/***
	 * Metodo Encargado e validar el ingreso usuario y contraseña de la persona a loggearse
	 */
	public function validarIngreso() {
		$data = [];
		$data['success'] = FALSE;
	
		$this->form_validation->set_error_delimiters("'<div>","</div>'");
		$this->form_validation->set_rules('username', 'Nombre', 'trim|required');
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|required');
	
		$existenciaUsuario = $this->UsuarioLoginM->validar($this->input->post('username'), $this->input->post('password'));
		
	
		if($this->form_validation->run() && $existenciaUsuario){
			$data['success'] = TRUE;
			$usuario_sesion =$this->UsuarioLoginM->asignar($this->input->post('username'),$this->input->post('password'));
			$rol=$this->UtilitarioM->tipoUsuario($usuario_sesion[0]->USU_TIPO);

			$datos_usuario_sesion = array(
					'codigo'  => $usuario_sesion[0]->USU_CODIGO,
					'nombre'     => $usuario_sesion[0]->USU_NOMBRE,
					'nombreEmpleado' =>$usuario_sesion[0]->USU_NOMBRE_EMP.' '.$usuario_sesion[0]->USU_APELLIDO_EMP,
					'rol' => $rol,
					'tipoRol' => $usuario_sesion[0]->USU_TIPO	
			);
			$this->session->set_userdata($datos_usuario_sesion);
			$this->layout_admin();
		}else{
			$data['success'] = FALSE;
			$this->layout_con_datos ( 'usuarioLogin/UsuarioLoginV','usuarioLogin/UsuarioLoginS', $data );
		}
	
	}
    
    /***
	 * Metodo encargado de cargar la vista principal de login y ejecutar los scripts
	 * @param  $contenido - direccion de la vista del Login
	 * @param  $script - direccion de los scripts
	 * @param  $datos - verificacion de logeo
	 */
	public function layout_con_datos($contenido,$script,$datos){
		$this->load->view($contenido, $datos);
		$this->load->view($script);
	}

	public function layout_admin(){
		
		$this->load->view('plantillaAdmin/inicio');
		$this->load->view('plantillaAdmin/dashboard');
		$this->load->view('plantillaAdmin/fin');
		$this->load->view('plantillaAdmin/dashboardS');
	
	}

	/***
	 * Metodo encargado de cerrar la session del sistema redirigiendo a la vista principal login 
	 */
	public function cerrar_sesion(){
		$this->session->sess_destroy();
		$data = [];
		$data['success'] = TRUE;
		redirect('Login');
	}
}
?>