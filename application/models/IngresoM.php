<?php
class IngresoM extends CI_Model {

    /**
     * CAMPOS
     */
    var $tabla = 'ingreso';
	var $columnas =array('ING_CODIGO','PER_CODIGO','ING_TIPO_COMPROBANTE','ING_NUMERO_COMPROBANTE','ING_FECHA_HORA','ING_IMPUESTO','ING_ESTADO_ING','ING_ESTADO','ING_TOTAL');
	var $col=array('i.ING_CODIGO','i.PER_CODIGO','p.PER_NOMBRE','i.ING_TIPO_COMPROBANTE','i.ING_NUMERO_COMPROBANTE','i.ING_FECHA_HORA','i.ING_IMPUESTO','i.ING_ESTADO_ING','i.ING_ESTADO','i.ING_TOTAL');
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
		$this->db->select('i.ING_CODIGO,i.PER_CODIGO,p.PER_NOMBRE,i.ING_TIPO_COMPROBANTE,i.ING_NUMERO_COMPROBANTE,i.ING_FECHA_HORA,i.ING_IMPUESTO,i.ING_ESTADO_ING,i.ING_ESTADO,i.ING_TOTAL');
        $this->db->from ('ingreso i, persona p');
		$this->db->where ('p.PER_CODIGO=i.PER_CODIGO');
		$this->db->where ('ING_ESTADO', 1 );
		$this->db->order_by ( 'ING_CODIGO', 'desc' );	
	
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
			$this->db->order_by ( 'ING_CODIGO', 'desc' );	
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

			$this->db->select('i.ING_CODIGO,i.PER_CODIGO,p.PER_NOMBRE,p.PER_NUM_DOCUMENTO,i.ING_TIPO_COMPROBANTE,i.ING_NUMERO_COMPROBANTE,i.ING_IMPUESTO');
			$this->db->from('ingreso i, persona p');
			$this->db->where('p.PER_CODIGO=i.PER_CODIGO');
			$this->db->where('i.ING_CODIGO',$codigo);
			$query = $this->db->get();
		
			return $query->row();
    }

    public function modificar($where, $data){
		$this->db->update($this->tabla, $data, $where);
		return $this->db->affected_rows();
		}
		

	public function obtener_total_compras(){

		$query = $this->db->select_sum('ING_TOTAL', 'totalCompras');
		$query = $this->db->where('ING_ESTADO',1);
		$query = $this->db->get('ingreso');
		$result = $query->result();
		return $result[0];
	}
	
	public function obtener_compras_por_meses($anio){

		$this->db->select('MONTH(ING_FECHA_HORA) mes, COUNT(MONTH(ING_FECHA_HORA)) cantidad');
		$this->db->from('ingreso');
		$this->db->where('YEAR(ING_FECHA_HORA)', $anio);
		$this->db->where('ING_ESTADO', UtilitarioM::ACTIVO);
		$this->db->group_by("MONTH(ING_FECHA_HORA)"); 
		$this->db->order_by("MONTH(ING_FECHA_HORA)", "asc"); 
		$query = $this->db->get();

		return  $query->result();
	}
}
?>