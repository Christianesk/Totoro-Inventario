<?php
class UsuarioM extends CI_Model {

    /**
     * CAMPOS
     */
    var $tabla = 'usuario';
    var $columnas =array('USU_CODIGO','USU_NOMBRE_EMP','USU_APELLIDO_EMP','USU_NOMBRE','USU_CONTRASENA','USU_TIPO');
    /**
     * MÉTODOS
     */

    public function lista() {
		$this->lista_activa_jquery ();
		if ($_POST ['length'] != - 1)
			$this->db->limit ( $_POST ['length'], $_POST ['start'] );
			$query = $this->db->get ();
			return $query->result ();
    }
    
    private function lista_activa_jquery() {
		$this->db->select($this->columnas);
		$this->db->from ($this->tabla);
		$this->db->not_like ('USU_CODIGO', 1 );
        $this->db->where ('USU_ESTADO', 1 );
	
		$i = 0;
	
		foreach ( $this->columnas as $item ) // bucle de la columna
		{
			if ($_POST ['search'] ['value']) // Si se realiza una busqueda (BUSQUEDA, VALOR)
			{
	
				if ($i === 0) // Primera Iteracion
				{
					$this->db->group_start (); // Se realiza una consulta con AND en el caso que se realize una busqueda multiple
					$this->db->like ( $item, $_POST ['search'] ['value'] );
				} else {
					$this->db->or_like ( $item, $_POST ['search'] ['value'] );
				}
	
				if (count ( $this->columnas ) - 1 == $i) // ultima iteración del bucle
					$this->db->group_end (); // Corchete de cierre
			}
			$column [$i] = $item; // set column array variable to order processing
			$i ++;
		}
	
		if (isset ( $_POST ['order'] )) // Para el proceso de orden
		{
			$this->db->order_by ( $column [$_POST ['order'] ['0'] ['column']], $_POST ['order'] ['0'] ['dir'] );
		} else if (isset ( $this->order )) {
			$this->db->order_by ( 'USU_CODIGO', 'asc' );	
		}
    }
    
    public function tamanio_filtro() {
		$this->lista_activa_jquery ();
		$query = $this->db->get ();
		return $query->num_rows ();
	}
	
	public function tamanio_lista() {
		$this->lista_activa_jquery ();
		return $this->db->count_all_results ();
    }
    
    
    public function guardar($data){
        $this->db->insert($this->tabla, $data);
		return $this->db->insert_id();
    }

    public function obtener_por_codigo($codigo){
        $this->db->from($this->tabla);
		$this->db->where('USU_ESTADO',1);
		$this->db->where('USU_CODIGO',$codigo);
		$query = $this->db->get();
	
		return $query->row();
    }

    public function modificar($where, $data){
        $this->db->update($this->tabla, $data, $where);
		return $this->db->affected_rows();
    }


    public function existencia($codigo) {
	
		
		$this->db->from($this->tabla);
		$this->db->where('USU_NOMBRE',$codigo);
		$this->db->or_where('USU_NOMBRE',strtoupper ($codigo));
		$this->db->where('USU_ESTADO',0);
		$query = $this->db->get();
	
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
    }
    
    public function dependencia($codigo) {
	
		$where1['ING_USU_CODIGO_CREACION'] = $codigo;
		$where1['ING_ESTADO'] = 1;
        $query1 = $this->db->get_where('ingreso',$where1,1);
        
        $where['VEN_USU_CODIGO_CREACION'] = $codigo;
		$where['VEN_ESTADO'] = 1;
		$query2 = $this->db->get_where('venta',$where,1);
	
		if($query1->num_rows() > 0 || $query2->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
?>