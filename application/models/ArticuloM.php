<?php
class ArticuloM extends CI_Model{

    /**
     * CAMPOS
     */
    var $tabla = 'articulo';
	var $columnas =array('ART_CODIGO','CAT_CODIGO','ART_SERIAL','ART_NOMBRE','ART_STOCK','ART_DESCRIPCION','ART_IMAGEN','ART_ESTADO','ART_TIPO');
	var $col=array('a.ART_CODIGO','a.ART_SERIAL','a.ART_NOMBRE','a.ART_STOCK','a.ART_DESCRIPCION','a.ART_IMAGEN','c.CAT_NOMBRE','a.ART_ESTADO','a.ART_PRECIO_COMPRA','a.ART_PRECIO_VENTA','a.ART_TIPO');
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
		$this->db->select('a.ART_CODIGO,a.ART_SERIAL,a.ART_NOMBRE,a.ART_STOCK,a.ART_DESCRIPCION,a.ART_IMAGEN,c.CAT_NOMBRE,a.ART_ESTADO,a.ART_PRECIO_COMPRA,a.ART_PRECIO_VENTA,a.ART_TIPO');
        $this->db->from ('articulo a, categoria c');
		$this->db->where ('c.CAT_CODIGO=a.CAT_CODIGO');
		$this->db->where ('a.ART_ESTADO=1');
		
	
		$i = 0;
	
		foreach ( $this->col as $item ) // bucle de la columna
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
	
				if (count ( $this->col ) - 1 == $i) // ultima iteración del bucle
					$this->db->group_end (); // Corchete de cierre
			}
			$column [$i] = $item; // set column array variable to order processing
			$i ++;
		}
	
		if (isset ( $_POST ['order'] )) // Para el proceso de orden
		{
			$this->db->order_by ( $column [$_POST ['order'] ['0'] ['column']], $_POST ['order'] ['0'] ['dir'] );
		} else if (isset ( $this->order )) {
			$this->db->order_by ( 'a.ART_NOMBRE', 'asc' );	
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

    public function modificar($where, $data){
		$this->db->update($this->tabla, $data, $where);
		return $this->db->affected_rows();
	}

    public function obtener_por_codigo($codigo){
        $this->db->select('a.ART_CODIGO,a.ART_SERIAL,a.ART_NOMBRE,a.ART_STOCK,a.ART_DESCRIPCION,c.CAT_NOMBRE,c.CAT_CODIGO,a.ART_PRECIO_COMPRA,a.ART_PRECIO_VENTA,a.ART_TIPO');
        $this->db->from ('articulo a, categoria c');
        $this->db->where ('c.CAT_CODIGO=a.CAT_CODIGO');
		//$this->db->where('ART_ESTADO',1);
		$this->db->where('a.ART_CODIGO',$codigo);
		$query = $this->db->get();
	
		return $query->row();
    }

    public function existencia($codigo) {
	
		
		$this->db->from($this->tabla);
		$this->db->where('ART_SERIAL',$codigo);
		$this->db->where('ART_ESTADO',0);
		$query = $this->db->get();
	
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
    }
    
    public function dependencia_combodet($codigo) {
	
		$where['COD_ART_CODIGO'] = $codigo;
		$where['COD_ESTADO'] = 1;
        $query = $this->db->get_where('combo_detalle',$where,1);
	
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function dependencia_ingreso($codigo) {
	
		$where['ART_CODIGO'] = $codigo;
		$where['IND_ESTADO'] = 1;
        $query = $this->db->get_where('ingreso_detalle',$where,1);
	
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function dependencia_venta($codigo) {
	
		$where['ART_CODIGO'] = $codigo;
		$where['VED_ESTADO'] = 1;
        $query = $this->db->get_where('venta_detalle',$where,1);
	
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function autocompletar($parametroBusqueda){
		
		$this->db->where('ART_ESTADO',1);
		$this->db->where('ART_TIPO',1);
		$this->db->where("(ART_NOMBRE LIKE '%".$parametroBusqueda."%')");
		$this->db->limit(10);
		$query = $this->db->get($this->tabla);
		return  $query->result();
	}
	public function autocompletar_venta($parametroBusqueda){
		
		$this->db->where('ART_ESTADO',1);
		$this->db->where("(ART_NOMBRE LIKE '%".$parametroBusqueda."%')");
		$this->db->limit(10);
		$query = $this->db->get($this->tabla);
		return  $query->result();
	}
	

	public function obtener_stock_por_codigo_articulo($codigo){
        $this->db->select('ART_STOCK');
        $this->db->from ($this->tabla);
        $this->db->where ('ART_ESTADO',1);
		$this->db->where('ART_CODIGO',$codigo);
		$query = $this->db->get();
	
		return $query->row();
	}

	public function dependencia_combo($codigo) {
	
		$where['ART_CODIGO'] = $codigo;
		//$where['ART_ESTADO'] = 1;
        $query2 = $this->db->get_where('ingreso_detalle',$where,1);
        $query3 = $this->db->get_where('venta_detalle',$where,1);
	
		if($query2->num_rows() > 0&&$query3->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}


	public function obtener_precio_venta($codigo){
        $this->db->select('ART_PRECIO_VENTA');
        $this->db->from('articulo');
        $this->db->where('ART_CODIGO',$codigo);
        $query = $this->db->get();

		return /*$query->result()*/$query->row();
	}
	
	public function obtener_precio_compra($codigo){
        $this->db->select('ART_PRECIO_COMPRA');
        $this->db->from('articulo');
        $this->db->where('ART_CODIGO',$codigo);
        $query = $this->db->get();

		return /*$query->result()*/$query->row();
    }
	
	
}
?>