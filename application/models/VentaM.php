<?php
class VentaM extends CI_Model{

    /**
     * CAMPOS
     */
    var $tabla = 'venta';
    var $columnas =array('VEN_CODIGO','PER_CODIGO','USU_CODIGO','VEN_TIPO_COMPROBANTE','VEN_FECHA_HORA','VEN_IMPUESTO','VEN_TOTAL','VEN_ESTADO_VENTA');
    var $col=array('v.VEN_CODIGO','v.PER_CODIGO','p.PER_NOMBRE','v.VEN_TIPO_COMPROBANTE','v.VEN_NUM_COMPROBANTE','v.VEN_FECHA_HORA','v.VEN_IMPUESTO','v.VEN_ESTADO_VENTA','VEN_ESTADO','v.VEN_TOTAL','v.VEN_GANANCIA');
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
		$this->db->select('v.VEN_CODIGO,v.PER_CODIGO,p.PER_NOMBRE,v.VEN_TIPO_COMPROBANTE,v.VEN_NUM_COMPROBANTE,v.VEN_FECHA_HORA,v.VEN_IMPUESTO,v.VEN_ESTADO_VENTA,v.VEN_ESTADO,v.VEN_TOTAL,v.VEN_GANANCIA');
        $this->db->from ('venta v, persona p');
		$this->db->where ('p.PER_CODIGO=v.PER_CODIGO');
		$this->db->where ('v.VEN_ESTADO', 1 );
		$this->db->order_by ( 'v.VEN_CODIGO', 'desc' );	
	
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
			$this->db->order_by ( 'v.VEN_CODIGO', 'desc' );	
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

		$this->db->select('v.VEN_CODIGO,v.PER_CODIGO,p.PER_NOMBRE,p.PER_NUM_DOCUMENTO,v.VEN_TIPO_COMPROBANTE,v.VEN_NUM_COMPROBANTE,v.VEN_IMPUESTO');
		$this->db->from('venta v, persona p');
		$this->db->where('p.PER_CODIGO=v.PER_CODIGO');
		$this->db->where('v.VEN_CODIGO',$codigo);
		$query = $this->db->get();
	
		return $query->row();
    }

    public function modificar($where, $data){
		$this->db->update($this->tabla, $data, $where);
		return $this->db->affected_rows();
	}
	

	public function obtener_total_ventas(){

		$query = $this->db->select_sum('VEN_TOTAL', 'totalVentas');
		$query = $this->db->where('VEN_ESTADO',1);
		$query = $this->db->get('venta');
		$result = $query->result();
		
		return $result[0];
	}

	public function obtener_total_actual($fechaActual){

		$query = $this->db->select_sum('VEN_TOTAL', 'totalActual');
		$query = $this->db->where('VEN_ESTADO',1);
		$query = $this->db->where('date_format(VEN_FECHA_HORA, \'%Y-%m-%d\') =',$fechaActual);
		$query = $this->db->get('venta');
		$result = $query->result();
		
		return $result[0];
	}


	
	public function obtener_total_ganacias($fechaActual){
		$query = $this->db->select_sum('VEN_GANANCIA', 'totalGanancia');
		$query = $this->db->where('VEN_ESTADO',1);
		$query = $this->db->where('date_format(VEN_FECHA_HORA, \'%Y-%m-%d\') =',$fechaActual);
		$query = $this->db->get('venta');
		$result = $query->result();
		
		return $result[0];
	}

	public function obtener_ventas_por_meses($anio){

		$this->db->select('MONTH(VEN_FECHA_HORA) mes, COUNT(MONTH(VEN_FECHA_HORA)) cantidad');
		$this->db->from('venta');
		$this->db->where('YEAR(VEN_FECHA_HORA)', $anio);
		$this->db->where('VEN_ESTADO', UtilitarioM::ACTIVO);
		$this->db->group_by("MONTH(VEN_FECHA_HORA)"); 
		$query = $this->db->get();

		return  $query->result();
	}

	public function obtener_mas_vendidos($anio){

		$this->db->select('a.ART_NOMBRE articulo, COUNT(v.ART_CODIGO) cantidad');
		$this->db->from('venta_detalle v, articulo a');
		$this->db->where('v.ART_CODIGO=a.ART_CODIGO');
		$this->db->where('YEAR(VED_FECHA_CREACION)', $anio);
		$this->db->where('VED_ESTADO', UtilitarioM::ACTIVO);
		$this->db->group_by("v.ART_CODIGO"); 
		$this->db->order_by("COUNT(v.ART_CODIGO)", "desc"); 
		$this->db->limit(5);
		$query = $this->db->get();

		return  $query->result();
	}

	
	public function obtener_ventas_por_dia($anio,$mes,$validacion){

		$this->db->select('DATE(VEN_FECHA_HORA) fecha, COUNT(DATE(VEN_FECHA_HORA)) cantidad');
		$this->db->from('venta');
		$this->db->where('YEAR(VEN_FECHA_HORA)', $anio);
		if ($validacion==2) {
			$this->db->where('MONTH(VEN_FECHA_HORA)', $mes);
		}
		$this->db->where('VEN_ESTADO', UtilitarioM::ACTIVO);
		$this->db->group_by("DATE(VEN_FECHA_HORA)"); 
		$this->db->order_by("DATE(VEN_FECHA_HORA)", "asc"); 
		$query = $this->db->get();
		
		return  $query->result();
	}
	

	public function obtener_anios(){

		$this->db->select('DISTINCT(YEAR(VEN_FECHA_HORA)) año');
		$this->db->from('venta');
		$this->db->where('VEN_ESTADO', UtilitarioM::ACTIVO);
		$this->db->order_by("YEAR(VEN_FECHA_HORA)", "desc"); 
		$query = $this->db->get();
		return  $query->result();
	}
	public function obtener_mes($anio){

		$this->db->select('DISTINCT(MONTH(VEN_FECHA_HORA)) mes');
		$this->db->from('venta');
		$this->db->where('YEAR(VEN_FECHA_HORA)', $anio);
		$this->db->where('VEN_ESTADO', UtilitarioM::ACTIVO);
		$this->db->order_by("MONTH(VEN_FECHA_HORA)", "asc"); 
		$query = $this->db->get();

		return  $query->result();
	}
	
	
	
}
?>