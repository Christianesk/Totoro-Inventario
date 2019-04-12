<?php
class UsuarioLoginM extends CI_Model {

    /**
     * CAMPOS
     */
    var $tabla = 'usuario';
    /**
     * MÉTODOS
     */
    public function validar($usuario, $password) {
		$where['USU_NOMBRE'] = $usuario;
		$where['USU_CONTRASENA'] = sha1($password);
		$query = $this->db->get_where($this->tabla,$where,1);
	
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function asignar($usuario, $clave) {
	
		$this->db->select('USU_CODIGO, USU_NOMBRE, USU_NOMBRE_EMP,USU_APELLIDO_EMP,USU_TIPO');
		$this->db->from($this->tabla);
		$this->db->where("USU_NOMBRE='".$usuario."'");
		$this->db->where("USU_CONTRASENA='".sha1($clave)."'");
		$query = $this->db->get();
		return $query->result();
	}
}
?>