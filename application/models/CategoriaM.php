<?php
class CategoriaM extends CI_Model {

    /**
     * CAMPOS
     */
    var $tabla = 'categoria';
    var $columnas =array('CAT_CODIGO','CAT_NOMBRE','CAT_DESCRIPCION');
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
        $this->db->where ('CAT_ESTADO', 1 );
	
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
			$this->db->order_by ( 'CAT_CODIGO', 'asc' );	
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
		$this->db->where('CAT_ESTADO',1);
		$this->db->where('CAT_CODIGO',$codigo);
		$query = $this->db->get();
	
		return $query->row();
    }

    public function modificar($where, $data){
		$this->db->update($this->tabla, $data, $where);
		return $this->db->affected_rows();
	}
	
	public function existencia($codigo) {
	
		
		$this->db->from($this->tabla);
		$this->db->where('CAT_NOMBRE',$codigo);
		$this->db->or_where('CAT_NOMBRE',strtoupper ($codigo));
		$this->db->where('CAT_ESTADO',0);
		$query = $this->db->get();
	
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function dependencia($codigo) {
	
		$where['CAT_CODIGO'] = $codigo;
		$where['ART_ESTADO'] = 1;
		$query = $this->db->get_where('articulo',$where,1);
	
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function autocompletar($parametroBusqueda){
		
		$this->db->where('CAT_ESTADO',1);
		$this->db->where("(CAT_NOMBRE LIKE '%".$parametroBusqueda."%')");
		$this->db->limit(5);
		$query = $this->db->get($this->tabla);
		return  $query->result();
	}
}
?>